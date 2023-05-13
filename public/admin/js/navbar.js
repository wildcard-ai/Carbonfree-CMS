var toggleButton = document.querySelector('[data-toggle="collapse"]');
var menu = document.querySelector('[data-menu="collapse"]');
var isAnimating = false; // Flag to track animation state

toggleButton.addEventListener('click', function() {
  if (isAnimating) {
    return; // Do nothing if animation is already in progress
  }

  var isMenuToggled = toggleButton.classList.contains('toggled');

  if (!isMenuToggled) {
    slideDown(menu);
  } else {
    slideUp(menu);
  }
});

function slideDown(element) {
  isAnimating = true; // Set the flag to indicate animation is in progress
  element.classList.remove('collapse');
  var height = element.clientHeight;
  element.classList.add('collapsing');

  setTimeout(function() {
    element.style.height = height + 'px';
    element.addEventListener('transitionend', function onTransitionEnd() {
      element.removeEventListener('transitionend', onTransitionEnd);
      element.classList.remove('collapsing');
      element.classList.add('open');
      toggleButton.classList.add('toggled');
      isAnimating = false; // Reset the flag after animation completes
    });
  }, 0);
}

function slideUp(element) {
  isAnimating = true; // Set the flag to indicate animation is in progress
  element.classList.remove('open');
  element.classList.add('collapsing');
  setTimeout(function() {
    element.addEventListener('transitionend', function onTransitionEnd() {
      element.removeEventListener('transitionend', onTransitionEnd);
      element.classList.remove('collapsing');
      element.classList.add('collapse');
      element.style.height = '';
      toggleButton.classList.remove('toggled');
      isAnimating = false; // Reset the flag after animation completes
    });

    element.style.height = '0';
  }, 0);
}
