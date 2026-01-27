<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoScanner Pro – AI-Powered Waste Action for Tanzania</title>

    <!-- Professional font: Inter (used by Stripe, Notion, Linear, Figma, Vercel, etc.) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons for visual polish -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #0a5f3f;
            --primary-light: #10b981;
            --accent: #f59e0b;
            --dark: #0f172a;
            --text: #e2e8f0;
            --gray: #94a3b8;
            --light-gray: #cbd5e1;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--dark);
            color: var(--text);
            overflow-x: hidden;
            line-height: 1.7;
            font-weight: 400;
            font-size: 1.05rem;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        h1 {
            font-size: clamp(3.5rem, 9vw, 7.2rem);
            font-weight: 800;
            line-height: 1.05;
        }

        h2 {
            font-size: 3.2rem;
            font-weight: 800;
            line-height: 1.15;
        }

        h3 {
            font-size: 1.75rem;
            font-weight: 700;
        }

        .badge {
            font-weight: 600;
            letter-spacing: 0.02em;
            background: rgba(16,185,129,0.15);
            border: 1px solid rgba(16,185,129,0.3);
            padding: 0.6rem 1.8rem;
            border-radius: 50px;
            font-size: 0.95rem;
            backdrop-filter: blur(12px);
            display: inline-block;
        }

        .hero-description,
        .section-subtitle,
        .feature-description,
        .problem-text,
        .stat-label {
            color: var(--gray);
            font-weight: 400;
        }

        .hero-description,
        .section-subtitle {
            font-size: 1.3rem;
            line-height: 1.8;
        }

        .stat-number {
            font-weight: 800;
            font-size: 3.8rem;
            letter-spacing: -0.03em;
            color: var(--primary-light);
        }

        .btn {
            font-weight: 600;
            letter-spacing: 0.01em;
            padding: 1.3rem 3.2rem;
            font-size: 1.15rem;
            border-radius: 50px;
            transition: all 0.35s cubic-bezier(0.4,0,0.2,1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: white;
            box-shadow: 0 12px 40px rgba(16,185,129,0.35);
        }

        .btn-primary:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 60px rgba(16,185,129,0.5);
        }

        .btn-secondary {
            background: rgba(255,255,255,0.08);
            border: 2px solid rgba(255,255,255,0.15);
            color: white;
        }

        .btn-secondary:hover {
            background: rgba(255,255,255,0.15);
            border-color: var(--primary-light);
            transform: translateY(-4px);
        }

        /* Background & Effects */
        .bg-container {
            position: fixed;
            inset: 0;
            z-index: -1;
            background: linear-gradient(135deg, #0a5f3f 0%, #064e3b 50%, #0f172a 100%);
        }

        .bg-container::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 20% 30%, rgba(16,185,129,0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(245,158,11,0.1) 0%, transparent 50%);
            animation: drift 15s ease-in-out infinite;
        }

        @keyframes drift {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-50px, -50px); }
        }

        .noise {
            position: fixed;
            inset: 0;
            opacity: 0.03;
            pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }

        /* Layout */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4rem 2rem 6rem;
            position: relative;
        }

        .hero-content {
            max-width: 1100px;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 2rem;
            margin: 5rem 0;
        }

        .stat-item {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 2.2rem 1.5rem;
            transition: all 0.4s ease;
        }

        .stat-item:hover {
            transform: translateY(-8px);
            background: rgba(16,185,129,0.08);
            border-color: var(--primary-light);
        }

        .section {
            padding: 8rem 2rem;
        }

        .section-title {
            font-size: 3.2rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 1.2rem;
            letter-spacing: -0.025em;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.3rem;
            opacity: 0.85;
            max-width: 720px;
            margin: 0 auto 5rem;
        }

        .features-grid, .impact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card, .impact-item {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 24px;
            padding: 2.8rem;
            transition: all 0.4s ease;
        }

        .feature-card:hover, .impact-item:hover {
            transform: translateY(-12px);
            background: rgba(16,185,129,0.08);
            border-color: var(--primary-light);
        }

        .feature-icon, .impact-icon {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
        }

        .final-cta {
            background: linear-gradient(135deg, var(--primary), var(--dark));
            text-align: center;
            padding: 10rem 2rem;
        }

        .final-cta h2 {
            font-size: 3.8rem;
            margin-bottom: 1.8rem;
        }

        .scroll-indicator {
            position: absolute;
            bottom: 3rem;
            left: 50%;
            transform: translateX(-50%);
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50% { transform: translateX(-50%) translateY(10px); }
        }

        .scroll-indicator span {
            display: block;
            width: 30px;
            height: 50px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50px;
            position: relative;
        }

        .scroll-indicator span::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 6px;
            height: 6px;
            background: white;
            border-radius: 50%;
            animation: scroll 2s infinite;
        }

        @keyframes scroll {
            0% { top: 10px; opacity: 1; }
            100% { top: 30px; opacity: 0; }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            h1 { font-size: 3.2rem; }
            .hero-description, .section-subtitle { font-size: 1.15rem; }
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 1.5rem; }
            .stat-number { font-size: 2.5rem; }
            .btn { width: 100%; justify-content: center; }
            .section { padding: 6rem 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="bg-container"></div>
    <div class="noise"></div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="badge">🇹🇿 Built for Tanzania's Communities</div>
            
            <h1>Turn Waste into Real Change</h1>
            
            <p class="hero-description">
                Dar es Salaam generates over <strong>5,600 tonnes of waste every day</strong>. Only ~40% is collected properly. 
                EcoScanner Pro uses AI on your phone to detect waste, report hotspots, earn rewards, and help build a cleaner, healthier city—together.
            </p>

            <div class="cta-group" style="display: flex; gap: 1.5rem; justify-content: center; flex-wrap: wrap; margin: 3rem 0;">
                <a href="/register" class="btn btn-primary">
                    <i class="bi bi-rocket-takeoff-fill me-2"></i> Start Scanning – Free Forever
                </a>
                <a href="/login" class="btn btn-secondary">
                    <i class="bi bi-fingerprint me-2"></i> Login with Face
                </a>
            </div>

            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number">5,600+</span>
                    <span class="stat-label">Tonnes of Waste Daily</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">~40%</span>
                    <span class="stat-label">Collected Properly</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">~65%</span>
                    <span class="stat-label">E-Waste Mismanaged</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">12–13%</span>
                    <span class="stat-label">Plastic Waste Share</span>
                </div>
            </div>
        </div>

        <div class="scroll-indicator">
            <span></span>
        </div>
    </section>

    <!-- Problem Section -->
    <section class="section" style="background: rgba(245,158,11,0.05); border-top: 1px solid rgba(245,158,11,0.2); border-bottom: 1px solid rgba(245,158,11,0.2);">
        <div style="max-width: 1000px; margin: 0 auto; text-align: center;">
            <div style="font-size: 4rem; font-weight: 800; color: var(--accent); margin-bottom: 1rem;">The Crisis Is Real</div>
            <p class="problem-text" style="font-size: 1.4rem; max-width: 900px; margin: 0 auto 2.5rem;">
                With rapid urbanization, Dar es Salaam produces ~5,600–5,700 tonnes of solid waste daily. 
                Collection covers only about 40%, leaving uncollected trash that blocks drains, causes flooding, spreads disease, and pollutes our ocean and air.
            </p>
            <p style="font-size: 1.6rem; color: var(--accent); font-weight: 600;">
                <strong>We can't wait for solutions from above. Technology + community = the way forward.</strong>
            </p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section">
        <h2 class="section-title">Why EcoScanner Pro Works</h2>
        <p class="section-subtitle">
            Advanced AI meets real community power to fight waste at scale
        </p>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3 class="feature-title">Instant AI Detection</h3>
                <p class="feature-description">
                    Point your phone — our TensorFlow.js model identifies plastic, e-waste, organics & more in real time with high accuracy.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">👤</div>
                <h3 class="feature-title">Secure Face Login</h3>
                <p class="feature-description">
                    Register once with your face — no passwords, no hassle. Fast, private login every time.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🏆</div>
                <h3 class="feature-title">Earn While You Clean</h3>
                <p class="feature-description">
                    Get points, badges, levels & compete locally. Turn eco-action into motivation and rewards.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">📍</div>
                <h3 class="feature-title">Live Hotspot Reporting</h3>
                <p class="feature-description">
                    Tag locations, add photos/notes. Build a community map that authorities & recyclers can act on.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">♻️</div>
                <h3 class="feature-title">E-Waste Focus</h3>
                <p class="feature-description">
                    Higher rewards for phones, batteries, laptops. Connect to certified collectors in Dar.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🌐</div>
                <h3 class="feature-title">Offline-First</h3>
                <p class="feature-description">
                    Works anywhere — queue reports & sync when online. Built for Dar's connectivity reality.
                </p>
            </div>
        </div>
    </section>

    <!-- Impact Section -->
    <section class="section">
        <h2 class="section-title">Real Change Starts Here</h2>
        <p class="section-subtitle">
            What your scans & reports can achieve together
        </p>

        <div class="impact-grid">
            <div class="impact-item">
                <div class="impact-icon">🗑️</div>
                <h3 class="impact-title">Cut Illegal Dumping</h3>
                <p>Community reports = faster cleanups & accountability</p>
            </div>
            <div class="impact-item">
                <div class="impact-icon">💧</div>
                <h3 class="impact-title">Prevent Floods</h3>
                <p>Spot blocked drains early — save lives & property</p>
            </div>
            <div class="impact-item">
                <div class="impact-icon">♻️</div>
                <h3 class="impact-title">Boost Recycling</h3>
                <p>Connect waste to value — create jobs & reduce landfill</p>
            </div>
            <div class="impact-item">
                <div class="impact-icon">📚</div>
                <h3 class="impact-title">Educate & Empower</h3>
                <p>Instant tips change habits — build a sustainable culture</p>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="final-cta">
        <h2>Join the Movement Today</h2>
        <p style="font-size: 1.4rem; max-width: 720px; margin: 0 auto 3rem; opacity: 0.92;">
            Hundreds of Dar residents are already scanning, reporting, and earning while cleaning our city. 
            Be part of the change — it takes 30 seconds to start.
        </p>
        <div class="cta-group">
            <a href="/register" class="btn btn-primary">
                <i class="bi bi-rocket-takeoff-fill me-2"></i> Start Scanning – Free Forever
            </a>
            <a href="/login" class="btn btn-secondary">
                <i class="bi bi-fingerprint me-2"></i> Login with Face
            </a>
        </div>
        <div style="margin-top: 4rem; opacity: 0.7; font-size: 1rem;">
            ✓ No card needed • ✓ Offline ready • ✓ Privacy-first • Built for Tanzania
        </div>
    </section>

    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', e => {
                e.preventDefault();
                document.querySelector(anchor.getAttribute('href'))?.scrollIntoView({ behavior: 'smooth' });
            });
        });
    </script>
</body>
</html>