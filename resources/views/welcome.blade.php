<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - TaskMate</title>

    @vite(['resources/css/app.css'])
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #1c1412 0%, #291c1a 50%, #362522 100%);
            color: white;
            font-family: 'Figtree', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Animated background shapes */
        .bg-shape {
            position: fixed;
            border-radius: 50%;
            opacity: 0.05;
            pointer-events: none;
        }
        .shape-1 { width: 400px; height: 400px; top: -100px; left: -100px; background: #e91e63; animation: float 20s ease-in-out infinite; }
        .shape-2 { width: 300px; height: 300px; bottom: -50px; right: -50px; background: #faf6f0; animation: float 25s ease-in-out infinite reverse; }

        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, 30px); }
        }

        .stars {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            animation: twinkle 3s infinite;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.2; }
            50% { opacity: 1; }
        }

        .container {
            text-align: center;
            max-width: 700px;
            z-index: 10;
            animation: slideUp 0.9s cubic-bezier(0.34, 1.56, 0.64, 1);
            transition: all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        body.exit-mode .container {
            animation: exitRotate 1.2s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(60px) scale(0.8);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes exitRotate {
            0% {
                opacity: 1;
                transform: translateY(0) rotateY(0deg) scale(1);
                filter: blur(0px);
            }
            50% {
                transform: translateY(-30px) rotateY(45deg) scale(1.1);
            }
            100% {
                opacity: 0;
                transform: translateY(-100vh) rotateY(90deg) scale(0.5);
                filter: blur(20px);
            }
        }

        .welcome-icon {
            font-size: 5rem;
            margin-bottom: 2rem;
            animation: bounceRotate 3s ease-in-out infinite;
            display: inline-block;
        }

        @keyframes bounceRotate {
            0%, 100% { transform: translateY(0) rotateZ(0deg); }
            25% { transform: translateY(-30px) rotateZ(-10deg); }
            50% { transform: translateY(-50px) rotateZ(10deg); }
            75% { transform: translateY(-30px) rotateZ(-10deg); }
        }

        h1 {
            font-size: 3.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #fbf7f4 0%, #ff8da1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            letter-spacing: -1px;
            animation: fadeInScale 1s ease-out 0.2s both;
        }

        .subtitle {
            font-size: 1.35rem;
            color: #cbd5e1;
            margin-bottom: 0.5rem;
            font-weight: 500;
            animation: fadeInScale 1s ease-out 0.3s both;
        }

        .description {
            font-size: 1.05rem;
            color: #a0aec0;
            margin-bottom: 3rem;
            line-height: 1.7;
            animation: fadeInScale 1s ease-out 0.4s both;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3.5rem;
            animation: fadeInScale 1s ease-out 0.5s both;
        }

        .feature-item {
            padding: 1.8rem 1.5rem;
            border-radius: 16px;
            background: rgba(233, 30, 99, 0.08);
            border: 1px solid rgba(233, 30, 99, 0.35);
            backdrop-filter: blur(12px);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .feature-item::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(233, 30, 99, 0.3) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .feature-item:hover {
            background: rgba(233, 30, 99, 0.15);
            border-color: rgba(233, 30, 99, 0.8);
            transform: translateY(-8px) scale(1.05);
            box-shadow: 0 12px 30px rgba(233, 30, 99, 0.25);
        }

        .feature-item:hover::before {
            opacity: 1;
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 0.7rem;
            transition: transform 0.4s ease;
        }

        .feature-item:hover .feature-icon {
            transform: rotateY(360deg) scale(1.2);
        }

        .feature-name {
            font-size: 0.95rem;
            color: #cbd5e1;
            font-weight: 700;
            letter-spacing: 0.5px;
            position: relative;
            z-index: 1;
        }

        .button-group {
            display: flex;
            gap: 1.2rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInScale 1s ease-out 0.6s both;
        }

        .btn {
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-size: 1.05rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-dashboard {
            background: linear-gradient(135deg, #e91e63 0%, #f06292 50%, #ec407a 100%);
            color: white;
            background-size: 200% 200%;
            animation: gradientShift 3s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .btn-dashboard:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 15px 40px rgba(233, 30, 99, 0.5);
        }

        .btn-dashboard:active {
            transform: scale(0.98);
        }

        .btn-logout {
            background: rgba(233, 30, 99, 0.07);
            color: #ff8da1;
            border: 2px solid rgba(233, 30, 99, 0.3);
            transition: all 0.4s ease;
        }

        .btn-logout:hover {
            background: rgba(233, 30, 99, 0.15);
            border-color: rgba(233, 30, 99, 0.6);
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(233, 30, 99, 0.2);
        }

        .user-badge {
            display: inline-block;
            background: linear-gradient(135deg, rgba(233, 30, 99, 0.3) 0%, rgba(250, 246, 240, 0.1) 100%);
            border: 1.5px solid rgba(233, 30, 99, 0.6);
            padding: 0.6rem 1.3rem;
            border-radius: 25px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: #ff8da1;
            font-weight: 600;
            animation: fadeInScale 1s ease-out 0.1s both;
            letter-spacing: 0.5px;
        }

        @media (max-width: 640px) {
            h1 { font-size: 2.5rem; }
            .subtitle { font-size: 1.1rem; }
            .welcome-icon { font-size: 3.5rem; }
            .features { grid-template-columns: 1fr; gap: 1rem; }
            .button-group { gap: 0.8rem; }
            .btn { padding: 0.85rem 1.8rem; font-size: 0.95rem; }
        }
    </style>
</head>

<body>
    <!-- Animated Background Shapes -->
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>

    <div class="stars" id="stars"></div>

    <div class="container">
        <div class="user-badge">✨ Terverifikasi</div>

        <div class="welcome-icon">👋</div>

        <h1>Selamat Datang!</h1>
        <p class="subtitle">Anda berhasil login ke TaskMate</p>
        <p class="description">Kelola tugas, deadline, dan kolaborasi tim dengan lebih efektif menggunakan platform kami.</p>

        <div class="features">
            <div class="feature-item">
                <div class="feature-icon">📋</div>
                <div class="feature-name">Task Management</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">📅</div>
                <div class="feature-name">Deadline Tracking</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">👥</div>
                <div class="feature-name">Team Collaboration</div>
            </div>
        </div>

        <div class="button-group">
            <a href="{{ route('dashboard') }}" class="btn btn-dashboard" id="dashboardBtn">
                🚀 Ke Dashboard
            </a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-logout">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <script>
        // Generate random stars
        const starsContainer = document.getElementById('stars');
        for (let i = 0; i < 50; i++) {
            const star = document.createElement('div');
            star.className = 'star';
            star.style.left = Math.random() * 100 + '%';
            star.style.top = Math.random() * 100 + '%';
            star.style.width = Math.random() * 3 + 1 + 'px';
            star.style.height = star.style.width;
            star.style.animationDelay = Math.random() * 3 + 's';
            starsContainer.appendChild(star);
        }

        // Exit animation to dashboard
        const dashboardBtn = document.getElementById('dashboardBtn');
        dashboardBtn.addEventListener('click', (e) => {
            e.preventDefault();
            document.body.classList.add('exit-mode');
            setTimeout(() => {
                window.location.href = '{{ route("dashboard") }}';
            }, 1200);
        });

        // Auto-redirect after 5 seconds
        setTimeout(() => {
            if (!document.body.classList.contains('exit-mode')) {
                document.body.classList.add('exit-mode');
                setTimeout(() => {
                    window.location.href = '{{ route("dashboard") }}';
                }, 1200);
            }
        }, 5000);
    </script>
</body>
</html>
