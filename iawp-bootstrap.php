<?php

namespace IAWP_SCOPED;

use  IAWP_SCOPED\eftec\bladeone\BladeOne ;
use  IAWP_SCOPED\IAWP\Dashboard_Options ;
use  IAWP_SCOPED\IAWP\Date_Range\Exact_Date_Range ;
use  IAWP_SCOPED\IAWP\Env ;
use  IAWP_SCOPED\IAWP\Geo_Database_Background_Job ;
use  IAWP_SCOPED\IAWP\Independent_Analytics ;
use  IAWP_SCOPED\IAWP\Interrupt ;
use  IAWP_SCOPED\IAWP\Migrations ;
use  IAWP_SCOPED\IAWP\Public_API\Analytics ;
use  IAWP_SCOPED\IAWP\Public_API\Singular_Analytics ;
use  IAWP_SCOPED\IAWP\WP_Option_Cache_Bust ;
\define( 'IAWP_DIRECTORY', \rtrim( \plugin_dir_path( __FILE__ ), \DIRECTORY_SEPARATOR ) );
\define( 'IAWP_URL', \rtrim( \plugin_dir_url( __FILE__ ), '/' ) );
\define( 'IAWP_VERSION', '2.2.1' );
\define( 'IAWP_SCOPED\\IAWP_DATABASE_VERSION', '27' );
\define( 'IAWP_LANGUAGES_DIRECTORY', \dirname( \plugin_basename( __FILE__ ) ) . '/languages' );
\define( 'IAWP_SCOPED\\IAWP_PLUGIN_FILE', __DIR__ . '/iawp.php' );

if ( \file_exists( \IAWP_SCOPED\iawp_path_to( 'vendor/scoper-autoload.php' ) ) ) {
    require_once \IAWP_SCOPED\iawp_path_to( 'vendor/scoper-autoload.php' );
} else {
    require_once \IAWP_SCOPED\iawp_path_to( 'vendor/autoload.php' );
}

/**
 * @param $log
 *
 * @return void
 * @internal
 */
function iawp_log( $log ) : void
{
    if ( \WP_DEBUG === \true && \WP_DEBUG_LOG === \true ) {
        
        if ( \is_array( $log ) || \is_object( $log ) ) {
            \error_log( \print_r( $log, \true ) );
        } else {
            \error_log( $log );
        }
    
    }
}

/** @internal */
function iawp_path_to( string $path ) : string
{
    $path = \trim( $path, \DIRECTORY_SEPARATOR );
    return \implode( \DIRECTORY_SEPARATOR, [ \IAWP_DIRECTORY, $path ] );
}

/**
 * add_filter('iawp_temp_directory_path', function ($value) {
 *     return '/Users/andrew/site/wp-content/uploads/iawp';
 * });
 *
 * @param string $path
 *
 * @return string
 * @throws Exception
 * @internal
 */
function iawp_temp_path_to( string $path ) : string
{
    $temp_directory = \apply_filters( 'iawp_temp_directory_path', 'temp' );
    $path = \rtrim( $path, \DIRECTORY_SEPARATOR );
    if ( $temp_directory === 'temp' ) {
        return \IAWP_SCOPED\iawp_path_to( \implode( \DIRECTORY_SEPARATOR, [ $temp_directory, $path ] ) );
    }
    $temp_directory = \rtrim( $temp_directory, \DIRECTORY_SEPARATOR );
    if ( !\is_writable( $temp_directory ) ) {
        \wp_mkdir_p( $temp_directory );
    }
    // Separate condition to see if wp_mkdir_p call fixed the issue
    if ( !\is_writable( $temp_directory ) ) {
        throw new \Exception( 'You have provided and missing or non-writable directory for the iawp_temp_directory_path filter: ' . $temp_directory );
    }
    return \implode( \DIRECTORY_SEPARATOR, [ $temp_directory, $path ] );
}

/** @internal */
function iawp_url_to( string $path ) : string
{
    $path = \trim( $path, '/' );
    return \implode( '/', [ \IAWP_URL, $path ] );
}

/** @internal */
function iawp_upload_path_to( string $path ) : string
{
    $path = \trim( $path, \DIRECTORY_SEPARATOR );
    return \implode( \DIRECTORY_SEPARATOR, [ \wp_upload_dir()['basedir'], $path ] );
}

/**
 * Determines if the user is running a licensed pro version
 *
 * @return bool
 * @internal
 */
function iawp_is_pro() : bool
{
    return \false;
}

/**
 * Determines if the user is running a free version or an unlicensed pro version
 * @return bool
 * @internal
 */
function iawp_is_free() : bool
{
    return !\IAWP_SCOPED\iawp_is_pro();
}

/**
 * Determines if a pro user has WooCommerce activated
 * @return bool
 * @internal
 */
