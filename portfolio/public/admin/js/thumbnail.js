// Thumbnail Toggle Switch

const thumbnailToggleSwitch = document.getElementById('edit-thumbnails');
const buttons = document.querySelectorAll('.tmb-btn-container');

thumbnailToggleSwitch.addEventListener('change', () => {
  buttons.forEach(button => button.classList.toggle('show-buttons'));
});

// Upload Thumbnail

const uploadForms = document.querySelectorAll('.thumbnail-form');

uploadForms.forEach(uploadForm => {
  const fileInput = uploadForm.querySelector('input[type="file"]');
  const projectId = uploadForm.querySelector('input[name="project_id"]');
  const thumbnail = document.getElementById("thumbnail-" + projectId.value);

  uploadForm.addEventListener('change', (event) => {
    event.preventDefault();
    const files = fileInput.files;
    const id = projectId.value;
    console.log(files);
    const formData = new FormData();
    formData.append('project_id', id);
    formData.append('file', files[0]);

    fetch('thumbnail_upload.php', {
      method: 'POST',
      body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        thumbnail.src = data.cover_path; // change the src attribute to the new image URL    
      } else {
        console.log(data.error);
      }
    })
    .catch((error) => console.log(error));  

    fileInput.value = '';
  });
});