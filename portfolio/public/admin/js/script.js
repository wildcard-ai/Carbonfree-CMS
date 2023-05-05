// Navbar

var toggleBtn = document.querySelector('[data-toggle-target="menu"]');
var item = document.querySelector('[data-toggle-id="menu"]');

toggleBtn.addEventListener('click', function() {
  item.classList.toggle('show');
});