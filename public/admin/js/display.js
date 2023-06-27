/*
    Show display options
*/

const openDisplayButton = document.querySelector("[data-button='display']");
const displayOptionsBox = document.querySelector("[data-box='display']");
const closeDisplayButton = document.querySelector("[data-dismiss='display']");

openDisplayButton.addEventListener("click", openDisplayOptions);
closeDisplayButton.addEventListener("click", closeDisplayOptions);

function openDisplayOptions() {
    displayOptionsBox.classList.add("show");
    openDisplayButton.classList.remove("show");
}

function closeDisplayOptions() {
    displayOptionsBox.classList.remove("show");
    openDisplayButton.classList.add("show");
}

/*
    Columns
*/

const minusButton = document.querySelector("[data-button='minus']");
const plusButton = document.querySelector("[data-button='plus']");
const gridBox = document.querySelector("[data-column='projects-list']");
const columnNumber = document.querySelector(".column-number");

minusButton.addEventListener("click", reduceGridColumns);
plusButton.addEventListener("click", increaseGridColumns);

function reduceGridColumns() {
    let currentColumns = parseInt(columnNumber.innerText);
    if (currentColumns > 1) {
        gridBox.classList.remove(`${toWords(currentColumns)}-col`);
        currentColumns = currentColumns - 1;
        gridBox.classList.add(`${toWords(currentColumns)}-col`);
        columnNumber.innerText = currentColumns;
    }

    updateButtonState(currentColumns);
    updateColumns(currentColumns);
}

function increaseGridColumns() {
    let currentColumns = parseInt(columnNumber.innerText);
    if (currentColumns < 3) {
        gridBox.classList.remove(`${toWords(currentColumns)}-col`);
        currentColumns = currentColumns + 1;
        gridBox.classList.add(`${toWords(currentColumns)}-col`);
        columnNumber.innerText = currentColumns;
    }

    updateButtonState(currentColumns);
    updateColumns(currentColumns);
}

function updateButtonState(columnCount) {
    minusButton.disabled = columnCount <= 1;
    plusButton.disabled = columnCount >= 3;
}

// Helper function to convert number to words
function toWords(number) {
    const words = ["one", "two", "three"];
    return words[number - 1];
}

function updateColumns(columnCount) {
    const url = "update_column.php";
    const data = {columnNumber: columnCount};

    fetch(url, {
        body: JSON.stringify(data),
        headers: {
            "Content-Type": "application/json"
        },
        method: "POST"
    }).then(function (response) {
        return response.json();
    }).then(function (data) {
        console.log(data);
    }).catch(function (error) {
        console.error(error);
    });
}

/*
  Text position
*/

const textSelect = document.querySelector("[data-select='text']");
const textBox = document.querySelectorAll("[data-text='position']");

textSelect.addEventListener("change", changeTextPosition);

function changeTextPosition() {
    const selectedOption = textSelect.value;

    // Remove all existing classes from the textBox elements
    textBox.forEach(function (element) {
        element.classList.remove("text-below", "text-inside", "text-hidden");
    });

    // Add the appropriate class based on the selected option
    textBox.forEach(function (element) {
        element.classList.add(`text-${selectedOption}`);
    });

    updateTextPosition(selectedOption);
}

function updateTextPosition(selectedOption) {
    const url = "update_text_position.php";
    const data = {textPosition: selectedOption};

    fetch(url, {
        body: JSON.stringify(data),
        headers: {
            "Content-Type": "application/json"
        },
        method: "POST"
    }).then(function (response) {
        return response.json();
    }).then(function (data) {
        console.log(data);
    }).catch(function (error) {
        console.error(error);
    });
}

/*
  Thumbnail Toggle Switch
*/

const thumbToggleSwitch = document.querySelector("[data-switch='thumbnail']");
const buttons = document.querySelectorAll("[data-button-type='thumbnail']");

thumbToggleSwitch.addEventListener("change", function () {
    buttons.forEach(function (button) {
        button.classList.toggle("show-buttons");
    });
});

// Upload Thumbnail

const uploadForms = document.querySelectorAll("[data-form-type='thumbnail']");

uploadForms.forEach(function (uploadForm) {
    const projectId = uploadForm.querySelector("[data-project-id='thumbnail']");
    const fileInput = uploadForm.querySelector("[data-file-type='thumbnail']");
    const thumbnail = document.querySelector(`[data-id="${projectId.value}"]`);
    const target = thumbnail.parentNode.parentNode.parentNode;
    const status = target.querySelector("[data-status='thumbnail']");

    uploadForm.addEventListener("change", function (event) {
        event.preventDefault();
        const id = projectId.value;
        const files = fileInput.files;
        const formData = new FormData();
        formData.append("project_id", id);
        formData.append("file", files[0]);
        console.log(status);
        status.style.visibility = "visible";

        fetch("thumbnail_upload.php", {
            body: formData,
            method: "POST"
        }).then(function (response) {
            return response.json();
        }).then(function (data) {
            console.log(data);

            const img = thumbnail;
            img.src = "";

            img.src = data.cover_path;
        }).catch(function (error) {
            console.error(error);
        }).finally(function () {
            status.style.visibility = "hidden";
        });

        fileInput.value = "";
    });
});