function iawp_using_woocommerce() : bool
{
    global  $wpdb ;
    if ( \IAWP_SCOPED\iawp_is_free() ) {
        return \false;
    }
    $class_missing = \class_exists( '\\WooCommerce' ) === \false;
    if ( $class_missing ) {
        return \false;
    }
    $table_name = $wpdb->prefix . 'wc_order_stats';
    $order_stats_table = $wpdb->get_row( $wpdb->prepare( '
                SELECT * FROM INFORMATION_SCHEMA.TABLES 
                WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s
            ', $wpdb->dbname, $table_name ) );
    if ( \is_null( $order_stats_table ) ) {
        return \false;
    }
    return \true;
}

/** @internal */
function iawp_dashboard_url( array $query_arguments = [] ) : string
{
    $default_query_arguments = [
        'page' => 'independent-analytics',
    ];
    return \add_query_arg( \array_merge( $default_query_arguments, $query_arguments ), \admin_url( 'admin.php' ) );
}

/** @internal */
function iawp_blade() : BladeOne
{
    if ( !\file_exists( \IAWP_SCOPED\iawp_temp_path_to( 'template-cache' ) ) ) {
        \wp_mkdir_p( \IAWP_SCOPED\iawp_temp_path_to( 'template-cache' ) );
    }
    $blade = new BladeOne( \IAWP_SCOPED\iawp_path_to( 'views' ), \IAWP_SCOPED\iawp_temp_path_to( 'template-cache' ) );
    $blade->share( 'env', new Env() );
    return $blade;
}

/**
 * iawp_singular_analytics('60', new DateTime('-3 days'), new DateTime());
 *
 * @param string|int $singular_id
 * @param DateTime $from
 * @param DateTime $to
 *
 * @return Singular_Analytics|null
 * @internal
 */
function iawp_singular_analytics( $singular_id, \DateTime $from, \DateTime $to ) : ?Singular_Analytics
{
    $date_range = new Exact_Date_Range( $from, $to );
    return Singular_Analytics::for( $singular_id, $date_range );
}

/**
 * iawp_analytics(new DateTime('-3 days'), new DateTime());
 *
 * @param DateTime $from
 * @param DateTime $to
 *
 * @return Analytics
 * @internal
 */
function iawp_analytics( \DateTime $from, \DateTime $to ) : Analytics
{
    $date_range = new Exact_Date_Range( $from, $to );
    return Analytics::for( $date_range );
}


if ( !\extension_loaded( 'pdo' ) || !\extension_loaded( 'pdo_mysql' ) ) {
    $interrupt = new Interrupt( 'interrupt.pdo', \false );
    $interrupt->render();
    return;
}

global  $wpdb ;

if ( \strlen( $wpdb->prefix ) > 25 ) {
    $interrupt = new Interrupt( 'interrupt.database-prefix-too-long', \false );
    $interrupt->render( [
        'prefix' => $wpdb->prefix,
        'length' => \strlen( $wpdb->prefix ),
    ] );
    return;
}


if ( Migrations\Migrations::is_database_ahead_of_plugin() ) {
    $interrupt = new Interrupt( 'interrupt.database-ahead-of-plugin', \false );
    $interrupt->render();
    return;
}

WP_Option_Cache_Bust::register( 'iawp_is_migrating' );
WP_Option_Cache_Bust::register( 'iawp_is_database_downloading' );
WP_Option_Cache_Bust::register( 'iawp_db_version' );
WP_Option_Cache_Bust::register( 'iawp_geo_database_version' );
/** @internal */
function iawp()
{
    return Independent_Analytics::getInstance();
}

\IAWP_SCOPED\iawp();
\register_activation_hook( \IAWP_SCOPED\IAWP_PLUGIN_FILE, function () {
    \wp_mkdir_p( \IAWP_SCOPED\iawp_temp_path_to( 'template-cache' ) );
    \wp_mkdir_p( \IAWP_SCOPED\iawp_temp_path_to( 'device-data-cache' ) );
    
    if ( \get_option( 'iawp_db_version', '0' ) === '0' ) {
        // If there is no database installed, run migration on current process
        Migrations\Migrations::create_or_migrate();
    } else {
        // If there is a database, run migration in a background process
        Migrations\Migration_Job::maybe_dispatch();
    }
    
    Geo_Database_Background_Job::maybe_dispatch();
    \update_option( 'iawp_need_clear_cache', \true );
    \IAWP_SCOPED\iawp()->cron_manager->schedule_refresh_salt();
    if ( \IAWP_SCOPED\iawp_is_pro() ) {
        \IAWP_SCOPED\iawp()->email_reports->schedule_email_report();
    }
} );
\register_deactivation_hook( \IAWP_SCOPED\IAWP_PLUGIN_FILE, function () {
    \IAWP_SCOPED\iawp()->cron_manager->unschedule_daily_salt_refresh();
    if ( \IAWP_SCOPED\iawp_is_pro() ) {
        \IAWP_SCOPED\iawp()->email_reports->unschedule_email_report();
    }
    \wp_delete_file( \trailingslashit( \WPMU_PLUGIN_DIR ) . 'iawp-performance-boost.php' );
    \delete_option( 'iawp_must_use_directory_not_writable' );
} );
/*
* The admin_init hook will fire when the dashboard is loaded or an admin ajax request is made
*/
\add_action( 'admin_init', function () {
    Migrations\Migrations::handle_migration_18_error();
    Migrations\Migrations::handle_migration_22_error();
    $options = new Dashboard_Options();
    $options->maybe_redirect();
    new Migrations\Migration_Job();
    
    if ( \get_option( 'iawp_db_version', '0' ) === '0' ) {
        // If there is no database installed, run migration on current process
        Migrations\Migrations::create_or_migrate();
    } else {
        // If there is a database, run migration in a background process
        Migrations\Migration_Job::maybe_dispatch();
    }
    
    Geo_Database_Background_Job::maybe_dispatch();
} );