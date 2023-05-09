// Modal

function modal(form = null, resetValidation = null) {
  const modal = document.querySelector('[data-modal-id="modal-wrapper"]');
  const openModalBtns = document.querySelectorAll('[data-modal-target="modal"]');
  const closeModalBtns = document.querySelectorAll('[data-modal-action="close"]');
  const body = document.body;

  // Loop through all open modal buttons and add an event listener to each
  openModalBtns.forEach(function(openModalBtn) {
    // Open modal
    openModalBtn.addEventListener('click', function() {
      modal.style.display = 'block';
      body.style.overflow = 'hidden';
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
    if (form && resetValidation) {
      form.reset();
      resetValidation();
    }
  }
}