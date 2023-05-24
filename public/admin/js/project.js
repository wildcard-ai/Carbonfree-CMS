/*
  Upload Section
*/

// Constants
const uploadForm = document.querySelector('[data-form-id="upload"]');
const fileInput = document.querySelector('[data-input-id="upload"]');
const imageList = document.querySelector('[data-list-id="upload"]');
const editImageButton = document.querySelector('[data-edit-button="image"]');
const selectedCount = document.querySelector('[data-selected-count="image"]');
const deleteImageButton = document.querySelector('[data-delete-button="image"]');
const selectAllButton = document.querySelector('[data-select-all-button="image"]');
let checkboxes = document.querySelectorAll('[data-checkbox="image"]');
let imageIds = new Set(); // Use a Set instead of an array

// Event listeners

uploadForm.addEventListener('change', handleFileUpload);
editImageButton.addEventListener('click', toggleEditMode);
selectAllButton.addEventListener('click', selectionToggle);
deleteImageButton.addEventListener('click', function() {
  deleteImages(imageIds);
});
checkboxes.forEach((checkbox) => {
  checkbox.addEventListener('click', function (event) { // Add event parameter
    getImageIds(event); // Pass the event object to getImageIds
  });
});

// Functions

function toggleEditMode() {
  const checkboxes = imageList.querySelectorAll('[data-checkbox="image"]');

  checkboxes.forEach((checkbox) => {
    checkbox.classList.toggle('show');
    checkbox.disabled = !checkbox.classList.contains('show');
  });

  if (editImageButton.getAttribute('data-edit-button-toggled') === 'true') {
    editImageButton.textContent = 'Edit';
    editImageButton.classList.replace('button-success', 'button-primary');
    editImageButton.setAttribute('data-edit-button-toggled', 'false');
  } else {
    editImageButton.textContent = 'Done';
    editImageButton.classList.replace('button-primary', 'button-success');
    editImageButton.setAttribute('data-edit-button-toggled', 'true');
  }
}

function updateUI() {
  const hasSelectedImages = imageIds.size > 0; // Use the size property for Sets

  selectedCount.classList.toggle('show', hasSelectedImages);
  deleteImageButton.classList.toggle('show', hasSelectedImages);
  selectAllButton.classList.toggle('show', hasSelectedImages);
  editImageButton.classList.toggle('show', !hasSelectedImages);
  uploadForm.classList.toggle('show', !hasSelectedImages);

  selectedCount.textContent = `${imageIds.size} selected`; // Use the size property for Sets

  if (isAllChecked()) {
    selectAllButton.textContent = 'Clear Selection';
  } else {
    selectAllButton.textContent = 'Select All';
  }
}

function selectionToggle() {
  const checkboxes = imageList.querySelectorAll('[data-checkbox="image"]');
  const allChecked = isAllChecked();

  checkboxes.forEach((checkbox) => {
    checkbox.checked = !allChecked;
    const event = { target: checkbox }; // Create an event object with the checkbox as the target
    getImageIds(event); // Pass the event object to getImageIds
  });

  updateUI();
  console.log(Array.from(imageIds)); // Convert the Set to an array if needed
}

function isAllChecked() {
  const checkboxes = imageList.querySelectorAll('[data-checkbox="image"]');
  return Array.from(checkboxes).every((checkbox) => checkbox.checked);
}

function getImageIds(event) {
  const checkbox = event.target;
  console.log(checkbox);
  const imageId = checkbox.getAttribute('data-image-id');
  console.log(imageId);

  if (checkbox.checked) {
    imageIds.add(imageId);
  } else {
    imageIds.delete(imageId);
  }

  updateUI();
}

