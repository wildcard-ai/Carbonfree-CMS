/*
  Upload Images
*/

// Constants
const uploadForm = document.querySelector('[data-form-id="upload"]');
const fileInput = document.querySelector('[data-input-id="upload"]');
const imageList = document.querySelector('[data-list-id="upload"]');
const editImageButton = document.querySelector('[data-edit-button="image"]');
const selectedCount = document.querySelector('[data-selected-count="image"]');
const deleteImageButton = document.querySelector('[data-delete-button="image"]');
const selectAllButton = document.querySelector('[data-select-all-button="image"]');
const captionContainer = document.querySelector('[data-switch-container="caption"]');
const deleteToolbar = document.querySelector('[data-delete-buttons="toolbar"]');
let imageIds = new Set(); // Use a Set instead of an array

// Event listeners

uploadForm.addEventListener('change', handleFileUpload);
editImageButton.addEventListener('click', toggleEditMode);
selectAllButton.addEventListener('click', selectionToggle);
deleteImageButton.addEventListener('click', () => deleteSelectedImages(imageIds));
// Attach the event listener to a parent element
imageList.addEventListener('click', function (event) {
  // Check if the clicked element is a checkbox
  if (event.target.matches('[data-checkbox="image"]')) {
    getImageIds(event);
    updateUI();
  }
});


// Functions

function toggleEditMode() {
  const checkboxes = imageList.querySelectorAll('[data-checkbox="image"]');
  const isEditModeToggled = editImageButton.getAttribute('data-edit-button-toggled') === 'true';

  if (isEditModeToggled) {
    editImageButton.textContent = 'Edit';
    editImageButton.classList.replace('button-success', 'button-primary');
    editImageButton.setAttribute('data-edit-button-toggled', 'false');
  } else {
    editImageButton.textContent = 'Done';
    editImageButton.classList.replace('button-primary', 'button-success');
    editImageButton.setAttribute('data-edit-button-toggled', 'true');
  }

  checkboxes.forEach((checkbox) => {
    checkbox.classList.toggle('show');
    checkbox.disabled = isEditModeToggled;
  });

  captionContainer.classList.toggle('show');
}

let isAlreadyExpanded = false;
function updateUI() {
  // deleteToolbar.classList.toggle('show', hasSelectedImages);

  // const isShown = deleteToolbar.classList.contains('show');


  if(imageIds.size === 0) {
    collapseToolbar(deleteToolbar);
  } else {
    expandToolbar(deleteToolbar);
  }

  selectedCount.textContent = `${imageIds.size} selected`; // Use the size property for Sets

  selectAllButton.textContent = isAllChecked() ? 'Clear Selection' : 'Select All';
}

/* Toolbar Collapse */
function expandToolbar(element) {
  console.log(isAlreadyExpanded);
  if(isAlreadyExpanded === true) {
    return;
  }
  element.classList.replace('collapse', 'toolbar-collapsing');
  element.style.height = element.scrollHeight + 'px'; // Set the expanded height immediately

  function onTransitionEnd() {
    console.log("expand");
    element.classList.replace('toolbar-collapsing', 'collapse');
    element.classList.add('show');
    element.style.height = null;
    element.removeEventListener('transitionend', onTransitionEnd);
  }

  element.addEventListener('transitionend', onTransitionEnd);
  isAlreadyExpanded = true;
}

function collapseToolbar(element) {
  console.log(isAlreadyExpanded);
  if(isAlreadyExpanded === false) {
    return;
  }
  const expandedHeight = element.clientHeight + 'px';
  console.log(expandedHeight);
  element.style.height = expandedHeight;

  // Check if the style change has been applied
  if (window.getComputedStyle(element).height === expandedHeight) {
    console.log(window.getComputedStyle(element).height);
    element.classList.remove('collapse', 'show');
    element.classList.add('toolbar-collapsing');
    element.style.height = null; // collapse
  }

  function onTransitionEnd() {
    console.log("collapse");
    element.classList.replace('toolbar-collapsing', 'collapse');
    element.removeEventListener('transitionend', onTransitionEnd);
  }

  element.addEventListener('transitionend', onTransitionEnd);
  isAlreadyExpanded = false;
}

function selectionToggle() {
  const checkboxes = imageList.querySelectorAll('[data-checkbox="image"]');
  const allChecked = isAllChecked();

  checkboxes.forEach((checkbox) => {
    checkbox.checked = !allChecked;
    getImageIds({ target: checkbox }); // Create an event object with the checkbox as the target and pass it to getImageIds
  });

  updateUI();
  // console.log(Array.from(imageIds)); // Convert the Set to an array if needed
}

function isAllChecked() {
  const checkboxes = imageList.querySelectorAll('[data-checkbox="image"]');
  return Array.from(checkboxes).every((checkbox) => checkbox.checked);
}

function getImageIds(event) {
  const { target: checkbox } = event; // Destructure the event object to get the checkbox
  const imageId = checkbox.getAttribute('data-image-id');

  if (checkbox.checked) {
    imageIds.add(imageId);
  } else {
    imageIds.delete(imageId);
  }
}

function deleteSelectedImages(imageIds) {
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