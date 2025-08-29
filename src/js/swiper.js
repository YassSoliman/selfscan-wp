// Import Swiper and its styles
import Swiper from 'swiper';
import { Navigation, Keyboard } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';

// Register Swiper modules
Swiper.use([Navigation, Keyboard]);

/**
 * Initialize Swiper instances
 */
export function initSwipers() {
  // Initialize Swiper only if the element exists
  const swiperElement = document.querySelector('.pricing-reviews__swiper');
  if (swiperElement && swiperElement.getAttribute('data-enable-swiper') === 'true') {
    const swiperPricingReviews = new Swiper('.pricing-reviews__swiper', {
      loop: true,
      slidesPerView: 1,
      spaceBetween: 40,
      navigation: {
        nextEl: '.pricing-reviews__button-next',
        prevEl: '.pricing-reviews__button-prev',
      },
      keyboard: true,
      breakpoints: {
        768: {
          slidesPerView: 2,
          spaceBetween: 40,
        }
      }
    });
  }

  // Initialize reviews swiper
  const reviewsSwiper = document.querySelector('.reviews__swiper');
  if (reviewsSwiper) {
    const swiperReviews = new Swiper('.reviews__swiper', {
      loop: true,
      slidesPerView: 1,
      spaceBetween: 20,
      keyboard: true,
      breakpoints: {
        768: {
          slidesPerView: 2,
          spaceBetween: 40,
        },
        1100: {
          slidesPerView: 3,
          spaceBetween: 40,
        }
      }
    });
  }
}

export default initSwipers;
