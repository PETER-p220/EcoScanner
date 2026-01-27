@extends('layouts.app')

@section('title', 'Register with Face - EcoScanner Pro')

@push('styles')
<style>
    .registration-container {
        max-width: 700px;
        margin: 3rem auto;
        padding: 0 1rem;
    }

    .progress-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 3rem;
        position: relative;
    }

    .progress-steps::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e5e7eb;
        z-index: -1;
    }

    .progress-line {
        position: absolute;
        top: 20px;
        left: 0;
        height: 2px;
        background: linear-gradient(90deg, #10b981, #0a5f3f);
        transition: width 0.4s ease;
        z-index: -1;
    }

    .step {
        flex: 1;
        text-align: center;
        position: relative;
    }

    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        border: 3px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .step.active .step-circle {
        background: linear-gradient(135deg, #10b981, #0a5f3f);
        color: white;
        border-color: #10b981;
        transform: scale(1.1);
    }

    .step.completed .step-circle {
        background: #10b981;
        color: white;
        border-color: #10b981;
    }

    .step.completed .step-circle::after {
        content: '✓';
    }

    .step-label {
        font-size: 0.9rem;
        color: #6b7280;
        font-weight: 500;
    }

    .step.active .step-label {
        color: #10b981;
        font-weight: 700;
    }

    .card {
        border-radius: 24px;
        border: 2px solid #e5e7eb;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .card-body {
        padding: 2.5rem;
    }

    .step-content {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .step-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        padding: 0.875rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }

    /* Camera Section */
    .camera-section {
        margin: 2rem 0;
    }

    .camera-container {
        position: relative;
        background: #000;
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    }

    #faceVideo, #faceCanvas {
        width: 100%;
        height: auto;
        display: block;
        max-height: 400px;
        object-fit: cover;
    }

    #faceCanvas {
        position: absolute;
        top: 0;
        left: 0;
    }

    .face-guide {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 200px;
        height: 250px;
        border: 3px dashed rgba(16, 185, 129, 0.5);
        border-radius: 50%;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .face-guide.detected {
        border-color: #10b981;
        border-style: solid;
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.5);
    }

    .camera-status {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    .camera-status.ready {
        background: rgba(16, 185, 129, 0.9);
    }

    .camera-instructions {
        background: linear-gradient(135deg, #dbeafe, #e0f2fe);
        border-left: 4px solid #3b82f6;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }

    .camera-instructions h5 {
        color: #1e40af;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .camera-instructions ul {
        margin: 0;
        padding-left: 1.5rem;
        color: #1e3a8a;
    }

    .camera-instructions li {
        margin-bottom: 0.5rem;
    }

    /* Review Section */
    .review-section {
        text-align: center;
    }

    .captured-face {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #10b981;
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
        margin: 2rem auto;
    }

    .review-info {
        background: #f9fafb;
        border-radius: 16px;
        padding: 1.5rem;
        margin: 1.5rem 0;
        text-align: left;
    }

    .review-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .review-item:last-child {
        border-bottom: none;
    }

    .review-label {
        color: #6b7280;
        font-weight: 500;
    }

    .review-value {
        color: #111827;
        font-weight: 600;
    }

    /* Buttons */
    .btn {
        padding: 0.875rem 2rem;
        font-weight: 700;
        border-radius: 12px;
        transition: all 0.3s ease;
        border: none;
        font-size: 1rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #10b981, #0a5f3f);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-group-custom {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-group-custom .btn {
        flex: 1;
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.5rem;
    }

    .spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem;
        }
        
        .btn-group-custom {
            flex-direction: column-reverse;
        }
        
        .btn-group-custom .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="registration-container">
    <!-- Progress Steps -->
    <div class="progress-steps">
        <div class="progress-line" id="progressLine" style="width: 0%"></div>
        <div class="step active" data-step="1">
            <div class="step-circle">1</div>
            <div class="step-label">Personal Info</div>
        </div>
        <div class="step" data-step="2">
            <div class="step-circle">2</div>
            <div class="step-label">Face Capture</div>
        </div>
        <div class="step" data-step="3">
            <div class="step-circle">3</div>
            <div class="step-label">Review</div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card">
        <div class="card-body">
            <!-- Error & Success Messages -->
            <div id="errorMessage" class="alert alert-danger mb-3" style="display:none;"></div>
            <div id="successMessage" class="alert alert-success mb-3" style="display:none;"></div>

            <form id="registerForm">
                @csrf

                <!-- Step 1: Personal Information -->
                <div class="step-content active" data-step="1">
                    <h3 class="text-center mb-4">Personal Information</h3>
                    
                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" id="name" class="form-control" required 
                               placeholder="Enter your full name">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" id="email" class="form-control" required
                               placeholder="your.email@example.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" id="phone" class="form-control" 
                               placeholder="+255 XXX XXX XXX">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" id="password" class="form-control" 
                               required minlength="8" placeholder="Minimum 8 characters">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password *</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="form-control" required placeholder="Re-enter password">
                    </div>

                    <div class="btn-group-custom">
                        <button type="button" class="btn btn-primary" onclick="goToStep(2)">
                            Next: Capture Face →
                        </button>
                    </div>
                </div>

                <!-- Step 2: Face Capture -->
                <div class="step-content" data-step="2">
                    <h3 class="text-center mb-4">Capture Your Face</h3>

                    <div class="camera-instructions">
                        <h5>📸 Instructions for Best Results:</h5>
                        <ul>
                            <li>Position your face inside the circle</li>
                            <li>Ensure good lighting (face clearly visible)</li>
                            <li>Remove glasses, hats, or masks</li>
                            <li>Look directly at the camera</li>
                            <li>Stay still when capturing</li>
                        </ul>
                    </div>

                    <div class="camera-section">
                        <div class="camera-container">
                            <div class="camera-status" id="cameraStatus">
                                <span class="spinner"></span> Initializing camera...
                            </div>
                            <video id="faceVideo" autoplay playsinline muted></video>
                            <canvas id="faceCanvas"></canvas>
                            <div class="face-guide" id="faceGuide"></div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success btn-lg" id="captureBtn" 
                                    onclick="captureFace()" disabled>
                                📷 Capture Face
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="face_image" id="faceImage">
                    <input type="hidden" name="face_encoding" id="faceEncoding">

                    <div class="btn-group-custom">
                        <button type="button" class="btn btn-secondary" onclick="goToStep(1)">
                            ← Back
                        </button>
                        <button type="button" class="btn btn-primary" id="nextToReview" 
                                onclick="goToStep(3)" disabled>
                            Next: Review →
                        </button>
                    </div>
                </div>

                <!-- Step 3: Review & Submit -->
                <div class="step-content" data-step="3">
                    <h3 class="text-center mb-4">Review Your Information</h3>

                    <div class="review-section">
                        <img id="capturedFacePreview" src="" alt="Your Face" class="captured-face">
                        
                        <div class="alert alert-success">
                            ✅ Face captured successfully!
                        </div>

                        <div class="review-info">
                            <div class="review-item">
                                <span class="review-label">Name:</span>
                                <span class="review-value" id="reviewName"></span>
                            </div>
                            <div class="review-item">
                                <span class="review-label">Email:</span>
                                <span class="review-value" id="reviewEmail"></span>
                            </div>
                            <div class="review-item">
                                <span class="review-label">Phone:</span>
                                <span class="review-value" id="reviewPhone"></span>
                            </div>
                        </div>
                    </div>

                    <div class="btn-group-custom">
                        <button type="button" class="btn btn-secondary" onclick="goToStep(2)">
                            ← Retake Photo
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            Complete Registration ✓
                        </button>
                    </div>
                </div>
            </form>

            <p class="text-center mt-4 mb-0" style="color: #6b7280;">
                Already have an account? <a href="{{ route('login') }}" style="color: #10b981; font-weight: 600;">Login here</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Face-api.js for face detection -->
<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>

<script>
let currentStep = 1;
let video = null;
let canvas = null;
let ctx = null;
let faceDetectionInterval = null;
let faceCaptured = false;
let faceModelsLoaded = false;

// Step Navigation
function goToStep(step) {
    // Validate before moving forward
    if (step > currentStep) {
        if (currentStep === 1 && !validateStep1()) return;
        if (currentStep === 2 && !faceCaptured) {
            showError('Please capture your face before proceeding');
            return;
        }
    }

    // Update step content
    document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
    document.querySelector(`.step-content[data-step="${step}"]`).classList.add('active');

    // Update progress
    document.querySelectorAll('.step').forEach(el => {
        const stepNum = parseInt(el.dataset.step);
        el.classList.remove('active', 'completed');
        if (stepNum < step) el.classList.add('completed');
        if (stepNum === step) el.classList.add('active');
    });

    // Update progress line
    const progress = ((step - 1) / 2) * 100;
    document.getElementById('progressLine').style.width = progress + '%';

    currentStep = step;

    // Initialize camera on step 2
    if (step === 2 && !faceModelsLoaded) {
        initializeFaceCapture();
    }

    // Populate review on step 3
    if (step === 3) {
        populateReview();
    }

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function validateStep1() {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirmation').value;

    if (!name || !email || !password || !passwordConfirm) {
        showError('Please fill in all required fields');
        return false;
    }

    if (password.length < 8) {
        showError('Password must be at least 8 characters');
        return false;
    }

    if (password !== passwordConfirm) {
        showError('Passwords do not match');
        return false;
    }

    return true;
}

// Face Capture
async function initializeFaceCapture() {
    try {
        updateCameraStatus('Loading AI models...', false);

        // Load face-api models
        const modelPath = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model/';
        await faceapi.nets.tinyFaceDetector.loadFromUri(modelPath);
        await faceapi.nets.faceLandmark68Net.loadFromUri(modelPath);
        await faceapi.nets.faceRecognitionNet.loadFromUri(modelPath);
        
        faceModelsLoaded = true;

        // Setup camera
        video = document.getElementById('faceVideo');
        canvas = document.getElementById('faceCanvas');
        ctx = canvas.getContext('2d');

        const stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } }
        });

        video.srcObject = stream;
        await new Promise(resolve => { video.onloadedmetadata = resolve; });
        await video.play();

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        updateCameraStatus('Position your face in the circle', true);
        
        // Start face detection
        startFaceDetection();

    } catch (err) {
        updateCameraStatus('Camera error: ' + err.message, false);
        showError('Unable to access camera. Please check permissions.');
        console.error('Face capture error:', err);
    }
}

