/**
 * Navigation functionality including mobile menu, sticky header, and spollers
 */

/**
 * Initialize mobile menu
 */
export function initMobileMenu() {
  // Elements
  const burgerMenu = document.querySelector('.burger-menu');
  const closeMenu = document.querySelector('.mobile-nav__close');
  const mobileNav = document.querySelector('.mobile-nav');
  const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
  let scrollPosition = 0;
  
  // Mobile menu functions
  const openMobileMenu = () => {
    // Store current scroll position
    scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
    
    // Lock the body to prevent scrolling
    document.body.style.position = 'fixed';
    document.body.style.top = `-${scrollPosition}px`;
    document.body.style.width = '100%';
    document.body.style.overflow = 'hidden';
    document.body.classList.add('menu-is-active');
    
    // Show mobile menu and overlay
    mobileNav.classList.add('is-active');
    mobileMenuOverlay.classList.add('is-active');
  };
  
  const closeMobileMenu = () => {
    // Hide mobile menu and overlay
    mobileNav.classList.remove('is-active');
    mobileMenuOverlay.classList.remove('is-active');
    
    // Unlock the body
    document.body.style.position = '';
    document.body.style.top = '';
    document.body.style.width = '';
    document.body.style.overflow = '';
    document.body.classList.remove('menu-is-active');
    
    // Restore scroll position
    window.scrollTo(0, scrollPosition);
  };
  
  // Event listeners
  if (burgerMenu && closeMenu && mobileNav && mobileMenuOverlay) {
    // Open menu button
    burgerMenu.addEventListener('click', (e) => {
      e.preventDefault();
      openMobileMenu();
    });
    
    // Close menu button
    closeMenu.addEventListener('click', (e) => {
      e.preventDefault();
      closeMobileMenu();
    });
    
    // Close when clicking overlay
    mobileMenuOverlay.addEventListener('click', () => {
      closeMobileMenu();
    });
    
    // Close on ESC key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && mobileNav.classList.contains('is-active')) {
        closeMobileMenu();
      }
    });
    
    // Handle resize to desktop - automatically close mobile menu
    window.addEventListener('resize', () => {
      if (window.innerWidth >= 768 && mobileNav.classList.contains('is-active')) {
        closeMobileMenu();
      }
    });
    
    // Safety fallback - ensure menu closes when page is hidden
    document.addEventListener('visibilitychange', () => {
      if (document.visibilityState === 'hidden' && mobileNav.classList.contains('is-active')) {
        closeMobileMenu();
      }
    });
  }
}

/**
 * Initialize sticky header
 */
export function initStickyHeader() {
  const stickyElement = document.querySelector('[data-sticky-element]');
  if (stickyElement) {
    const stickyAnchor = stickyElement.parentElement;
    let state = false;

    function getAnchorOffset() {
      if (!stickyAnchor) return 0;
      return stickyAnchor.getBoundingClientRect().top;
    }

    function updateSticky() {
      if (!state && (getAnchorOffset() < 0)) {
        stickyElement.classList.add("is-sticky");
        if (stickyAnchor) {
          stickyAnchor.style.height = `${stickyElement.offsetHeight}px`;
        }
        state = true;
      } else if (state && (getAnchorOffset() >= 0)) {
        stickyElement.classList.remove("is-sticky");
        if (stickyAnchor) {
          stickyAnchor.style.height = 'unset';
        }
        state = false;
      }
    }

    window.addEventListener('scroll', updateSticky);
    window.addEventListener('resize', updateSticky);

    updateSticky();
  }
}

/**
 * Initialize all navigation-related functionality
 */
export function initNavigation() {
  initMobileMenu();
  initStickyHeader();
}

export default initNavigation;
