const editButtons = document.querySelectorAll("[data-button='edit']");
const cancelButtons = document.querySelectorAll("[data-button='cancel']");
const usernameForm = document.querySelector("[data-form='username']");
const passwordForm = document.querySelector("[data-form='password']");
const emailForm = document.querySelector("[data-form='email']");

editButtons.forEach(function (editButton) {
    editButton.addEventListener("click", function () {
        const label = editButton.closest("label");
        const inputField = label.querySelector("[data-input='focus']");

        toggleCollapse(editButton);
        toggleDisabled(true, editButton);
        toggleFocus(inputField);
    });
});

cancelButtons.forEach(function (cancelButton) {
    cancelButton.addEventListener("click", function () {
        const label = cancelButton.closest("label");
        const closestEditButton = label.querySelector("[data-button='edit']");

        toggleCollapse(cancelButton);
        toggleDisabled(false, closestEditButton);
    });
});

usernameForm.addEventListener("submit", function (event) {
    saveUsername(event);
});

passwordForm.addEventListener("submit", function (event) {
    savePassword(event);
});

emailForm.addEventListener("submit", function (event) {
    saveEmail(event);
});

function toggleCollapse(button) {
    const label = button.closest("label");
    const elements = label.querySelectorAll("[data-collapse='toggle']");

    label.classList.toggle("show");

    elements.forEach(function (target) {
        target.classList.toggle("show");
    });
}

function toggleFocus(formElement) {
    formElement.select();
}

function toggleDisabled(isDisabled, editButton) {
    editButton.disabled = isDisabled;
}

function saveUsername(event) {
    event.preventDefault();
    console.log("saved");
}

function savePassword(event) {
    event.preventDefault();
    console.log("saved");
}

function saveEmail(event) {
    event.preventDefault();
    console.log("saved");
}