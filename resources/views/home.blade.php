<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TaskMate</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  @vite(['resources/css/app.css'])
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    html, body {
      width: 100%;
      min-height: 100%;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: #07090f;
      color: #e2e8f5;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* ── BACKGROUND ── */
    .bg {
      position: fixed; inset: 0; z-index: 0; overflow: hidden; pointer-events: none;
    }
    .bg-blob {
      position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.6;
    }
    .b1 {
      width: 500px; height: 500px; top: -150px; left: -100px;
      background: rgba(99, 102, 241, 0.22);
      animation: fl1 20s ease-in-out infinite;
    }
    .b2 {
      width: 420px; height: 420px; top: -80px; right: -80px;
      background: rgba(168, 85, 247, 0.16);
      animation: fl2 26s ease-in-out infinite;
    }
    .b3 {
      width: 350px; height: 350px; bottom: 0; left: 50%; transform: translateX(-50%);
      background: rgba(217, 70, 239, 0.1);
      animation: fl3 32s ease-in-out infinite;
    }
    .bg-noise {
      position: absolute; inset: 0;
      background-image:
        linear-gradient(rgba(255,255,255,0.018) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.018) 1px, transparent 1px);
      background-size: 48px 48px;
    }
    @keyframes fl1 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(16px,18px)} }
    @keyframes fl2 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(-16px,12px)} }
    @keyframes fl3 {
      0%,100%{transform:translateX(-50%)}
      50%{transform:translateX(-50%) translateY(-14px)}
    }

    /* ── MAIN ── */
    main {
      position: relative; z-index: 1;
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 56px 24px 36px;
      text-align: center;
    }

    /* Status pill */
    .pill {
      display: inline-flex; align-items: center; gap: 7px;
      padding: 5px 14px;
      border-radius: 99px;
      border: 1px solid rgba(99,102,241,0.35);
      background: rgba(99,102,241,0.08);
      font-size: 11px; font-weight: 700;
      letter-spacing: 1px; text-transform: uppercase; color: #a5b4fc;
      margin-bottom: 28px;
      opacity: 0;
      animation: up .6s ease .05s forwards;
    }
    .pill-dot {
      width: 5px; height: 5px; border-radius: 50%; background: #818cf8;
      animation: blink 2s infinite;
    }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

    /* Logo / Title — NO gradient text, just clean white */
    .logo-wrap {
      margin-bottom: 18px;
      opacity: 0;
      animation: up .6s ease .12s forwards;
    }
    .logo-icon-row {
      display: flex; align-items: center; justify-content: center; gap: 14px;
      margin-bottom: 0;
    }
    .logo-icon {
      width: 52px; height: 52px;
      border-radius: 14px;
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      display: flex; align-items: center; justify-content: center;
      font-size: 24px;
      box-shadow: 0 0 28px rgba(99,102,241,0.4);
      flex-shrink: 0;
    }
    .logo-text {
      font-size: clamp(36px, 9vw, 72px);
      font-weight: 800;
      letter-spacing: -1.5px;
      line-height: 1;
      color: #f1f5ff;
      /* safe — no gradient clip, no overflow issue */
      white-space: nowrap;
    }

    /* Tagline */
    .tagline {
      font-size: 13px; font-weight: 700;
      letter-spacing: 2px; text-transform: uppercase;
      color: #4f5a75;
      margin-bottom: 20px;
      opacity: 0;
      animation: up .6s ease .2s forwards;
    }

    /* Sub */
    .sub {
      font-size: 15px; color: #7a8aa8;
      max-width: 360px; line-height: 1.8;
      margin-bottom: 36px; font-weight: 400;
      opacity: 0;
      animation: up .6s ease .28s forwards;
    }

    /* Buttons */
    .btns {
      display: flex; gap: 10px;
      width: 100%; max-width: 340px;
      margin-bottom: 36px;
      opacity: 0;
      animation: up .6s ease .36s forwards;
    }
    .btn {
      flex: 1;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      padding: 13px 20px;
      border-radius: 12px;
      font-size: 14px; font-weight: 600;
      font-family: 'Plus Jakarta Sans', sans-serif;
      text-decoration: none;
      transition: all .22s ease;
      cursor: pointer; white-space: nowrap;
    }
    .btn i { font-size: 16px; }
    .btn:hover  { transform: translateY(-2px); }
    .btn:active { transform: scale(.97); }
    .btn-a {
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      color: #fff;
      box-shadow: 0 4px 20px rgba(99,102,241,0.38);
    }
    .btn-a:hover { box-shadow: 0 6px 30px rgba(99,102,241,0.55); }
    .btn-b {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.12);
      color: #c8d0e0;
    }
    .btn-b:hover { background: rgba(255,255,255,0.09); border-color: rgba(255,255,255,0.2); }

    /* Stats row */
    .stats {
      display: flex; gap: 10px; flex-wrap: wrap; justify-content: center;
      opacity: 0;
      animation: up .6s ease .44s forwards;
    }
    .stat {
      display: flex; flex-direction: column; align-items: center; gap: 3px;
      padding: 12px 18px;
      border-radius: 12px;
      background: rgba(255,255,255,0.03);
      border: 1px solid rgba(255,255,255,0.07);
      min-width: 80px;
    }
    .stat-num {
      font-size: 18px; font-weight: 800; color: #e2e8f5;
    }
    .stat-lbl {
      font-size: 10.5px; font-weight: 500; color: #4f5a75;
      text-transform: uppercase; letter-spacing: 0.5px;
    }

    /* Divider */
    .divider {
      width: 100%; max-width: 640px; height: 1px;
      background: linear-gradient(90deg, transparent, rgba(99,102,241,0.3) 30%, rgba(139,92,246,0.3) 70%, transparent);
      margin: 36px 0 0;
    }

    /* ── FOOTER ── */
    footer {
      position: relative; z-index: 1;
      flex-shrink: 0;
      padding: 16px 24px 24px;
      opacity: 0;
      animation: up .6s ease .52s forwards;
    }
    .footer-row {
      max-width: 800px; margin: 0 auto;
      display: flex; align-items: center; justify-content: space-between;
      gap: 12px; flex-wrap: wrap;
    }
    .f-left {
      display: flex; align-items: center; gap: 10px;
    }
    .f-logo {
      width: 26px; height: 26px; border-radius: 7px;
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      display: flex; align-items: center; justify-content: center;
      font-size: 13px;
    }
    .f-name {
      font-size: 13px; font-weight: 800; color: #9aa4bc; letter-spacing: -0.2px;
    }
    .f-sep { width: 1px; height: 14px; background: rgba(255,255,255,0.09); }
    .f-copy { font-size: 11.5px; color: #32394d; }

    .f-right {
      display: flex; align-items: center; gap: 8px;
    }
    .f-by { font-size: 11.5px; color: #32394d; }
    .f-credit {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 6px 12px;
      border-radius: 8px;
      background: rgba(99,102,241,0.07);
      border: 1px solid rgba(99,102,241,0.2);
      color: #a5b4fc;
      font-size: 12px; font-weight: 600;
      font-family: 'Plus Jakarta Sans', sans-serif;
      text-decoration: none;
      transition: all .2s;
    }
    .f-credit i { font-size: 13px; color: #d946ef; }
    .f-credit:hover {
      background: rgba(99,102,241,0.13);
      border-color: rgba(99,102,241,0.4);
      transform: translateY(-2px);
    }

    /* keyframe */
    @keyframes up {
      from { opacity:0; transform:translateY(18px); }
      to   { opacity:1; transform:translateY(0); }
    }

    /* ══════════════════════════
       MOBILE ≤ 480px
    ══════════════════════════ */
    @media (max-width: 480px) {
      main { padding: 44px 20px 28px; }

      .logo-icon { width: 42px; height: 42px; font-size: 20px; border-radius: 12px; }
      .logo-text  { font-size: clamp(34px, 10vw, 44px); letter-spacing: -1px; }

      .tagline { font-size: 11px; letter-spacing: 1.5px; }
      .sub { font-size: 14px; }

      .btns { flex-direction: column; max-width: 290px; }
      .btn  { width: 100%; }

      .stats { gap: 8px; }
      .stat  { padding: 10px 14px; min-width: 72px; }
      .stat-num { font-size: 16px; }

      footer { padding: 14px 18px 20px; }
      .footer-row { flex-direction: column; align-items: center; text-align: center; gap: 10px; }
      .f-left { flex-wrap: wrap; justify-content: center; }
      .f-right { flex-direction: column; align-items: center; gap: 6px; }
    }

    /* TABLET ≤ 768px */
    @media (max-width: 768px) and (min-width: 481px) {
      .logo-text { font-size: clamp(44px, 10vw, 64px); }
    }
  </style>
</head>
<body>

  <!-- BG -->
  <div class="bg">
    <div class="bg-blob b1"></div>
    <div class="bg-blob b2"></div>
    <div class="bg-blob b3"></div>
    <div class="bg-noise"></div>
  </div>

  <!-- MAIN -->
  <main>

    <!-- Pill -->
    <div class="pill">
      <div class="pill-dot"></div>
      TaskMate v2.0
    </div>

    <!-- Logo + Title -->
    <div class="logo-wrap">
      <div class="logo-icon-row">
      
        <span class="logo-text">TaskMate</span>
      </div>
    </div>

    <!-- Tagline -->
    <p class="tagline">Manajemen Tugas Modern</p>

    <!-- Sub -->
    <p class="sub">
      Atur project, deadline, dan kolaborasi tim kamu dalam satu platform yang simpel dan powerful.
    </p>

    <!-- Buttons -->
    <div class="btns">
      <a href="{{ route('login') }}" class="btn btn-a">
        <i class="ti ti-login"></i> Login
      </a>
      <a href="{{ route('register') }}" class="btn btn-b">
        <i class="ti ti-user-plus"></i> Register
      </a>
    </div>

    <!-- Stats -->
    <div class="stats">
      <div class="stat">
        <span class="stat-num">4</span>
        <span class="stat-lbl">Kolom</span>
      </div>
      <div class="stat">
        <span class="stat-num">∞</span>
        <span class="stat-lbl">Tasks</span>
      </div>
      <div class="stat">
        <span class="stat-num">
          <i class="ti ti-check" style="font-size:17px;color:#34d399"></i>
        </span>
        <span class="stat-lbl">Gratis</span>
      </div>
    </div>

    <div class="divider"></div>

  </main>

  <!-- FOOTER -->
  <footer>
    <div class="footer-row">
      <div class="f-left">
        
        <span class="f-name">TaskMate</span>
        <div class="f-sep"></div>
        <span class="f-copy">© 2026 All rights reserved</span>
      </div>
      <div class="f-right">
        <span class="f-by">UI/UX Design by</span>
        <a href="https://www.instagram.com/yoilham_15/" target="_blank" class="f-credit">
          <i class="ti ti-brand-instagram"></i>
          Muhammad Ilham.H.B
        </a>
      </div>
    </div>
  </footer>

</body>
</html>