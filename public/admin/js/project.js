/* Upload files */ 

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

/* Details */
// Project Name

const editProjectNameButton = document.querySelector('[data-button-edit="project-name"]');
const projectNameWrappers = document.querySelectorAll('[data-title-collapse="project-name"]');
const editProjectNameWrappers = document.querySelectorAll('[data-edit-collapse="project-name"]');
const formProjectNameWrappers = document.querySelectorAll('[data-form-collapse="project-name"]');
const projectNameInput = document.querySelector('[data-input-id="project-name"]');
const saveProjectNameButton = document.querySelector('[data-button-save="project-name"]');
const cancelProjectNameButton = document.querySelector('[data-button-cancel="project-name"]');

let originalProjectName = '';

function showProjectNameFormWrappers() {
  projectNameWrappers[0].style.display = 'none';
  formProjectNameWrappers[0].style.display = 'block';
  projectNameInput.focus();
  projectNameInput.select();
}

function hideProjectNameFormWrappers() {
  projectNameWrappers[0].style.display = 'block';
  formProjectNameWrappers[0].style.display = 'none';
}

function saveProjectName() {
  const form = document.querySelector('[data-form-id="project-name"]');
  const projectId = form.querySelector('[name="project-id"]').value;
  const projectName = form.querySelector('[name="project-name"]').value;
  const formData = new FormData();
  formData.append('project_id', projectId);
  formData.append('project_name', projectName);

  // Create fetch request
  fetch("project_name.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    console.log(data);
    const newProjectNameElements = document.querySelectorAll('[data-new-project-title="project-name"]');
    const newProjectNameArray = Array.from(newProjectNameElements);
    newProjectNameArray.forEach(element => {
      element.textContent = data.newProjectName;
    });
    originalProjectName = projectName;
    hideProjectNameFormWrappers();
    editProjectNameWrappers[0].style.display = 'block';
  })
  .catch(error => console.error(error));
}

editProjectNameButton.addEventListener('click', (event) => {
  event.preventDefault();
  showProjectNameFormWrappers();
  editProjectNameWrappers[0].style.display = 'none';
  originalProjectName = projectNameInput.value;
});

saveProjectNameButton.addEventListener('click', (event) => {
  event.preventDefault();
  saveProjectName();
});

cancelProjectNameButton.addEventListener('click', (event) => {
  event.preventDefault();
  projectNameInput.value = originalProjectName;
  hideProjectNameFormWrappers();
  editProjectNameWrappers[0].style.display = 'block';
});

// Description

const editDescriptionButton = document.querySelector('[data-button-edit="description"]');
const descriptionWrappers = document.querySelectorAll('[data-title-collapse="description"]');
const editDescriptionWrappers = document.querySelectorAll('[data-edit-collapse="description"]');
const formDescriptionWrappers = document.querySelectorAll('[data-form-collapse="description"]');
const descriptionInput = document.querySelector('[data-input-id="description"]');
const saveDescriptionButton = document.querySelector('[data-button-save="description"]');
const cancelDescriptionButton = document.querySelector('[data-button-cancel="description"]');

let originalDescription = '';

function showDescriptionFormWrappers() {
  descriptionWrappers[0].style.display = 'none';
  formDescriptionWrappers[0].style.display = 'block';
  descriptionInput.focus();
  descriptionInput.select();
}

function hideDescriptionFormWrappers() {
  descriptionWrappers[0].style.display = 'block';
  formDescriptionWrappers[0].style.display = 'none';
}

function saveDescription() {
  const form = document.querySelector('[data-form-id="description"]');
  const projectId = form.querySelector('[name="project-id"]').value;
  const description = form.querySelector('[name="description"]').value;
  const formData = new FormData();
  formData.append('project_id', projectId);
  formData.append('description', description);

  // Create fetch request
  fetch("project_description.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    console.log(data);
    const newDescriptionElements = document.querySelectorAll('[data-new-description="description"]');
    const newDescriptionArray = Array.from(newDescriptionElements);
    newDescriptionArray.forEach(element => {
      element.textContent = data.newDescription;
    });
    originalDescription = description;
    hideDescriptionFormWrappers();
    editDescriptionWrappers[0].style.display = 'block';
  })
  .catch(error => console.error(error));
}

editDescriptionButton.addEventListener('click', (event) => {
  event.preventDefault();
  showDescriptionFormWrappers();
  editDescriptionWrappers[0].style.display = 'none';
  originalDescription = descriptionInput.value;
});

saveDescriptionButton.addEventListener('click', (event) => {
  event.preventDefault();
  saveDescription();
});

cancelDescriptionButton.addEventListener('click', (event) => {
  event.preventDefault();
  descriptionInput.value = originalDescription;
  hideDescriptionFormWrappers();
  editDescriptionWrappers[0].style.display = 'block';
});

/* Project Visibility */

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