<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoScanner Pro – AI-Powered Waste Action for Tanzania</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* PALETTE: White, Red (#dc2626), Dark Blue (#0f172a) */
        :root {
            --primary: #dc2626; /* Red */
            --dark-blue: #0f172a;
        }

        /* Theme 1: Light (White) */
        body.theme-light {
            --bg: #ffffff;
            --container-bg: #f1f5f9;
            --text-main: #0f172a;
            --text-muted: #475569;
            --border: #e2e8f0;
        }

        /* Theme 2: Dark (Dark Blue) */
        body.theme-dark {
            --bg: #0f172a;
            --container-bg: rgba(255, 255, 255, 0.05);
            --text-main: #ffffff;
            --text-muted: #cbd5e1;
            --border: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            line-height: 1.7;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Theme Toggle Button */
        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Typography */
        h1, h2, h3 { font-weight: 800; letter-spacing: -0.02em; }
        h1 { font-size: clamp(3rem, 8vw, 6rem); line-height: 1.05; margin-bottom: 1.5rem; }
        h2 { font-size: 3rem; margin-bottom: 1rem; }

        .badge {
            font-weight: 600;
            border: 2px solid var(--primary);
            color: var(--primary);
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-size: 0.9rem;
            display: inline-block;
            margin-bottom: 2rem;
        }

        /* Buttons */
        .btn {
            font-weight: 700;
            padding: 1.2rem 2.5rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: transform 0.2s, opacity 0.2s;
            font-size: 1.1rem;
        }

        .btn-primary { background-color: var(--primary); color: #ffffff; }
        .btn-secondary { background-color: var(--dark-blue); color: #ffffff; border: 1px solid rgba(255,255,255,0.2); }
        .btn:hover { transform: translateY(-3px); opacity: 0.9; }

        /* Layout */
        .section { padding: 100px 20px; max-width: 1200px; margin: 0 auto; }
        .hero { min-height: 90vh; display: flex; align-items: center; justify-content: center; text-align: center; padding: 60px 20px; }
        .hero-description { font-size: 1.25rem; color: var(--text-muted); max-width: 800px; margin: 0 auto 2.5rem; }

        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 4rem; }
        .card { background: var(--container-bg); padding: 3rem; border-radius: 16px; border: 1px solid var(--border); }
        .card h3 { color: var(--primary); margin-bottom: 1rem; }

        .stat-number { display: block; font-size: 3.5rem; font-weight: 800; color: var(--primary); }

        .problem-callout {
            background: var(--dark-blue);
            color: white;
            padding: 80px 20px;
            text-align: center;
            border-top: 5px solid var(--primary);
            border-bottom: 5px solid var(--primary);
        }

        .final-cta { background: var(--primary); color: white; text-align: center; padding: 100px 20px; }
        .final-cta .btn-secondary { background: white; color: var(--primary); }

        @media (max-width: 768px) {
            .cta-group { display: flex; flex-direction: column; gap: 10px; }
            .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body class="theme-dark"> <button class="theme-toggle" onclick="toggleTheme()">
        <i class="bi bi-palette-fill"></i>
        <span id="theme-text">Switch to Light</span>
    </button>

    <section class="hero">
        <div class="hero-content">
            <div class="badge">🇹🇿 Built for Tanzania</div>
            <h1>Waste to <span style="color:var(--primary)">Action</span></h1>
            <p class="hero-description">
                Join thousands of Dar es Salaam residents using AI to clean our streets and earn rewards.
            </p>

            <div class="cta-group">
                <a href="register" class="btn btn-primary">Start Scanning</a>
                <a href="login" class="btn btn-secondary">Login with Face</a>
            </div>

            <div class="grid">
                <div class="stat-item">
                    <span class="stat-number">5,600+</span>
                    <p>Daily Tonnes</p>
                </div>
                <div class="stat-item">
                    <span class="stat-number">40%</span>
                    <p>Collected</p>
                </div>
            </div>
        </div>
    </section>

    <section class="problem-callout">
        <div style="max-width: 900px; margin: 0 auto;">
            <h2 style="color:var(--primary)">The Crisis is Real</h2>
            <p style="font-size: 1.4rem; opacity: 0.9;">
                Technology + Community = The solution for a cleaner, flood-free Dar es Salaam.
            </p>
        </div>
    </section>

    <section class="section">
        <div class="grid">
            <div class="card">
                <h3>AI Detection</h3>
                <p>Real-time identification of plastic and e-waste using TensorFlow.js.</p>
            </div>
            <div class="card">
                <h3>Rewards</h3>
                <p>Earn points and badges for every report verified by the community.</p>
            </div>
        </div>
    </section>

    <section class="final-cta">
        <h2>Start the Change Today</h2>
        <p style="margin-bottom: 2rem;">Be part of Tanzania's digital environmental revolution.</p>
        <a href="register" class="btn btn-secondary">Create Account</a>
    </section>

    <script>
        function toggleTheme() {
            const body = document.body;
            const themeText = document.getElementById('theme-text');
            
            if (body.classList.contains('theme-dark')) {
                body.classList.remove('theme-dark');
                body.classList.add('theme-light');
                themeText.innerText = "Switch to Dark Blue";
            } else {
                body.classList.remove('theme-light');
                body.classList.add('theme-dark');
                themeText.innerText = "Switch to White";
            }
        }
    </script>
</body>
</html>