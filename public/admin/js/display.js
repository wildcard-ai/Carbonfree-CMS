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
        currentColumns--;
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
        currentColumns++;
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
    const data = { columnNumber: columnCount };

    fetch(url, {
        body: JSON.stringify(data),
        headers: {
            "Content-Type": "application/json"
        },
        method: "POST"
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
    })
    .catch(error => {
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
    textBox.forEach(element => {
        element.classList.remove("text-below", "text-inside", "text-hidden");
    });

    // Add the appropriate class based on the selected option
    textBox.forEach(element => {
        element.classList.add(`text-${selectedOption}`);
    });

    updateTextPosition(selectedOption);
}

function updateTextPosition(textPosition) {
    const url = "update_text_position.php";
    const data = { textPosition: textPosition };

    fetch(url, {
        method: "POST",
        headers: {
        "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
    })
    .catch(error => {
        console.error(error);
    });
}

/*
  Thumbnail Toggle Switch
*/

const thumbToggleSwitch = document.querySelector("[data-switch='thumbnail']");
const buttons = document.querySelectorAll("[data-button-type='thumbnail']");

thumbToggleSwitch.addEventListener("change", () => {
    buttons.forEach(button => button.classList.toggle("show-buttons"));
});

// Upload Thumbnail

const uploadForms = document.querySelectorAll("[data-form-type='thumbnail']");

uploadForms.forEach(async (uploadForm) => {
    const projectId = uploadForm.querySelector("[data-project-id='thumbnail']");
    const fileInput = uploadForm.querySelector("[data-file-type='thumbnail']");
    const thumbnail = document.querySelector(`[data-id="${projectId.value}"]`);
    const status = thumbnail.closest("[data-status='thumbnail']");

    uploadForm.addEventListener("change", async (event) => {
        event.preventDefault();
        const id = projectId.value;
        const files = fileInput.files;
        const formData = new FormData();
        formData.append("project_id", id);
        formData.append("file", files[0]);

        status.style.visibility = "visible";

        fetch("thumbnail_upload.php", {
        method: "POST",
        body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);

            const img = thumbnail;
            img.src = "";

            img.src = data.cover_path;
        })
        .catch(error => {
            console.error(error);
        })
        .finally(() => {
            status.style.visibility = "hidden";
        });

        fileInput.value = "";
    });
});