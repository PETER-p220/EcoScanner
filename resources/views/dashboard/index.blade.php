@extends('layouts.app')

@section('title', 'Dashboard - EcoScanner Pro')

@push('styles')
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    :root {
        --primary: #0a5f3f;
        --primary-light: #10b981;
        --secondary: #f59e0b;
        --danger: #ef4444;
        --dark: #0f172a;
        --card-bg: #ffffff;
        --ewaste: #8b5cf6;
        --organic: #84cc16;
        --plastic: #3b82f6;
    }

    body {
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #e8f5e9 100%);
        font-family: 'Outfit', -apple-system, sans-serif;
    }

    .dashboard-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* Animated Background Particles */
    .particles {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        overflow: hidden;
        pointer-events: none;
    }

    .particle {
        position: absolute;
        width: 4px;
        height: 4px;
        background: var(--primary-light);
        border-radius: 50%;
        opacity: 0.3;
        animation: float 20s infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) translateX(0); }
        25% { transform: translateY(-100px) translateX(50px); }
        50% { transform: translateY(-50px) translateX(-50px); }
        75% { transform: translateY(-150px) translateX(100px); }
    }

    /* Hero Welcome Section */
    .hero-welcome {
        background: linear-gradient(135deg, var(--primary) 0%, #065f46 100%);
        border-radius: 32px;
        padding: 3rem;
        margin-bottom: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(10, 95, 63, 0.3);
    }

    .hero-welcome::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .welcome-title {
        font-size: 3rem;
        font-weight: 900;
        margin-bottom: 0.5rem;
        animation: fadeInUp 0.8s ease;
    }

    .welcome-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 2rem;
        animation: fadeInUp 0.8s ease 0.2s both;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
        animation: fadeInUp 0.8s ease 0.4s both;
    }

    .quick-stat {
        text-align: center;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: transform 0.3s ease;
    }

    .quick-stat:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.15);
    }

    .quick-stat-number {
        font-size: 2.5rem;
        font-weight: 900;
        display: block;
        margin-bottom: 0.5rem;
    }

    .quick-stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    /* Main Grid Layout */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    /* Scanner Section */
    .scanner-card {
        background: var(--card-bg);
        border-radius: 28px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        border: 2px solid #e5e7eb;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--dark);
    }

    .camera-container {
        position: relative;
        background: #000;
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.3);
    }

    #scannerVideo, #scannerCanvas {
        width: 100%;
        height: auto;
        max-height: 500px;
        display: block;
        object-fit: cover;
    }

    #scannerCanvas {
        position: absolute;
        top: 0;
        left: 0;
    }

    .camera-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        background: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, transparent 20%, transparent 80%, rgba(0,0,0,0.4) 100%);
    }

    .scanner-status {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(10px);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        z-index: 10;
    }

    .status-dot {
        width: 10px;
        height: 10px;
        background: #22c55e;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .fps-counter {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.9rem;
        z-index: 10;
    }

    /* Detected User Card */
    .detected-user-card {
        position: absolute;
        top: 5rem;
        right: 1rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.3);
        z-index: 100;
        animation: slideInRight 0.4s ease;
        min-width: 280px;
        border: 2px solid var(--primary-light);
    }

    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(50px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .detected-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 3px solid var(--primary);
        object-fit: cover;
        margin-bottom: 1rem;
    }

    .detected-name {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }

    .detected-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .detected-stat-item {
        text-align: center;
        padding: 0.75rem;
        background: #f3f4f6;
        border-radius: 12px;
    }

    .detected-stat-value {
        display: block;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary);
    }

    .detected-stat-label {
        font-size: 0.75rem;
        color: #6b7280;
        text-transform: uppercase;
        font-weight: 600;
    }

    /* Control Buttons */
    .controls-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .control-btn {
        background: linear-gradient(135deg, var(--primary-light), var(--primary));
        color: white;
        border: none;
        padding: 1rem 1.5rem;
        border-radius: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .control-btn:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
    }

    .control-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .control-btn.secondary {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
    }

    /* Detection Feed */
    .detection-feed {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    .detection-card {
        background: #f9fafb;
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        border-left: 4px solid var(--primary);
        animation: slideInLeft 0.4s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .detection-card.ewaste { border-left-color: var(--ewaste); }
    .detection-card.organic { border-left-color: var(--organic); }
    .detection-card.plastic { border-left-color: var(--plastic); }

    .detection-icon {
        font-size: 2rem;
        margin-right: 1rem;
    }

    .detection-info h4 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .detection-meta {
        font-size: 0.85rem;
        color: #6b7280;
    }

    .detection-points {
        background: var(--primary-light);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-weight: 800;
        font-size: 1.1rem;
    }

    /* Sidebar */
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .points-card {
        background: linear-gradient(135deg, var(--secondary), #ea580c);
        border-radius: 24px;
        padding: 2rem;
        color: white;
        text-align: center;
        box-shadow: 0 12px 48px rgba(245, 158, 11, 0.4);
        animation: pulse 3s ease-in-out infinite;
    }

    .points-number {
        font-size: 4rem;
        font-weight: 900;
        display: block;
        margin-bottom: 0.5rem;
        text-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .points-label {
        font-size: 1.2rem;
        font-weight: 700;
        opacity: 0.95;
    }

    .level-progress {
        margin-top: 1.5rem;
        background: rgba(255, 255, 255, 0.2);
        height: 14px;
        border-radius: 10px;
        overflow: hidden;
    }

    .level-bar {
        height: 100%;
        background: white;
        border-radius: 10px;
        transition: width 0.5s ease;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }

    .level-info {
        margin-top: 1rem;
        font-size: 0.95rem;
        opacity: 0.9;
    }

    /* Stats Card */
    .stats-card {
        background: var(--card-bg);
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        border: 2px solid #e5e7eb;
    }

    .stat-row {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .stat-row:last-child {
        border-bottom: none;
    }

    .stat-label {
        color: #6b7280;
        font-weight: 600;
    }

    .stat-value {
        color: var(--dark);
        font-weight: 800;
        font-size: 1.2rem;
    }

    /* Badges Grid */
    .badges-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-top: 1rem;
    }

    .badge-item {
        background: #f9fafb;
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        padding: 1rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .badge-item.earned {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        border-color: var(--primary-light);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .badge-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        filter: grayscale(100%);
        opacity: 0.3;
    }

    .badge-item.earned .badge-icon {
        filter: grayscale(0%);
        opacity: 1;
        animation: bounceIn 0.6s ease;
    }

    @keyframes bounceIn {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    .badge-name {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--dark);
    }

    /* Leaderboard */
    .leaderboard-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 16px;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    .leaderboard-item:hover {
        transform: translateX(5px);
        background: #f3f4f6;
    }

    .rank-badge {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-light), var(--primary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 1.2rem;
    }

    .rank-badge.gold { background: linear-gradient(135deg, #fbbf24, #f59e0b); }
    .rank-badge.silver { background: linear-gradient(135deg, #cbd5e1, #94a3b8); }
    .rank-badge.bronze { background: linear-gradient(135deg, #fb923c, #ea580c); }

    /* Responsive */
    @media (max-width: 1200px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .hero-welcome {
            padding: 2rem;
        }
        
        .welcome-title {
            font-size: 2rem;
        }
        
        .quick-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .controls-grid {
            grid-template-columns: 1fr;
        }
        
        .detected-user-card {
            position: relative;
            top: auto;
            right: auto;
            margin-top: 1rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Animated Particles Background -->
<div class="particles" id="particles"></div>

<div class="dashboard-container">
    <!-- Hero Welcome Section -->
    <div class="hero-welcome">
        <div class="hero-content">
            <h1 class="welcome-title">Welcome back, {{ Auth::user()->name }}! 👋</h1>
            <p class="welcome-subtitle">
                Level {{ Auth::user()->level ?? 1 }} • Making Dar es Salaam cleaner, one scan at a time
            </p>
            
            <div class="quick-stats">
                <div class="quick-stat">
                    <span class="quick-stat-number">{{ Auth::user()->points ?? 0 }}</span>
                    <span class="quick-stat-label">Total Points</span>
                </div>
                <div class="quick-stat">
                    <span class="quick-stat-number">{{ Auth::user()->total_scans ?? 0 }}</span>
                    <span class="quick-stat-label">Items Scanned</span>
                </div>
                <div class="quick-stat">
                    <span class="quick-stat-number">{{ Auth::user()->ewaste_count ?? 0 }}</span>
                    <span class="quick-stat-label">E-Waste Found</span>
                </div>
                <div class="quick-stat">
                    <span class="quick-stat-number">{{ Auth::user()->total_reports ?? 0 }}</span>
                    <span class="quick-stat-label">Reports Filed</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Grid -->
    <div class="dashboard-grid">
        <!-- Left Column: Scanner -->
        <div class="scanner-card">
            <div class="section-header">
                <h2 class="section-title">🎯 AI Waste Scanner</h2>
            </div>

            <!-- Control Buttons -->
            <div class="controls-grid">
                <button class="control-btn" id="startScanBtn">
                    📹 Start Scanner
                </button>
                <button class="control-btn secondary" id="pauseBtn" style="display:none;">
                    ⏸️ Pause
                </button>
                <button class="control-btn secondary" id="switchBtn" style="display:none;">
                    🔄 Switch Camera
                </button>
                <button class="control-btn secondary" id="reportBtn" style="display:none;">
                    📍 Report Location
                </button>
            </div>

            <!-- Camera View -->
            <div class="camera-container">
                <div class="scanner-status">
                    <span class="status-dot"></span>
                    <span id="statusText">Ready to Scan</span>
                </div>
                <div class="fps-counter" id="fpsCounter" style="display:none;">FPS: 0</div>
                
                <video id="scannerVideo" autoplay playsinline muted></video>
                <canvas id="scannerCanvas"></canvas>
                <div class="camera-overlay"></div>

                <!-- Detected User Card (appears when face is recognized) -->
                <div id="detectedUserCard" class="detected-user-card" style="display:none;">
                    <div class="text-center">
                        <img id="detectedAvatar" src="" alt="" class="detected-avatar">
                        <h4 class="detected-name" id="detectedName"></h4>
                        <p class="text-muted small mb-0" id="detectedEmail"></p>
                        <div class="detected-stats">
                            <div class="detected-stat-item">
                                <span class="detected-stat-value" id="detectedScans">0</span>
                                <span class="detected-stat-label">Scans</span>
                            </div>
                            <div class="detected-stat-item">
                                <span class="detected-stat-value" id="detectedPoints">0</span>
                                <span class="detected-stat-label">Points</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detection Feed -->
            <div>
                <h5 class="fw-bold mb-3">Recent Detections (This Session)</h5>
                <div class="detection-feed" id="detectionFeed">
                    <p class="text-muted text-center py-4">Start scanning to see detections...</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Stats & Gamification -->
        <div class="sidebar">
            <!-- Points Card -->
            <div class="points-card">
                <span class="points-number" id="userPoints">{{ Auth::user()->points ?? 0 }}</span>
                <span class="points-label">EcoPoints</span>
                
                <div class="level-progress">
                    <div class="level-bar" id="levelBar" style="width: 0%"></div>
                </div>
                
                <div class="level-info">
                    <strong>Level {{ Auth::user()->level ?? 1 }}</strong> • 
                    <span id="pointsToNext">100</span> points to next level
                </div>
            </div>

            <!-- Your Stats -->
            <div class="stats-card">
                <h5 class="fw-bold mb-3">📊 Your Statistics</h5>
                <div class="stat-row">
                    <span class="stat-label">Total Scans:</span>
                    <span class="stat-value" id="totalScans">{{ Auth::user()->total_scans ?? 0 }}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Reports Filed:</span>
                    <span class="stat-value" id="totalReports">{{ Auth::user()->total_reports ?? 0 }}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">E-Waste Found:</span>
                    <span class="stat-value" id="ewasteFound">{{ Auth::user()->ewaste_count ?? 0 }}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Your Rank:</span>
                    <span class="stat-value text-primary">#{{ $userRank ?? '-' }}</span>
                </div>
            </div>

            <!-- Badges -->
            <div class="stats-card">
                <h5 class="fw-bold mb-3">🏆 Your Badges</h5>
                <div class="badges-grid" id="badgesGrid">
                    <div class="badge-item">
                        <div class="badge-icon">🎯</div>
                        <div class="badge-name">First Scan</div>
                    </div>
                    <div class="badge-item">
                        <div class="badge-icon">⚡</div>
                        <div class="badge-name">E-Waste Hunter</div>
                    </div>
                    <div class="badge-item">
                        <div class="badge-icon">📢</div>
                        <div class="badge-name">Reporter</div>
                    </div>
                    <div class="badge-item">
                        <div class="badge-icon">🏆</div>
                        <div class="badge-name">Champion</div>
                    </div>
                </div>
            </div>

            <!-- Mini Leaderboard -->
            <div class="stats-card">
                <h5 class="fw-bold mb-3">🏆 Top Champions</h5>
                @if(isset($leaderboard) && $leaderboard->count() > 0)
                    @foreach($leaderboard->take(5) as $index => $leader)
                        <div class="leaderboard-item">
                            <div class="rank-badge {{ $index === 0 ? 'gold' : ($index === 1 ? 'silver' : ($index === 2 ? 'bronze' : '')) }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $leader->name }}</div>
                                <small class="text-muted">{{ $leader->total_scans ?? 0 }} scans</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-primary">{{ $leader->points ?? 0 }}</div>
                                <small class="text-muted">pts</small>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center py-3">Be the first champion!</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- TensorFlow.js for waste detection -->
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.22.0/dist/tf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/coco-ssd@2.2.3"></script>

<!-- Face-api.js for face recognition -->
<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>

<script>
// Generate animated particles
const particlesContainer = document.getElementById('particles');
for (let i = 0; i < 20; i++) {
    const particle = document.createElement('div');
    particle.className = 'particle';
    particle.style.left = Math.random() * 100 + '%';
    particle.style.top = Math.random() * 100 + '%';
    particle.style.animationDelay = Math.random() * 20 + 's';
    particlesContainer.appendChild(particle);
}

// Global variables
let video, canvas, ctx;
let wasteModel = null;
let faceModelsLoaded = false;
let isScanning = false;
let facingMode = 'user';
let lastDetectionTime = 0;
let fps = 0;
let sessionDetections = [];
let lastFaceCheck = 0;

// Waste database
const wasteDatabase = {
    'bottle': { category: 'plastic', points: 5, icon: '🍾' },
    'cup': { category: 'plastic', points: 4, icon: '🥤' },
    'cell phone': { category: 'ewaste', points: 25, icon: '📱' },
    'laptop': { category: 'ewaste', points: 30, icon: '💻' },
    'keyboard': { category: 'ewaste', points: 15, icon: '⌨️' },
    'banana': { category: 'organic', points: 3, icon: '🍌' },
    'apple': { category: 'organic', points: 3, icon: '🍎' },
    'backpack': { category: 'textile', points: 10, icon: '🎒' },
};

// Initialize
async function init() {
    updateStatus('Loading AI models...');
    try {
        // Load waste detection
        wasteModel = await cocoSsd.load({ base: 'lite_mobilenet_v2' });
        
        // Load face recognition models
        const modelPath = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model/';
        await faceapi.nets.tinyFaceDetector.loadFromUri(modelPath);
        await faceapi.nets.faceLandmark68Net.loadFromUri(modelPath);
        await faceapi.nets.faceRecognitionNet.loadFromUri(modelPath);
        faceModelsLoaded = true;
        
        updateStatus('AI Ready! Click Start Scanner');
        document.getElementById('startScanBtn').disabled = false;
    } catch (err) {
        updateStatus('Error loading AI models');
        console.error('Init error:', err);
    }
}

function updateStatus(text) {
    document.getElementById('statusText').textContent = text;
}

// Start Scanner
document.getElementById('startScanBtn').addEventListener('click', async function() {
    video = document.getElementById('scannerVideo');
    canvas = document.getElementById('scannerCanvas');
    ctx = canvas.getContext('2d');
    
    try {
        const stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode, width: { ideal: 1280 }, height: { ideal: 720 } }
        });
        
        video.srcObject = stream;
        await new Promise(resolve => { video.onloadedmetadata = resolve; });
        await video.play();
        
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        updateStatus('Scanning...');
        document.getElementById('fpsCounter').style.display = 'block';
        
        // Show controls
        ['pauseBtn', 'switchBtn', 'reportBtn'].forEach(id => {
            document.getElementById(id).style.display = 'inline-flex';
        });
        this.style.display = 'none';
        
        isScanning = true;
        scanFrame();
    } catch (err) {
        updateStatus('Camera error: ' + err.message);
    }
});

// Scanning loop
async function scanFrame() {
    if (!isScanning || !video || video.readyState < 2) {
        if (isScanning) requestAnimationFrame(scanFrame);
        return;
    }
    
    const now = performance.now();
    if (lastDetectionTime) {
        fps = Math.round(1000 / (now - lastDetectionTime));
        document.getElementById('fpsCounter').textContent = `FPS: ${fps}`;
    }
    lastDetectionTime = now;
    
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    try {
        // Waste detection
        const predictions = await wasteModel.detect(video);
        const filtered = predictions.filter(p => p.score >= 0.55);
        
        for (const pred of filtered) {
            const [x, y, w, h] = pred.bbox;
            const wasteInfo = wasteDatabase[pred.class];
            const color = wasteInfo?.category === 'ewaste' ? '#8b5cf6' :
                         wasteInfo?.category === 'organic' ? '#84cc16' :
                         wasteInfo?.category === 'plastic' ? '#3b82f6' : '#10b981';
            
            // Draw box
            ctx.strokeStyle = color;
            ctx.lineWidth = 4;
            ctx.strokeRect(x, y, w, h);
            
            // Draw label
            const confidence = Math.round(pred.score * 100);
            const label = `${pred.class} ${confidence}%`;
            ctx.font = 'bold 18px Outfit';
            const textWidth = ctx.measureText(label).width;
            ctx.fillStyle = color;
            ctx.fillRect(x, y - 40, textWidth + 20, 40);
            ctx.fillStyle = '#fff';
            ctx.fillText(label, x + 10, y - 15);
            
            // Save detection
            if (wasteInfo && !sessionDetections.find(d => d.type === pred.class && Date.now() - d.time < 3000)) {
                addDetection(pred.class, wasteInfo);
            }
        }
        
        // Face recognition (every 2 seconds)
        if (faceModelsLoaded && Date.now() - lastFaceCheck > 2000) {
            lastFaceCheck = Date.now();
            checkForFace();
        }
        
    } catch (e) {
        console.warn('Detection error:', e);
    }
    
    requestAnimationFrame(scanFrame);
}

// Add detection to feed
function addDetection(type, info) {
    sessionDetections.push({ type, time: Date.now() });
    
    const feed = document.getElementById('detectionFeed');
    if (feed.querySelector('.text-muted')) {
        feed.innerHTML = '';
    }
    
    const card = document.createElement('div');
    card.className = `detection-card ${info.category}`;
    card.innerHTML = `
        <div class="d-flex align-items-center flex-grow-1">
            <div class="detection-icon">${info.icon}</div>
            <div class="detection-info">
                <h4>${type}</h4>
                <div class="detection-meta">${info.category} • Just now</div>
            </div>
        </div>
        <div class="detection-points">+${info.points}</div>
    `;
    feed.insertBefore(card, feed.firstChild);
    
    // Update points
    const currentPoints = parseInt(document.getElementById('userPoints').textContent);
    document.getElementById('userPoints').textContent = currentPoints + info.points;
    
    // Save to backend (would be AJAX in production)
    console.log('Detected:', type, info);
}

// Face recognition
async function checkForFace() {
    try {
        const detections = await faceapi
            .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks()
            .withFaceDescriptor();
        
        if (detections) {
            // In production, send encoding to server to recognize user
            // For now, just show a demo
            const card = document.getElementById('detectedUserCard');
            if (card.style.display === 'none') {
                // Show demo user
                document.getElementById('detectedAvatar').src = 'https://ui-avatars.com/api/?name=Scanner+User&background=10b981&color=fff';
                document.getElementById('detectedName').textContent = 'Scanner Active';
                document.getElementById('detectedEmail').textContent = 'Detecting waste...';
                document.getElementById('detectedScans').textContent = sessionDetections.length;
                document.getElementById('detectedPoints').textContent = 'Active';
                card.style.display = 'block';
            }
        } else {
            document.getElementById('detectedUserCard').style.display = 'none';
        }
    } catch (e) {
        console.warn('Face check error:', e);
    }
}

// Control buttons
document.getElementById('pauseBtn').addEventListener('click', function() {
    isScanning = !isScanning;
    this.innerHTML = isScanning ? '⏸️ Pause' : '▶️ Resume';
    if (isScanning) scanFrame();
});

document.getElementById('switchBtn').addEventListener('click', async function() {
    facingMode = facingMode === 'user' ? 'environment' : 'user';
    const stream = await navigator.mediaDevices.getUserMedia({
        video: { facingMode, width: { ideal: 1280 }, height: { ideal: 720 } }
    });
    video.srcObject = stream;
});

// Calculate level progress
const userLevel = {{ Auth::user()->level ?? 1 }};
const userPoints = {{ Auth::user()->points ?? 0 }};
const nextLevelPoints = userLevel * 100;
const prevLevelPoints = (userLevel - 1) * 100;
const progress = ((userPoints - prevLevelPoints) / (nextLevelPoints - prevLevelPoints)) * 100;
document.getElementById('levelBar').style.width = Math.min(100, Math.max(0, progress)) + '%';
document.getElementById('pointsToNext').textContent = Math.max(0, nextLevelPoints - userPoints);

// Initialize on load
init();

// Cleanup
window.addEventListener('beforeunload', () => {
    if (video && video.srcObject) {
        video.srcObject.getTracks().forEach(track => track.stop());
    }
});
</script>
@endpush