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
let lightMoonSvg = `
<svg xmlns="http://www.w3.org/2000/svg" class="lightModeBtn" onclick="toggleDarkMode()"  width="50" height="50" viewBox="0 0 24 24"><path fill="black" d="M6 20h6v1c0 .55-.45 1-1 1H7c-.55 0-1-.45-1-1v-1m5-5.11V16H7v-2.42C5.23 12.81 4 11.05 4 9c0-2.76 2.24-5 5-5c.9 0 1.73.26 2.46.67c.54-.47 1.2-.86 1.89-1.17C12.16 2.57 10.65 2 9 2C5.13 2 2 5.13 2 9c0 2.38 1.19 4.47 3 5.74V17c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-.68c-.75-.36-1.43-.82-2-1.43m9.92-4.95l-.5-1.64l1.3-1.08l-1.68-.04l-.63-1.58l-.56 1.61l-1.68.11l1.33 1.03l-.4 1.65l1.4-.97l1.42.91m-1.8 2.31a4.622 4.622 0 0 1-3.27-2.28c-.73-1.26-.8-2.72-.35-4c.14-.34-.16-.68-.5-.63c-3.44.66-5 4.79-2.78 7.61c1.81 2.24 5.28 2.32 7.17.05c.23-.26.08-.7-.27-.75Z"/></svg>
`
let darkMoonSvg = `
<svg xmlns="http://www.w3.org/2000/svg" class="lightModeBtn" onclick="toggleDarkMode()"  width="50" height="50" viewBox="0 0 24 24"><path fill="white" d="M6 20h6v1c0 .55-.45 1-1 1H7c-.55 0-1-.45-1-1v-1m5-5.11V16H7v-2.42C5.23 12.81 4 11.05 4 9c0-2.76 2.24-5 5-5c.9 0 1.73.26 2.46.67c.54-.47 1.2-.86 1.89-1.17C12.16 2.57 10.65 2 9 2C5.13 2 2 5.13 2 9c0 2.38 1.19 4.47 3 5.74V17c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-.68c-.75-.36-1.43-.82-2-1.43m9.92-4.95l-.5-1.64l1.3-1.08l-1.68-.04l-.63-1.58l-.56 1.61l-1.68.11l1.33 1.03l-.4 1.65l1.4-.97l1.42.91m-1.8 2.31a4.622 4.622 0 0 1-3.27-2.28c-.73-1.26-.8-2.72-.35-4c.14-.34-.16-.68-.5-.63c-3.44.66-5 4.79-2.78 7.61c1.81 2.24 5.28 2.32 7.17.05c.23-.26.08-.7-.27-.75Z"/></svg>
`
let btnDiv = document.querySelector('.lightBtnDiv')
let d = false
function toggleDarkMode() {
  if (!d){ d = true; btnDiv.innerHTML = darkMoonSvg;}
  else {d = false; btnDiv.innerHTML = lightMoonSvg;}
  // body 
  document.querySelector('body').classList.toggle('dark-mode');
  // projects section
  document.querySelector(".projects").classList.toggle('dark')
  // cards
  document.querySelectorAll('.project-card').forEach(el => el.classList.toggle('darkModeCard'))
  document.querySelectorAll('.card').forEach(el => el.classList.toggle('darkModeCard'))
  // navbar
  document.querySelector('.navBar').classList.toggle('dark')
  // text inside cards
  document.querySelector('.project-category').classList.toggle('dark')
}

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
