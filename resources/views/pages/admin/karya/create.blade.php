@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="m-0 font-weight-bold text-primary">Unggah Karya Baru</h3>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm" style="border-radius: 12px; border: none;">
                <div class="card-header bg-transparent py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">Form Unggah Karya</h5>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form id="uploadForm" method="POST" action="{{ route('admin.karya.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="judul" class="form-label">Judul Karya</label>
                                    <input type="text" class="form-control" id="judul" name="judul" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Nama Pengunggah</label>
                                    <select class="form-select" id="user_id" name="user_id" required>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Karya</label>
                            <!-- Trix Editor -->
                            <input id="deskripsi" type="hidden" name="deskripsi">
                            <trix-editor input="deskripsi" class="trix-content"></trix-editor>
                        </div>

                        <!-- Hidden Trix file attachment inputs -->
                        <input type="file" id="trix-file-input" class="d-none" multiple>
                        
                        <!-- Drag & Drop Area -->
                        <div class="mb-4">
                            <label class="form-label">Unggah Karya</label>
                            <div class="dropzone border rounded p-5 text-center position-relative" id="dropzone" style="border-radius: 8px; min-height: 200px;">
                                <div id="dropzoneContent">
                                    <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                                    <p class="mt-3 mb-1">Seret dan lepas file di sini atau klik untuk memilih file</p>
                                    <p class="small text-muted">Format yang didukung: 
                                        <br>Video (MP4, MOV, AVI - maks 50MB), 
                                        <br>Dokumen (PDF, DOC - maks 3MB), 
                                        <br>Gambar (JPG, PNG - maks 2MB per gambar, maks 3 gambar)
                                    </p>
                                </div>
                                
                                <!-- Hidden file inputs -->
                                <input type="file" id="video_karya" name="video_karya" accept="video/*" class="d-none">
                                <input type="file" id="dokumen_karya" name="dokumen_karya" accept=".pdf" class="d-none">
                                <input type="file" id="gambar_karya" name="gambar_karya[]" accept="image/*" multiple class="d-none">
                                
                                <!-- Preview area -->
                                <div id="filePreview" class="mt-3 d-flex flex-wrap gap-3"></div>
                                
                                <!-- Progress bar (initially hidden) -->
                                <div id="uploadProgressContainer" class="mt-3 d-none">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Mengunggah...</span>
                                        <span id="uploadPercentage">0%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div id="uploadProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" 
                                             role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <div class="text-muted small mt-1" id="uploadSpeed"></div>
                                </div>
                                
                                <!-- Reset button (shown when files are selected) -->
                                <button type="button" id="resetFileType" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2 d-none">
                                    <i class="bi bi-arrow-repeat"></i> Ganti Jenis
                                </button>
                            </div>
                            <div id="fileError" class="text-danger small mt-2"></div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill" id="submitButton">
                                <i class="bi bi-upload me-2"></i> Unggah Karya
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Success Modal -->
<div class="modal fade" id="uploadSuccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Upload Berhasil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-check-circle-fill text-success fs-1 mb-3"></i>
                <p>Karya Anda berhasil diunggah dan sedang menunggu persetujuan admin.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- File Type Selection Modal -->
<div class="modal fade" id="fileTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Pilih Jenis Karya</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row text-center">
                    <div class="col-md-4 mb-3">
                        <button class="btn btn-outline-primary w-100 h-100 p-3" onclick="selectFileType('video')">
                            <i class="bi bi-camera-video fs-1 d-block mb-2"></i>
                            Video
                        </button>
                    </div>
                    <div class="col-md-4 mb-3">
                        <button class="btn btn-outline-primary w-100 h-100 p-3" onclick="selectFileType('document')">
                            <i class="bi bi-file-earmark-text fs-1 d-block mb-2"></i>
                            Dokumen
                        </button>
                    </div>
                    <div class="col-md-4 mb-3">
                        <button class="btn btn-outline-primary w-100 h-100 p-3" onclick="selectFileType('image')">
                            <i class="bi bi-images fs-1 d-block mb-2"></i>
                            Gambar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Trix Editor CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css" />
