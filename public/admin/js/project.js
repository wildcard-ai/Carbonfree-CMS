// Upload files

const uploadForm = document.querySelector('[data-form-id="upload"]');
const fileInput = document.querySelector('[data-input-id="upload"]');
const fileList = document.querySelector('[data-list-id="upload"]');

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
      console.log(data);
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

const editButton = document.querySelector('[data-button-edit="project-name"]');
const projectNameWrappers = document.querySelectorAll('[data-title-collapse="project-name"]');
const editWrappers = document.querySelectorAll('[data-edit-collapse="project-name"]');
const formWrappers = document.querySelectorAll('[data-form-collapse="project-name"]');
const projectNameInput = document.querySelector('[data-input-id="project-name"]');
const saveButton = document.querySelector('[data-button-save="project-name"]');
const cancelButton = document.querySelector('[data-button-cancel="project-name"]');

let originalProjectName = '';

function showFormWrappers() {
  projectNameWrappers[0].style.display = 'none';
  formWrappers[0].style.display = 'block';
  projectNameInput.focus();
  projectNameInput.select();
}

function hideFormWrappers() {
  projectNameWrappers[0].style.display = 'block';
  formWrappers[0].style.display = 'none';
}

function saveProjectName() {
  const form = document.querySelector('[data-form-id="project-name"]');
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
      element.textContent = data.newProjectName;
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

const visibilityCheckbox = document.querySelector('[data-checkbox-type="visibility"]');

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