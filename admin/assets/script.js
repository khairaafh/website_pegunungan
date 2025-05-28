// File upload handling
const fileUploadArea = document.getElementById('fileUploadArea');
const fileInput = document.getElementById('gambar');
const filePreview = document.getElementById('filePreview');
const fileName = document.getElementById('fileName');

// Drag and drop functionality
fileUploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    fileUploadArea.classList.add('dragover');
});

fileUploadArea.addEventListener('dragleave', () => {
    fileUploadArea.classList.remove('dragover');
});

fileUploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    fileUploadArea.classList.remove('dragover');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        handleFileSelect(files[0]);
    }
});

fileInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        handleFileSelect(e.target.files[0]);
    }
});

function handleFileSelect(file) {
    if (file) {
        fileName.textContent = file.name;
        filePreview.style.display = 'block';
        
        // Update upload area
        const uploadContent = fileUploadArea.querySelector('.file-upload-content');
        
        // Check if we're in edit mode (current image exists)
        const isEditMode = document.querySelector('.current-image') !== null;
        
        if (isEditMode) {
            uploadContent.innerHTML = `
                <i class="fas fa-check-circle file-upload-icon" style="color: #28a745;"></i>
                <div class="upload-text" style="color: #28a745;">File baru berhasil dipilih</div>
                <div class="upload-subtext">${file.name}</div>
                <div class="upload-subtext" style="margin-top: 0.5rem; color: #f39c12;">
                    <i class="fas fa-info-circle"></i>
                    Gambar ini akan mengganti gambar yang ada
                </div>
            `;
        } else {
            uploadContent.innerHTML = `
                <i class="fas fa-check-circle file-upload-icon" style="color: #28a745;"></i>
                <div class="upload-text" style="color: #28a745;">File berhasil dipilih</div>
                <div class="upload-subtext">${file.name}</div>
            `;
        }
        
        fileUploadArea.style.borderColor = '#28a745';
        fileUploadArea.style.background = '#d4edda';
    }
}

// Form submission with loading
document.getElementById('mountainForm').addEventListener('submit', function(e) {
    const loadingOverlay = document.getElementById('loadingOverlay');
    loadingOverlay.style.display = 'flex';
});

// Input animations
document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.classList.add('focused');
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.classList.remove('focused');
    });
});

// Form validation
function validateForm() {
    const requiredFields = [
        'nama_gunung',
        'tinggi',
        'lokasi',
        'provinsi'
    ];
    
    let isValid = true;
    
    requiredFields.forEach(fieldName => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (!field.value.trim()) {
            field.style.borderColor = '#e74c3c';
            isValid = false;
        } else {
            field.style.borderColor = '#e1e8ed';
        }
    });
    
    // Check difficulty selection
    const difficultySelected = document.querySelector('input[name="tingkat_kesulitan"]:checked');
    if (!difficultySelected) {
        isValid = false;
        // Add visual feedback for difficulty selector
        document.querySelector('.difficulty-selector').style.border = '2px solid #e74c3c';
        document.querySelector('.difficulty-selector').style.borderRadius = '12px';
        document.querySelector('.difficulty-selector').style.padding = '0.5rem';
    } else {
        document.querySelector('.difficulty-selector').style.border = 'none';
        document.querySelector('.difficulty-selector').style.padding = '0';
    }
    
    return isValid;
}

// Real-time validation
document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('input', function() {
        if (this.value.trim()) {
            this.style.borderColor = '#27ae60';
        } else {
            this.style.borderColor = '#e1e8ed';
        }
    });
});

// Difficulty selector validation
document.querySelectorAll('input[name="tingkat_kesulitan"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelector('.difficulty-selector').style.border = 'none';
        document.querySelector('.difficulty-selector').style.padding = '0';
    });
});

// File size validation
function validateFileSize(file) {
    const maxSize = 5 * 1024 * 1024; // 5MB
    if (file.size > maxSize) {
        alert('Ukuran file terlalu besar. Maksimal 5MB.');
        return false;
    }
    return true;
}

// Enhanced file input handling with validation
fileInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        const file = e.target.files[0];
        if (validateFileSize(file)) {
            handleFileSelect(file);
        } else {
            e.target.value = '';
            filePreview.style.display = 'none';
        }
    }
});

// Auto-grow textarea
const textarea = document.getElementById('deskripsi');
if (textarea) {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
}

// Character counter for description
if (textarea) {
    const maxChars = 1000;
    const counter = document.createElement('div');
    counter.className = 'char-counter';
    counter.style.cssText = `
        text-align: right;
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.5rem;
    `;
    textarea.parentNode.appendChild(counter);
    
    textarea.addEventListener('input', function() {
        const remaining = maxChars - this.value.length;
        counter.textContent = `${this.value.length}/${maxChars} karakter`;
        
        if (remaining < 100) {
            counter.style.color = '#f39c12';
        } else if (remaining < 0) {
            counter.style.color = '#e74c3c';
        } else {
            counter.style.color = '#6c757d';
        }
    });
    
    // Initialize counter
    textarea.dispatchEvent(new Event('input'));
}