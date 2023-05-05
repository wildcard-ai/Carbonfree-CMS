// Upload files

const uploadForm = document.getElementById('upload-image-form');
const fileInput = document.getElementById('file-input');
const uploadBtn = document.getElementById('upload-btn');
const fileList = document.getElementById('file-list');

uploadForm.addEventListener('change', (event) => {
  event.preventDefault();
  const files = fileInput.files;
  const projectId = document.querySelector('[name="project_id"]').value;
  const formData = new FormData();
  formData.append('project_id', projectId);
  formData.append('file', files[0]);

  fetch('project_upload.php', {
    method: 'POST',
    body: formData,
  })
  .then((response) => response.json())
  .then((data) => {
    if (data.success) {
      const img = document.createElement('img');
      img.classList.add('uploaded-image');
      img.src = data.path;
      fileList.insertBefore(img, fileList.firstChild);
    } else {
      console.log(data.error);
    }
  })
  .catch((error) => console.log(error));

  fileInput.value = '';
});

// Project Name Edit

const editButton = document.getElementById('edit-project-name');
const saveButton = document.getElementById('save-project-name');
const cancelButton = document.getElementById('cancel-project-name');
const projectNameWrappers = document.getElementsByClassName('project-name-wrapper');
const projectNameFormWrappers = document.getElementsByClassName('project-name-form-wrapper');
const projectNameInput = document.getElementById('project-name-input');
const editWrappers = document.getElementsByClassName('edit-wrapper');

let originalProjectName = '';

function showFormWrappers() {
  projectNameWrappers[0].style.display = 'none';
  projectNameFormWrappers[0].style.display = 'block';
  projectNameInput.focus();
  projectNameInput.select();
}

function hideFormWrappers() {
  projectNameWrappers[0].style.display = 'block';
  projectNameFormWrappers[0].style.display = 'none';
}

function saveProjectName() {
  const form = document.getElementById('edit-project-name-form');
  const projectId = form.querySelector('[name="project-id"]').value;
  const projectName = form.querySelector('[name="project-name"]').value;
  const formData = new FormData();
  formData.append('project_id', projectId);
  formData.append('project_name', projectName);

  // Create fetch request
  fetch("project_name_edit.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    console.log(data);
    const newProjectNameElements = document.getElementsByClassName('new-project-name');
    const newProjectNameArray = Array.from(newProjectNameElements);
    newProjectNameArray.forEach(element => {
      element.textContent = data.newprojectname;
    });
    originalProjectName = projectName;
    hideFormWrappers();
    editWrappers[0].style.display = 'block';
  })
  .catch(error => console.error(error));
}

editButton.addEventListener('click', (event) => {
  event.preventDefault();
  showFormWrappers();
  editWrappers[0].style.display = 'none';
  originalProjectName = projectNameInput.value;
});

saveButton.addEventListener('click', (event) => {
  event.preventDefault();
  saveProjectName();
});

cancelButton.addEventListener('click', (event) => {
  event.preventDefault();
  projectNameInput.value = originalProjectName;
  hideFormWrappers();
  editWrappers[0].style.display = 'block';
});

// Project Visibility

const visibilityCheckbox = document.getElementById("visibility-checkbox");

// Fetch form data and submit to PHP script
visibilityCheckbox.addEventListener("change", function(event) {
  event.preventDefault(); // Prevent default form submission behavior
  const projectId = document.querySelector('[name="project_id"]').value;
  const visible = visibilityCheckbox.checked ? 1 : 0;
  const formData = new FormData();
  formData.append('project_id', projectId);
  formData.append('visible', visible);

  // Create fetch request
  fetch("project_visibility.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    console.log(data);
  })
  .catch(error => console.error(error));
});

// Project Delete

const deleteForm = document.querySelector('[data-form-id="delete-project-form"]');

deleteForm.addEventListener('submit', async (event) => {
  const confirmed = confirm(deleteForm.getAttribute('data-confirm-message'));
  if (!confirmed) {
    event.preventDefault();
    return;
  }

  const projectId = deleteForm.querySelector('input');
  const id = projectId.value;
  const formData = new FormData();
  formData.append('project_id', id);

  try {
    const response = await fetch('project_delete.php', {
      method: 'POST',
      body: formData
    });
    const data = await response.json();
    if (data.success) {
      const url = `${data.redirect}?data=${encodeURIComponent(JSON.stringify(data))}`; // Redirect to the project page with the id
      window.location.href = url;
    } else {
      console.log(data.error);
    }
  } catch (error) {
    console.log(error);
  }
});
