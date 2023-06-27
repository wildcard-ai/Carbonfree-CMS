const modal = document.querySelector("[data-dialog='modal']");
const openModalBtns = document.querySelectorAll("[data-modal-target='modal']");
const closeModalBtns = document.querySelectorAll("[data-dismiss='modal']");

// Loop through all open modal buttons and add an event listener to each
openModalBtns.forEach(function (openModalBtn) {
    // Open modal
    openModalBtn.addEventListener("click", function () {
        modal.showModal();
    });
});

// Loop through all close modal buttons and add an event listener to each
closeModalBtns.forEach(function(closeModalBtn) {
    // Close modal
    closeModalBtn.addEventListener("click", function () {
        closeModal();
    });
});

// Close modal on click outside
modal.addEventListener("click", function (event) {
    if (event.target === modal) {
        closeModal();
    }
});

// Reset form on pressing Esc key
window.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
        resetForm();
    }
});

// Function to close modal and reset form
function closeModal() {
    modal.close();
    resetForm();
}

function resetForm() {
    const isFormUndefined = typeof createProjectForm === "undefined";
    const isInputUndefined = typeof createProjectInput === "undefined";

    if (!isFormUndefined && !isInputUndefined) {
        createProjectForm.reset();
        resetValidation(createProjectInput);
    }
}