// Navbar

document.addEventListener("DOMContentLoaded", function() {
  var toggleBtn = document.querySelector("[data-toggle-target='menu']");
  var item = document.querySelector("[data-toggle-id='menu']");

  toggleBtn.addEventListener("click", function() {
      if (item.classList.contains("show")) {
          item.classList.remove("show");
      } else {
          item.classList.add("show");
      }
  });
});

// Create Project

const modal = document.querySelector("[data-modal-id='modal-wrapper']");
const openModalBtn = document.querySelector("[data-modal-target='modal-wrapper']");
const closeModalBtn = document.querySelector("[data-modal-action='close']");
const body = document.body;
const form = document.querySelector("[data-form-id='create-project-form']");
const projectName = document.querySelector("[data-input-id='project-name']");
const visibility = document.querySelector("[data-input-id='visible-input']");

// Open modal
openModalBtn.addEventListener("click", () => {
  modal.style.display = "block";
  body.style.overflow = "hidden";
});

// Close modal on clicking X
closeModalBtn.addEventListener("click", () => {
  closeModal();
});

// Close modal on click outside
window.addEventListener("click", (event) => {
  if (event.target == modal) {
    closeModal();
  }
});

// Close modal on click of close button
//const closeBtn = document.querySelector('.close-modal-btn');
//closeBtn.addEventListener('click', closeModal);

// Function to close modal and reset form
function closeModal() {
  modal.style.display = "none";
  body.style.overflow = "auto";
  form.reset();
}

// Submit form
form.addEventListener('submit', (event) => {
  event.preventDefault(); // Prevent default form submission behavior
  // Get input from form
  const projectNameValue = projectName.value;
  const visible = visibility.checked ? 1 : 0;
  const formData = new FormData();
  formData.append('project_name', projectNameValue);
  formData.append('visible', visible);

  // Create fetch request
  fetch("create_project.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    console.log(data);
    const newId = data; // get the id from the response data
    window.location.href = newId; // Redirect to the project page with the id
  })
  .catch(error => {
    console.error("Error:", error);
  });

});