// Thumbnail Toggle Switch

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

  uploadForm.addEventListener('change', async (event) => {
    event.preventDefault();
    const id = projectId.value;
    const files = fileInput.files;
    const formData = new FormData();
    formData.append('project_id', id);
    formData.append('file', files[0]);

    fetch('thumbnail_upload.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        const img = thumbnail;
        img.src = data.cover_path; // change the src attribute to the new image URL
        // Do something with the data
    })
    .catch(error => {
        console.error(error);
    });

    fileInput.value = '';
  });
});

// Create Project

const form = document.querySelector('[data-form-id="create-project-form"]');
const projectName = document.querySelector('[data-input-id="project-name"]');
const visibility = document.querySelector('[data-input-id="visible-input"]');

modal(form);

// Submit form
form.addEventListener('submit', function(event) {
  event.preventDefault(); // Prevent default form submission behavior
  // Get input from form
  const projectNameValue = projectName.value;
  const visible = visibility.checked ? 1 : 0;
  const formData = new FormData();
  formData.append('project_name', projectNameValue);
  formData.append('visible', visible);

  // Create fetch request
  fetch('create_project.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    console.log(data);
    const newId = data; // get the id from the response data
    window.location.href = newId; // Redirect to the project page with the id
  })
  .catch(error => {
    console.error('Error:', error);
  });
});