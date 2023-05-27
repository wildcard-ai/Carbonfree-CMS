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
const deleteToolbar = document.querySelector('[data-delete-buttons="toolbar"]');
const addCaptionButton = document.querySelector('[data-done-button="caption"]');
const cancelCaptionButton = document.querySelector('[data-cancel-button="caption"]');
let imageIds = new Set(); // Use a Set instead of an array
let isAlreadyExpanded = false;

// Event listeners

uploadForm.addEventListener('change', handleFileUpload);
selectAllButton.addEventListener('click', selectionToggle);
deleteImageButton.addEventListener('click', () => deleteSelectedImages(imageIds));

// Attach the event listener to a parent element
imageList.addEventListener('click', function (event) {
  // Check if the clicked element is a checkbox
  if (event.target.matches('[data-checkbox="image"]')) {
    updateImageIds(event);
    updateUI();
  }
});

imageList.addEventListener('click', function (event) {
  // Check if the clicked element is a checkbox
  if (event.target.matches('[data-add-button="caption"]')) {
    addCaption(event.target);
  }
});

imageList.addEventListener('click', function (event) {
  // Check if the clicked element is a checkbox
  if (event.target.matches('[data-input="caption"]')) {
    showCaptionButtons(event.target);
  }
});

imageList.addEventListener('click', function (event) {
  // Check if the clicked element is a checkbox
  if (event.target.matches('[data-cancel-button="caption"]')) {
    hideCaptionButtons(event.target);
  }
});

// Functions

function showCaptionButtons(input) {
  input.nextElementSibling.classList.add('show');
}

function hideCaptionButtons(cancelButton) {
  //const inputValue = cancelButton.parentNode.previousElementSibling.value;
  cancelButton.parentNode.classList.remove('show');
}

function addCaption(addButton) {
  const input = addButton.parentNode.previousElementSibling;
  const id = input.getAttribute('data-image-id');
  console.log(id);
  
  const caption = input.value;
  console.log(caption);

  // Create an object with the data to send
  const data = {
    id: id,
    caption: caption
  };

  // Make a Fetch request to the PHP file
  fetch('add_caption.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
  })
  .then(response => response.json())
  .then(data => {
    console.log(data);
    addButton.parentNode.classList.remove('show');
  })
  .catch(error => {
    console.error('Error:', error);
  });
}

function updateUI() {

  if(imageIds.size === 0) {
    collapseToolbar(deleteToolbar);
  } else {
    expandToolbar(deleteToolbar);
  }

  selectedCount.textContent = `${imageIds.size} selected`; // Use the size property for Sets

  selectAllButton.textContent = isAllChecked() ? 'Clear Selection' : 'Select All';
}

function expandToolbar(element) {
  if(isAlreadyExpanded === true) {
    return;
  }
  element.classList.replace('collapse', 'toolbar-collapsing');
  element.style.height = element.scrollHeight + 'px'; // Set the expanded height immediately

  function onTransitionEnd() {
    element.classList.replace('toolbar-collapsing', 'collapse');
    element.classList.add('show');
    element.style.height = null;
    element.removeEventListener('transitionend', onTransitionEnd);
  }

  element.addEventListener('transitionend', onTransitionEnd);
  isAlreadyExpanded = true;
}

function collapseToolbar(element) {
  if(isAlreadyExpanded === false) {
    return;
  }
  const expandedHeight = element.clientHeight + 'px';
  element.style.height = expandedHeight;

  // Check if the style change has been applied
  if (window.getComputedStyle(element).height === expandedHeight) {
    element.classList.remove('collapse', 'show');
    element.classList.add('toolbar-collapsing');
    element.style.height = null; // collapse
  }

  function onTransitionEnd() {
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
    updateImageIds({ target: checkbox }); // Create an event object with the checkbox as the target and pass it to updateImageIds
  });

  updateUI();
  // console.log(Array.from(imageIds)); // Convert the Set to an array if needed
}

function isAllChecked() {
  const checkboxes = imageList.querySelectorAll('[data-checkbox="image"]');
  return Array.from(checkboxes).every((checkbox) => checkbox.checked);
}

function updateImageIds(event) {
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
        imageElement.parentNode.parentNode.remove();
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
  const newDiv = document.createElement('div');
  newDiv.classList.add('image-caption-container');

  const label = document.createElement('label');
  label.classList.add('image-container');

  const img = document.createElement('img');
  img.classList.add('uploaded-image');
  img.src = url;

  const checkbox = document.createElement('input');
  checkbox.classList.add('image-checkbox');
  checkbox.type = 'checkbox';
  checkbox.dataset.checkbox = 'image';
  checkbox.dataset.imageId = id;

  const captionContainer = document.createElement('div');
  captionContainer.classList.add('caption-container');
  captionContainer.dataset.captionCollapseId = 'caption';

  const captionInput = document.createElement('input');
  captionInput.type = 'text';
  captionInput.classList.add('caption-input');
  captionInput.dataset.input = 'caption';
  captionInput.dataset.imageId = id;
  captionInput.placeholder = 'Add caption...';

  const captionButtons = document.createElement('div');
  captionButtons.classList.add('caption-buttons');
  captionButtons.classList.add('collapse');

  const saveButton = document.createElement('button');
  saveButton.classList.add('button');
  saveButton.classList.add('button-secondary');
  saveButton.classList.add('caption-button');
  saveButton.dataset.addButton = 'caption';
  saveButton.textContent = 'Save';

  const cancelButton = document.createElement('button');
  cancelButton.classList.add('button');
  cancelButton.classList.add('button-light');
  cancelButton.classList.add('caption-button');
  cancelButton.dataset.cancelButton = 'caption';
  cancelButton.textContent = 'Cancel';

  captionButtons.appendChild(saveButton);
  captionButtons.appendChild(cancelButton);

  captionContainer.appendChild(captionInput);
  captionContainer.appendChild(captionButtons);
  label.appendChild(img);
  label.appendChild(checkbox);
  newDiv.appendChild(label);
  newDiv.appendChild(captionContainer);

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