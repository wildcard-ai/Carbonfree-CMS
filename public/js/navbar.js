const toggleButton = document.querySelector('[data-toggle="collapse"]');
let isTransitioning = false;
let expandedHeight = false;

toggleButton.addEventListener('click', function() {
  if (isTransitioning) {
    return;
  }

  const targetId = this.getAttribute('data-target');
  const target = document.querySelector(targetId);

  const isShown = target.classList.contains('show');

  if (!isShown) {
    toggleElement(target, 'expand');
  } else {
    toggleElement(target, 'collapse');
  }
});

function toggleElement(element, action) {
  isTransitioning = true;
  const isExpanding = action === 'expand';

  element.classList.remove('collapse', 'show');

  if (isExpanding) {
    expandedHeight = element.getBoundingClientRect().height + 'px';
  } else {
    element.style.height = expandedHeight;
  }

  if(navigator.userAgent.toLowerCase().indexOf('firefox') > -1){
    setTimeout(function() {
      if(isExpanding) {
        element.style.height = expandedHeight;
      } else {
        element.style.height = null;
      }
    }, 10);
  } else {
    requestAnimationFrame(function() {
      if(isExpanding) {
        element.style.height = expandedHeight;
      } else {
        element.style.height = null;
      }
    });
  }

  element.classList.add('collapsing');

  element.addEventListener('transitionend', function onTransitionEnd() {
    element.classList.replace('collapsing', 'collapse');
    element.classList.toggle('show', isExpanding);
    element.style.height = null;
    element.removeEventListener('transitionend', onTransitionEnd);
    isTransitioning = false;
  });
}