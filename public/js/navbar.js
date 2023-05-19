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

  element.classList.replace('collapse', 'collapsing');
  element.style.height = element.scrollHeight + 'px'; // Set the expanded height immediately

  function onTransitionEnd() {
    console.log("expand");
    element.classList.replace('collapsing', 'collapse');
    element.classList.add('show');
    element.style.height = null;
    element.removeEventListener('transitionend', onTransitionEnd);
    isTransitioning = false;
  }

  element.addEventListener('transitionend', onTransitionEnd);
}

function collapse(element) {
  isTransitioning = true;

  function applyHeightAndAnimate() {
    const expandedHeight = element.getBoundingClientRect().height + 'px';
    element.style.height = expandedHeight;
  
    // Check if the style change has been applied
    if (window.getComputedStyle(element).height === expandedHeight) {
      console.log(window.getComputedStyle(element).height);
      element.classList.remove('collapse', 'show');
      element.classList.add('collapsing');
      element.style.height = null; // Animate to 0 height for collapse
  
      // Start your animation here
    } else {
      // Style change not applied yet, wait for the next frame
      requestAnimationFrame(applyHeightAndAnimate);
      console.log("requestAnimationFrame");
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