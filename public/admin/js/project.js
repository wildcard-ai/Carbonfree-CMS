/*
  Upload Section
*/

// Edit button

const editImageButton = document.querySelector('[data-edit-button="image"]');

editImageButton.addEventListener('click', function() {
  showCheckboxes();
});

function showCheckboxes() {
  const uploadDataList = document.querySelector('[data-list-id="upload"]');
  const checkboxes = uploadDataList.querySelectorAll('input[type="checkbox"]');
  
  checkboxes.forEach((checkbox) => {
    checkbox.classList.toggle('show');
    if (checkbox.classList.contains('show')) {
      checkbox.disabled = false;
      editImageButton.setAttribute('data-edit-button-toggled', 'true');
      editImageButton.textContent = 'Done';
      editImageButton.classList.replace('button-primary', 'button-success');
    } else {
      checkbox.disabled = true;
      editImageButton.setAttribute('data-edit-button-toggled', 'false');
      editImageButton.textContent = 'Edit';
      editImageButton.classList.replace('button-success', 'button-primary');
    }
  });
}

// Delete and Select button

const uploadForm = document.querySelector('[data-form-id="upload"]');
const selectedCount = document.querySelector('[data-selected-count="image"]');
const deleteImageButton = document.querySelector('[data-delete-button="image"]');
const selectAllButton = document.querySelector('[data-select-all-button="image"]');
const clearSelectionButton = document.querySelector('[data-clear-selection-button="image"]');
let imageIds = [];

function updateDeleteButtonVisibility() {
  if (imageIds.length > 0) {
    selectedCount.classList.add('show');
    deleteImageButton.classList.add('show');
    selectAllButton.classList.add('show');

    editImageButton.classList.remove('show');
    uploadForm.classList.remove('show');
    console.log(imageIds.length);
  } else if(imageIds.length === 0) {
    selectedCount.classList.remove('show');
    deleteImageButton.classList.remove('show');
    selectAllButton.classList.remove('show');
    editImageButton.classList.add('show');
    uploadForm.classList.add('show');
    console.log(imageIds.length);
  }
}

// Select All images

selectAllButton.addEventListener('click', function(){
  selectAllImages();
});

function areAllChecked() {
  var checkboxes = document.querySelectorAll('[data-checkbox="image"]');
  for (var i = 0; i < checkboxes.length; i++) {
    if (!checkboxes[i].checked) {
      return false;
    }
  }
  return true;
}

function selectAllImages() {
  imageIds = [];
  const uploadDataList = document.querySelector('[data-list-id="upload"]');
  const checkboxes = uploadDataList.querySelectorAll('input[type="checkbox"]');

  let allChecked = true;
  checkboxes.forEach((checkbox) => {
    if (!checkbox.checked) {
      allChecked = false;
    }
  });

  checkboxes.forEach((checkbox) => {
    if (imageIds.length !== checkboxes.length || allChecked) {
      checkbox.checked = !allChecked;
      if (!allChecked) {
        selectAllButton.textContent = 'Clear Selection';
        const imageId = checkbox.getAttribute('data-image-id');
        imageIds.push(imageId);
        selectedCount.textContent = imageIds.length + ' selected';
        updateDeleteButtonVisibility();
      } else {
        selectAllButton.textContent = 'Select All';
        selectedCount.textContent = imageIds.length + ' selected';
        updateDeleteButtonVisibility();
      }
    }
  });
  console.log(imageIds);
}

//  Delete Images

document.addEventListener('click', function(event) {
  const target = event.target;
  if (target.matches('[data-checkbox="image"]')) {
    if (target.checked) {
      const imageId = target.getAttribute('data-image-id');
      imageIds.push(imageId);
      updateDeleteButtonVisibility();
      console.log(imageIds);
      selectedCount.textContent = imageIds.length + ' selected';
      if(areAllChecked()){
        selectAllButton.textContent = 'Clear Selection';
      }
    } else {
      const imageId = target.getAttribute('data-image-id');
      const index = imageIds.indexOf(imageId);
      if (index > -1) {
        imageIds.splice(index, 1);
      }
      console.log(imageIds);
      selectedCount.textContent = imageIds.length + ' selected';
      updateDeleteButtonVisibility();
      if(!areAllChecked()){
        selectAllButton.textContent = 'Select All';
      }
    }
  }
});

deleteImageButton.addEventListener('click', function() {
  deleteImages(imageIds);
  
  // Reset the imageIds array
  imageIds = [];
});

function deleteImages(imageIds) {
  // Make a POST request to your PHP script with the imageIds data
  fetch('delete_images.php', {
    method: 'POST',
    body: JSON.stringify(imageIds),
    headers: {
      'Content-Type': 'application/json'
    }
  })
  .then(response => response.json())
  .then(data => {
    console.log(data);
    // Remove deleted elements from the DOM
    imageIds.forEach(imageId => {
      const imageElement = document.querySelector(`[data-image-id="${imageId}"]`);
      if (imageElement) {
        imageElement.parentNode.remove();
      }

      updateDeleteButtonVisibility();
    });
  })
  .catch(error => {
    console.log('Error:', error);
  });
}

//  Upload images

const fileInput = document.querySelector('[data-input-id="upload"]');
const fileList = document.querySelector('[data-list-id="upload"]');

uploadForm.addEventListener('change', (event) => {
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
    body: formData,
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
        const newDiv = document.createElement('label');
        newDiv.classList.add('image-container');

        if (editImageButton.getAttribute('data-edit-button-toggled') === 'true') {
          newDiv.innerHTML = `
            <img class="uploaded-image" src="${url}">
            <input class="image-checkbox collapse show" type="checkbox" data-checkbox="image" data-image-id="${id}">
          `;
        } else {
          newDiv.innerHTML = `
            <img class="uploaded-image" src="${url}">
            <input class="image-checkbox collapse" type="checkbox" data-checkbox="image" data-image-id="${id}" disabled>
          `;
        }
    
        fileList.insertBefore(newDiv, fileList.firstChild);
      }
    } else {
      console.log(data.error);
    }
  })
  .catch((error) => console.log(error));

  fileInput.value = '';
});

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