<!-- Trix Editor JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Trix editor file handling
    const trixEditor = document.querySelector('trix-editor');
    const trixFileInput = document.getElementById('trix-file-input');
    
    // Handle Trix file attachment
    trixEditor.addEventListener('trix-attachment-add', function(event) {
        const attachment = event.attachment;
        if (attachment.file) {
            uploadTrixAttachment(attachment);
        }
    });
    
    // Function to handle Trix file uploads
    function uploadTrixAttachment(attachment) {
        const file = attachment.file;
        const formData = new FormData();
        formData.append('file', file);
        
        // You'll need to implement your own endpoint for file uploads
        fetch('/upload-trix-attachment', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Set the attachment URL
                attachment.setAttributes({
                    url: data.fileUrl,
                    href: data.fileUrl
                });
            } else {
                attachment.remove();
                showError('Gagal mengunggah lampiran: ' + data.message);
            }
        })
        .catch(error => {
            attachment.remove();
            showError('Terjadi kesalahan saat mengunggah lampiran');
        });
    }
    
    // Main file upload functionality
    const dropzone = document.getElementById('dropzone');
    const dropzoneContent = document.getElementById('dropzoneContent');
    const videoInput = document.getElementById('video_karya');
    const docInput = document.getElementById('dokumen_karya');
    const imageInput = document.getElementById('gambar_karya');
    const filePreview = document.getElementById('filePreview');
    const fileError = document.getElementById('fileError');
    const form = document.getElementById('uploadForm');
    const submitButton = document.getElementById('submitButton');
    const uploadProgressContainer = document.getElementById('uploadProgressContainer');
    const uploadProgressBar = document.getElementById('uploadProgressBar');
    const uploadPercentage = document.getElementById('uploadPercentage');
    const uploadSpeed = document.getElementById('uploadSpeed');
    const resetFileTypeBtn = document.getElementById('resetFileType');
    const fileTypeModal = new bootstrap.Modal(document.getElementById('fileTypeModal'));
    
    let currentFileType = null;
    let files = [];
    
    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });
    
    // Highlight drop zone when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, unhighlight, false);
    });
    
    // Handle dropped files
    dropzone.addEventListener('drop', handleDrop, false);
    
    // Click to select files
    dropzone.addEventListener('click', () => {
        if (!currentFileType) {
            fileTypeModal.show();
        } else {
            // Trigger appropriate input based on current file type
            if (currentFileType === 'video') videoInput.click();
            else if (currentFileType === 'document') docInput.click();
            else if (currentFileType === 'image') imageInput.click();
        }
    });
    
    // Reset file type selection
    resetFileTypeBtn.addEventListener('click', resetFileType);
    
    // Handle file selection via input
    videoInput.addEventListener('change', handleFiles);
    docInput.addEventListener('change', handleFiles);
    imageInput.addEventListener('change', handleFiles);
    
    // Form submission with AJAX for progress tracking
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        if (videoInput.files.length === 0 && 
            docInput.files.length === 0 && 
            imageInput.files.length === 0) {
            showError('Harap unggah minimal satu file karya');
            return;
        }
        
        // Get Trix editor content
        const trixInput = document.getElementById('deskripsi');
        if (!trixInput.value || trixInput.value.trim() === '') {
            showError('Harap isi deskripsi karya');
            return;
        }
        
        // Prepare form data
        const formData = new FormData(form);
        
        // Show progress bar
        uploadProgressContainer.classList.remove('d-none');
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengunggah...';
        
        // Track upload progress
        const xhr = new XMLHttpRequest();
        
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = Math.round((e.loaded / e.total) * 100);
                updateProgress(percentComplete, e.loaded, e.total);
            }
        });
        
        xhr.addEventListener('load', function() {
            if (xhr.status === 200) {
                // Upload complete
                updateProgress(100);
                
                // Show success message
                const successModal = new bootstrap.Modal(document.getElementById('uploadSuccessModal'));
                successModal.show();
                
                // Reset form after 2 seconds
                setTimeout(() => {
                    form.reset();
                    resetFileType();
                    uploadProgressContainer.classList.add('d-none');
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="bi bi-upload me-2"></i> Unggah Karya';
                    
                    // Clear Trix editor
                    const trixEditor = document.querySelector('trix-editor');
                    trixEditor.editor.loadHTML('');
                }, 2000);
            } else {
                // Handle error
                showError('Gagal mengunggah: ' + xhr.responseText);
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="bi bi-upload me-2"></i> Unggah Karya';
                uploadProgressContainer.classList.add('d-none');
            }
        });
        
        xhr.addEventListener('error', function() {
            showError('Terjadi kesalahan saat mengunggah');
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="bi bi-upload me-2"></i> Unggah Karya';
            uploadProgressContainer.classList.add('d-none');
        });
        
        xhr.open('POST', form.action);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send(formData);
    });
    
    function updateProgress(percent, loaded = 0, total = 0) {
        uploadProgressBar.style.width = percent + '%';
        uploadPercentage.textContent = percent + '%';
        
        // Calculate upload speed if we have loaded and total
        if (loaded > 0 && total > 0) {
            const elapsedTime = (Date.now() - startTime) / 1000; // in seconds
            const uploadSpeedValue = (loaded / elapsedTime) / 1024; // in KB/s
            
            if (percent < 100) {
                uploadSpeed.textContent = `Kecepatan: ${Math.round(uploadSpeedValue)} KB/s - ${formatFileSize(loaded)} dari ${formatFileSize(total)}`;
            } else {
                uploadSpeed.textContent = `Selesai - ${formatFileSize(total)} diunggah`;
            }
        }
    }
    
    let startTime = 0;
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    function highlight() {
        dropzone.classList.add('border-primary', 'bg-light');
    }
    
    function unhighlight() {
        dropzone.classList.remove('border-primary', 'bg-light');
    }
    
    function resetFileType() {
        currentFileType = null;
        files = [];
        filePreview.innerHTML = '';
        videoInput.value = '';
        docInput.value = '';
        imageInput.value = '';
        resetFileTypeBtn.classList.add('d-none');
        
        // Restore original dropzone content
        dropzoneContent.innerHTML = `
            <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
            <p class="mt-3 mb-1">Seret dan lepas file di sini atau klik untuk memilih file</p>
            <p class="small text-muted">Format yang didukung: 
                <br>Video (MP4, MOV, AVI - maks 50MB), 
                <br>Dokumen (PDF - maks 3MB), 
                <br>Gambar (JPG, PNG - maks 2MB per gambar, maks 3 gambar)
            </p>
        `;
    }
    
    window.selectFileType = function(type) {
        currentFileType = type;
        fileTypeModal.hide();
        resetFileTypeBtn.classList.remove('d-none');
        
        // Update dropzone content
        dropzoneContent.innerHTML = `
            <i class="bi bi-${getFileTypeIcon(type)} fs-1 text-primary"></i>
            <p class="mt-3 mb-1">${getFileTypeInstruction(type)}</p>
            <p class="small text-muted">${getFileTypeRequirements(type)}</p>
        `;
        
        // Trigger file input if files were dropped before type selection
        if (files.length > 0) {
            handleFiles({ target: { files } });
        }
    };
    
    function getFileTypeIcon(type) {
        switch(type) {
            case 'video': return 'camera-video';
            case 'document': return 'file-earmark-text';
            case 'image': return 'images';
            default: return 'cloud-arrow-up';
        }
    }
    
    function getFileTypeInstruction(type) {
        switch(type) {
            case 'video': return 'Seret dan lepas video di sini atau klik untuk memilih video';
            case 'document': return 'Seret dan lepas dokumen di sini atau klik untuk memilih dokumen';
            case 'image': return 'Seret dan lepas gambar di sini atau klik untuk memilih gambar (maks 3)';
            default: return 'Seret dan lepas file di sini atau klik untuk memilih file';
        }
    }
    
    function getFileTypeRequirements(type) {
        switch(type) {
            case 'video': return 'Format: MP4, MOV, AVI - Ukuran maksimal: 50MB';
            case 'document': return 'Format: PDF - Ukuran maksimal: 3MB';
            case 'image': return 'Format: JPG, PNG - Ukuran maksimal: 2MB per gambar (maks 3 gambar)';
            default: return 'Format yang didukung: Video, Dokumen, Gambar';
        }
    }
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const droppedFiles = dt.files;
        
        files = Array.from(droppedFiles);
        
        if (!currentFileType) {
            // Show file type selection modal
            fileTypeModal.show();
            return;
        }
        
        validateAndProcessFiles();
    }
    
    function validateAndProcessFiles() {
        // Validate files based on type
        let isValid = true;
        let errorMessage = '';
        
        if (currentFileType === 'video') {
            if (files.length > 1) {
                isValid = false;
                errorMessage = 'Hanya boleh mengunggah satu video';
            } else if (!files[0].type.startsWith('video/')) {
                isValid = false;
                errorMessage = 'File harus berupa video';
            } else if (files[0].size > 50 * 1024 * 1024) {
                isValid = false;
                errorMessage = 'Ukuran video maksimal 50MB';
            }
        } 
        else if (currentFileType === 'document') {
            if (files.length > 1) {
                isValid = false;
                errorMessage = 'Hanya boleh mengunggah satu dokumen';
            } else {
                const validDocTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!validDocTypes.includes(files[0].type)) {
                    isValid = false;
                    errorMessage = 'File harus berupa PDF atau DOC';
                } else if (files[0].size > 3 * 1024 * 1024) {
                    isValid = false;
                    errorMessage = 'Ukuran dokumen maksimal 3MB';
                }
            }
        } 
        else if (currentFileType === 'image') {
            if (files.length > 3) {
                isValid = false;
                errorMessage = 'Maksimal 3 gambar yang dapat diunggah';
            } else {
                for (let file of files) {
                    if (!file.type.startsWith('image/')) {
                        isValid = false;
                        errorMessage = 'Semua file harus berupa gambar';
                        break;
                    }
                    if (file.size > 2 * 1024 * 1024) {
                        isValid = false;
                        errorMessage = 'Ukuran gambar maksimal 2MB per file';
                        break;
                    }
                }
            }
        }
        
        if (!isValid) {
            showError(errorMessage);
            files = [];
            return;
        }
        
        // Assign files to appropriate input
        const dataTransfer = new DataTransfer();
        for (let file of files) {
            dataTransfer.items.add(file);
        }
        
        if (currentFileType === 'video') {
            videoInput.files = dataTransfer.files;
        } else if (currentFileType === 'document') {
            docInput.files = dataTransfer.files;
        } else if (currentFileType === 'image') {
            imageInput.files = dataTransfer.files;
        }
        
        handleFiles({ target: { files: dataTransfer.files } });
    }
    
    function handleFiles(e) {
        files = Array.from(e.target.files);
        fileError.textContent = '';
        startTime = Date.now(); // Reset timer for new upload
        
        if (files.length === 0) return;
        
        // Clear previous preview
        filePreview.innerHTML = '';
        
        if (currentFileType === 'video') {
            const video = files[0];
            const videoPreview = createFilePreviewCard(
                'camera-video', 
                'Video', 
                video.name, 
                video.size,
                'video'
            );
            filePreview.appendChild(videoPreview);
            
        } else if (currentFileType === 'document') {
            const doc = files[0];
            const docPreview = createFilePreviewCard(
                'file-earmark-text', 
                'Dokumen', 
                doc.name, 
                doc.size,
                'document'
            );
            filePreview.appendChild(docPreview);
            
        } else if (currentFileType === 'image') {
            files.forEach((image, index) => {
                const imgPreview = createImagePreviewCard(image, index);
                filePreview.appendChild(imgPreview);
            });
        }
    }
    
    function createFilePreviewCard(icon, type, name, size, fileType) {
        const previewCard = document.createElement('div');
        previewCard.className = 'file-preview-card border rounded p-3 bg-white';
        previewCard.style.width = '200px';
        
        previewCard.innerHTML = `
            <div class="text-center mb-2">
                <i class="bi bi-${icon} fs-1 text-primary"></i>
                <small class="d-block text-muted">${type}</small>
            </div>
            <div class="text-truncate mb-1" title="${name}">${name}</div>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">${formatFileSize(size)}</small>
                <button type="button" class="btn btn-sm btn-outline-danger btn-sm rounded-circle p-1" 
                        onclick="removeFile(this, '${fileType}')">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        
        return previewCard;
    }
    
    function createImagePreviewCard(image, index) {
        const previewCard = document.createElement('div');
        previewCard.className = 'file-preview-card border rounded p-2 bg-white';
        previewCard.style.width = '200px';
        
        previewCard.innerHTML = `
            <div class="position-relative">
                <img src="${URL.createObjectURL(image)}" class="img-fluid rounded" style="height: 100px; width: 100%; object-fit: cover;">
                <div class="position-absolute top-0 end-0 m-1">
                    <span class="badge bg-primary">Gambar ${index + 1}</span>
                </div>
            </div>
            <div class="mt-2">
                <div class="text-truncate small" title="${image.name}">${image.name}</div>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <small class="text-muted">${formatFileSize(image.size)}</small>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-sm rounded-circle p-1" 
                            onclick="removeFile(this, 'image', ${index})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        return previewCard;
    }
    
    window.removeFile = function(button, type, index = null) {
        if (type === 'video') {
            videoInput.value = '';
        } else if (type === 'document') {
            docInput.value = '';
        } else if (type === 'image') {
            if (index !== null) {
                files.splice(index, 1);
                const dataTransfer = new DataTransfer();
                files.forEach(file => dataTransfer.items.add(file));
                imageInput.files = dataTransfer.files;
            }
        }
        
        // Remove the preview card
        button.closest('.file-preview-card').remove();
        
        // Reset if no files left
        if (filePreview.children.length === 0) {
            resetFileType();
        }
    };
    
    function showError(message) {
        fileError.textContent = message;
        fileError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
});
</script>

<style>
    /* Trix Editor Styles */
    trix-toolbar .trix-button-group {
        margin-bottom: 0;
    }
    
    trix-toolbar .trix-button {
        border-radius: 4px !important;
    }
    
    .trix-content {
        min-height: 200px;
        border: 1px solid #dee2e6 !important;
        border-radius: 0.375rem !important;
        padding: 1rem !important;
    }
    
    .trix-content:focus {
        border-color: #86b7fe !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }
    
    .trix-content img {
        max-width: 100%;
        height: auto;
    }
    
    /* Dropzone Styles */
    .dropzone {
        border: 2px dashed #dee2e6;
        transition: all 0.3s;
        cursor: pointer;
        background-color: #f8f9fa;
    }
    
    .dropzone:hover {
        border-color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.05);
    }
    
    .dropzone.highlight {
        border-color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.1);
    }
    
    #uploadProgressContainer {
        transition: all 0.3s;
    }
    
    .progress {
        border-radius: 10px;
    }
    
    .progress-bar {
        border-radius: 10px;
    }
    
    .file-preview-card {
        transition: all 0.2s;
    }
    
    .file-preview-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    
    #resetFileType {
        transition: all 0.3s;
    }
</style>

@endsection

