const toggleButton = document.querySelector('[data-toggle="collapse"]');
let isTransitioning = false;

toggleButton.addEventListener('click', function() {
  if (isTransitioning) {
    return;
  }

  const targetId = toggleButton.getAttribute('data-target');
  const target = document.querySelector(`[data-navbar-collapse-id="${targetId}"]`);

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

  const expandedHeight = element.clientHeight + 'px';
  element.style.height = expandedHeight;

  // Check if the style change has been applied
  if (window.getComputedStyle(element).height === expandedHeight) {
    element.classList.remove('collapse', 'show');
    element.classList.add('collapsing');
    element.style.height = null; // collapse
  }

  function onTransitionEnd() {
    element.classList.replace('collapsing', 'collapse');
    element.removeEventListener('transitionend', onTransitionEnd);
    isTransitioning = false;
  }

  element.addEventListener('transitionend', onTransitionEnd);
}