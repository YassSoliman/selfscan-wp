/**
 * Tracking functionality for analytics
 */

/**
 * Track CTA button clicks
 */
export function trackCTAClicks() {
  // Find all CTA buttons with the tracking attribute
  const ctaButtons = document.querySelectorAll('[data-track-cta], .menu-header__link.button');
  
  ctaButtons.forEach(button => {
    button.addEventListener('click', function(e) {      
      // Push to dataLayer
      window.dataLayer = window.dataLayer || [];
      window.dataLayer.push({
        'event': 'selfscanctaclick',
      });
    });
  });
}

/**
 * Initialize tracking
 */
export function initTracking() {
  // Initialize CTA tracking
  trackCTAClicks();
}

export default initTracking;
