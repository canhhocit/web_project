const input = document.getElementById("anhxe");
const preview = document.getElementById("preview");

let filesArray = [];

input.addEventListener("change", function () {
    const files = Array.from(input.files);
    for (let i = 0; i < files.length; i++) {
        filesArray.push(files[i]);
    }
    renderPreview();
    updateInputFiles();
});

function renderPreview() {
    preview.innerHTML = "";
    for (let i = 0; i < filesArray.length; i++) {
        const file = filesArray[i];
        const div = document.createElement("div");
        div.classList.add("preview-item");

        const img = document.createElement("img");
        img.src = URL.createObjectURL(file);

        const btn = document.createElement("button");
        btn.innerText = "×";
        btn.type = "button";
        btn.classList.add("remove-btn");

        btn.onclick = function () {
            removeImage(i);
        };

        div.appendChild(img);
        div.appendChild(btn);
        preview.appendChild(div);
    }
}

function removeImage(index) {
    filesArray.splice(index, 1);
    renderPreview();
    updateInputFiles();
}
//U inpFile
function updateInputFiles() {
    const dataTransfer = new DataTransfer();
    for (let i = 0; i < filesArray.length; i++) {
        dataTransfer.items.add(filesArray[i]);
    }

    input.files = dataTransfer.files;
}