function updateCameraStatus(message, ready = false) {
    const statusEl = document.getElementById('cameraStatus');
    statusEl.innerHTML = ready ? message : `<span class="spinner"></span> ${message}`;
    statusEl.className = 'camera-status' + (ready ? ' ready' : '');
}

function startFaceDetection() {
    faceDetectionInterval = setInterval(async () => {
        if (!video || video.readyState !== 4) return;

        const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions());

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        if (detections.length > 0) {
            const box = detections[0].box;
            ctx.strokeStyle = '#10b981';
            ctx.lineWidth = 3;
            ctx.strokeRect(box.x, box.y, box.width, box.height);

            document.getElementById('faceGuide').classList.add('detected');
            document.getElementById('captureBtn').disabled = false;
        } else {
            document.getElementById('faceGuide').classList.remove('detected');
            document.getElementById('captureBtn').disabled = true;
        }
    }, 100);
}

async function captureFace() {
    try {
        const detections = await faceapi
            .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks()
            .withFaceDescriptor();

        if (!detections) {
            showError('No face detected. Please try again.');
            return;
        }

        // Capture face image
        const tempCanvas = document.createElement('canvas');
        tempCanvas.width = video.videoWidth;
        tempCanvas.height = video.videoHeight;
        const tempCtx = tempCanvas.getContext('2d');
        tempCtx.drawImage(video, 0, 0);
        
        const faceImage = tempCanvas.toDataURL('image/png');
        document.getElementById('faceImage').value = faceImage;
        document.getElementById('capturedFacePreview').src = faceImage;

        // Store face encoding
        const faceEncoding = Array.from(detections.descriptor);
        document.getElementById('faceEncoding').value = JSON.stringify(faceEncoding);

        faceCaptured = true;
        document.getElementById('nextToReview').disabled = false;

        // Stop camera
        stopCamera();

        showSuccess('Face captured successfully! Click Next to continue.');

    } catch (err) {
        showError('Error capturing face. Please try again.');
        console.error('Capture error:', err);
    }
}

