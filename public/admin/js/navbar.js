// Navbar

var toggleBtn = document.querySelector('[data-toggle="collapse"]');
var item = document.querySelector('[data-menu="collapse"]');

toggleBtn.addEventListener('click', function() {
  item.classList.toggle('show');
});