const toggleButton = document.querySelector('[data-toggle="collapse"]');
let isTransitioning = false;

toggleButton.addEventListener('click', function() {
  if (isTransitioning) {
    return;
  }

  // Get the target element
  const targetId = this.getAttribute('data-target');
  const target = document.querySelector(targetId);

  // Check if the target element is already shown
  const isShown = target.classList.contains('show');

  if (!isShown) {
    // If not shown, trigger expand action
    toggleElement(target, 'expand');
  } else {
    // If shown, trigger collapse action
    toggleElement(target, 'collapse');
  }
});

function toggleElement(element, action) {
  // set the transition state
  isTransitioning = true;

  // Switch display to block by removing collapse class
  element.classList.remove('collapse', 'show');

  // get height info
  function getHeight() {
    return element.getBoundingClientRect().height;
  }

  // calculate a new height of the element
  const isExpanding = action === 'expand';
  let initialHeight, finalHeight;
  if (isExpanding) {
    initialHeight = null;
    finalHeight = getHeight() + 'px';
  } else {
    initialHeight = getHeight() + 'px';
    finalHeight = null;
  }

  // Add the collapsing class with transition css property to the target element, collapsing class has height 0
  element.classList.add('collapsing');

  // set the initial height inline style
  element.style.height = initialHeight;

  // start animation to this height
  requestAnimationFrame(function() {
    element.style.height = finalHeight;
  });

  // handle classes on transition end
  element.addEventListener('transitionend', function onTransitionEnd() {
    // replace collapsing class with collapse class
    element.classList.replace('collapsing', 'collapse');

    // add show class
    element.classList.toggle('show', isExpanding);

    // Reset the height inline style
    if (isExpanding) {
      element.style.height = null;
    }

    // Remove the transitionend event listener
    element.removeEventListener('transitionend', onTransitionEnd);

    // Update the transition state
    isTransitioning = false;
  });
}