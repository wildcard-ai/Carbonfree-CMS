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
  var height = element.clientHeight; // Get the current height of the element
  element.classList.add('collapsing');

  setTimeout(function() {
    element.style.height = height + 'px'; // Animate to the computed height
  }, 0);

  element.addEventListener('transitionend', function onTransitionEnd() {
    element.removeEventListener('transitionend', onTransitionEnd);
    element.classList.remove('collapsing');
    element.classList.add('open');
    toggleButton.classList.add('toggled');
    isAnimating = false; // Reset the flag after animation completes
    element.style.height = ''; // Remove the inline height style
  });
}

function slideUp(element) {
  isAnimating = true; // Set the flag to indicate animation is in progress
  var height = element.clientHeight; // Get the original height
  element.style.height = height + 'px'; // Set the initial height
  
  element.classList.remove('open');
  element.classList.add('collapsing');
  setTimeout(function() {
    element.style.height = ''; // Animate to height 0
  }, 0);

  element.addEventListener('transitionend', function onTransitionEnd() {
    element.removeEventListener('transitionend', onTransitionEnd);
    element.classList.remove('collapsing');
    element.classList.add('collapse');
    toggleButton.classList.remove('toggled');
    isAnimating = false; // Reset the flag after animation completes
  });
}

