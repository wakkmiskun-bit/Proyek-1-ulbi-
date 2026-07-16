<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="base-url" content="{{ url('/') }}">
  <title>Tambah Akun Mahasiswa - TaskMate Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --bg-main: #f8fafc;
      --bg-sidebar: #ffffff;
      --border-color: #e2e8f0;
      --text-main: #1e293b;
      --text-muted: #64748b;
      --pink-primary: #e91e63;
      --pink-hover: #d81b60;
      --pink-light: rgba(233, 30, 99, 0.08);
      --pink-border: rgba(233, 30, 99, 0.2);
      --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
      --shadow-md: 0 4px 20px rgba(0,0,0,0.03);
      --radius-lg: 16px;
      --radius-md: 10px;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
      background-color: var(--bg-main);
      color: var(--text-main);
      display: flex;
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* Sidebar Styling */
    .sidebar {
      width: 280px;
      background-color: var(--bg-sidebar);
      border-right: 1px solid var(--border-color);
      padding: 32px 24px;
      display: flex;
      flex-direction: column;
      position: fixed;
      height: 100vh;
      left: 0;
      top: 0;
      z-index: 100;
    }

    .sidebar-brand {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 40px;
    }

    .sidebar-brand-icon {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--pink-primary), #f48fb1);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 10px rgba(233, 30, 99, 0.2);
    }

    .sidebar-brand-icon svg {
      width: 22px;
      height: 22px;
      fill: white;
    }

    .sidebar-brand-name {
      font-size: 18px;
      font-weight: 800;
      color: var(--text-main);
      letter-spacing: -0.5px;
    }

    .sidebar-menu {
      display: flex;
      flex-direction: column;
      gap: 8px;
      list-style: none;
      flex: 1;
    }

    .sidebar-menu-item a {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 14px 20px;
      border-radius: var(--radius-md);
      color: var(--text-muted);
      text-decoration: none;
      font-size: 14px;
      font-weight: 600;
      transition: all 0.2s ease;
    }

    .sidebar-menu-item a:hover {
      background-color: #f1f5f9;
      color: var(--text-main);
    }

    .sidebar-menu-item.active a {
      background-color: var(--pink-light);
      color: var(--pink-primary);
    }

    .sidebar-menu-item i {
      font-size: 18px;
      width: 24px;
      text-align: center;
    }

    .sidebar-footer {
      border-top: 1px solid var(--border-color);
      padding-top: 24px;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .user-profile {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: var(--pink-light);
      color: var(--pink-primary);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 16px;
    }

    .user-info {
      display: flex;
      flex-direction: column;
    }

    .user-name {
      font-size: 14px;
      font-weight: 700;
      color: var(--text-main);
    }

    .user-role {
      font-size: 11px;
      color: var(--text-muted);
    }

    .btn-logout {
      width: 100%;
      padding: 12px;
      border-radius: var(--radius-md);
      background: transparent;
      border: 1px solid var(--border-color);
      color: var(--text-muted);
      font-weight: 600;
      font-size: 13px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      transition: all 0.2s;
    }

    .btn-logout:hover {
      background-color: #fee2e2;
      color: #ef4444;
      border-color: #fecaca;
    }

    /* Main Content Area */
    .main-content {
      margin-left: 280px;
      flex: 1;
      padding: 40px 48px;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .breadcrumb {
      font-size: 13px;
      color: var(--text-muted);
      margin-bottom: 12px;
      font-weight: 500;
    }

    .breadcrumb span {
      margin: 0 4px;
    }

    .breadcrumb a {
      color: var(--text-muted);
      text-decoration: none;
      transition: color 0.2s;
    }

    .breadcrumb a:hover {
      color: var(--pink-primary);
    }

    .header-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 32px;
    }

    .page-title {
      font-size: 26px;
      font-weight: 800;
      color: var(--text-main);
      letter-spacing: -0.5px;
    }

    .header-actions {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .search-wrapper {
      position: relative;
    }

    .search-wrapper i {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      font-size: 14px;
    }

    .search-input {
      padding: 12px 16px 12px 42px;
      border-radius: 50px;
      border: 1px solid var(--border-color);
      font-family: inherit;
      font-size: 14px;
      width: 220px;
      outline: none;
      transition: all 0.2s;
    }

    .search-input:focus {
      border-color: var(--pink-primary);
      box-shadow: 0 0 0 3px var(--pink-light);
      width: 260px;
    }

    .btn-action-primary {
      padding: 12px 24px;
      border-radius: 50px;
      background-color: var(--pink-primary);
      color: white;
      border: none;
      font-weight: 700;
      font-size: 14px;
      cursor: pointer;
      box-shadow: 0 4px 14px rgba(233, 30, 99, 0.2);
      transition: all 0.2s;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .btn-action-primary:hover {
      background-color: var(--pink-hover);
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(233, 30, 99, 0.3);
    }

    /* Form Card Container */
    .card-container {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      flex: 1;
      padding-top: 10px;
    }

    .form-card {
      width: 100%;
      max-width: 580px;
      background-color: white;
      border-radius: var(--radius-lg);
      border: 1px solid var(--border-color);
      box-shadow: var(--shadow-md);
      padding: 40px;
    }

    .form-card-header {
      margin-bottom: 32px;
    }

    .form-card-title {
      font-size: 20px;
      font-weight: 800;
      color: var(--text-main);
      letter-spacing: -0.3px;
      margin-bottom: 6px;
    }

    .form-card-subtitle {
      font-size: 13px;
      color: var(--text-muted);
      font-weight: 500;
    }

    .form-group {
      margin-bottom: 22px;
    }

    .form-label {
      display: block;
      font-size: 13px;
      font-weight: 700;
      color: var(--text-main);
      margin-bottom: 8px;
    }

    .form-input-wrapper {
      position: relative;
    }

    .form-input {
      width: 100%;
      padding: 12px 16px;
      border-radius: 8px;
      border: 1px solid var(--border-color);
      font-family: inherit;
      font-size: 14px;
      color: var(--text-main);
      outline: none;
      transition: all 0.2s;
      background-color: #ffffff;
    }

    .form-input:focus {
      border-color: var(--pink-primary);
      box-shadow: 0 0 0 3px var(--pink-light);
    }

    .password-toggle {
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      cursor: pointer;
      font-size: 16px;
      transition: color 0.2s;
    }

    .password-toggle:hover {
      color: var(--pink-primary);
    }

    .form-actions {
      display: flex;
      gap: 16px;
      margin-top: 36px;
    }

    .btn-submit {
      flex: 1;
      padding: 14px;
      border-radius: 8px;
      background-color: var(--pink-primary);
      color: white;
      border: none;
      font-weight: 700;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.2s;
      box-shadow: 0 4px 12px rgba(233, 30, 99, 0.15);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .btn-submit:hover {
      background-color: var(--pink-hover);
      box-shadow: 0 6px 16px rgba(233, 30, 99, 0.25);
    }

    .btn-cancel {
      flex: 1;
      padding: 14px;
      border-radius: 8px;
      background-color: #f1f5f9;
      color: var(--text-muted);
      border: 1px solid var(--border-color);
      font-weight: 700;
      font-size: 14px;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
    }

    .btn-cancel:hover {
      background-color: #e2e8f0;
      color: var(--text-main);
    }

    /* Alerts */
    .alert {
      padding: 14px 16px;
      border-radius: 8px;
      font-size: 13px;
      margin-bottom: 24px;
      display: none;
      align-items: center;
      gap: 12px;
      font-weight: 600;
    }

    .alert-success {
      background-color: #dcfce7;
      color: #15803d;
      border: 1px solid #bbf7d0;
    }

    .alert-error {
      background-color: #fee2e2;
      color: #b91c1c;
      border: 1px solid #fecaca;
    }

    .loader {
      display: none;
      width: 16px;
      height: 16px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-top-color: white;
      border-radius: 50%;
      animation: spin 0.6s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Responsive */
    @media (max-width: 1024px) {
      .sidebar {
        width: 80px;
        padding: 32px 12px;
      }
      .sidebar-brand-name, .user-info, .btn-logout span {
        display: none;
      }
      .sidebar-menu-item a {
        justify-content: center;
        padding: 14px;
      }
      .sidebar-menu-item span {
        display: none;
      }
      .main-content {
        margin-left: 80px;
        padding: 32px 24px;
      }
    }

    @media (max-width: 768px) {
      body {
        flex-direction: column;
      }
      .sidebar {
        position: static;
        width: 100%;
        height: auto;
        padding: 20px;
        border-right: none;
        border-bottom: 1px solid var(--border-color);
      }
      .sidebar-menu {
        flex-direction: row;
        flex-wrap: wrap;
      }
      .main-content {
        margin-left: 0;
        padding: 24px 16px;
      }
      .header-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
      }
      .header-actions {
        width: 100%;
        justify-content: space-between;
      }
      .form-actions {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-brand-icon">
        <svg viewBox="0 0 24 24">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14h-2v-6H8V8h8v2h-3v6z" />
        </svg>
      </div>
      <span class="sidebar-brand-name">TaskMate Admin</span>
    </div>

    <ul class="sidebar-menu">
      <li class="sidebar-menu-item">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fa-solid fa-house"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="sidebar-menu-item active">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fa-solid fa-user-group"></i>
          <span>Kelola Mahasiswa</span>
        </a>
      </li>
      <li class="sidebar-menu-item">
        <a href="#">
          <i class="fa-solid fa-graduation-cap"></i>
          <span>Kelola Pengajar</span>
        </a>
      </li>
      <li class="sidebar-menu-item">
        <a href="#">
          <i class="fa-solid fa-book-open"></i>
          <span>Manajemen Mata Kuliah</span>
        </a>
      </li>
      <li class="sidebar-menu-item">
        <a href="#">
          <i class="fa-solid fa-gear"></i>
          <span>Pengaturan</span>
        </a>
      </li>
    </ul>

    <div class="sidebar-footer">
      <div class="user-profile">
        <div class="user-avatar">
          {{ strtoupper(substr(auth('admin')->user()->nama, 0, 1)) }}
        </div>
        <div class="user-info">
          <span class="user-name">{{ auth('admin')->user()->nama }}</span>
          <span class="user-role">Administrator</span>
        </div>
      </div>
      <form method="POST" action="{{ route('admin.logout') }}" style="width:100%">
        @csrf
        <button type="submit" class="btn-logout">
          <i class="fa-solid fa-arrow-right-from-bracket"></i>
          <span>Logout</span>
        </button>
      </form>
    </div>
  </aside>

  <!-- Main Content Area -->
  <main class="main-content">
    <!-- Breadcrumb -->
    <nav class="breadcrumb">
      <a href="{{ route('admin.dashboard') }}">Kelola Mahasiswa</a>
      <span>/</span>
      <a href="#" style="color: var(--text-main);">Tambah Akun</a>
    </nav>

    <!-- Header Row -->
    <div class="header-row">
      <h1 class="page-title">Kelola Akun Mahasiswa</h1>
      <div class="header-actions">
        <div class="search-wrapper">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" class="search-input" placeholder="Search">
        </div>
        <a href="{{ route('admin.mahasiswas.create') }}" class="btn-action-primary">
          Tambah Mahasiswa
        </a>
      </div>
    </div>

    <!-- Form Card Container -->
    <div class="card-container">
      <div class="form-card">
        <div class="form-card-header">
          <h2 class="form-card-title">Formulir Akun Mahasiswa</h2>
          <p class="form-card-subtitle">Tambah atau edit data mahasiswa baru</p>
        </div>

        <!-- Alerts -->
        <div id="successAlert" class="alert alert-success">
          <i class="fa-solid fa-circle-check"></i>
          <span>Akun mahasiswa berhasil ditambahkan!</span>
        </div>
        <div id="errorAlert" class="alert alert-error">
          <i class="fa-solid fa-circle-xmark"></i>
          <span id="errorAlertText">Terjadi kesalahan. Silakan periksa kembali data Anda.</span>
        </div>

        <!-- Form -->
        <form id="createForm" method="POST" action="{{ route('admin.mahasiswas.store') }}">
          @csrf

          <!-- Nama Lengkap -->
          <div class="form-group">
            <label class="form-label" for="name">Nama Lengkap</label>
            <div class="form-input-wrapper">
              <input type="text" id="name" name="name" class="form-input" placeholder="Andi Prasetyo" required>
            </div>
          </div>

          <!-- NIM -->
          <div class="form-group">
            <label class="form-label" for="nim">NIM</label>
            <div class="form-input-wrapper">
              <input type="text" id="nim" name="nim" class="form-input" placeholder="20110543" required>
            </div>
          </div>

          <!-- Email -->
          <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <div class="form-input-wrapper">
              <input type="email" id="email" name="email" class="form-input" placeholder="andip@university.ac.id" required>
            </div>
          </div>

          <!-- Nomor WhatsApp -->
          <div class="form-group">
            <label class="form-label" for="phone">Nomor WhatsApp</label>
            <div class="form-input-wrapper">
              <input type="tel" id="phone" name="phone" class="form-input" placeholder="+62 812 3456 7890">
            </div>
          </div>

          <!-- Universitas -->
          <div class="form-group">
            <label class="form-label" for="universitas">Universitas</label>
            <div class="form-input-wrapper">
              <select id="universitas" name="universitas" class="form-input" style="appearance: none;" required>
                <option value="" disabled selected>Pilih Universitas</option>
                <option value="Universitas Indonesia">Universitas Indonesia</option>
                <option value="Institut Teknologi Bandung">Institut Teknologi Bandung</option>
                <option value="Universitas Padjadjaran">Universitas Padjadjaran</option>
                <option value="Universitas Gadjah Mada">Universitas Gadjah Mada</option>
                <option value="Universitas Logistik dan Bisnis Internasional">Universitas Logistik dan Bisnis Internasional</option>
              </select>
              <i class="fa-solid fa-chevron-down" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; font-size: 13px;"></i>
            </div>
          </div>

          <!-- Semester -->
          <div class="form-group">
            <label class="form-label" for="semester">Semester</label>
            <div class="form-input-wrapper">
              <select id="semester" name="semester" class="form-input" style="appearance: none;" required>
                <option value="" disabled selected>Pilih Semester</option>
                @for($i = 1; $i <= 8; $i++)
                  <option value="{{ $i }}">Semester {{ $i }}</option>
                @endfor
              </select>
              <i class="fa-solid fa-chevron-down" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; font-size: 13px;"></i>
            </div>
          </div>

          <!-- Password -->
          <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="form-input-wrapper">
              <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
              <i class="fa-solid fa-eye-slash password-toggle" id="togglePasswordBtn"></i>
            </div>
          </div>

          <!-- Form Actions -->
          <div class="form-actions">
            <button type="submit" class="btn-submit" id="submitBtn">
              <span>Simpan Data</span>
              <div class="loader" id="submitLoader"></div>
            </button>
            <a href="{{ route('admin.dashboard') }}" class="btn-cancel">Batal</a>
          </div>
        </form>

      </div>
    </div>
  </main>

  <script>
    // Password Toggle Visibility
    const passwordInput = document.getElementById('password');
    const togglePasswordBtn = document.getElementById('togglePasswordBtn');

    togglePasswordBtn.addEventListener('click', function() {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      
      // Toggle eye icons
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });

    // AJAX Form Submit
    const createForm = document.getElementById('createForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitLoader = document.getElementById('submitLoader');
    const successAlert = document.getElementById('successAlert');
    const errorAlert = document.getElementById('errorAlert');
    const errorAlertText = document.getElementById('errorAlertText');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    createForm.addEventListener('submit', async function(e) {
      e.preventDefault();

      // Reset alert states
      successAlert.style.display = 'none';
      errorAlert.style.display = 'none';

      // Set Loading state
      submitBtn.disabled = true;
      submitLoader.style.display = 'block';

      try {
        const formData = new FormData(createForm);
        const payload = Object.fromEntries(formData.entries());

        const response = await fetch(createForm.action, {
          method: 'POST',
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
          // If validator fails, we display errors
          let errorMsg = result.message || 'Gagal menyimpan data akun mahasiswa.';
          if (result.errors) {
            const firstErrorKey = Object.keys(result.errors)[0];
            errorMsg = result.errors[firstErrorKey][0];
          }
          throw new Error(errorMsg);
        }

        // Show Success Alert
        successAlert.style.display = 'flex';
        createForm.reset();

        // Redirect to dashboard after 1.5 seconds
        setTimeout(() => {
          window.location.href = "{{ route('admin.dashboard') }}";
        }, 1500);

      } catch (error) {
        errorAlertText.textContent = error.message;
        errorAlert.style.display = 'flex';
      } finally {
        submitBtn.disabled = false;
        submitLoader.style.display = 'none';
      }
    });
  </script>
</body>
</html>
