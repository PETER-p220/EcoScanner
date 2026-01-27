<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoScanner Pro - AI-Powered Waste Management for Tanzania</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
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
            --light: #f8fafc;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--dark);
            color: white;
            overflow-x: hidden;
        }

        /* Animated Background */
        .bg-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(135deg, #0a5f3f 0%, #064e3b 50%, #0f172a 100%);
        }

        .bg-container::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 20% 30%, rgba(16, 185, 129, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(245, 158, 11, 0.1) 0%, transparent 50%);
            animation: drift 15s ease-in-out infinite;
        }

        @keyframes drift {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-50px, -50px); }
        }

        .noise {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.03;
            pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }

        .hero-content {
            max-width: 1200px;
            text-align: center;
            animation: fadeInUp 1s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .badge {
            display: inline-block;
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid rgba(16, 185, 129, 0.3);
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease 0.2s both;
            backdrop-filter: blur(10px);
        }

        h1 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(3rem, 8vw, 6rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            animation: fadeInUp 1s ease 0.4s both;
            background: linear-gradient(135deg, #ffffff 0%, #10b981 50%, #f59e0b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.02em;
        }

        .hero-description {
            font-size: 1.4rem;
            line-height: 1.7;
            margin-bottom: 3rem;
            opacity: 0.9;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 1s ease 0.6s both;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin: 4rem 0;
            animation: fadeInUp 1s ease 0.8s both;
        }

        .stat-item {
            text-align: center;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(16, 185, 129, 0.3);
        }

        .stat-number {
            font-family: 'Syne', sans-serif;
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary-light);
            display: block;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.8;
        }

        /* CTA Buttons */
        .cta-group {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease 1s both;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.25rem 3rem;
            font-size: 1.1rem;
            font-weight: 700;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: white;
            box-shadow: 0 10px 40px rgba(16, 185, 129, 0.3);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 50px rgba(16, 185, 129, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-3px);
        }

        /* Features Section */
        .features {
            padding: 6rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            font-family: 'Syne', sans-serif;
            font-size: 3rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            opacity: 0.8;
            margin-bottom: 4rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 2.5rem;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-light), var(--accent));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(16, 185, 129, 0.3);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            transform: rotate(-5deg);
            transition: transform 0.4s ease;
        }

        .feature-card:hover .feature-icon {
            transform: rotate(0deg) scale(1.1);
        }

        .feature-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .feature-description {
            opacity: 0.85;
            line-height: 1.7;
        }

        /* Problem Statement */
        .problem-section {
            padding: 6rem 2rem;
            background: rgba(245, 158, 11, 0.05);
            border-top: 1px solid rgba(245, 158, 11, 0.2);
            border-bottom: 1px solid rgba(245, 158, 11, 0.2);
        }

        .problem-content {
            max-width: 1000px;
            margin: 0 auto;
            text-align: center;
        }

        .problem-stat {
            font-family: 'Syne', sans-serif;
            font-size: 4rem;
            font-weight: 800;
            color: var(--accent);
            margin-bottom: 1rem;
        }

        .problem-text {
            font-size: 1.3rem;
            line-height: 1.8;
            opacity: 0.9;
        }

        /* Impact Section */
        .impact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }

        .impact-item {
            text-align: center;
        }

        .impact-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .impact-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        /* Final CTA */
        .final-cta {
            padding: 8rem 2rem;
            text-align: center;
        }

        .final-cta h2 {
            font-family: 'Syne', sans-serif;
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 2rem;
            line-height: 1.2;
        }

        .final-cta p {
            font-size: 1.3rem;
            opacity: 0.9;
            margin-bottom: 3rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Scroll Indicator */
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
            border: 2px solid rgba(255, 255, 255, 0.3);
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

        /* Responsive */
        @media (max-width: 768px) {
            h1 { font-size: 3rem; }
            .hero-description { font-size: 1.1rem; }
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 1rem; }
            .stat-number { font-size: 2rem; }
            .cta-group { flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
            .section-title { font-size: 2rem; }
            .features-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="bg-container"></div>
    <div class="noise"></div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="badge">🇹🇿 Built for Tanzania's Future</div>
            
            <h1>Transform Waste into<br>Environmental Action</h1>
            
            <p class="hero-description">
                Join the AI-powered movement solving Tanzania's waste crisis. Use facial recognition, 
                detect waste in real-time, earn rewards, and make Dar es Salaam cleaner—one scan at a time.
            </p>

            <div class="cta-group">
                <a href="/register" class="btn btn-primary">
                    <span>📸</span> Start with Face Recognition
                </a>
                <a href="/login" class="btn btn-secondary">
                    <span>🔐</span> Login Instantly
                </a>
            </div>

            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number">4,252</span>
                    <span class="stat-label">Tons of Daily Waste in Dar</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">42%</span>
                    <span class="stat-label">Not Collected Properly</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">65%</span>
                    <span class="stat-label">E-Waste Improperly Disposed</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">$8.3M</span>
                    <span class="stat-label">Recycling Investment Potential</span>
                </div>
            </div>
        </div>

        <div class="scroll-indicator">
            <span></span>
        </div>
    </section>

    <!-- Problem Section -->
    <section class="problem-section">
        <div class="problem-content">
            <div class="problem-stat">The Challenge</div>
            <p class="problem-text">
                Dar es Salaam faces a critical waste management crisis. With rapid urbanization, 
                <strong>only 58% of waste is collected</strong>, leading to flooding, health risks, and environmental degradation. 
                E-waste is dumped illegally, plastic clogs drainage systems, and communities lack the tools to take action.
            </p>
            <p class="problem-text" style="margin-top: 2rem; font-size: 1.5rem; color: var(--accent);">
                <strong>It's time for a solution powered by technology and community.</strong>
            </p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <h2 class="section-title">Powerful Features</h2>
        <p class="section-subtitle">
            Advanced AI technology meets community action to create lasting environmental impact
        </p>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3 class="feature-title">AI Waste Detection</h3>
                <p class="feature-description">
                    Point your camera at any waste item. Our TensorFlow-powered AI instantly identifies 
                    plastic, e-waste, organic materials, and more with 95%+ accuracy.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">👤</div>
                <h3 class="feature-title">Facial Recognition Login</h3>
                <p class="feature-description">
                    No passwords needed. Register once with your face and login instantly. 
                    Your face becomes your secure, convenient access key.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🎮</div>
                <h3 class="feature-title">Gamified Rewards</h3>
                <p class="feature-description">
                    Earn points for every detection, level up, unlock badges, and compete on leaderboards. 
                    Turn environmental action into an engaging challenge.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">📍</div>
                <h3 class="feature-title">Community Reporting</h3>
                <p class="feature-description">
                    Report waste hotspots with GPS tagging. Create a live map of environmental issues 
                    and connect directly with local authorities.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3 class="feature-title">E-Waste Tracking</h3>
                <p class="feature-description">
                    Specialized detection for phones, laptops, and electronics. Get higher rewards 
                    and connect with certified e-waste recyclers in Dar es Salaam.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🌐</div>
                <h3 class="feature-title">Offline-First Design</h3>
                <p class="feature-description">
                    Works without internet. Queue your reports and detections, then sync automatically 
                    when you're back online. Perfect for all areas of Dar.
                </p>
            </div>
        </div>
    </section>

    <!-- Impact Section -->
    <section class="features">
        <h2 class="section-title">Real Impact</h2>
        <p class="section-subtitle">
            Here's how EcoScanner is solving Tanzania's environmental challenges
        </p>

        <div class="impact-grid">
            <div class="impact-item">
                <div class="impact-icon">🗑️</div>
                <h3 class="impact-title">Reduce Illegal Dumping</h3>
                <p class="feature-description">
                    Community reports create accountability and faster response from authorities
                </p>
            </div>

            <div class="impact-item">
                <div class="impact-icon">💧</div>
                <h3 class="impact-title">Prevent Flooding</h3>
                <p class="feature-description">
                    Detect blocked drainage systems before the rainy season causes disasters
                </p>
            </div>

            <div class="impact-item">
                <div class="impact-icon">♻️</div>
                <h3 class="impact-title">Boost Recycling</h3>
                <p class="feature-description">
                    Connect waste collectors with valuable materials and create economic opportunities
                </p>
            </div>

            <div class="impact-item">
                <div class="impact-icon">📚</div>
                <h3 class="impact-title">Educate Communities</h3>
                <p class="feature-description">
                    Real-time tips teach proper waste disposal and sustainability practices
                </p>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="final-cta">
        <h2>Ready to Make a Difference?</h2>
        <p>
            Join hundreds of environmental champions in Dar es Salaam who are using technology 
            to solve our waste crisis. Register in 30 seconds with just your face.
        </p>
        <div class="cta-group">
            <a href="/register" class="btn btn-primary">
                <span>🚀</span> Get Started Now
            </a>
            <a href="#features" class="btn btn-secondary">
                <span>📖</span> Learn More
            </a>
        </div>
        
        <div style="margin-top: 4rem; opacity: 0.6; font-size: 0.9rem;">
            <p>✓ No credit card required &nbsp;•&nbsp; ✓ Free forever &nbsp;•&nbsp; ✓ Works offline</p>
        </div>
    </section>

    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });

        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeInUp 0.8s ease forwards';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card, .impact-item').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>