function deleteImages(imageIds) {
  fetch('delete_images.php', {
    method: 'POST',
    body: JSON.stringify(Array.from(imageIds)), // Convert the Set to an array before sending
    headers: {
      'Content-Type': 'application/json'
    }
  })
  .then(response => response.json())
  .then(data => {
    console.log(data);

    imageIds.forEach(imageId => {
      const imageElement = document.querySelector(`[data-image-id="${imageId}"]`);
      if (imageElement) {
        imageElement.parentNode.remove();
      }
    });

    imageIds.clear(); // Use the clear method to empty the Set
    updateUI();
  })
  .catch(error => {
    console.log('Error:', error);
  });
}

function handleFileUpload(event) {
  event.preventDefault();

  const files = fileInput.files;
  const projectId = uploadForm.getAttribute('data-project-id');
  const formData = new FormData();

  formData.append('project_id', projectId);

  for (let i = 0; i < files.length; i++) {
    let file = files[i];
    formData.append('files[]', file);
  }

  fetch('project_upload.php', {
    method: 'POST',
    body: formData
  })
  .then((response) => response.json())
  .then((data) => {
    if (data.success) {
      console.log(data);
      const ids = data.ids;
      const pathUrls = data.pathUrls;
    
      for (let i = 0; i < ids.length; i++) {
        const id = ids[i];
        const path = Object.keys(pathUrls)[i];
        const url = pathUrls[path];
        const newDiv = createImageContainer(id, url);
    
        imageList.insertBefore(newDiv, imageList.firstChild);
      }
    } else {
      console.log(data.error);
    }
  })
  .catch((error) => console.log(error));

  fileInput.value = '';
}

function createImageContainer(id, url) {
  const newDiv = document.createElement('label');
  newDiv.classList.add('image-container');

  const checkbox = document.createElement('input');
  checkbox.classList.add('image-checkbox', 'collapse');
  checkbox.type = 'checkbox';
  checkbox.dataset.checkbox = 'image';
  checkbox.dataset.imageId = id;

  // Attach event listener to the checkbox
  checkbox.addEventListener('click', function(event) {
    getImageIds(event);
  });

  if (editImageButton.getAttribute('data-edit-button-toggled') === 'true') {
    checkbox.classList.add('show');
    checkbox.disabled = false;
  } else {
    checkbox.disabled = true;
  }

  const img = document.createElement('img');
  img.classList.add('uploaded-image');
  img.src = url;

  newDiv.appendChild(img);
  newDiv.appendChild(checkbox);

  checkboxes = document.querySelectorAll('[data-checkbox="image"]');

  return newDiv;
}

/*
  Project Details
*/

const projectNameEditButton = document.querySelector('[data-edit-button="project-name"]');
const projectNameCancelButton = document.querySelector('[data-cancel-button="project-name"]');
const projectNameForm = document.querySelector('[data-form-id="project-name"]');
const projectNameNew = document.querySelectorAll('[data-update="project-name"]');

projectNameEditButton.addEventListener('click', () => toggleDetails(projectNameEditButton));
projectNameCancelButton.addEventListener('click', () => toggleDetails(projectNameCancelButton));
projectNameForm.addEventListener('submit', function(event) {
  saveProject(event, projectNameForm, projectNameNew);
});

const descriptionEditButton = document.querySelector('[data-edit-button="description"]');
const descriptionCancelButton = document.querySelector('[data-cancel-button="description"]');
const descriptionForm = document.querySelector('[data-form-id="description"]');
const descriptionNew = document.querySelectorAll('[data-update="description"]');

descriptionEditButton.addEventListener('click', () => toggleDetails(descriptionEditButton));
descriptionCancelButton.addEventListener('click', () => toggleDetails(descriptionCancelButton));
descriptionForm.addEventListener('submit', function(event) {
  saveProject(event, descriptionForm, descriptionNew);
});

function toggleDetails(element) {
  const collapseTargetIds = element.getAttribute('data-collapse-target');
  const collapseTargets = document.querySelectorAll(`[data-collapse-id="${collapseTargetIds}"]`);

  const targets = Array.from(collapseTargets);
  targets.forEach(function(target) {
    target.classList.toggle('show');
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
      element.innerHTML = data.newText.replace(/\n/g, '<br>');
    });
    toggleDetails(form);
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