function stopCamera() {
    if (video && video.srcObject) {
        video.srcObject.getTracks().forEach(track => track.stop());
    }
    if (faceDetectionInterval) {
        clearInterval(faceDetectionInterval);
    }
}

function populateReview() {
    document.getElementById('reviewName').textContent = document.getElementById('name').value;
    document.getElementById('reviewEmail').textContent = document.getElementById('email').value;
    document.getElementById('reviewPhone').textContent = document.getElementById('phone').value || 'Not provided';
}

// Form Submission
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner"></span> Registering...';

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    try {
        const response = await fetch('{{ route("register.facial") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            showSuccess(result.message);
            setTimeout(() => {
                window.location.href = result.redirect;
            }, 1500);
        } else {
            showError(result.message || 'Registration failed. Please try again.');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Complete Registration ✓';
        }
    } catch (error) {
        showError('Network error. Please check your connection and try again.');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Complete Registration ✓';
        console.error('Error:', error);
    }
});

function showError(message) {
    const errorEl = document.getElementById('errorMessage');
    errorEl.textContent = message;
    errorEl.style.display = 'block';
    document.getElementById('successMessage').style.display = 'none';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function showSuccess(message) {
    const successEl = document.getElementById('successMessage');
    successEl.textContent = message;
    successEl.style.display = 'block';
    document.getElementById('errorMessage').style.display = 'none';
}

// Cleanup on page unload
window.addEventListener('beforeunload', stopCamera);
</script>
@endpush