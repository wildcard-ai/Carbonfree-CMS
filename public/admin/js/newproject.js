// form validation for create project

const form = document.querySelector('[data-form-id="create-project-form"]');
const input = document.querySelector('[data-input-id="create-project-form"]');

form.addEventListener("submit", formValidation);
input.addEventListener("keyup", resetValidation);

function formValidation(event) {
  if(input.value.trim() === '') {
    event.preventDefault();
    input.focus();
    input.classList.add('danger'); // Add red border to input field
    return false;
  } else {
    return true;
  }
}

function resetValidation(event) {
  if (event && event.keyCode === 13) {
    // Ignore Enter key
    return;
  }
  return input.classList.remove('danger'); // Remove red border from input field
}

modal(form, resetValidation);