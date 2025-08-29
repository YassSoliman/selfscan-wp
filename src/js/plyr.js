/**
 * Initialize Plyr video players
 * Note: Plyr is loaded via CDN in functions.php
 */
export function initPlyr() {
  // Wait for Plyr to be available
  if (typeof window.Plyr !== 'undefined') {
    const videoPlayers = document.querySelectorAll('[data-video-player]');

    if (videoPlayers.length > 0) {
      videoPlayers.forEach(videoPlayer => {
        const player = new window.Plyr(videoPlayer, {
          controls: ['play', 'current-time']
        });
      });
    }
  } else {
    // If Plyr isn't loaded yet, try again in a moment
    setTimeout(() => initPlyr(), 100);
  }
}

export default initPlyr;