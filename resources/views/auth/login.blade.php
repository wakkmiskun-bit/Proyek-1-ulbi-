<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login � TaskMate</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    @vite(['resources/css/app.css'])
    <style>
        body { animation: pageEnter 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
        @keyframes pageEnter { from { opacity: 0; transform: rotateX(15deg) rotateZ(5deg) translateY(40px); filter: blur(8px); } to { opacity: 1; transform: rotateX(0deg) rotateZ(0deg) translateY(0); filter: blur(0); } }
        @keyframes float { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(20px, 20px); } }
        .form-group { animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; opacity: 0; }
        .form-group:nth-child(1) { animation-delay: 0.2s; }
        .form-group:nth-child(2) { animation-delay: 0.35s; }
        .form-group:nth-child(3) { animation-delay: 0.5s; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes gradientShift { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
        .form-input { transition: all 0.3s ease; }
        .form-input:focus { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(99, 102, 241, 0.25) !important; }
        .submit-btn { position: relative; overflow: hidden; transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.65s both; }
        .submit-btn::before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.2); transition: left 0.5s ease; z-index: -1; }
        .submit-btn:hover::before { left: 100%; }
        .submit-btn:hover { transform: translateY(-4px) scale(1.02); box-shadow: 0 12px 40px rgba(99, 102, 241, 0.5) !important; }
        .submit-btn:active { transform: scale(0.98); }
        .logo-badge { animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0s both; }
        .card-container { animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.1s both; }
        .status-msg { animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.15s both; }
        .login-link { animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.75s both; }
        @media (max-width: 768px) { .card-container { padding: 1.5rem; } .form-input { font-size: 14px; padding: 0.6rem 0.8rem 0.6rem 2rem; } .submit-btn { padding: 0.75rem !important; font-size: 14px; } }
        @media (max-width: 480px) { .card-container { padding: 1.25rem; border-radius: 1.5rem; } .form-input { font-size: 13px; padding: 0.5rem 0.6rem 0.5rem 1.75rem; } .submit-btn { padding: 0.6rem !important; font-size: 13px; } }
    </style>
</head>
<body class="font-['Outfit'] overflow-hidden" style="background: #0a0e1a;">
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute w-[500px] h-[500px] rounded-full -top-32 -left-24" style="background: radial-gradient(circle, rgba(99,102,241,0.2) 0%, transparent 70%); animation: float 20s ease-in-out infinite;"></div>
        <div class="absolute w-[400px] h-[400px] rounded-full top-10 -right-20" style="background: radial-gradient(circle, rgba(236,72,153,0.12) 0%, transparent 70%); animation: float 25s ease-in-out infinite reverse;"></div>
        <div class="absolute w-[350px] h-[350px] rounded-full -bottom-16 left-1/3" style="background: radial-gradient(circle, rgba(139,92,246,0.12) 0%, transparent 70%); animation: float 30s ease-in-out infinite;"></div>
        <div class="absolute inset-0" style="background-image: linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px); background-size: 48px 48px;"></div>
    </div>
    <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3 mb-4 logo-badge">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all hover:scale-110" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); box-shadow: 0 0 20px rgba(99,102,241,0.4);"><i class="ti ti-layout-kanban text-white text-xl"></i></div>
                    <span class="text-2xl font-black tracking-tight md:text-xl" style="background: linear-gradient(135deg, #a5b4fc, #e0e7ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">TaskMate</span>
                </a>
                <h2 class="text-2xl font-bold text-white mt-2 md:text-xl">Selamat datang kembali</h2>
                <p class="text-sm mt-1 md:text-xs" style="color: #8b97b0;">Masuk ke akun TaskMate kamu</p>
            </div>
            @if (session('status')) <div class="mb-5 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2 status-msg" style="background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.25); color: #6ee7b7;"><i class="ti ti-circle-check text-base"></i> {{ session('status') }}</div> @endif
            <div class="rounded-3xl p-8 card-container md:p-6" style="background: rgba(17,24,39,0.85); border: 1px solid rgba(255,255,255,0.09); backdrop-filter: blur(24px);">
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div class="form-group">
                        <label for="login" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color: #8b97b0;">NIM atau Email</label>
                        <div class="relative">
                            <i class="ti ti-id absolute left-3.5 top-1/2 -translate-y-1/2 text-lg" style="color: #5a6880;"></i>
                            <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username" placeholder="NIM atau email@kamu.com" class="form-input w-full pl-10 pr-4 py-3 rounded-xl text-sm font-medium outline-none md:py-2.5 md:text-xs" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #e8edf5;" onfocus="this.style.borderColor='rgba(99,102,241,0.6)'; this.style.background='rgba(99,102,241,0.08)';" onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.background='rgba(255,255,255,0.05)';">
                        </div>
                        @error('login') <p class="text-xs mt-1.5 flex items-center gap-1" style="color: #f87171;"><i class="ti ti-alert-circle text-sm"></i> {{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="text-xs font-bold uppercase tracking-widest" style="color: #8b97b0;">Password</label>
                            @if (Route::has('password.request')) <a href="{{ route('password.request') }}" class="text-xs font-semibold transition-colors hover:text-indigo-300" style="color: #a5b4fc;">Lupa password?</a> @endif
                        </div>
                        <div class="relative">
                            <i class="ti ti-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-lg" style="color: #5a6880;"></i>
                            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Password kamu" class="form-input w-full pl-10 pr-10 py-3 rounded-xl text-sm font-medium outline-none md:py-2.5 md:text-xs" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #e8edf5;" onfocus="this.style.borderColor='rgba(99,102,241,0.6)'; this.style.background='rgba(99,102,241,0.08)';" onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.background='rgba(255,255,255,0.05)';">
                            <button type="button" onclick="togglePwd('password', 'eye-icon')" class="absolute right-3.5 top-1/2 -translate-y-1/2 transition-colors hover:text-white" style="color: #5a6880; background: none; border: none; cursor: pointer;"><i id="eye-icon" class="ti ti-eye text-lg"></i></button>
                        </div>
                        @error('password') <p class="text-xs mt-1.5 flex items-center gap-1" style="color: #f87171;"><i class="ti ti-alert-circle text-sm"></i> {{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-600 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-0 transition-all" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.2); width:16px; height:16px;">
                            <span class="text-sm" style="color: #8b97b0;">Ingat saya</span>
                        </label>
                    </div>
                    <button type="submit" class="submit-btn w-full py-3.5 rounded-xl font-bold text-base text-white flex items-center justify-center gap-2 md:py-3 md:text-sm" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%); background-size: 200% 200%; box-shadow: 0 4px 24px rgba(99,102,241,0.4); animation: gradientShift 3s ease infinite;">
                        <i class="ti ti-arrow-right text-lg md:text-base"></i><span>Masuk Sekarang</span>
                    </button>
                </form>
            </div>
            <p class="text-center text-sm mt-6 login-link md:text-xs" style="color: #5a6880;">Belum punya akun? <a href="{{ route('register') }}" class="font-bold transition-colors hover:text-indigo-300" style="color: #a5b4fc;">Daftar sekarang</a></p>
        </div>
    </div>
    <script>
        function togglePwd(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'ti ti-eye-off text-lg';
            } else {
                input.type = 'password';
                icon.className = 'ti ti-eye text-lg';
            }
        }
    </script>
</body>
</html>


