const toggleButton = document.querySelector('[data-toggle="collapse"]');
let isCollapsing = false;

toggleButton.addEventListener('click', function() {
  if (isCollapsing) {
    return;
  }

  const targetId = this.getAttribute('data-target');
  const target = document.querySelector(targetId);

  const isShow = target.classList.contains('show');

  if (!isShow) {
    slideToggle(target, 'slideDown');
  } else {
    slideToggle(target, 'slideUp');
  }
});

function slideToggle(element, action) {
  isCollapsing = true;
  const isSlideDown = action === 'slideDown';
  
  if (isSlideDown) {
    toggleButton.classList.remove('collapsed');
    element.classList.remove('collapse');
    var height = element.clientHeight;
  } else {
    toggleButton.classList.add('collapsed');
    element.style.height = element.clientHeight + 'px';
    element.classList.remove('collapse');
    element.classList.remove('show');
  }
  
  setTimeout(function() {
    element.style.height = isSlideDown ? height + 'px' : '';
  }, 0);

  element.classList.add('collapsing');

  element.addEventListener('transitionend', function onTransitionEnd() {
    element.classList.remove('collapsing');
		element.classList.add('collapse');
    
    if (isSlideDown) {
      element.classList.add('show');
      element.style.height = '';
    }

    element.removeEventListener('transitionend', onTransitionEnd);
    isCollapsing = false;
  });
}