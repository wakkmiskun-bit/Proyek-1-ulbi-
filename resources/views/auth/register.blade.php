<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — TaskMate</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    @vite(['resources/css/app.css'])
    <style>
        * { font-family: 'DM Sans', sans-serif; }
        body { animation: pageEnter 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; background: #fbf7f4; }
        @keyframes pageEnter { from { opacity: 0; transform: translateY(30px); filter: blur(6px); } to { opacity: 1; transform: translateY(0); filter: blur(0); } }
        @keyframes float { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(20px, 20px); } }
        .form-group { animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; opacity: 0; }
        .form-group:nth-child(1) { animation-delay: 0.15s; }
        .form-group:nth-child(2) { animation-delay: 0.25s; }
        .form-group:nth-child(3) { animation-delay: 0.35s; }
        .form-group:nth-child(4) { animation-delay: 0.45s; }
        .form-group:nth-child(5) { animation-delay: 0.55s; }
        .form-group:nth-child(6) { animation-delay: 0.65s; }
        .form-group:nth-child(7) { animation-delay: 0.75s; }
        .form-group:nth-child(8) { animation-delay: 0.85s; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes gradientShift { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
        .form-input { transition: all 0.3s ease; }
        .form-input:focus { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(233, 30, 99, 0.15) !important; border-color: rgba(233, 30, 99, 0.4) !important; }
        .submit-btn { transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.95s both; }
        .submit-btn:hover { transform: translateY(-3px); box-shadow: 0 12px 40px rgba(233, 30, 99, 0.4) !important; }
        .logo-badge, .card-container, .login-link { animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) both; }
        .card-container { animation-delay: 0.1s; }
        .login-link { animation-delay: 1.0s; }
        .brand-name { font-family: 'Syne', sans-serif; }
    </style>
</head>
<body class="overflow-x-hidden">
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute w-[500px] h-[500px] rounded-full -top-32 -left-24" style="background: radial-gradient(circle, rgba(233,30,99,0.06) 0%, transparent 70%); animation: float 20s ease-in-out infinite;"></div>
        <div class="absolute w-[400px] h-[400px] rounded-full top-10 -right-20" style="background: radial-gradient(circle, rgba(240,98,146,0.05) 0%, transparent 70%); animation: float 25s ease-in-out infinite reverse;"></div>
    </div>
    <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3 mb-4 logo-badge">
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center" style="background: linear-gradient(135deg, #e91e63, #f06292); box-shadow: 0 4px 20px rgba(233, 30, 99, 0.35);"><i class="ti ti-layout-kanban text-white text-xl"></i></div>
                    <span class="brand-name text-2xl font-black tracking-tight" style="color: #3d2b27;">TaskMate</span>
                </a>
                <h2 class="brand-name text-2xl font-bold mt-2" style="color: #3d2b27;">Registrasi Mahasiswa</h2>
                <p class="text-sm mt-1" style="color: #705953;">Isi data lengkap — nomor WhatsApp untuk pengingat deadline</p>
            </div>
            <div class="rounded-3xl p-8 card-container" style="background: #ffffff; border: 1px solid rgba(61,43,39,0.08); box-shadow: 0 16px 48px rgba(61,43,39,0.06);">
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="form-group">
                        <label for="nim" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color: #705953;">NIM</label>
                        <div class="relative">
                            <i class="ti ti-id absolute left-3.5 top-1/2 -translate-y-1/2 text-lg" style="color: #a8938e;"></i>
                            <input id="nim" type="text" name="nim" value="{{ old('nim') }}" required autofocus placeholder="Contoh: 1234567890" class="form-input w-full pl-10 pr-4 py-2.5 rounded-xl text-sm font-medium outline-none" style="background: #fffdfa; border: 1px solid rgba(61,43,39,0.15); color: #3d2b27;">
                        </div>
                        @error('nim') <p class="text-xs mt-1 flex items-center gap-1" style="color: #ef4444;"><i class="ti ti-alert-circle text-sm"></i> {{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label for="name" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color: #705953;">Nama Lengkap</label>
                        <div class="relative">
                            <i class="ti ti-user absolute left-3.5 top-1/2 -translate-y-1/2 text-lg" style="color: #a8938e;"></i>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required placeholder="Nama kamu" class="form-input w-full pl-10 pr-4 py-2.5 rounded-xl text-sm font-medium outline-none" style="background: #fffdfa; border: 1px solid rgba(61,43,39,0.15); color: #3d2b27;">
                        </div>
                        @error('name') <p class="text-xs mt-1 flex items-center gap-1" style="color: #ef4444;"><i class="ti ti-alert-circle text-sm"></i> {{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label for="email" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color: #705953;">Email</label>
                        <div class="relative">
                            <i class="ti ti-mail absolute left-3.5 top-1/2 -translate-y-1/2 text-lg" style="color: #a8938e;"></i>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="email@kamu.com" class="form-input w-full pl-10 pr-4 py-2.5 rounded-xl text-sm font-medium outline-none" style="background: #fffdfa; border: 1px solid rgba(61,43,39,0.15); color: #3d2b27;">
                        </div>
                        @error('email') <p class="text-xs mt-1 flex items-center gap-1" style="color: #ef4444;"><i class="ti ti-alert-circle text-sm"></i> {{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label for="phone" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color: #705953;">Nomor WhatsApp</label>
                        <div class="relative">
                            <i class="ti ti-brand-whatsapp absolute left-3.5 top-1/2 -translate-y-1/2 text-lg" style="color: #a8938e;"></i>
                            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required placeholder="08xxxxxxxxxx" class="form-input w-full pl-10 pr-4 py-2.5 rounded-xl text-sm font-medium outline-none" style="background: #fffdfa; border: 1px solid rgba(61,43,39,0.15); color: #3d2b27;">
                        </div>
                        <p class="text-xs mt-1" style="color: #a8938e;">Pengingat otomatis H-5 & H-2 sebelum deadline via WhatsApp</p>
                        @error('phone') <p class="text-xs mt-1 flex items-center gap-1" style="color: #ef4444;"><i class="ti ti-alert-circle text-sm"></i> {{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label for="universitas" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color: #705953;">Universitas</label>
                        <div class="relative">
                            <i class="ti ti-school absolute left-3.5 top-1/2 -translate-y-1/2 text-lg" style="color: #a8938e;"></i>
                            <input id="universitas" type="text" name="universitas" value="{{ old('universitas') }}" required placeholder="Nama Universitas kamu" class="form-input w-full pl-10 pr-4 py-2.5 rounded-xl text-sm font-medium outline-none" style="background: #fffdfa; border: 1px solid rgba(61,43,39,0.15); color: #3d2b27;">
                        </div>
                        @error('universitas') <p class="text-xs mt-1 flex items-center gap-1" style="color: #ef4444;"><i class="ti ti-alert-circle text-sm"></i> {{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label for="photo" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color: #705953;">Foto Mahasiswa</label>
                        <input id="photo" type="file" name="photo" accept="image/*" class="form-input w-full py-2 rounded-xl text-sm" style="background: #fffdfa; border: 1px solid rgba(61,43,39,0.15); color: #3d2b27;">
                        @error('photo') <p class="text-xs mt-1 flex items-center gap-1" style="color: #ef4444;"><i class="ti ti-alert-circle text-sm"></i> {{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label for="password" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color: #705953;">Password</label>
                        <div class="relative">
                            <i class="ti ti-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-lg" style="color: #a8938e;"></i>
                            <input id="password" type="password" name="password" required placeholder="Min. 8 karakter" class="form-input w-full pl-10 pr-10 py-2.5 rounded-xl text-sm font-medium outline-none" style="background: #fffdfa; border: 1px solid rgba(61,43,39,0.15); color: #3d2b27;">
                            <button type="button" onclick="togglePwd('password', 'eye-icon')" class="absolute right-3.5 top-1/2 -translate-y-1/2" style="color: #a8938e; background: none; border: none; cursor: pointer;"><i id="eye-icon" class="ti ti-eye text-lg"></i></button>
                        </div>
                        @error('password') <p class="text-xs mt-1 flex items-center gap-1" style="color: #ef4444;"><i class="ti ti-alert-circle text-sm"></i> {{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color: #705953;">Konfirmasi Password</label>
                        <div class="relative">
                            <i class="ti ti-lock-check absolute left-3.5 top-1/2 -translate-y-1/2 text-lg" style="color: #a8938e;"></i>
                            <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Ulangi password" class="form-input w-full pl-10 pr-10 py-2.5 rounded-xl text-sm font-medium outline-none" style="background: #fffdfa; border: 1px solid rgba(61,43,39,0.15); color: #3d2b27;">
                            <button type="button" onclick="togglePwd('password_confirmation', 'eye-icon-2')" class="absolute right-3.5 top-1/2 -translate-y-1/2" style="color: #a8938e; background: none; border: none; cursor: pointer;"><i id="eye-icon-2" class="ti ti-eye text-lg"></i></button>
                        </div>
                    </div>
                    <button type="submit" class="submit-btn w-full py-3.5 rounded-xl font-bold text-base text-white flex items-center justify-center gap-2" style="background: linear-gradient(135deg, #e91e63 0%, #f06292 50%, #ec407a 100%); background-size: 200% 200%; box-shadow: 0 4px 24px rgba(233, 30, 99, 0.35); animation: gradientShift 3s ease infinite;">
                        <i class="ti ti-arrow-right text-lg"></i><span>Daftar Sekarang</span>
                    </button>
                </form>
            </div>
            <p class="text-center text-sm mt-6 login-link" style="color: #705953;">Sudah punya akun? <a href="{{ route('login') }}" class="font-bold" style="color: #e91e63;">Login di sini</a></p>
        </div>
    </div>
    <script>
        function togglePwd(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.className = input.type === 'password' ? 'ti ti-eye text-lg' : 'ti ti-eye-off text-lg';
        }
    </script>
</body>
</html>
