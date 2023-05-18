const toggleButton = document.querySelector('[data-toggle="collapse"]');
let isTransitioning = false;

toggleButton.addEventListener('click', function() {
  if (isTransitioning) {
    return;
  }

  const targetId = this.getAttribute('data-target');
  const target = document.querySelector(targetId);

  const isShown = target.classList.contains('show');

  if (!isShown) {
    expand(target);
  } else {
    collapse(target);
  }
});

function expand(element) {
  isTransitioning = true;

  element.classList.remove('collapse');
  element.classList.add('collapsing');
  element.style.height = element.scrollHeight + 'px'; // Set the expanded height immediately

  function onTransitionEnd() {
    console.log("expand");
    element.classList.replace('collapsing', 'collapse');
    element.classList.add('show');
    element.removeEventListener('transitionend', onTransitionEnd);
    isTransitioning = false;
    element.style.height = null;
  }

  element.addEventListener('transitionend', onTransitionEnd);
}

function collapse(element) {
  isTransitioning = true;

  function applyHeightAndAnimate() {
    element.style.height = '130px';
  
    // Check if the style change has been applied
    if (window.getComputedStyle(element).height === '130px') {
      element.classList.remove('collapse', 'show');
      element.classList.add('collapsing');
      element.style.height = null; // Animate to 0 height for collapse
  
      // Start your animation here
    } else {
      // Style change not applied yet, wait for the next frame
      requestAnimationFrame(applyHeightAndAnimate);
    }
  }
  
  applyHeightAndAnimate();

  function onTransitionEnd() {
    console.log("collapse");
    element.classList.replace('collapsing', 'collapse');
    element.removeEventListener('transitionend', onTransitionEnd);
    isTransitioning = false;
  }

  element.addEventListener('transitionend', onTransitionEnd);
}