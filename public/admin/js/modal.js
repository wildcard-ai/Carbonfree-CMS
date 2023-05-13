// Modal

const modal = document.querySelector('[data-modal-id="modal-wrapper"]');
const openModalBtns = document.querySelectorAll('[data-modal-target="modal"]');
const closeModalBtns = document.querySelectorAll('[data-dismiss="modal"]');
const body = document.body;

// Loop through all open modal buttons and add an event listener to each
openModalBtns.forEach(function(openModalBtn) {
  // Open modal
  openModalBtn.addEventListener('click', function() {
    modal.style.display = 'flex';
    body.style.overflow = 'hidden';
    createModalBackdrop();
  });
});

// Loop through all close modal buttons and add an event listener to each
closeModalBtns.forEach(function(closeModalBtn) {
  // Close modal
  closeModalBtn.addEventListener('click', function() {
    closeModal();
  });
});

// Close modal on click outside
window.addEventListener('click', function(event) {
  if (event.target == modal) {
    closeModal();
  }
});

// Close modal on pressing Esc key
window.addEventListener('keydown', function(event) {
  if (event.key === 'Escape') {
    closeModal();
  }
});

// Function to close modal and reset form
function closeModal() {
  modal.style.display = 'none';
  body.style.overflow = 'auto';
  destroyModalBackdrop();

  if (typeof createProjectForm !== 'undefined' && typeof createProjectInput !== 'undefined') {
    createProjectForm.reset();
    resetValidation(createProjectInput);
  }
}

function createModalBackdrop() {
  var modalBackdrop = document.querySelector('.modal-backdrop');

  if (!modalBackdrop) {
    var divElement = document.createElement('div');
    divElement.setAttribute('class', 'modal-backdrop');
    body.appendChild(divElement);
  }
}

function destroyModalBackdrop() {
  var modalBackdrop = document.querySelector('.modal-backdrop');

  if (modalBackdrop) {
    modalBackdrop.parentNode.removeChild(modalBackdrop);
  }
}