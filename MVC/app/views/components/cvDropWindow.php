<link rel="stylesheet" href="<?= ROOT ?>assets/css/cvDropWindow.css">
<div class="cvdw_pageCover">
    <div class="drop_window">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="job_apply">
            <div class="upload-box" id="uploadBox">
                <input type="file" id="fileInput" accept=".pdf" name="cv_file_path">
                <i>📂</i>
                <p>Drag & Drop a pdf of your CV here</p>
                <p>or</p>
                <button type="button" class="upload-btn" onclick="document.getElementById('fileInput').click()">Browse Files</button>
                <div class="file-list" id="fileList"></div>
            </div>
            <div class="form_btns">
                <button id="dw_backBtn">Back</button>
                <button type="submit">Send</button>
            </div>
        </form>
    </div>
</div>
<script>
    const uploadBox = document.getElementById("uploadBox");
    const fileInput = document.getElementById("fileInput");
    const fileList = document.getElementById("fileList");

    uploadBox.addEventListener("dragover", (e) => {
        e.preventDefault();
        uploadBox.classList.add("dragover");
    });

    uploadBox.addEventListener("dragleave", () => {
        uploadBox.classList.remove("dragover");
    });

    uploadBox.addEventListener("drop", (e) => {
        e.preventDefault();
        uploadBox.classList.remove("dragover");
        fileInput.files = e.dataTransfer.files;
        handleFiles(e.dataTransfer.files);
    });

    fileInput.addEventListener("change", () => {
        handleFiles(fileInput.files);
    });

    function handleFiles(files) {
        fileList.innerHTML = "";
        [...files].forEach(file => {
            const item = document.createElement("div");
            item.textContent = `${file.name} (${Math.round(file.size / 1024)} KB)`;
            fileList.appendChild(item);
        });
    }
</script>