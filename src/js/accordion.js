/**
 * Accordion/Spollers functionality
 */

/**
 * Initialize spollers (accordion-like functionality)
 */
export function initAccordion() {
  const spollersArray = document.querySelectorAll('[data-spollers]');
  if (spollersArray.length > 0) {
    const spollersRegular = Array.from(spollersArray).filter(function (item) {
      return !item.dataset.spollers.split(',')[0];
    });

    if (spollersRegular.length > 0) {
      initSpollersBlock(spollersRegular);
    }
  }

  const spollersMedia = Array.from(document.querySelectorAll('[data-spollers]')).filter(function (item) {
    return item.dataset.spollers.split(',')[0];
  });

  if (spollersMedia.length > 0) {
    const breakpoinsArray = [];
    spollersMedia.forEach((item) => {
      const params = item.dataset.spollers;
      const breakpoint = {};
      const paramsArray = params.split(',');
      breakpoint.value = paramsArray[0];
      breakpoint.type = paramsArray[1] ? paramsArray[1].trim() : 'max';
      breakpoint.item = item;
      breakpoinsArray.push(breakpoint);
    });

    let mediaQueries = breakpoinsArray.map((item) => {
      return '(' + item.type + "-width: " + item.value + 'px),' + item.value + ',' + item.type;
    });

    mediaQueries = mediaQueries.filter((item, index, self) => {
      return self.indexOf(item) === index;
    });

    mediaQueries.forEach((breakpoint) => {
      const paramsArray = breakpoint.split(',');
      const mediaBreakpoint = paramsArray[1];
      const mediaType = paramsArray[2];
      const matchMedia = window.matchMedia(paramsArray[0]);

      const spollersArray = breakpoinsArray.filter((item) => {
        if (item.value === mediaBreakpoint && item.type === mediaType) {
          return true;
        }
        return false;
      });
      
      if (matchMedia) {
        matchMedia.addEventListener("change", function () {
          initSpollersBlock(spollersArray, matchMedia);
        });
        initSpollersBlock(spollersArray, matchMedia);
      }
    });
  }

  function initSpollersBlock(spollersArray, matchMedia = false) {
    spollersArray.forEach((spollersBlock) => {
      const block = matchMedia ? spollersBlock.item : spollersBlock;
      if (!block) return;
      
      if (matchMedia.matches || !matchMedia) {
        block.classList.add('_init');
        initSpollerBody(block);
        block.addEventListener('click', setSpollerAction);
      } else {
        block.classList.remove('_init');
        initSpollerBody(block, false);
        block.removeEventListener('click', setSpollerAction);
      }
    });
  }

  function initSpollerBody(spollersBlock, hideSpollerBody = true) {
    if (!spollersBlock) return;
    
    const spollerTitles = spollersBlock.querySelectorAll('[data-spoller]');
    if (spollerTitles.length > 0) {
      spollerTitles.forEach(spollerTitle => {
        if (hideSpollerBody) {
          spollerTitle.removeAttribute('tabindex');
          if (!spollerTitle.classList.contains('_active') && spollerTitle.nextElementSibling) {
            spollerTitle.nextElementSibling.hidden = true;
          }
        } else {
          spollerTitle.setAttribute('tabindex', '-1');
          if (spollerTitle.nextElementSibling) {
            spollerTitle.nextElementSibling.hidden = false;
          }
        }
      });
    }
  }

  function setSpollerAction(e) {
    const el = e.target;
    if (!el) return;
    
    if (el.hasAttribute('data-spoller') || el.closest('[data-spoller]')) {
      const spollerTitle = el.hasAttribute('data-spoller') ? el : el.closest('[data-spoller]');
      if (!spollerTitle) return;
      
      const spollersBlock = spollerTitle.closest('[data-spollers]');
      if (!spollersBlock) return;
      
      const oneSpoller = spollersBlock.hasAttribute('data-one-spoller');
      
      if (!spollersBlock.querySelectorAll('._slide').length) {
        if (oneSpoller && !spollerTitle.classList.contains('_active')) {
          hideSpollerBody(spollersBlock);
        }
        
        spollerTitle.classList.toggle('_active');
        
        if (spollerTitle.nextElementSibling) {
          _slideToggle(spollerTitle.nextElementSibling, 500);
        }
      }
      e.preventDefault();
    }
  }

  function hideSpollerBody(spollersBlock) {
    if (!spollersBlock) return;
    
    const spollerActiveTitle = spollersBlock.querySelector('[data-spoller]._active');
    if (spollerActiveTitle && spollerActiveTitle.nextElementSibling) {
      spollerActiveTitle.classList.remove('_active');
      _slideUp(spollerActiveTitle.nextElementSibling, 500);
    }
  }

  let _slideUp = (target, duration = 500) => {
    if (!target || target.classList.contains('_slide')) return;
    
    target.classList.add('_slide');
    target.style.transitionProperty = 'height, margin, padding';
    target.style.transitionDuration = duration + 'ms';
    target.style.height = target.offsetHeight + 'px';
    target.offsetHeight;
    target.style.overflow = 'hidden';
    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;
    window.setTimeout(() => {
      target.hidden = true;
      target.style.removeProperty('height');
      target.style.removeProperty('padding-top');
      target.style.removeProperty('padding-bottom');
      target.style.removeProperty('margin-top');
      target.style.removeProperty('margin-bottom');
      target.style.removeProperty('overflow');
      target.style.removeProperty('transition-duration');
      target.style.removeProperty('transition-property');
      target.classList.remove('_slide');
    }, duration);
  };

  let _slideDown = (target, duration = 500) => {
    if (!target || target.classList.contains('_slide')) return;
    
    target.classList.add('_slide');
    if (target.hidden) {
      target.hidden = false;
    }
    let height = target.offsetHeight;
    target.style.overflow = 'hidden';
    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;
    target.offsetHeight;
    target.style.transitionProperty = 'height, margin, padding';
    target.style.transitionDuration = duration + 'ms';
    target.style.height = height + 'px';
    target.style.removeProperty('padding-top');
    target.style.removeProperty('padding-bottom');
    target.style.removeProperty('margin-top');
    target.style.removeProperty('margin-bottom');
    window.setTimeout(() => {
      target.style.removeProperty('height');
      target.style.removeProperty('overflow');
      target.style.removeProperty('transition-duration');
      target.style.removeProperty('transition-property');
      target.classList.remove('_slide');
    }, duration);
  };

  let _slideToggle = (target, duration = 500) => {
    if (!target) return;
    
    if (target.hidden) {
      return _slideDown(target, duration);
    } else {
      _slideUp(target, duration);
    }
  };
}

export default initAccordion; 