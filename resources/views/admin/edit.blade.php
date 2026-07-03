<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Edit Mahasiswa - TaskMate Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,400&display=swap" rel="stylesheet">
  @vite(['resources/css/admin.css', 'resources/js/admin.js'])
  <style>
    .edit-container { max-width: 600px; margin: 24px auto; padding: 0 16px; }
    .edit-breadcrumb { display: flex; gap: 8px; align-items: center; margin-bottom: 24px; font-size: 14px; }
    .edit-breadcrumb a { color: #e91e63; text-decoration: none; }
    .edit-breadcrumb a:hover { text-decoration: underline; }
    .edit-header { display: flex; gap: 16px; align-items: center; margin-bottom: 32px; }
    .edit-header h1 { margin: 0; font-size: 24px; font-weight: 700; flex: 1; }
    .edit-card { background: var(--surface2, #faf4f0); padding: 24px; border-radius: 12px; border: 1px solid var(--border); }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px; color: var(--text); }
    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid var(--border);
      border-radius: 6px;
      font-family: inherit;
      font-size: 14px;
      background: white;
      color: var(--text);
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: #e91e63;
      box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.1);
    }
    .form-hint { font-size: 12px; color: var(--text3); margin-top: 4px; }
    .form-error { border-color: #ef4444 !important; box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important; }
    .form-error-msg { color: #ef4444; font-size: 12px; margin-top: 4px; }
    .form-divider { border: none; border-top: 1px solid var(--border); margin: 24px 0; }
    .form-actions { display: flex; gap: 12px; margin-top: 24px; }
    .btn-cancel { flex: 1; padding: 12px; background: var(--surface2); color: var(--text2); border: 1px solid var(--border); border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; display: flex; align-items: center; justify-content: center; }
    .btn-cancel:hover { background: var(--border); }
    .btn-submit { flex: 1; padding: 12px; background: #e91e63; color: white; border: 1px solid #e91e63; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; }
    .btn-submit:hover { background: #c2185b; }
    .btn-submit:disabled { background: var(--border2); border-color: var(--border2); cursor: not-allowed; }
    .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; }
    .alert-success { background: #dcfce7; color: #166534; border-left: 3px solid #22c55e; }
    .alert-error { background: #fee2e2; color: #991b1b; border-left: 3px solid #ef4444; }
    .loader { display: none; width: 16px; height: 16px; border: 2px solid #e5e7eb; border-top-color: white; border-radius: 50%; animation: spin 0.6s linear infinite; margin-left: 8px; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .btn-submit.loading { opacity: 0.8; pointer-events: none; }
    .btn-submit.loading .loader { display: inline-block; }
  </style>
</head>
<body style="background: var(--bg, #fbf7f4);">
  <nav class="admin-nav">
    <div class="admin-nav-left">
      <div class="admin-logo">
        <span class="admin-logo-icon">🛡️</span>
        <div>
          <div class="admin-logo-title">TaskMate Admin</div>
          <div class="admin-logo-sub">Panel Kontrol Mahasiswa</div>
        </div>
      </div>
    </div>
    <div class="admin-nav-right">
      <span class="admin-badge">{{ auth('admin')->user()->nama }}</span>
      <form method="POST" action="{{ route('admin.logout') }}" style="margin:0">
        @csrf
        <button type="submit" class="btn-admin-logout">Logout</button>
      </form>
    </div>
  </nav>

  <div class="edit-container">
    <!-- Breadcrumb -->
    <div class="edit-breadcrumb">
      <a href="{{ route('admin.dashboard') }}">Dashboard</a>
      <span>/</span>
      <span>Daftar Mahasiswa</span>
      <span>/</span>
      <span>{{ $mahasiswa->nama }}</span>
      <span>/</span>
      <span>Edit</span>
    </div>

    <!-- Header -->
    <div class="edit-header">
      <h1>✏️ Edit Data Mahasiswa</h1>
    </div>

    <!-- Card -->
    <div class="edit-card">
      <form id="editForm" method="POST" action="{{ route('admin.mahasiswas.update', $mahasiswa->id) }}">
        @csrf
        @method('PUT')

        <!-- Alert Messages -->
        <div id="successAlert" class="alert alert-success" style="display: none;">
          ✅ Data mahasiswa berhasil diperbarui!
        </div>
        <div id="errorAlert" class="alert alert-error" style="display: none;"></div>

        <!-- NIM -->
        <div class="form-group">
          <label for="nim">NIM</label>
          <input type="text" id="nim" name="nim" value="{{ $mahasiswa->nim }}" required>
          <div class="form-hint">Nomor Induk Mahasiswa (tidak boleh diubah jika sudah terdaftar di sistem)</div>
        </div>

        <!-- Nama -->
        <div class="form-group">
          <label for="nama">Nama Lengkap</label>
          <input type="text" id="nama" name="nama" value="{{ $mahasiswa->nama }}" required>
        </div>

        <!-- Email -->
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="{{ $mahasiswa->email }}" required>
        </div>

        <!-- WhatsApp -->
        <div class="form-group">
          <label for="phone">Nomor WhatsApp</label>
          <input type="tel" id="phone" name="phone" value="{{ $mahasiswa->phone ?? '' }}" placeholder="08xxxxxxxxxx">
          <div class="form-hint">Format: 08xxxxxxxxxx atau +62xxxxxxxxxx</div>
        </div>

        <!-- Universitas -->
        <div class="form-group">
          <label for="universitas">Universitas</label>
          <input type="text" id="universitas" name="universitas" value="{{ $mahasiswa->universitas ?? '' }}" required>
        </div>

        <hr class="form-divider">

        <!-- Password Section -->
        <h3 style="font-size: 16px; margin-bottom: 16px; margin-top: 0;">🔐 Reset Password (Opsional)</h3>
        <p style="font-size: 13px; color: #666; margin-bottom: 16px;">Kosongkan kedua field password jika tidak ingin mengubah password mahasiswa.</p>

        <!-- Password Baru -->
        <div class="form-group">
          <label for="password">Password Baru</label>
          <input type="password" id="password" name="password" placeholder="Minimal 8 karakter">
          <div class="form-hint">Harus minimal 8 karakter dengan kombinasi huruf, angka, dan simbol</div>
        </div>

        <!-- Konfirmasi Password -->
        <div class="form-group">
          <label for="password_confirmation">Konfirmasi Password</label>
          <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
          <a href="{{ route('admin.dashboard') }}" class="btn-cancel">← Batal</a>
          <button type="submit" class="btn-submit" id="submitBtn">
            Simpan Perubahan
            <div class="loader"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const editForm = document.getElementById('editForm');
    const submitBtn = document.getElementById('submitBtn');
    const successAlert = document.getElementById('successAlert');
    const errorAlert = document.getElementById('errorAlert');

    editForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      // Hide alerts
      successAlert.style.display = 'none';
      errorAlert.style.display = 'none';

      // Show loading state
      submitBtn.classList.add('loading');
      submitBtn.disabled = true;

      try {
        const formData = new FormData(editForm);
        const data = Object.fromEntries(formData);

        // Validate password confirmation if password is provided
        if (data.password && !data.password_confirmation) {
          throw new Error('Konfirmasi password harus diisi jika mengubah password');
        }

        if (data.password && data.password !== data.password_confirmation) {
          throw new Error('Password dan konfirmasi password tidak cocok');
        }

        // Remove confirmation from payload (sent via name attribute)
        const payload = {
          nim: data.nim,
          nama: data.nama,
          email: data.email,
          phone: data.phone || null,
          universitas: data.universitas || null,
        };

        if (data.password) {
          payload.password = data.password;
          payload.password_confirmation = data.password_confirmation;
        }

        const response = await fetch(editForm.action, {
          method: 'PUT',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
          },
          body: JSON.stringify(payload),
        });

        const result = await response.json();

        if (!response.ok) {
          throw new Error(result.message || 'Gagal menyimpan data');
        }

        // Show success message
        successAlert.style.display = 'block';
        successAlert.scrollIntoView({ behavior: 'smooth', block: 'start' });

        // Redirect after 2 seconds
        setTimeout(() => {
          window.location.href = '{{ route('admin.dashboard') }}';
        }, 2000);

      } catch (error) {
        errorAlert.textContent = '❌ ' + (error.message || 'Terjadi kesalahan saat menyimpan data');
        errorAlert.style.display = 'block';
        errorAlert.scrollIntoView({ behavior: 'smooth', block: 'start' });
      } finally {
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;
      }
    });
  </script>
</body>
</html>
