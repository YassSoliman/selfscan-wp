// Import our SCSS
import '../scss/main.scss';

// Import modules
import initNavigation from './nav';
import initSwipers from './swiper';
import initTracking from './tracking';
import initAccordion from './accordion';

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
	// Initialize navigation (mobile menu, sticky header)
	initNavigation();
	
	// Initialize accordion/spollers
	initAccordion();
	
	// Initialize Swiper instances
	initSwipers();
	
	// Initialize tracking
	initTracking();
});


