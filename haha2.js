window.addEventListener('load', function() {
    var curtain = document.getElementById('curtain');
    var content = document.getElementById('content');
  
    curtain.style.opacity = '0';
    setTimeout(function() {
      curtain.style.display = 'none';
      content.style.visibility = 'visible';
    }, 1000); // Adjust the duration (in milliseconds) as needed
  });
  