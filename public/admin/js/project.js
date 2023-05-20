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

/* Project Details */

const projectNameEditButton = document.querySelector('[data-edit-button="project-name"]');
const projectNameSaveButton = document.querySelector('[data-save-button="project-name"]');
const projectNameCancelButton = document.querySelector('[data-cancel-button="project-name"]');
const projectNameForm = document.querySelector('[data-form-id="project-name"]');
const projectNameInput = document.querySelector('[data-input-id="project-name"]');
const projectNameNew = document.querySelectorAll('[data-update="project-name"]');

let originalText = '';

projectNameEditButton.addEventListener('click', toggleDetails);
projectNameCancelButton.addEventListener('click', toggleDetails);
projectNameForm.addEventListener('submit', function(event) {
  saveProject(event, projectNameForm, projectNameNew);
});

const descriptionEditButton = document.querySelector('[data-edit-button="description"]');
const descriptionSaveButton = document.querySelector('[data-save-button="description"]');
const descriptionCancelButton = document.querySelector('[data-cancel-button="description"]');
const descriptionForm = document.querySelector('[data-form-id="description"]');
const descriptionNew = document.querySelectorAll('[data-update="description"]');

descriptionEditButton.addEventListener('click', toggleDetails);
descriptionCancelButton.addEventListener('click', toggleDetails);
descriptionForm.addEventListener('submit', function(event) {
  saveProject(event, descriptionForm, descriptionNew);
});

function toggleDetails() {
  const collapseTargetIds = this.getAttribute('data-target-collapse');
  const collapseTargets = document.querySelectorAll(`[data-collapse-id="${collapseTargetIds}"]`);

  const targets = Array.from(collapseTargets);
  detailsToggle(targets);
}

function detailsToggle(elements) {
  elements.forEach(function(element) {
    element.classList.toggle('show');
  });
}

function saveProject(event, formElement, updateElements) {
  event.preventDefault();
  const form = event.target;
  const projectId = form.querySelector('[name="project-id"]').value;
  const formData = new FormData();
  formData.append('project_id', projectId);

  if (formElement === projectNameForm) {
    const projectName = form.querySelector('[name="project-name"]').value;
    formData.append('project_name', projectName);
  }
  
  if (formElement === descriptionForm) {
    const description = form.querySelector('[name="description"]').value;
    formData.append('description', description);
  }

  // Create fetch request
  fetch("project_details.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    console.log(data);
    const newTextArray = Array.from(updateElements);
    newTextArray.forEach(element => {
      element.textContent = data.newText;
    });
    const collapseTargetIds = form.getAttribute('data-target-collapse');
    const collapseTargets = document.querySelectorAll(`[data-collapse-id="${collapseTargetIds}"]`);
  
    const targets = Array.from(collapseTargets);
    detailsToggle(targets);
  })
  .catch(error => console.error(error));
}

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