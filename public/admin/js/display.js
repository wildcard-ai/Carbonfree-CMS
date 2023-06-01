const openDisplayButton = document.querySelector('[data-button="display"]');
const displayOptionsBox = document.querySelector('[data-box="display"]');
const closeDisplayButton = document.querySelector('[data-dismiss="display"]');

openDisplayButton.addEventListener('click', openDisplayOptions);
closeDisplayButton.addEventListener('click', closeDisplayOptions);

function openDisplayOptions() {
  displayOptionsBox.classList.add('show');
  openDisplayButton.classList.remove('show');
}

function closeDisplayOptions() {
  displayOptionsBox.classList.remove('show');
  openDisplayButton.classList.add('show');
}