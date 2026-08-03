<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — TaskMate</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    @vite(['resources/css/app.css'])
    <style>
        * { font-family: 'DM Sans', sans-serif; }
        body { animation: pageEnter 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; background: #fbf7f4; }
        @keyframes pageEnter { from { opacity: 0; transform: translateY(30px); filter: blur(6px); } to { opacity: 1; transform: translateY(0); filter: blur(0); } }
        @keyframes float { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(20px, 20px); } }
        .form-group { animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; opacity: 0; }
        .form-group:nth-child(1) { animation-delay: 0.2s; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes gradientShift { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
        .form-input { transition: all 0.3s ease; }
        .form-input:focus { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(233, 30, 99, 0.15) !important; border-color: rgba(233, 30, 99, 0.4) !important; }
        .submit-btn { position: relative; overflow: hidden; transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.35s both; }
        .submit-btn:hover { transform: translateY(-3px); box-shadow: 0 12px 40px rgba(233, 30, 99, 0.4) !important; }
        .logo-badge { animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0s both; }
        .card-container { animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.1s both; }
        .status-msg { animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.15s both; }
        .login-link { animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.45s both; }
        .brand-name { font-family: 'Syne', sans-serif; }
    </style>
</head>
<body class="overflow-x-hidden">
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute w-[500px] h-[500px] rounded-full -top-32 -left-24" style="background: radial-gradient(circle, rgba(233,30,99,0.06) 0%, transparent 70%); animation: float 20s ease-in-out infinite;"></div>
        <div class="absolute w-[400px] h-[400px] rounded-full top-10 -right-20" style="background: radial-gradient(circle, rgba(240,98,146,0.05) 0%, transparent 70%); animation: float 25s ease-in-out infinite reverse;"></div>
        <div class="absolute w-[350px] h-[350px] rounded-full -bottom-16 left-1/3" style="background: radial-gradient(circle, rgba(233,30,99,0.04) 0%, transparent 70%); animation: float 30s ease-in-out infinite;"></div>
    </div>
    <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3 mb-4 logo-badge">
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center" style="background: linear-gradient(135deg, #e91e63, #f06292); box-shadow: 0 4px 20px rgba(233, 30, 99, 0.35);"><i class="ti ti-layout-kanban text-white text-xl"></i></div>
                    <span class="brand-name text-2xl font-black tracking-tight" style="color: #3d2b27;">TaskMate</span>
                </a>
                <h2 class="brand-name text-2xl font-bold mt-2" style="color: #3d2b27;">Lupa Password?</h2>
                <p class="text-sm mt-2 leading-relaxed" style="color: #705953;">Jangan khawatir! Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password.</p>
            </div>
            
            @if (session('status'))
                <div class="mb-5 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2 status-msg" style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); color: #059669;"><i class="ti ti-circle-check text-base"></i> {{ session('status') }}</div>
            @endif

            <div class="rounded-3xl p-8 card-container" style="background: #ffffff; border: 1px solid rgba(61,43,39,0.08); box-shadow: 0 16px 48px rgba(61,43,39,0.06);">
                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="block text-xs font-bold uppercase tracking-widest mb-2" style="color: #705953;">Email</label>
                        <div class="relative">
                            <i class="ti ti-mail absolute left-3.5 top-1/2 -translate-y-1/2 text-lg" style="color: #a8938e;"></i>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="email@kamu.com" class="form-input w-full pl-10 pr-4 py-3 rounded-xl text-sm font-medium outline-none" style="background: #fffdfa; border: 1px solid rgba(61,43,39,0.15); color: #3d2b27;">
                        </div>
                        @error('email') <p class="text-xs mt-1.5 flex items-center gap-1" style="color: #ef4444;"><i class="ti ti-alert-circle text-sm"></i> {{ $message }}</p> @enderror
                    </div>
                    
                    <button type="submit" class="submit-btn w-full py-3.5 rounded-xl font-bold text-base text-white flex items-center justify-center gap-2" style="background: linear-gradient(135deg, #e91e63 0%, #f06292 50%, #ec407a 100%); background-size: 200% 200%; box-shadow: 0 4px 24px rgba(233, 30, 99, 0.35); animation: gradientShift 3s ease infinite;">
                        <i class="ti ti-send text-lg"></i><span>Kirim Tautan Reset</span>
                    </button>
                </form>
            </div>
            
            <p class="text-center text-sm mt-6 login-link" style="color: #705953;">Ingat password kamu? <a href="{{ route('login') }}" class="font-bold" style="color: #e91e63;">Kembali ke login</a></p>
        </div>
    </div>
</body>
</html>
