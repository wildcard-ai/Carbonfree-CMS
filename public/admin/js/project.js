/*
  Upload Images
*/

// Selectors
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

// State
let isAlreadyExpanded = false;
let deleteImageIds = new Set(); // Use a Set instead of an array
let images = new Set();

// Event listeners
openImageModalBtn.addEventListener('click', handleOpenImageModal);
closeImageModalBtn.addEventListener('click', handleCloseImageModal);
imageModal.addEventListener('keydown', handleImageModalKeydown);
uploadForm.addEventListener('change', handleFileUpload);
doneButton.addEventListener('click', handleDoneButton);
selectAllButton.addEventListener('click', toggleSelectButton);
deleteImageButton.addEventListener('click', () => deleteSelectedImagesFromDOM(deleteImageIds));
imageList.addEventListener('click', function (event) {
  if (event.target.matches('[data-checkbox="image"]')) {
    updateDeleteImageIds(event);
    toggleDeleteToolbar();
    countSelected();
    renameSelectAllButton();
  }
});

// Functions
function handleOpenImageModal() {
  openImage();
  imageModal.showModal();
}

function handleCloseImageModal() {
  cancelImage();
  imageModal.close();
}

function handleImageModalKeydown(event) {
  if (event.key === 'Escape') {
    cancelImage();
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

function createImageContainer(id, url, caption) {
  const newEl = document.createElement('div');
  newEl.classList.add('image-caption-container');

  newEl.innerHTML = `
    <label class="image-container">
      <img class="uploaded-image" src="${url}" data-image-id="${id}">
      <input class="image-checkbox" type="checkbox" data-checkbox="image" data-image-id="${id}">
    </label>
    <div class="caption-container" data-caption-collapse-id="caption">
      <input type="text" class="caption-input" data-input="caption" data-image-id="${id}" value="${caption}" placeholder="Add caption...">
    </div>
  `;

  return newEl;
}

function handleDoneButton() {
  images.clear();
  const modalImages = document.querySelectorAll('[data-list-id="upload"] .uploaded-image');

  modalImages.forEach((image) => {
    const captionInput = image.parentNode.nextElementSibling.querySelector('.caption-input');
    const id = parseInt(image.getAttribute('data-image-id'));
    const url = image.getAttribute('src');
    const caption = captionInput.value;
    images.add({ id: id, url: url, caption: caption });
  });

  const sortedImages = Array.from(images).sort((a, b) => b.id - a.id);

  sortedImages.forEach(({ id, url, caption }) => {
    const newDiv = createViewerImage(id, url, caption);
    viewerImageList.appendChild(newDiv);
  });

  const imageContainers = document.querySelectorAll('.image-caption-container');

  imageContainers.forEach(function (imageContainer) {
    const imageId = imageContainer.querySelector('.image-checkbox').getAttribute('data-image-id');

    unmarkImageAsDraft(imageId);
  });

  deleteSelectedImagesFromDatabase(deleteImageIds);
  addCaption();
  imageList.innerHTML = '';
  images.clear();
  imageModal.close();
}

function toggleSelectButton() {
  const checkboxes = imageList.querySelectorAll('[data-checkbox="image"]');
  const allChecked = isAllChecked();

  checkboxes.forEach((checkbox) => {
    checkbox.checked = !allChecked;
    updateDeleteImageIds({ target: checkbox });
  });

  renameSelectAllButton();
  toggleDeleteToolbar();
  countSelected();
}

function deleteSelectedImagesFromDOM() {
  deleteImageIds.forEach((imageId) => {
    const imageContainer = document.querySelector('.modal.manage-images [data-image-id="' + imageId + '"]');
    if (imageContainer) {
      const parentContainer = imageContainer.parentNode.parentNode;
      parentContainer.remove();
    }
  });
  toggleDeleteToolbar();
  countSelected();
}

function openImage() {
  images.clear();
  const viewerImages = document.querySelectorAll('[data-viewer="images"] .viewer-image');

  viewerImages.forEach((image) => {
    const id = parseInt(image.getAttribute('data-image-id'));
    const url = image.getAttribute('src');
    const caption = image.getAttribute('alt');
    images.add({ id: id, url: url, caption: caption });
  });
  
  const sortedImages = Array.from(images).sort((a, b) => b.id - a.id);

  sortedImages.forEach(({ id, url, caption }) => {
    const newDiv = createImageContainer(id, url, caption);
    imageList.appendChild(newDiv);
  });
  viewerImageList.innerHTML = '';
}

function cancelImage() {
  deleteDrafts();

  const sortedImages = Array.from(images).sort((a, b) => b.id - a.id);

  sortedImages.forEach(({ id, url, caption }) => {
    const newDiv = createViewerImage(id, url, caption);
    viewerImageList.appendChild(newDiv);
  });

  imageList.innerHTML = ''; // Clear the existing content in imageList
  images.clear();
}

function createViewerImage(id, url, caption) {
  const div = document.createElement('div');
  div.classList.add('image-container');

  const img = document.createElement('img');
  img.classList.add('viewer-image');
  img.setAttribute('src', url);
  img.setAttribute('alt', caption);
  img.setAttribute('data-image-id', id);

  div.appendChild(img);
  return div;
}

function deleteDrafts() {
  fetch('check_drafts.php')
    .then(response => response.json())
    .then(data => {
      if (data.hasDrafts) {
        fetch('delete_drafts.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          }
        })
        .then(response => response.json())
        .then(data => {
          console.log(data.message);
        })
        .catch(error => {
          console.error('Error:', error);
        });
      } else {
        console.log('No drafts found');
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
}

function unmarkImageAsDraft(imageId) {
  if (imageId) {
    const formData = new FormData();
    formData.append('image_id', imageId);

    fetch('unmark_image_as_draft.php', {
      method: 'POST',
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
      })
      .catch((error) => console.log(error));
  }
}

function deleteSelectedImagesFromDatabase(deleteImageIds) {
  fetch('delete_images.php', {
    method: 'POST',
    body: JSON.stringify(Array.from(deleteImageIds)),
    headers: {
      'Content-Type': 'application/json'
    }
  })
  .then(response => response.json())
  .then(data => {
    console.log(data);

    deleteImageIds.clear();
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
  selectedCount.textContent = `${checkedCheckboxes.length} selected`;
}

function renameSelectAllButton() {
  selectAllButton.textContent = isAllChecked() ? 'Clear Selection' : 'Select All';
}

function expandToolbar(element) {
  if(isAlreadyExpanded === true) {
    return;
  }
  element.classList.replace('collapse', 'toolbar-collapsing');
  element.style.height = element.scrollHeight + 'px';

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
  element.style.height = null;

  function onTransitionEnd() {
    element.classList.replace('toolbar-collapsing', 'collapse');
    element.removeEventListener('transitionend', onTransitionEnd);
  }

  element.addEventListener('transitionend', onTransitionEnd);
  isAlreadyExpanded = false;
}

function isAllChecked() {
  const checkboxes = imageList.querySelectorAll('[data-checkbox="image"]');
  return Array.from(checkboxes).every((checkbox) => checkbox.checked);
}

function updateDeleteImageIds(event) {
  const { target: checkbox } = event;
  const imageId = checkbox.getAttribute('data-image-id');

  if (checkbox.checked) {
    deleteImageIds.add(imageId);
  } else {
    deleteImageIds.delete(imageId);
  }
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