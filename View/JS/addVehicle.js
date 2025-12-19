const input = document.getElementById("anhxe");
const preview = document.getElementById("preview");

let filesArray = [];

input.addEventListener("change", function () {
    const files = Array.from(input.files);

    files.forEach(file => {
        filesArray.push(file);
    });

    renderPreview();
    updateInputFiles();
});

function renderPreview() {
    preview.innerHTML = "";

    filesArray.forEach((file, index) => {
        const reader = new FileReader();

        reader.onload = function (e) {
            const div = document.createElement("div");
            div.classList.add("preview-item");

            div.innerHTML = `
                <img src="${e.target.result}">
                <button type="button" class="remove-btn" onclick="removeImage(${index})">×</button>
            `;

            preview.appendChild(div);
        };

        reader.readAsDataURL(file);
    });
}

function removeImage(index) {
    filesArray.splice(index, 1);
    renderPreview();
    updateInputFiles();
}

// Cập nhật lại input file sau khi xóa
function updateInputFiles() {
    const dataTransfer = new DataTransfer();
    filesArray.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;
}
