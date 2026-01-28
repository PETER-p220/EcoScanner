@extends('layouts.app')

@section('title', 'Dashboard - EcoScanner Pro')

@push('styles')
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
        --bg: #f9f9f9;
        --card-bg: #ffffff;
        --text: #111111;
        --text-light: #555555;
        --border: #dddddd;
        --accent: #c00000;
        --accent-dark: #a00000;
        --accent-hover: #e00000;
        --shadow: rgba(0,0,0,0.14);
        --status-active: #444444;
    }

    [data-theme="dark"] {
        --bg: #111111;
        --card-bg: #1a1a1a;
        --text: red;
        --text-light: #bbbbbb;
        --border: #444444;
        --accent: #e00000;
        --accent-dark: #b00000;
        --accent-hover: #ff3333;
        --shadow: rgba(0,0,0,0.6);
        --status-active: #777777;
    }

    body {
        background: var(--bg);
        color: var(--text);
        font-family: 'Segoe UI', system-ui, sans-serif;
        transition: background 0.35s ease, color 0.35s ease;
        min-height: 100vh;
    }

    .dashboard-container { max-width: 1600px; margin: 0 auto; padding: 1.5rem 1rem; }

    .theme-toggle {
        position: fixed; top: 1rem; right: 1.5rem; z-index: 1000;
        background: var(--card-bg); border: 1px solid var(--border); border-radius: 50%;
        width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;
        cursor: pointer; box-shadow: 0 3px 12px var(--shadow); transition: all 0.3s;
        font-size: 1.4rem;
    }
    .theme-toggle:hover { transform: scale(1.1); box-shadow: 0 6px 20px var(--shadow); }

    .hero-welcome {
        background: var(--text); color: white; border-radius: 20px; padding: 2.5rem 2rem;
        margin-bottom: 2rem; box-shadow: 0 12px 40px var(--shadow);
    }
    .welcome-title { font-size: 2.8rem; font-weight: 900; margin-bottom: 0.6rem; }
    .welcome-subtitle { font-size: 1.15rem; opacity: 0.9; margin-bottom: 1.8rem; }

    .quick-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1.2rem; }
    .quick-stat {
        background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18);
        border-radius: 12px; padding: 1.3rem; text-align: center;
    }
    [data-theme="dark"] .quick-stat { background: rgba(0,0,0,0.35); border-color: #555; }
    .quick-stat-number { font-size: 2.4rem; font-weight: 900; color: var(--accent); display: block; }

    .dashboard-grid { display: grid; grid-template-columns: 1fr 380px; gap: 2rem; }

    .scanner-card {
        background: var(--card-bg); border-radius: 20px; padding: 1.8rem;
        border: 1px solid var(--border); box-shadow: 0 6px 24px var(--shadow);
    }
    .section-title { font-size: 1.9rem; font-weight: 800; margin-bottom: 1.2rem; }

    .controls-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .control-btn {
        background: var(--accent); color: white; border: none; padding: 1rem 1.4rem;
        border-radius: 12px; font-weight: 700; cursor: pointer; transition: all 0.25s;
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    }
    .control-btn:hover:not(:disabled) { background: var(--accent-hover); transform: translateY(-2px); }
    .control-btn:disabled { opacity: 0.5; cursor: not-allowed; }
    .control-btn.secondary { background: #555; color: white; }
    [data-theme="dark"] .control-btn.secondary { background: #666; }

    .camera-container {
        position: relative; background: #000; border-radius: 16px; overflow: hidden;
        margin-bottom: 1.5rem; box-shadow: 0 10px 40px rgba(0,0,0,0.45);
    }
    #scannerVideo, #scannerCanvas { width: 100%; height: auto; display: block; }
    #scannerCanvas { position: absolute; top: 0; left: 0; }

    .scanner-status {
        position: absolute; top: 1rem; left: 1rem; background: rgba(0,0,0,0.8); color: white;
        padding: 0.6rem 1.3rem; border-radius: 50px; font-weight: 700; display: flex;
        align-items: center; gap: 0.6rem; z-index: 10;
    }
    .status-dot { width: 10px; height: 10px; background: var(--status-active); border-radius: 50%; animation: pulse 2.5s infinite; }
    @keyframes pulse { 0%,100% {opacity:1} 50% {opacity:0.4} }

    .fps-counter { position: absolute; top: 1rem; right: 1rem; background: rgba(0,0,0,0.8); color: white; padding: 0.5rem 1rem; border-radius: 50px; font-size: 0.9rem; z-index: 10; }

    .detection-feed { max-height: 380px; overflow-y: auto; padding-right: 0.5rem; }
    .detection-card {
        background: #f4f4f4; border-radius: 12px; padding: 1.1rem; margin-bottom: 1rem;
        border-left: 5px solid var(--accent); display: flex; justify-content: space-between; align-items: center;
    }
    [data-theme="dark"] .detection-card { background: #222; }
    .detection-icon { font-size: 2rem; margin-right: 1rem; }
    .detection-info h4 { margin: 0 0 0.3rem; }
    .detection-meta { font-size: 0.85rem; color: var(--text-light); }
    .detection-points { background: var(--accent); color: white; padding: 0.5rem 1rem; border-radius: 10px; font-weight: 800; }

    .sidebar { display: flex; flex-direction: column; gap: 1.5rem; }

    .points-card {
        background: var(--accent); color: white; border-radius: 20px; padding: 2rem 1.5rem;
        text-align: center; box-shadow: 0 12px 48px rgba(192,0,0,0.3);
    }
    .points-number { font-size: 4rem; font-weight: 900; display: block; margin-bottom: 0.4rem; }

    .level-progress { margin: 1.2rem 0; background: rgba(255,255,255,0.25); height: 12px; border-radius: 6px; overflow: hidden; }
    .level-bar { height: 100%; background: white; border-radius: 6px; transition: width 0.6s ease; }

    .stats-card {
        background: var(--card-bg); border-radius: 20px; padding: 1.6rem;
        border: 1px solid var(--border); box-shadow: 0 6px 20px var(--shadow);
    }
    .stat-row { display: flex; justify-content: space-between; padding: 0.9rem 0; border-bottom: 1px solid var(--border); }
    .stat-row:last-child { border-bottom: none; }
    .stat-value { font-weight: 800; color: var(--accent); }

    @media (max-width: 1200px) { .dashboard-grid { grid-template-columns: 1fr; } }
    @media (max-width: 768px) {
        .welcome-title { font-size: 2.2rem; }
        .quick-stats { grid-template-columns: repeat(2, 1fr); }
        .controls-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

<div class="theme-toggle" id="themeToggle" title="Toggle dark/light mode">☀️</div>

<div class="dashboard-container">
    <div class="hero-welcome">
        <h1 class="welcome-title">Welcome back, {{ Auth::user()->name ?? 'User' }}! 👋</h1>
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

    <div class="dashboard-grid">
        <div class="scanner-card">
            <h2 class="section-title">🎯 AI Waste Scanner</h2>

            <div class="controls-grid">
                <button class="control-btn" id="startScanBtn">📹 Start Scanner</button>
                <button class="control-btn secondary" id="pauseBtn" style="display:none;">⏸️ Pause</button>
                <button class="control-btn secondary" id="switchBtn" style="display:none;">🔄 Switch Camera</button>
            </div>

            <div class="camera-container">
                <div class="scanner-status">
                    <span class="status-dot"></span>
                    <span id="statusText">Ready to Scan</span>
                </div>
                <div class="fps-counter" id="fpsCounter" style="display:none;">FPS: 0</div>

                <video id="scannerVideo" autoplay playsinline muted></video>
                <canvas id="scannerCanvas"></canvas>
            </div>

            <div>
                <h5 class="fw-bold mb-3">Recent Detections (This Session)</h5>
                <div class="detection-feed" id="detectionFeed">
                    <p class="text-center py-4" style="color:var(--text-light);">Start scanning to see detections...</p>
                </div>
            </div>
        </div>

        <div class="sidebar">
            <div class="points-card">
                <span class="points-number" id="userPoints">{{ Auth::user()->points ?? 0 }}</span>
                <span>EcoPoints</span>

                <div class="level-progress">
                    <div class="level-bar" id="levelBar" style="width:0%"></div>
                </div>

                <div class="level-info">
                    Level <strong>{{ Auth::user()->level ?? 1 }}</strong> •
                    <span id="pointsToNext">100</span> points to next level
                </div>
            </div>

            <div class="stats-card">
                <h5 class="fw-bold mb-3">📊 Your Statistics</h5>
                <div class="stat-row">
                    <span>Total Scans:</span>
                    <span class="stat-value" id="totalScans">{{ Auth::user()->total_scans ?? 0 }}</span>
                </div>
                <div class="stat-row">
                    <span>Reports Filed:</span>
                    <span class="stat-value" id="totalReports">{{ Auth::user()->total_reports ?? 0 }}</span>
                </div>
                <div class="stat-row">
                    <span>E-Waste Found:</span>
                    <span class="stat-value" id="ewasteFound">{{ Auth::user()->ewaste_count ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@3.18.0/dist/tf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/coco-ssd@2.2.2"></script>

<script>
// Theme toggle
const toggle = document.getElementById('themeToggle');
const html = document.documentElement;

function setTheme(theme) {
    html.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    toggle.textContent = theme === 'dark' ? '☀️' : '🌙';
}

const savedTheme = localStorage.getItem('theme') ||
    (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
setTheme(savedTheme);

toggle.addEventListener('click', () => {
    const current = html.getAttribute('data-theme') || 'light';
    setTheme(current === 'dark' ? 'light' : 'dark');
});

// Scanner logic
let video, canvas, ctx;
let wasteModel = null;
let isScanning = false;
let facingMode = 'user';
let lastDetectionTime = 0;
let fps = 0;
let sessionDetections = [];

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

async function init() {
    document.getElementById('statusText').textContent = 'Loading AI model...';
    try {
        wasteModel = await cocoSsd.load({ base: 'lite_mobilenet_v2' });
        document.getElementById('statusText').textContent = 'AI Ready! Click Start Scanner';
    } catch (err) {
        console.error('Model load failed:', err);
        document.getElementById('statusText').textContent = 'Failed to load model – check console';
    }
}

document.getElementById('startScanBtn').addEventListener('click', async function() {
    video = document.getElementById('scannerVideo');
    canvas = document.getElementById('scannerCanvas');
    ctx = canvas.getContext('2d');

    try {
        const stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode, width: { ideal: 1280 }, height: { ideal: 720 } }
        });

        video.srcObject = stream;
        await new Promise(resolve => video.onloadedmetadata = resolve);
        await video.play();

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        document.getElementById('statusText').textContent = 'Scanning...';
        document.getElementById('fpsCounter').style.display = 'block';

        document.getElementById('pauseBtn').style.display = 'inline-flex';
        document.getElementById('switchBtn').style.display = 'inline-flex';
        this.style.display = 'none';

        isScanning = true;
        scanFrame();
    } catch (err) {
        document.getElementById('statusText').textContent = 'Camera error: ' + err.message;
        console.error(err);
    }
});

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

    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    if (wasteModel) {
        try {
            const predictions = await wasteModel.detect(video);
            const filtered = predictions.filter(p => p.score >= 0.45);

            for (const pred of filtered) {
                const [x, y, w, h] = pred.bbox;
                const info = wasteDatabase[pred.class.toLowerCase()];

                ctx.strokeStyle = info ? 'red' : '#888';
                ctx.lineWidth = 4;
                ctx.strokeRect(x, y, w, h);

                const confidence = Math.round(pred.score * 100);
                const label = `${pred.class} ${confidence}%`;
                ctx.font = 'bold 18px Arial';
                const textWidth = ctx.measureText(label).width;
                ctx.fillStyle = info ? 'red' : '#888';
                ctx.fillRect(x, y - 40, textWidth + 20, 40);
                ctx.fillStyle = '#fff';
                ctx.fillText(label, x + 10, y - 15);

                if (info && !sessionDetections.find(d => d.type === pred.class && Date.now() - d.time < 4000)) {
                    await addDetection(pred.class, info);
                }
            }
        } catch (e) {
            console.warn('Detection error:', e);
        }
    }

    requestAnimationFrame(scanFrame);
}

async function addDetection(type, info) {
    console.log("addDetection called with:", {type, category: info.category, points: info.points});

    sessionDetections.push({ type, time: Date.now() });

    const feed = document.getElementById('detectionFeed');
    if (feed.querySelector('.text-center')) feed.innerHTML = '';

    const card = document.createElement('div');
    card.className = 'detection-card';
    card.innerHTML = `
        <div style="display:flex;align-items:center;gap:1rem;flex:1;">
            <div class="detection-icon">${info.icon}</div>
            <div class="detection-info">
                <h4>${type}</h4>
                <div class="detection-meta">${info.category} • Just now</div>
            </div>
        </div>
        <div class="detection-points">+${info.points}</div>
    `;
    feed.insertBefore(card, feed.firstChild);

    // Local update (should always show something)
    let pointsEl = document.getElementById('userPoints');
    let current = parseInt(pointsEl.textContent) || 0;
    current += info.points;
    pointsEl.textContent = current;
    console.log("Local points updated to:", current);

    // Backend save
    try {
        console.log("Sending AJAX to /scan/detect ...");

        const response = await fetch('/scan/detect', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                type: type,
                category: info.category,
                points: info.points
            })
        });

        console.log("Response status:", response.status);

        if (!response.ok) {
            const errorText = await response.text();
            console.error("Server error:", response.status, errorText);
            throw new Error(`Server responded ${response.status}`);
        }

        const data = await response.json();
        console.log("Server returned:", data);

        if (data.success) {
            document.getElementById('userPoints').textContent = data.new_points;
            document.getElementById('totalScans').textContent = data.total_scans;
            document.getElementById('ewasteFound').textContent = data.ewaste_count;
            console.log("Counters synced from server");
        } else {
            console.warn("Server said success=false", data);
        }
    } catch (err) {
        console.error('AJAX failed completely:', err);
    }
}
document.getElementById('pauseBtn').addEventListener('click', function() {
    isScanning = !isScanning;
    this.textContent = isScanning ? '⏸️ Pause' : '▶️ Resume';
    if (isScanning) scanFrame();
});

document.getElementById('switchBtn').addEventListener('click', async function() {
    facingMode = facingMode === 'user' ? 'environment' : 'user';
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode } });
        video.srcObject = stream;
    } catch (err) {
        console.error('Switch camera failed:', err);
    }
});

// Level progress
const level = {{ Auth::user()->level ?? 1 }};
const points = {{ Auth::user()->points ?? 0 }};
const next = level * 100;
const prev = (level - 1) * 100;
const progress = points > prev ? ((points - prev) / (next - prev)) * 100 : 0;
document.getElementById('levelBar').style.width = Math.min(100, progress) + '%';
document.getElementById('pointsToNext').textContent = Math.max(0, next - points);

// Start
init();

// Cleanup
window.addEventListener('beforeunload', () => {
    if (video?.srcObject) {
        video.srcObject.getTracks().forEach(t => t.stop());
    }
});
</script>
@endpush