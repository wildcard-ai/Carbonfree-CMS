const toggleButton = document.querySelector('[data-toggle="collapse"]');
const menu = document.querySelector('[data-menu="collapse"]');
let isAnimating = false;

toggleButton.addEventListener('click', function() {
  if (isAnimating) {
    return;
  }

  var isMenuToggled = toggleButton.classList.contains('toggled');

  if (!isMenuToggled) {
    slideToggle(menu, 'slideDown');
  } else {
    slideToggle(menu, 'slideUp');
  }
});

function slideToggle(element, action) {
  isAnimating = true;
  
  if (action === 'slideDown') {
    element.classList.remove('collapse');
    var height = element.clientHeight;
  } else {
    element.style.height = element.clientHeight + 'px';
    element.classList.remove('open');
  }

  element.classList.add('collapsing');

  setTimeout(function() {
    element.style.height = action === 'slideDown' ? height + 'px' : '';
  }, 0);

  element.addEventListener('transitionend', function onTransitionEnd() {
    element.classList.remove('collapsing');

    if (action === 'slideDown') {
      element.classList.add('open');
      toggleButton.classList.add('toggled');
      element.style.height = '';
    } else {
      element.classList.add('collapse');
      toggleButton.classList.remove('toggled');
    }

    element.removeEventListener('transitionend', onTransitionEnd);
    isAnimating = false;
  });
}