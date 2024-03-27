function showPopup() {
    var popup = document.getElementById("popup");
    var close = document.getElementsByClassName("close")[0];
    
    popup.classList.add("show");
    
    close.onclick = function() {
      popup.classList.remove("show");
    }
    
    window.onclick = function(event) {
      if (event.target == popup) {
        popup.classList.remove("show");
      }
    }
  }
  const notification = document.querySelector('.notification');
const closeBtn = document.querySelector('.notification-close');

// Show the notification gradually
notification.style.opacity = 50;
notification.style.transform = 'translateY(0)';

// Close the notification when the user clicks the close button
closeBtn.addEventListener('click', () => {
  notification.style.opacity = 0;
  notification.style.transform = 'translateY(-100%)';
});

// Automatically hide the notification after 5 seconds
setTimeout(() => {
  notification.style.opacity = 0;
  notification.style.transform = 'translateY(-100%)';
}, 15000);
const container = document.querySelector('.container');
const videos = container.querySelectorAll('.video');

container.addEventListener('mouseenter', () => {
  // Pause all videos in the container and show them
  videos.forEach(video => {
    video.pause();
    video.style.display = 'block';
  });
});

container.addEventListener('mouseleave', () => {
  // Play all videos in the container and hide them
  videos.forEach(video => {
    video.play();
    video.style.display = 'none';
  });
});

alert(" don't forget follow me in social media! <3 لاتنسى متابعتي في مواقع التواصل الاجتماعي");
document.addEventListener('DOMContentLoaded', function() {
  var cursor = document.getElementById('cursor');
  var timeout;

  document.addEventListener('mousemove', function(e) {
    cursor.style.transform = 'translate(' + e.clientX + 'px, ' + e.clientY + 'px)';
    cursor.style.display = 'block';

    clearTimeout(timeout);
    timeout = setTimeout(function() {
      cursor.style.display = 'none';
    }, 1000);
  });

  document.addEventListener('mouseout', function() {
    cursor.style.display = 'none';
  });
});



function typeWriter(text, i) {
  if (i < text.length) {
    document.getElementById("typewriter").innerHTML += text.charAt(i);
    i++;
    setTimeout(function() { typeWriter(text, i); }, 50);
  }
}

typeWriter(text, 0);
// Register the service worker
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('service-worker.js')
    .then(function(registration) {
      console.log('Service Worker registered with scope:', registration.scope);
    })
    .catch(function(error) {
      console.error('Service Worker registration failed:', error);
    });
}
