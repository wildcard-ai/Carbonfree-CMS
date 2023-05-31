// form validation for create project

const createProjectForm = document.querySelector('[data-form-id="create-project-form"]');
const createProjectInput = document.querySelector('[data-input-id="create-project-form"]');

createProjectForm.addEventListener("submit", formValidation);
createProjectInput.addEventListener("keyup", function(event) {
  resetValidation(createProjectInput, event);
});

function formValidation(event) {
  if(createProjectInput.value.trim() === '') {
    event.preventDefault();
    createProjectInput.focus();
    createProjectInput.classList.add('danger'); // Add red border to input field
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
  inputElement.classList.remove('danger'); // Remove red border from input field
}

/* 
  Thumbnail Toggle Switch
*/

const thumbnailToggleSwitch = document.querySelector('[data-switch-type="thumbnail"]');
const buttons = document.querySelectorAll('[data-button-type="thumbnail"]');

thumbnailToggleSwitch.addEventListener('change', () => {
  buttons.forEach(button => button.classList.toggle('show-buttons'));
});

// Upload Thumbnail

const uploadForms = document.querySelectorAll('[data-form-type="thumbnail"]');

uploadForms.forEach(async (uploadForm) => {
  const projectId = uploadForm.querySelector('[data-project-id="thumbnail"]');
  const fileInput = uploadForm.querySelector('[data-file-type="thumbnail"]');
  const thumbnail = document.querySelector(`[data-thumbnail-id="${projectId.value}"]`);
  const status = thumbnail.parentNode.parentNode.parentNode.querySelector('[data-status="thumbnail"]');

  uploadForm.addEventListener('change', async (event) => {
    event.preventDefault();
    const id = projectId.value;
    const files = fileInput.files;
    const formData = new FormData();
    formData.append('project_id', id);
    formData.append('file', files[0]);

    status.style.visibility = 'visible';  // Display "Uploading..." text

    fetch('thumbnail_upload.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);

        // Remove old image before uploading the new one
        const img = thumbnail;
        img.src = ''; // Set the src attribute to an empty string to remove the old image

        img.src = data.cover_path; // change the src attribute to the new image URL
        // Do something with the data
    })
    .catch(error => {
        console.error(error);
    })
    .finally(() => {
        status.style.visibility = 'hidden'; // Hide "Uploading..." text
    });

    fileInput.value = '';
  });
});