const toggleButton = document.querySelector('[data-toggle="collapse"]');
const menu = document.querySelector('[data-menu="collapse"]');
let isAnimating = false;

toggleButton.addEventListener('click', function() {
  if (isAnimating) {
    return;
  }

  const isMenuToggled = this.classList.contains('toggled');

  if (!isMenuToggled) {
    slideToggle(menu, 'slideDown');
  } else {
    slideToggle(menu, 'slideUp');
  }
});

function slideToggle(element, action) {
  isAnimating = true;
  const isSlideDown = action === 'slideDown';
  
  if (isSlideDown) {
    element.classList.remove('closed');
    var height = element.clientHeight;
  } else {
    element.style.height = element.clientHeight + 'px';
    element.classList.remove('open');
  }

  element.classList.add('sliding');

  setTimeout(function() {
    element.style.height = isSlideDown ? height + 'px' : '';
  }, 0);

  element.addEventListener('transitionend', function onTransitionEnd() {
    element.classList.remove('sliding');

    if (isSlideDown) {
      element.classList.add('open');
      toggleButton.classList.add('toggled');
      element.style.height = '';
    } else {
      element.classList.add('closed');
      toggleButton.classList.remove('toggled');
    }

    element.removeEventListener('transitionend', onTransitionEnd);
    isAnimating = false;
  });
}
