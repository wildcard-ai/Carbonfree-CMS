// form validation for create project

const createProjectForm = document.querySelector(
  '[data-form-id="create-project-form"]'
);
const createProjectInput = document.querySelector(
  '[data-input-id="create-project-form"]'
);

createProjectForm.addEventListener("submit", formValidation);
createProjectInput.addEventListener("keyup", function(event) {
  resetValidation(createProjectInput, event);
});

function formValidation(event) {
  if(createProjectInput.value.trim() === '') {
    event.preventDefault();
    createProjectInput.focus();
    createProjectInput.classList.add('danger');
    return false;
  } else {
    return true;
  }
}

function resetValidation(inputElement, event) {
  if (event && event.keyCode === 13) {
    // Ignore Enter key
    return;
  }
  inputElement.classList.remove('danger');
}