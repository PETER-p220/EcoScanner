<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - EcoScanner Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Manrope', -apple-system, sans-serif;
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Gradient Background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at 20% 50%, rgba(16, 185, 129, 0.12) 0%, transparent 50%),
                        radial-gradient(circle at 80% 50%, rgba(14, 165, 233, 0.12) 0%, transparent 50%);
            animation: bgFloat 15s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes bgFloat {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.1); }
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            position: relative;
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-header {
            text-align: center;
            padding: 40px 40px 30px;
            border-bottom: 1px solid #f1f5f9;
        }

        .logo {
            font-size: 60px;
            margin-bottom: 16px;
            display: inline-block;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .login-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .login-subtitle {
            color: #64748b;
            font-size: 15px;
        }

        .login-body {
            padding: 30px 40px 40px;
        }

        /* Tabs */
        .tabs {
            display: flex;
            background: #f8fafc;
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 28px;
            position: relative;
        }

        .tab {
            flex: 1;
            padding: 12px 16px;
            background: none;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            color: #64748b;
            cursor: pointer;
            transition: color 0.3s;
            position: relative;
            z-index: 2;
        }

        .tab.active {
            color: white;
        }

        .tab-slider {
            position: absolute;
            top: 4px;
            left: 4px;
            width: calc(50% - 4px);
            height: calc(100% - 8px);
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 10px;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
            z-index: 1;
        }

        .tab-slider.right {
            transform: translateX(calc(100% + 4px));
        }

        /* Alert */
        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 600;
            display: none;
            align-items: center;
            gap: 10px;
        }

        .alert.show {
            display: flex;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fee2e2;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #d1fae5;
        }

        /* Tab Content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Camera */
        .camera-box {
            background: #000;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            margin-bottom: 20px;
        }

        .camera-box video,
        .camera-box canvas {
            width: 100%;
            height: 280px;
            object-fit: cover;
            display: block;
        }

        .camera-box canvas {
            position: absolute;
            top: 0;
            left: 0;
        }

        .face-circle {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 160px;
            height: 160px;
            border: 3px dashed rgba(16, 185, 129, 0.4);
            border-radius: 50%;
            pointer-events: none;
            transition: all 0.3s;
        }

        .face-circle.active {
            border-color: #10b981;
            border-style: solid;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.4);
        }

        .camera-status {
            position: absolute;
            top: 12px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .camera-status.ready {
            background: rgba(16, 185, 129, 0.95);
        }

        .status-dot {
            width: 6px;
            height: 6px;
            background: #22c55e;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* Matched User */
        .matched-user {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            margin-bottom: 20px;
            animation: bounceIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes bounceIn {
            0% { opacity: 0; transform: scale(0.8); }
            50% { transform: scale(1.05); }
            100% { opacity: 1; transform: scale(1); }
        }

        .matched-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #059669;
            margin: 0 auto 12px;
            object-fit: cover;
        }

        .matched-name {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .matched-email {
            color: #059669;
            font-weight: 600;
            font-size: 14px;
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 700;
            font-size: 14px;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            font-family: 'Manrope', sans-serif;
            transition: all 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-input.error {
            border-color: #ef4444;
        }

        .error-text {
            color: #ef4444;
            font-size: 12px;
            margin-top: 4px;
            font-weight: 600;
        }

        /* Button */
        .btn {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-primary:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Helper */
        .helper {
            text-align: center;
            margin-top: 16px;
            font-size: 14px;
            color: #64748b;
        }

        .helper button {
            background: none;
            border: none;
            color: #10b981;
            font-weight: 700;
            cursor: pointer;
            text-decoration: underline;
        }

        .register-link {
            text-align: center;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
            font-size: 14px;
            color: #64748b;
        }

        .register-link a {
            color: #10b981;
            font-weight: 700;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-header,
            .login-body {
                padding-left: 24px;
                padding-right: 24px;
            }

            .login-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">🌿</div>
            <h1 class="login-title">Welcome Back</h1>
            <p class="login-subtitle">Login to EcoScanner Pro</p>
        </div>

        <div class="login-body">
            <!-- Tabs -->
            <div class="tabs">
                <div class="tab-slider" id="slider"></div>
                <button class="tab active" data-tab="face">👤 Face Login</button>
                <button class="tab" data-tab="password">🔐 Password</button>
            </div>

            <!-- Alerts -->
            <div id="errorAlert" class="alert alert-error">
                <span>⚠️</span>
                <span id="errorText"></span>
            </div>
            <div id="successAlert" class="alert alert-success">
                <span>✅</span>
                <span id="successText"></span>
            </div>

            @if ($errors->any())
                <div class="alert alert-error show">
                    <span>⚠️</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success show">
                    <span>✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Face Login -->
            <div id="faceContent" class="tab-content active">
                <div id="matchedUser"></div>

                <div class="camera-box" id="cameraBox">
                    <div class="camera-status" id="status">
                        <span class="status-dot"></span>
                        <span id="statusText">Initializing...</span>
                    </div>
                    <video id="video" autoplay playsinline muted></video>
                    <canvas id="canvas"></canvas>
                    <div class="face-circle" id="circle"></div>
                </div>

                <button class="btn btn-primary" id="faceBtn" disabled>
                    🔐 Login with Face
                </button>

                <div class="helper">
                    <button type="button" onclick="switchTab('password')">Use password instead</button>
                </div>
            </div>

            <!-- Password Login -->
            <div id="passwordContent" class="tab-content">
                <form method="POST" action="{{ route('login.facial') }}" id="loginForm">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" 
                               name="email" 
                               class="form-input @error('email') error @enderror" 
                               placeholder="your.email@example.com" 
                               value="{{ old('email') }}"
                               required>
                        @error('email')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" 
                               name="password" 
                               class="form-input @error('password') error @enderror" 
                               placeholder="Enter password" 
                               required>
                        @error('password')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span class="form-label" style="margin: 0;">Remember me</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" id="loginBtn">
                        🔐 Login
                    </button>
                </form>

                <div class="helper">
                    <button type="button" onclick="switchTab('face')">
                        <strong>Forgot password?</strong> Try Face Login
                    </button>
                </div>
            </div>

            <div class="register-link">
                Don't have an account? <a href="{{ route('register') }}">Register now</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>
    <script>
        let video, canvas, ctx;
        let modelsLoaded = false;
        let interval = null;
        let detected = false;

        // Tab Switching
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => {
                switchTab(tab.dataset.tab);
            });
        });

        function switchTab(name) {
            document.querySelectorAll('.tab').forEach(t => {
                t.classList.toggle('active', t.dataset.tab === name);
            });

            const slider = document.getElementById('slider');
            slider.classList.toggle('right', name === 'password');

            document.getElementById('faceContent').classList.toggle('active', name === 'face');
            document.getElementById('passwordContent').classList.toggle('active', name === 'password');

            hideAlerts();

            if (name === 'face' && !modelsLoaded) {
                initFace();
            } else if (name === 'password') {
                stopCamera();
            }
        }

        // Face Login
        async function initFace() {
            try {
                updateStatus('Loading AI...');

                const path = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model/';
                await faceapi.nets.tinyFaceDetector.loadFromUri(path);
                await faceapi.nets.faceLandmark68Net.loadFromUri(path);
                await faceapi.nets.faceRecognitionNet.loadFromUri(path);

                modelsLoaded = true;

                video = document.getElementById('video');
                canvas = document.getElementById('canvas');
                ctx = canvas.getContext('2d');

                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user', width: 640, height: 480 }
                });

                video.srcObject = stream;
                await new Promise(r => { video.onloadedmetadata = r; });
                await video.play();

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                updateStatus('Position face', true);
                startDetection();

            } catch (err) {
                updateStatus('Camera error');
                showError('Cannot access camera');
                console.error(err);
            }
        }

        function updateStatus(text, ready = false) {
            document.getElementById('statusText').textContent = text;
            document.getElementById('status').classList.toggle('ready', ready);
        }

        function startDetection() {
            interval = setInterval(async () => {
                if (!video || video.readyState !== 4) return;

                const faces = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions());
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                if (faces.length > 0) {
                    const box = faces[0].box;
                    ctx.strokeStyle = '#10b981';
                    ctx.lineWidth = 3;
                    ctx.strokeRect(box.x, box.y, box.width, box.height);

                    document.getElementById('circle').classList.add('active');
                    document.getElementById('faceBtn').disabled = false;
                    detected = true;
                    updateStatus('Face detected!', true);
                } else {
                    document.getElementById('circle').classList.remove('active');
                    document.getElementById('faceBtn').disabled = true;
                    detected = false;
                    updateStatus('Position face', true);
                }
            }, 100);
        }

        document.getElementById('faceBtn').addEventListener('click', async function() {
            if (!detected) return;

            this.disabled = true;
            this.innerHTML = '<span class="spinner"></span> Recognizing...';

            try {
                const face = await faceapi
                    .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (!face) {
                    showError('No face detected');
                    this.disabled = false;
                    this.innerHTML = '🔐 Login with Face';
                    return;
                }

                // Convert face descriptor to array
                const descriptor = Array.from(face.descriptor);

                // Send to Laravel backend - FIXED ROUTE
                const response = await fetch('{{ route("login.facial") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ face_encoding: JSON.stringify(descriptor) })
                });

                const data = await response.json();

                if (data.success) {
                    showMatchedUser(data.user);
                    showSuccess(data.message);
                    
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                } else {
                    showError(data.message || 'Face not recognized');
                    this.disabled = false;
                    this.innerHTML = '🔐 Login with Face';
                }

            } catch (err) {
                showError('Recognition failed. Please try again.');
                console.error(err);
                this.disabled = false;
                this.innerHTML = '🔐 Login with Face';
            }
        });

        function showMatchedUser(user) {
            const avatarUrl = user.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=10b981&color=fff`;
            
            document.getElementById('matchedUser').innerHTML = `
                <div class="matched-user">
                    <img src="${avatarUrl}" class="matched-avatar" alt="${user.name}">
                    <div class="matched-name">${user.name}</div>
                    <div class="matched-email">${user.email}</div>
                </div>
            `;

            document.getElementById('cameraBox').style.display = 'none';
            document.getElementById('faceBtn').style.display = 'none';
        }

        function stopCamera() {
            if (video && video.srcObject) {
                video.srcObject.getTracks().forEach(t => t.stop());
            }
            if (interval) clearInterval(interval);
        }

        // Password Login
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> Logging in...';
        });

        // Alerts
        function showError(msg) {
            document.getElementById('errorText').textContent = msg;
            document.getElementById('errorAlert').classList.add('show');
            document.getElementById('successAlert').classList.remove('show');
        }

        function showSuccess(msg) {
            document.getElementById('successText').textContent = msg;
            document.getElementById('successAlert').classList.add('show');
            document.getElementById('errorAlert').classList.remove('show');
        }

        function hideAlerts() {
            document.getElementById('errorAlert').classList.remove('show');
            document.getElementById('successAlert').classList.remove('show');
        }

        // Init
        window.addEventListener('DOMContentLoaded', initFace);
        window.addEventListener('beforeunload', stopCamera);
    </script>
</body>
</html>