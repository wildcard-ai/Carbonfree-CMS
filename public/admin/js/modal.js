// Modal

function modal(form = null) {
  const modal = document.querySelector('[data-modal-id="modal-wrapper"]');
  const openModalBtns = document.querySelectorAll('[data-modal-target="modal"]');
  const closeModalBtn = document.querySelector('[data-modal-action="close"]');
  const closeBtn = document.querySelector('[data-modal-button="close"]');
  const body = document.body;

  // Loop through all open modal buttons and add an event listener to each
  openModalBtns.forEach(function(openModalBtn) {
    // Open modal
    openModalBtn.addEventListener('click', function() {
      modal.style.display = 'block';
      body.style.overflow = 'hidden';
    });
  });

  // Close modal on clicking X
  closeModalBtn.addEventListener('click', function() {
    closeModal();
  });

  // Close modal on click outside
  window.addEventListener('click', function(event) {
    if (event.target == modal) {
      closeModal();
    }
  });

  // Close modal on click of close button
  if (closeBtn) {
    closeBtn.addEventListener('click', closeModal);
  }

  // Function to close modal and reset form
  function closeModal() {
    modal.style.display = 'none';
    body.style.overflow = 'auto';
    if (form) {
      form.reset();
    }
  }
}