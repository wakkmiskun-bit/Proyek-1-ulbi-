<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bantuan & Dukungan — TaskMate</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    html, body {
      width: 100%;
      min-height: 100%;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: linear-gradient(135deg, #1c1412 0%, #291c1a 50%, #362522 100%);
      color: #fbf7f4;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      overflow-x: hidden;
      position: relative;
    }

    /* ── BACKGROUND ── */
    .bg {
      position: fixed; inset: 0; z-index: 0; overflow: hidden; pointer-events: none;
    }
    .bg-blob {
      position: absolute; border-radius: 50%; filter: blur(120px); opacity: 0.5;
    }
    .b1 {
      width: 500px; height: 500px; top: -150px; left: -100px;
      background: rgba(233, 30, 99, 0.18);
      animation: fl1 20s ease-in-out infinite;
    }
    .b2 {
      width: 420px; height: 420px; top: -80px; right: -80px;
      background: rgba(250, 246, 240, 0.08);
      animation: fl2 26s ease-in-out infinite;
    }
    .b3 {
      width: 350px; height: 350px; bottom: -100px; left: 50%; transform: translateX(-50%);
      background: rgba(236, 72, 153, 0.12);
      animation: fl3 32s ease-in-out infinite;
    }
    .bg-noise {
      position: absolute; inset: 0;
      background-image:
        linear-gradient(rgba(250,246,240,0.015) 1px, transparent 1px),
        linear-gradient(90deg, rgba(250,246,240,0.015) 1px, transparent 1px);
      background-size: 48px 48px;
    }
    @keyframes fl1 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(16px,18px)} }
    @keyframes fl2 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(-16px,12px)} }
    @keyframes fl3 {
      0%,100%{transform:translateX(-50%)}
      50%{transform:translateX(-50%) translateY(-14px)}
    }

    /* ── HEADER ── */
    header {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      padding: 24px;
      display: flex;
      justify-content: flex-start;
      z-index: 10;
    }

    .btn-back {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 18px;
      border-radius: 99px;
      background: rgba(251, 247, 244, 0.04);
      border: 1px solid rgba(251, 247, 244, 0.1);
      color: #c3b4b0;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.25s ease;
      backdrop-filter: blur(10px);
    }
    .btn-back i {
      font-size: 16px;
    }
    .btn-back:hover {
      background: rgba(251, 247, 244, 0.08);
      border-color: rgba(251, 247, 244, 0.25);
      color: #fbf7f4;
      transform: translateX(-3px);
    }

    /* ── MAIN CONTENT ── */
    main {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 480px;
      padding: 80px 24px 40px;
      text-align: center;
      opacity: 0;
      animation: fadeInUp 0.6s ease forwards;
    }

    /* Icon support header */
    .support-icon-wrap {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 72px;
      height: 72px;
      border-radius: 20px;
      background: linear-gradient(135deg, rgba(233, 30, 99, 0.2), rgba(240, 98, 146, 0.2));
      border: 1px solid rgba(233, 30, 99, 0.4);
      box-shadow: 0 0 30px rgba(233, 30, 99, 0.2);
      margin-bottom: 24px;
    }
    .support-icon-wrap i {
      font-size: 32px;
      color: #ff8da1;
    }

    h1 {
      font-size: 32px;
      font-weight: 800;
      letter-spacing: -0.5px;
      margin-bottom: 12px;
      background: linear-gradient(to right, #fbf7f4, #c3b4b0);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .subtitle {
      font-size: 14px;
      color: #a8938e;
      line-height: 1.6;
      margin-bottom: 36px;
    }

    /* Contact Card */
    .contact-card {
      background: rgba(251, 247, 244, 0.03);
      border: 1px solid rgba(251, 247, 244, 0.08);
      border-radius: 24px;
      padding: 28px;
      text-align: left;
      backdrop-filter: blur(20px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    .contact-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0; height: 3px;
      background: linear-gradient(90deg, #25D366, #128C7E);
    }
    .contact-card:hover {
      transform: translateY(-4px);
      border-color: rgba(233, 30, 99, 0.2);
      box-shadow: 0 15px 40px rgba(233, 30, 99, 0.08);
    }

    .contact-header {
      display: flex;
      align-items: center;
      gap: 16px;
      margin-bottom: 18px;
    }
    .contact-avatar {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      background: rgba(37, 211, 102, 0.15);
      border: 1px solid rgba(37, 211, 102, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .contact-avatar i {
      font-size: 24px;
      color: #25D366;
    }
    .contact-title-info h3 {
      font-size: 18px;
      font-weight: 700;
      color: #fbf7f4;
      margin-bottom: 2px;
    }
    .contact-title-info p {
      font-size: 12px;
      color: #25D366;
      font-weight: 600;
    }

    .contact-body {
      font-size: 13.5px;
      color: #c3b4b0;
      line-height: 1.6;
      margin-bottom: 24px;
    }

    .btn-whatsapp {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      width: 100%;
      padding: 14px;
      border-radius: 14px;
      background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
      color: #fff;
      font-size: 14px;
      font-weight: 700;
      text-decoration: none;
      box-shadow: 0 4px 20px rgba(37, 211, 102, 0.3);
      transition: all 0.25s ease;
      cursor: pointer;
    }
    .btn-whatsapp i {
      font-size: 18px;
    }
    .btn-whatsapp:hover {
      box-shadow: 0 6px 24px rgba(37, 211, 102, 0.5);
      transform: translateY(-2px);
    }
    .btn-whatsapp:active {
      transform: scale(0.98);
    }

    /* ── FOOTER ── */
    footer {
      margin-top: 40px;
      font-size: 12px;
      color: #705953;
    }
    footer a {
      color: #c3b4b0;
      text-decoration: none;
      font-weight: 600;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 480px) {
      header {
        padding: 16px;
      }
      main {
        padding: 60px 16px 30px;
      }
      h1 {
        font-size: 26px;
      }
      .contact-card {
        padding: 20px;
      }
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

  <!-- HEADER -->
  <header>
    <a href="javascript:history.length > 1 ? history.back() : window.location.href='/'" class="btn-back">
      <i class="ti ti-arrow-left"></i> Kembali
    </a>
  </header>

  <!-- MAIN -->
  <main>
    <div class="support-icon-wrap">
      <i class="ti ti-help"></i>
    </div>

    <h1>Pusat Bantuan</h1>
    <p class="subtitle">Ada kendala atau pertanyaan? Tim kami siap membantu Anda menyelesaikan masalah dengan cepat.</p>

    <!-- Hubungi Admin Card -->
    <div class="contact-card">
      <div class="contact-header">
        <div class="contact-avatar">
          <i class="ti ti-brand-whatsapp"></i>
        </div>
        <div class="contact-title-info">
          <h3>Hubungi Admin</h3>
          <p>Tersedia via WhatsApp</p>
        </div>
      </div>
      <div class="contact-body">
        Silakan klik tombol di bawah untuk langsung terhubung dengan WhatsApp Admin TaskMate dan berkonsultasi mengenai kendala Anda.
      </div>
      <a href="https://wa.me/{{ $adminPhone }}?text=Halo%20Admin%20TaskMate%2C%20saya%20butuh%20bantuan%20terkait%20platform%20TaskMate." target="_blank" class="btn-whatsapp">
        <i class="ti ti-brand-whatsapp"></i> Kirim Pesan WhatsApp
      </a>
    </div>

    <footer>
      <span>&copy; 2026 <a href="/">TaskMate</a>. All rights reserved.</span>
    </footer>
  </main>

</body>
</html>
