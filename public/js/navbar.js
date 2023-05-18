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
    toggleElement(target, 'expand');
  } else {
    toggleElement(target, 'collapse');
  }
});

function toggleElement(element, action) {
  isTransitioning = true;
  const isExpanding = action === 'expand';
  
  toggleButton.classList.toggle('collapsed', !isExpanding);

  element.classList.remove('collapse', 'show');
  
  function getHeight() {
    return element.getBoundingClientRect().height;
  }
  
  let initialHeight, finalHeight;
  if (isExpanding) {
    initialHeight = null;
    finalHeight = getHeight() + 'px';
  } else {
    initialHeight = getHeight() + 'px';
    finalHeight = null;
  }
  
  element.style.height = initialHeight;
  
  setTimeout(function() {
    element.style.height = finalHeight;
  }, 0);
  
  element.classList.add('collapsing');
  
  element.addEventListener('transitionend', function() {
    element.classList.replace('collapsing', 'collapse');
    element.classList.toggle('show', isExpanding);
    element.style.height = null;
    isTransitioning = false;
  });
}