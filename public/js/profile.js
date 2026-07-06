const avatarWrapper = document.getElementById('avatarWrapper');
const avatarInput = document.getElementById('avatarInput');
const avatarImage = document.getElementById('avatarImage');
const uploadStatus = document.getElementById('uploadStatus');
const btnUpload = document.getElementById('btnUpload');

let selectedFile = null;

// Click avatar or overlay to open file picker
if (avatarWrapper && avatarInput) {
    avatarWrapper.addEventListener('click', function() {
        avatarInput.click();
    });
}

function triggerUpload() {
    if (avatarInput) {
        avatarInput.click();
    }
}

// Handle file selection
if (avatarInput && avatarImage && btnUpload) {
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];

        if (!file) return;

        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            showStatus('Format gambar tidak didukung. Gunakan JPG, PNG, atau GIF', 'error');
            return;
        }

        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            showStatus('Ukuran file terlalu besar. Maksimal 5MB', 'error');
            return;
        }

        selectedFile = file;
        avatarImage.src = URL.createObjectURL(file);
        btnUpload.style.display = 'inline-block';
        showStatus('Gambar dipilih. Klik tombol "Upload Avatar" untuk menyimpan.', 'uploading');
    });
}

function uploadAvatar() {
    if (!selectedFile) {
        showStatus('Silahkan pilih gambar terlebih dahulu', 'error');
        return;
    }

    const formData = new FormData();
    formData.append('avatar', selectedFile);
    formData.append('_token', window.profileConfig.csrfToken);

    showStatus('Mengupload...', 'uploading');

    fetch(window.profileConfig.uploadUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showStatus('✓ Foto profil berhasil diperbarui', 'success');
                if (btnUpload) {
                    btnUpload.style.display = 'none';
                }
                selectedFile = null;
            } else {
                showStatus('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showStatus('Terjadi kesalahan saat mengupload', 'error');
        });
}

function showStatus(message, type) {
    if (uploadStatus) {
        uploadStatus.textContent = message;
        uploadStatus.className = 'upload-status ' + type;

        if (type === 'success') {
            setTimeout(() => {
                uploadStatus.textContent = '';
                uploadStatus.className = 'upload-status';
            }, 3000);
        }
    }
}

// Modal Top Up Logic
const topupModal = document.getElementById('topupModal');
const topupAmountInput = document.getElementById('topupAmount');
const saldoDisplay = document.getElementById('saldoDisplay');

function openTopupModal() {
    if (topupModal) {
        topupModal.style.display = 'block';
        if (topupAmountInput) {
            topupAmountInput.value = '';
        }
        // Reset active state on nominal buttons
        document.querySelectorAll('.nominal-btn').forEach(btn => {
            btn.classList.remove('active');
        });
    }
}

function closeTopupModal() {
    if (topupModal) {
        topupModal.style.display = 'none';
    }
}

// Close modal when click outside of modal-content
window.addEventListener('click', function(event) {
    if (event.target === topupModal) {
        closeTopupModal();
    }
});

function selectNominal(amount, btnElement) {
    if (topupAmountInput) {
        topupAmountInput.value = amount;
    }
    // Remove active class from other buttons
    document.querySelectorAll('.nominal-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    // Add active class to clicked button
    if (btnElement) {
        btnElement.classList.add('active');
    }
}

function submitTopup() {
    if (!topupAmountInput) return;

    const amount = parseInt(topupAmountInput.value);
    const isAdmin = window.profileConfig.role === 'admin';

    if (isNaN(amount) || amount < 10000) {
        alert(isAdmin ? 'Minimal nominal pencairan saldo adalah Rp 10.000' : 'Minimal nominal top up adalah Rp 10.000');
        return;
    }

    const formData = new FormData();
    formData.append('amount', amount);
    formData.append('_token', window.profileConfig.csrfToken);

    fetch(window.profileConfig.topupUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                if (saldoDisplay) {
                    saldoDisplay.textContent = 'Rp ' + data.new_saldo;
                }
                closeTopupModal();
            } else {
                alert('Gagal: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(isAdmin ? 'Terjadi kesalahan saat memproses pencairan saldo.' : 'Terjadi kesalahan saat memproses top up.');
        });
}
