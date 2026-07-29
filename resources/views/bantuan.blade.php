<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pusat Bantuan — TaskMate</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    :root {
      --pink: #e91e63; --pink-light: rgba(233,30,99,0.12); --pink-glow: rgba(233,30,99,0.25);
      --text: #fbf7f4; --text2: #c3b4b0; --text3: #7a6560;
      --card: rgba(255,255,255,0.035); --card-border: rgba(255,255,255,0.08);
      --input-bg: rgba(255,255,255,0.05); --input-border: rgba(255,255,255,0.1);
      --radius: 16px; --radius-sm: 10px;
    }
    html, body { width: 100%; min-height: 100vh; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: linear-gradient(135deg, #120d0f 0%, #1c1412 50%, #251a18 100%); color: var(--text); min-height: 100vh; overflow-x: hidden; }

    .bg { position: fixed; inset: 0; z-index: 0; overflow: hidden; pointer-events: none; }
    .blob { position: absolute; border-radius: 50%; filter: blur(130px); opacity: 0.45; }
    .b1 { width: 550px; height: 550px; top: -180px; left: -120px; background: rgba(233,30,99,0.18); animation: fl1 22s ease-in-out infinite; }
    .b2 { width: 400px; height: 400px; top: -60px; right: -80px; background: rgba(255,255,255,0.05); animation: fl2 28s ease-in-out infinite; }
    .b3 { width: 380px; height: 380px; bottom: -100px; left: 50%; transform: translateX(-50%); background: rgba(236,72,153,0.1); animation: fl3 34s ease-in-out infinite; }
    .bg-grid { position: absolute; inset: 0; background-image: linear-gradient(rgba(255,255,255,0.012) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.012) 1px, transparent 1px); background-size: 48px 48px; }
    @keyframes fl1 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(18px,20px)} }
    @keyframes fl2 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(-14px,10px)} }
    @keyframes fl3 { 0%,100%{transform:translateX(-50%)} 50%{transform:translateX(-50%) translateY(-16px)} }

    .site-header { position: fixed; top: 0; left: 0; right: 0; z-index: 50; display: flex; align-items: center; justify-content: space-between; padding: 20px 32px; backdrop-filter: blur(20px); background: rgba(18,13,15,0.6); border-bottom: 1px solid rgba(255,255,255,0.05); }
    .logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
    .logo-icon { width: 36px; height: 36px; border-radius: 10px; background: var(--pink); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 18px; color: #fff; }
    .logo-text { font-weight: 800; font-size: 18px; color: var(--text); }
    .btn-back { display: inline-flex; align-items: center; gap: 8px; padding: 9px 18px; border-radius: 99px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); color: var(--text2); font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.22s; }
    .btn-back:hover { background: rgba(255,255,255,0.08); color: var(--text); transform: translateX(-2px); }

    .page-wrap { position: relative; z-index: 1; min-height: 100vh; display: flex; flex-direction: column; align-items: center; padding: 110px 20px 60px; }

    .hero { text-align: center; margin-bottom: 44px; animation: fadeUp 0.55s ease both; }
    .hero-badge { display: inline-flex; align-items: center; gap: 8px; padding: 6px 16px; border-radius: 99px; background: var(--pink-light); border: 1px solid rgba(233,30,99,0.3); color: #ff8da1; font-size: 12px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 20px; }
    .hero h1 { font-size: clamp(28px, 6vw, 44px); font-weight: 800; background: linear-gradient(135deg, #fff 0%, #c3b4b0 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1.15; margin-bottom: 14px; }
    .hero p { font-size: 14.5px; color: var(--text3); line-height: 1.65; max-width: 460px; margin: 0 auto; }

    .form-card { width: 100%; max-width: 580px; background: var(--card); border: 1px solid var(--card-border); border-radius: 24px; padding: 40px; backdrop-filter: blur(24px); box-shadow: 0 24px 60px rgba(0,0,0,0.3); animation: fadeUp 0.6s 0.1s ease both; position: relative; overflow: hidden; }
    .form-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--pink), #f06292, #ec4899); }

    .alert-success { display: flex; align-items: flex-start; gap: 14px; padding: 18px 20px; border-radius: 14px; background: rgba(37,211,102,0.1); border: 1px solid rgba(37,211,102,0.25); color: #4ade80; font-size: 14px; font-weight: 600; margin-bottom: 28px; }
    .alert-success i { font-size: 20px; flex-shrink: 0; margin-top: 1px; }
    .alert-error { display: flex; align-items: flex-start; gap: 12px; padding: 14px 18px; border-radius: 12px; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25); color: #f87171; font-size: 13px; margin-bottom: 22px; }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-size: 12px; font-weight: 700; color: var(--text2); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    .form-label span { color: var(--pink); }
    .form-input, .form-textarea, .form-select { width: 100%; padding: 13px 16px; background: var(--input-bg); border: 1px solid var(--input-border); border-radius: var(--radius-sm); color: var(--text); font-family: inherit; font-size: 14px; font-weight: 500; transition: border-color 0.22s, box-shadow 0.22s, background 0.22s; outline: none; }
    .form-input::placeholder, .form-textarea::placeholder { color: var(--text3); font-weight: 400; }
    .form-input:focus, .form-textarea:focus, .form-select:focus { border-color: var(--pink); background: rgba(233,30,99,0.06); box-shadow: 0 0 0 3px var(--pink-glow); }
    .form-select { cursor: pointer; appearance: none; -webkit-appearance: none; }
    .form-select option { background: #1c1412; color: var(--text); }
    .form-textarea { resize: vertical; min-height: 130px; line-height: 1.6; }
    .input-hint { font-size: 11.5px; color: var(--text3); margin-top: 6px; }

    .btn-submit { width: 100%; padding: 15px 24px; background: linear-gradient(135deg, var(--pink) 0%, #f06292 100%); border: none; border-radius: var(--radius-sm); color: #fff; font-family: inherit; font-size: 15px; font-weight: 700; cursor: pointer; box-shadow: 0 6px 24px rgba(233,30,99,0.35); transition: all 0.25s; }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(233,30,99,0.5); }
    .btn-submit:active { transform: scale(0.98); }
    .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
    .btn-submit i { margin-right: 8px; }

    .divider { display: flex; align-items: center; gap: 14px; margin: 24px 0 18px; }
    .divider hr { flex: 1; border: none; border-top: 1px solid rgba(255,255,255,0.07); }
    .divider span { font-size: 11.5px; color: var(--text3); font-weight: 600; white-space: nowrap; }

    .wa-link { display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 13px; border-radius: 12px; background: rgba(37,211,102,0.1); border: 1px solid rgba(37,211,102,0.25); color: #4ade80; font-size: 13.5px; font-weight: 700; text-decoration: none; transition: all 0.22s; }
    .wa-link:hover { background: rgba(37,211,102,0.2); transform: translateY(-1px); }
    .wa-link i { font-size: 18px; }

    .info-strip { display: flex; align-items: center; gap: 10px; padding: 13px 16px; border-radius: 12px; background: rgba(255,255,255,0.025); border: 1px solid rgba(255,255,255,0.06); color: var(--text3); font-size: 12.5px; margin-top: 14px; }
    .info-strip i { color: var(--pink); flex-shrink: 0; }

    @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    @media (max-width: 600px) {
      .site-header { padding: 14px 16px; }
      .form-card { padding: 26px 18px; }
      .form-row { grid-template-columns: 1fr; gap: 0; }
    }
  </style>
</head>
<body>

  <div class="bg">
    <div class="blob b1"></div><div class="blob b2"></div><div class="blob b3"></div>
    <div class="bg-grid"></div>
  </div>

  <header class="site-header">
    <a href="{{ url('/') }}" class="logo">
      <div class="logo-icon">+</div>
      <span class="logo-text">TaskMate</span>
    </a>
    <a href="javascript:history.length > 1 ? history.back() : window.location.href='/'" class="btn-back">
      <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
  </header>

  <div class="page-wrap">

    <div class="hero">
      <div class="hero-badge"><i class="fa-solid fa-headset"></i>&nbsp; Pusat Bantuan</div>
      <h1>Ada yang bisa<br>kami bantu?</h1>
      <p>Kirimkan pertanyaan atau kendala Anda. Admin akan merespons melalui email atau WhatsApp yang Anda cantumkan.</p>
    </div>

    <div class="form-card">

      @if(session('success'))
        <div class="alert-success">
          <i class="fa-solid fa-circle-check"></i>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      @if($errors->any())
        <div class="alert-error">
          <i class="fa-solid fa-circle-exclamation" style="flex-shrink:0;margin-top:1px;"></i>
          <ul style="list-style:none;padding:0;">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('bantuan.store') }}" id="supportForm">
        @csrf

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Nama Lengkap <span>*</span></label>
            <input type="text" name="nama" class="form-input"
              placeholder="Nama Anda..."
              value="{{ old('nama', $user?->name ?? '') }}" required>
          </div>
          <div class="form-group">
            <label class="form-label">Alamat Email <span>*</span></label>
            <input type="email" name="email" class="form-input"
              placeholder="email@gmail.com"
              value="{{ old('email', $user?->email ?? '') }}" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Nomor WhatsApp</label>
          <input type="text" name="whatsapp" class="form-input"
            placeholder="Contoh: 082216151741"
            value="{{ old('whatsapp', $user?->phone ?? '') }}">
          <p class="input-hint"><i class="fa-solid fa-circle-info" style="margin-right:4px;"></i>Opsional — memudahkan admin membalas via WhatsApp.</p>
        </div>

        <div class="form-group">
          <label class="form-label">Perihal / Topik <span>*</span></label>
          <select name="perihal" class="form-input form-select" required>
            <option value="" disabled {{ old('perihal') ? '' : 'selected' }}>-- Pilih topik bantuan --</option>
            <option value="Tidak bisa login" {{ old('perihal')=='Tidak bisa login'?'selected':'' }}>Tidak bisa login</option>
            <option value="Lupa password" {{ old('perihal')=='Lupa password'?'selected':'' }}>Lupa password</option>
            <option value="Bug / Error aplikasi" {{ old('perihal')=='Bug / Error aplikasi'?'selected':'' }}>Bug / Error aplikasi</option>
            <option value="Pertanyaan fitur" {{ old('perihal')=='Pertanyaan fitur'?'selected':'' }}>Pertanyaan fitur</option>
            <option value="Permintaan fitur baru" {{ old('perihal')=='Permintaan fitur baru'?'selected':'' }}>Permintaan fitur baru</option>
            <option value="Laporan data tidak sesuai" {{ old('perihal')=='Laporan data tidak sesuai'?'selected':'' }}>Laporan data tidak sesuai</option>
            <option value="Lainnya" {{ old('perihal')=='Lainnya'?'selected':'' }}>Lainnya</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Deskripsi Masalah <span>*</span></label>
          <textarea name="pesan" class="form-textarea"
            placeholder="Jelaskan kendala atau pertanyaan Anda secara detail..." required maxlength="3000">{{ old('pesan') }}</textarea>
          <p class="input-hint" id="charCount">Maks. 3000 karakter.</p>
        </div>

        <div class="form-group" style="margin-top:6px; margin-bottom:0;">
          <button type="submit" class="btn-submit" id="submitBtn">
            <i class="fa-solid fa-paper-plane"></i> Kirim Pesan ke Admin
          </button>
        </div>
      </form>

      <div class="divider"><hr><span>atau langsung hubungi</span><hr></div>

      @php $adminWa = preg_replace('/\D/', '', env('ADMIN_WHATSAPP', '6285191163819')); @endphp
      <a href="https://wa.me/{{ $adminWa }}?text=Halo+Admin+TaskMate%2C+saya+butuh+bantuan." target="_blank" class="wa-link">
        <i class="fa-brands fa-whatsapp"></i> Chat Langsung via WhatsApp
      </a>

      <div class="info-strip">
        <i class="fa-solid fa-clock"></i>
        <span>Respon admin biasanya dalam <strong style="color:var(--text2)">1 × 24 jam</strong> di hari kerja.</span>
      </div>
    </div>
  </div>

  <script>
    const textarea = document.querySelector('textarea[name="pesan"]');
    const counter  = document.getElementById('charCount');
    if (textarea && counter) {
      const upd = () => {
        const left = 3000 - textarea.value.length;
        counter.textContent = `Tersisa ${left} / 3000 karakter.`;
        counter.style.color = left < 100 ? '#f87171' : '';
      };
      textarea.addEventListener('input', upd); upd();
    }
    document.getElementById('supportForm')?.addEventListener('submit', function() {
      const btn = document.getElementById('submitBtn');
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim...';
    });
  </script>
</body>
</html>
