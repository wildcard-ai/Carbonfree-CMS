/*
  Upload Images
*/

// Constants
const imageModal = document.querySelector('[data-dialog="image"]');
const openImageModalBtn = document.querySelector('[data-modal-target="image"]');
const closeImageModalBtn = document.querySelector('[data-dismiss="image"]');
const uploadForm = document.querySelector('[data-form-id="upload"]');
const fileInput = document.querySelector('[data-input-id="upload"]');
const imageList = document.querySelector('[data-list-id="upload"]');
const selectedCount = document.querySelector('[data-selected-count="image"]');
const deleteImageButton = document.querySelector('[data-delete-button="image"]');
const selectAllButton = document.querySelector('[data-select-all-button="image"]');
const deleteToolbar = document.querySelector('[data-delete-buttons="toolbar"]');
const doneButton = document.querySelector('[data-done-button="images"]');
const viewerImageList = document.querySelector('[data-viewer="images"]');
let imageIds = new Set(); // Use a Set instead of an array
let deletedImageIds = new Set(); // Set to store deleted image IDs
let isAlreadyExpanded = false;
let deletedImages = [];

// Event listeners

openImageModalBtn.addEventListener('click', function() {
  imageList.innerHTML = ''; // Clear the existing content in imageList
  const viewerImages = document.querySelectorAll('[data-viewer="images"] .viewer-image');
  const images = [];
  
  // Extract image IDs and URLs from viewerImages
  viewerImages.forEach((image) => {
    const id = parseInt(image.getAttribute('data-image-id'));
    const url = image.getAttribute('src');
    images.push({ id, url });
  });
  
  // Sort images by descending ID
  images.sort((a, b) => b.id - a.id);
  
  // Create and insert image containers in descending order
  images.forEach(({ id, url }) => {
    const newDiv = createImageContainer(id, url);
    imageList.appendChild(newDiv);
  });  

  imageModal.showModal();
});

closeImageModalBtn.addEventListener('click', function() {
  imageModal.close();
});

uploadForm.addEventListener('change', handleFileUpload);
doneButton.addEventListener('click', finishImages);
selectAllButton.addEventListener('click', toggleSelectButton);
deleteImageButton.addEventListener('click', () => deleteSelectedImagesFromDOM(imageIds));

// Attach the event listener to a parent element
imageList.addEventListener('click', function (event) {
  // Check if the clicked element is a checkbox
  if (event.target.matches('[data-checkbox="image"]')) {
    updateImageIds(event);
    toggleDeleteToolbar();
    countSelected();
    renameSelectAllButton();
  }
});

// Functions

function createViewerImage(id, url) {
  const div = document.createElement('div');
  div.classList.add('image-container');

  const img = document.createElement('img');
  img.classList.add('viewer-image');
  img.setAttribute('src', url);
  img.setAttribute('data-image-id', id);

  div.appendChild(img);
  return div;
}

function deleteSelectedImagesFromDOM() {
  console.log('test');
  imageIds.forEach((imageId) => {
    const imageContainer = document.querySelector('.modal.manage-images [data-image-id="' + imageId + '"]');
    if (imageContainer) {
      const clonedContainer = imageContainer.cloneNode(true);
      const parentContainer = imageContainer.parentNode.parentNode;
      parentContainer.remove();

      deletedImages.push(clonedContainer);
      deletedImageIds.add(imageId);
    }
  });
  toggleDeleteToolbar();
  countSelected();
}

function finishImages() {

  viewerImageList.innerHTML = ''; // Clear the existing content in viewerImageList
  const modalImages = document.querySelectorAll('[data-list-id="upload"] .uploaded-image');
  const images = [];
  
  // Extract image IDs and URLs from modalImages
  modalImages.forEach((image) => {
    const id = parseInt(image.getAttribute('data-image-id'));
    const url = image.getAttribute('src');
    images.push({ id, url });
  });
  
  // Sort images by descending ID
  images.sort((a, b) => b.id - a.id);
  
  // Create and insert image containers in descending order
  images.forEach(({ id, url }) => {
    const newDiv = createViewerImage(id, url);
    viewerImageList.appendChild(newDiv);
  });  

  const imageContainers = document.querySelectorAll('.image-caption-container');

  // Iterate over the image containers and check if they are already added to the viewer
  imageContainers.forEach(function (imageContainer) {
    const imageId = imageContainer.querySelector('.image-checkbox').getAttribute('data-image-id');

    unmarkImageAsDraft(imageId);
  });
  deleteSelectedImagesFromDatabase(imageIds);
  addCaption();
  imageModal.close();
}

function unmarkImageAsDraft(imageId) {
  console.log(imageId);
  if (imageId) {
    // Send an AJAX request to update the image's draft status
    const formData = new FormData();
    formData.append('image_id', imageId);

    fetch('unmark_image_as_draft.php', {
      method: 'POST',
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          console.log('Image draft status updated');
        } else {
          console.log('Failed to update image draft status');
        }
      })
      .catch((error) => console.log(error));
  }
}

function deleteSelectedImagesFromDatabase(imageIds) {
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

    imageIds.clear(); // Use the clear method to empty the Set
  })
  .catch(error => {
    console.log('Error:', error);
  });
}

function addCaption() {
  const inputs = document.querySelectorAll('[data-input="caption"]');
  const data = [];

  inputs.forEach(input => {
    const id = input.getAttribute('data-image-id');
    const caption = input.value;

    data.push({
      id: id,
      caption: caption
    });
  });

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
    // Handle the response from the server if needed
  })
  .catch(error => {
    console.error('Error:', error);
  });
}

function toggleDeleteToolbar() {
  const checkedCheckboxes = document.querySelectorAll('.image-checkbox:checked');

  if(checkedCheckboxes.length === 0) {
    collapseToolbar(deleteToolbar);
  } else {
    expandToolbar(deleteToolbar);
  }
}

function countSelected() {
  const checkedCheckboxes = document.querySelectorAll('.image-checkbox:checked');
  selectedCount.textContent = `${checkedCheckboxes.length} selected`; // Use the size property for Sets
}

function renameSelectAllButton() {
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
    element.removeEventListener('transitionend', onTransitionEnd);
  }

  element.addEventListener('transitionend', onTransitionEnd);
  isAlreadyExpanded = true;
}

function collapseToolbar(element) {

  element.classList.remove('collapse', 'show');
  element.classList.add('toolbar-collapsing');
  element.style.height = null; // collapse

  function onTransitionEnd() {
    element.classList.replace('toolbar-collapsing', 'collapse');
    element.removeEventListener('transitionend', onTransitionEnd);
  }

  element.addEventListener('transitionend', onTransitionEnd);
  isAlreadyExpanded = false;
}

function toggleSelectButton() {
  const checkboxes = imageList.querySelectorAll('[data-checkbox="image"]');
  const allChecked = isAllChecked();

  checkboxes.forEach((checkbox) => {
    checkbox.checked = !allChecked;
    updateImageIds({ target: checkbox }); // Create an event object with the checkbox as the target and pass it to updateImageIds
  });

  renameSelectAllButton();
  toggleDeleteToolbar();
  countSelected();
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
  img.dataset.imageId = id;

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

  captionContainer.appendChild(captionInput);
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