// script.js
document.addEventListener("DOMContentLoaded", function () {
  const videoPlayer = document.getElementById("video");
  const videoLinks = document.querySelectorAll(".video-link");
  const btnNext = document.getElementById("btnNext");
  let currentIndex = 0;

  // Function to play a video by index
  function playVideoByIndex(index) {
    const src = videoLinks[index].getAttribute("data-src");
    videoPlayer.src = src;
    videoPlayer.load();
    videoPlayer.play();
  }

  // Play the clicked video
  videoLinks.forEach((link, index) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      playVideoByIndex(index);
      currentIndex = index;
    });
  });

  // Play the next video
  btnNext.addEventListener("click", function () {
    currentIndex = (currentIndex + 1) % videoLinks.length;
    playVideoByIndex(currentIndex);
  });
});
