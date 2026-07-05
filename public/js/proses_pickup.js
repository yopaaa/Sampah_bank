function previewFile(input) {
    const preview = document.getElementById('preview');
    const previewImg = document.getElementById('previewImg');
    const fileName = document.getElementById('fileName');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (previewImg) {
                previewImg.src = e.target.result;
            }
            if (fileName) {
                fileName.textContent = 'File: ' + input.files[0].name + ' (' + (input.files[0].size / 1024 / 1024).toFixed(2) + ' MB)';
            }
            if (preview) {
                preview.style.display = 'block';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Drag and drop support
const fileInputWrapper = document.querySelector('.file-input-wrapper');
const fileInput = document.getElementById('bukti');

if (fileInputWrapper && fileInput) {
    fileInputWrapper.addEventListener('dragover', (e) => {
        e.preventDefault();
        const fileLabel = fileInputWrapper.querySelector('.file-input-label');
        if (fileLabel) {
            fileLabel.style.background = '#e2e8f0';
            fileLabel.style.borderColor = '#2563eb';
        }
    });

    fileInputWrapper.addEventListener('dragleave', (e) => {
        e.preventDefault();
        const fileLabel = fileInputWrapper.querySelector('.file-input-label');
        if (fileLabel) {
            fileLabel.style.background = '#f1f5f9';
            fileLabel.style.borderColor = '#e2e8f0';
        }
    });

    fileInputWrapper.addEventListener('drop', (e) => {
        e.preventDefault();
        const fileLabel = fileInputWrapper.querySelector('.file-input-label');
        if (fileLabel) {
            fileLabel.style.background = '#f1f5f9';
            fileLabel.style.borderColor = '#e2e8f0';
        }
        fileInput.files = e.dataTransfer.files;
        previewFile(fileInput);
    });
}
