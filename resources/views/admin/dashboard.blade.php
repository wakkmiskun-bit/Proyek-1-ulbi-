<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="admin-board-base" content="{{ url('/admin/mahasiswas') }}">
  <title>TaskMate Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,400&display=swap" rel="stylesheet">
  @vite(['resources/css/admin.css', 'resources/js/admin.js'])
  <style>
    .admin-container { display: flex; height: 100vh; }
    .admin-sidebar { 
      width: 280px; background: linear-gradient(135deg, #e91e63, #f06292); 
      color: #fff; padding: 24px; position: fixed; height: 100vh; overflow-y: auto; z-index: 50;
      box-shadow: 4px 0 16px rgba(233, 30, 99, 0.2);
    }
    .admin-sidebar-header { margin-bottom: 32px; }
    .admin-sidebar-logo { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
    .admin-sidebar-logo-icon { font-size: 28px; }
    .admin-sidebar-title { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 800; letter-spacing: -0.3px; }
    .admin-sidebar-sub { font-size: 11px; opacity: 0.9; margin-top: 4px; }
    .admin-sidebar-nav { display: flex; flex-direction: column; gap: 8px; margin-bottom: 24px; }
    .admin-sidebar-item { 
      padding: 12px 16px; border-radius: 10px; background: rgba(255,255,255,0.15); 
      color: #fff; text-decoration: none; font-size: 14px; font-weight: 600;
      transition: all .2s; cursor: pointer; border: 1px solid rgba(255,255,255,0.2);
    }
    .admin-sidebar-item:hover { background: rgba(255,255,255,0.25); }
    .admin-sidebar-item.active { background: rgba(255,255,255,0.3); border-color: rgba(255,255,255,0.4); }
    .admin-sidebar-footer { margin-top: auto; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.2); }
    .admin-sidebar-user { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; }
    .admin-sidebar-user-avatar { width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-weight: 700; }
    .admin-sidebar-user-name { font-size: 12px; font-weight: 600; }
    .admin-content { margin-left: 280px; width: calc(100% - 280px); overflow-y: auto; }
    .admin-nav-top { height: 64px; background: #fff; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; padding: 0 28px; sticky: top: 0; z-index: 40; }
    .admin-nav-title { font-size: 18px; font-weight: 700; color: var(--text); }
    .admin-main { max-width: 100%; padding: 28px 24px 48px; }
    .admin-hero { margin-bottom: 28px; }
    .admin-hero h1 { font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 18px; }
    .admin-stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 28px; }
    .stat-card { 
      background: #fff; padding: 20px; border-radius: 14px; border: 1px solid var(--border);
      box-shadow: var(--shadow);
    }
    .stat-card-icon { font-size: 24px; margin-bottom: 12px; }
    .stat-card-value { font-size: 28px; font-weight: 800; color: var(--primary); font-family: 'Syne', sans-serif; margin-bottom: 4px; }
    .stat-card-label { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text3); }
    .admin-content-row { display: grid; grid-template-columns: 1fr 320px; gap: 24px; }
    .admin-panel { background: #fff; border: 1px solid var(--border); border-radius: 14px; box-shadow: var(--shadow); overflow: hidden; }
    .panel-head { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 20px 24px; border-bottom: 1px solid var(--border); flex-wrap: wrap; }
    .panel-head h2 { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 700; margin: 0; }
    .sidebar-panel { max-height: 100%; }
    @media (max-width: 1200px) { 
      .admin-content-row { grid-template-columns: 1fr; }
      .sidebar-panel { max-height: 400px; }
    }
    @media (max-width: 768px) { 
      .admin-sidebar { width: 100%; height: auto; position: static; }
      .admin-content { margin-left: 0; width: 100%; }
      .admin-nav-top { display: none; }
      .admin-stats-grid { grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); }
    }
  </style>
</head>
<body>
<div id="admin-app" class="visible admin-container">

  <!-- Sidebar -->
  <aside class="admin-sidebar">
    <div class="admin-sidebar-header">
      <div class="admin-sidebar-logo">
        <span class="admin-sidebar-logo-icon">🛡️</span>
        <div>
          <div class="admin-sidebar-title">TaskMate</div>
          <div class="admin-sidebar-sub">Admin Panel</div>
        </div>
      </div>
    </div>
    
    <nav class="admin-sidebar-nav">
      <button class="admin-sidebar-item active" data-nav="dashboard">📊 Dashboard</button>
      <button class="admin-sidebar-item" data-nav="admins">🛡️ Kelola Admin</button>
    </nav>

    <div class="admin-sidebar-footer">
      <div class="admin-sidebar-user">
        <div class="admin-sidebar-user-avatar">{{ strtoupper(substr(auth('admin')->user()->nama, 0, 1)) }}</div>
        <div class="admin-sidebar-user-name">{{ auth('admin')->user()->nama }}</div>
      </div>
      <form method="POST" action="{{ route('admin.logout') }}" style="width:100%">
        @csrf
        <button type="submit" class="admin-sidebar-item" style="width: 100%; text-align: left; margin:0">🚪 Logout</button>
      </form>
    </div>
  </aside>

  <!-- Content Area -->
  <div class="admin-content">
    <nav class="admin-nav-top">
      <h2 class="admin-nav-title">Dashboard Admin</h2>
    </nav>

    <main class="admin-main">
      <!-- Section Dashboard -->
      <div id="section-dashboard" class="admin-section">
        <!-- Stats Cards -->
        <div class="admin-hero">
          <h1>Selamat datang kembali! 👋</h1>
        </div>

        <div class="admin-stats-grid">
          <div class="stat-card">
            <div class="stat-card-icon">👥</div>
            <div class="stat-card-value" id="statUsers">{{ $stats['mahasiswa'] ?? 0 }}</div>
            <div class="stat-card-label">Total Mahasiswa</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-icon">📋</div>
            <div class="stat-card-value" id="statTasks">{{ $stats['tasks'] ?? 0 }}</div>
            <div class="stat-card-label">Total Tugas</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-icon">✅</div>
            <div class="stat-card-value" id="statDone">{{ $stats['done'] ?? 0 }}</div>
            <div class="stat-card-label">Tugas Selesai</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-icon">📈</div>
            <div class="stat-card-value">{{ round(($stats['done'] ?? 0) / max(1, ($stats['tasks'] ?? 0)) * 100) }}%</div>
            <div class="stat-card-label">Tingkat Penyelesaian</div>
          </div>
        </div>

        <!-- Main Content Row -->
        <div class="admin-content-row">
          <!-- Mahasiswa Table -->
          <section class="admin-panel">
            <div class="panel-head">
              <h2>Daftar Mahasiswa</h2>
              <div class="admin-search">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" id="userSearch" placeholder="Cari NIM, nama, atau email...">
              </div>
            </div>

            <div class="table-wrap">
              <table class="admin-table">
                <thead>
                  <tr>
                    <th>Foto</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>WhatsApp</th>
                    <th>Universitas</th>
                    <th>To Do</th>
                    <th>Doing</th>
                    <th>Review</th>
                    <th>Done</th>
                    <th>Total</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody id="usersTableBody">
                  @forelse ($mahasiswas as $mhs)
                    <tr data-search="{{ strtolower($mhs->nim . ' ' . $mhs->nama . ' ' . $mhs->email . ' ' . ($mhs->phone ?? '') . ' ' . ($mhs->universitas ?? '')) }}">
                      <td>
                        @if ($mhs->photo_url)
                          <img src="{{ $mhs->photo_url }}" alt="{{ $mhs->nama }}" class="user-avatar" style="width:34px;height:34px;border-radius:50%;object-fit:cover">
                        @else
                          <div class="user-avatar-fallback" style="width:34px;height:34px;border-radius:50%;background:#fce4ec;color:#e91e63;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px">
                            {{ strtoupper(substr($mhs->nama, 0, 1)) }}
                          </div>
                        @endif
                      </td>
                      <td><strong>{{ $mhs->nim }}</strong></td>
                      <td>{{ $mhs->nama }}</td>
                      <td>{{ $mhs->email }}</td>
                      <td>{{ $mhs->phone ?? '-' }}</td>
                      <td>{{ $mhs->universitas ?? '-' }}</td>
                      <td><span class="status-pill todo">{{ $mhs->todo_count ?? 0 }}</span></td>
                      <td><span class="status-pill doing">{{ $mhs->doing_count ?? 0 }}</span></td>
                      <td><span class="status-pill review">{{ $mhs->review_count ?? 0 }}</span></td>
                      <td><span class="status-pill done">{{ $mhs->done_count ?? 0 }}</span></td>
                      <td><strong>{{ $mhs->tasks_count ?? 0 }}</strong></td>
                      <td class="action-cell">
                        <a href="{{ route('admin.mahasiswas.board', $mhs->id) }}" class="btn-admin-sm primary">Dashboard</a>
                        <a href="{{ route('admin.mahasiswas.show', $mhs->id) }}" class="btn-admin-sm">Lihat Data</a>
                        <a href="{{ route('admin.mahasiswas.edit', $mhs->id) }}" class="btn-admin-sm outline">Edit</a>
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="12" class="empty-row">Belum ada mahasiswa terdaftar.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </section>

          <!-- Activity Sidebar -->
          <section class="admin-panel sidebar-panel">
            <div class="panel-head" style="border-bottom: 1px solid var(--border); margin-bottom: 0;">
              <h2>Aktivitas Terbaru</h2>
            </div>
            <div class="activity-feed" id="activityFeed" style="max-height: none; padding: 12px 8px 8px;">
              <div class="empty-row" style="padding: 16px 8px; text-align: center;">Memuat aktivitas...</div>
            </div>
          </section>
        </div>
      </div>

      <!-- Section Admins (Kelola Admin) -->
      <div id="section-admins" class="admin-section" style="display: none;">
        <div class="admin-hero">
          <h1>Kelola Admin 🛡️</h1>
          <p>Kelola data akun administrator yang memiliki akses ke panel TaskMate.</p>
        </div>

        <section class="admin-panel">
          <div class="panel-head">
            <h2>Daftar Administrator</h2>
            <button class="btn-admin-primary" id="addAdminBtn">+ Tambah Admin Baru</button>
          </div>

          <div class="table-wrap">
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Dibuat Pada</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="adminsTableBody">
                <tr><td colspan="4" class="empty-row">Memuat data administrator...</td></tr>
              </tbody>
            </table>
          </div>
        </section>
      </div>
    </main>
  </div>

  <!-- Modals -->
  <div class="admin-overlay" id="userDetailModal">
    <div class="admin-modal admin-modal-lg">
      <div class="admin-modal-head">
        <div>
          <h3 id="detailUserName">Detail Mahasiswa</h3>
          <p id="detailUserMeta"></p>
        </div>
        <button class="modal-x" id="closeDetailBtn">✕</button>
      </div>
      <div class="admin-modal-body">
        <div id="detailUserProfile" class="detail-profile"></div>
        <div class="detail-actions">
          <button class="btn-admin-primary" id="viewBoardBtn">Lihat Dashboard User</button>
          <button class="btn-admin-primary" id="editUserBtn">Edit Akun</button>
          <button class="btn-admin-danger" id="deleteUserBtn">Hapus Akun</button>
        </div>

        <div class="task-toolbar">
          <h4>Tugas Mahasiswa</h4>
          <button class="btn-admin-primary" id="addTaskBtn">+ Tambah Tugas</button>
        </div>

        <div class="table-wrap">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Judul</th>
                <th>Status</th>
                <th>Prioritas</th>
                <th>Deadline</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="tasksTableBody"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="admin-overlay" id="editUserModal">
    <div class="admin-modal">
      <div class="admin-modal-head">
        <h3>Edit Akun Mahasiswa</h3>
        <button class="modal-x" id="closeEditUserBtn">✕</button>
      </div>
      <div class="admin-modal-body">
        <div class="admin-form-group">
          <label>NIM</label>
          <input id="editNim" class="admin-input">
        </div>
        <div class="admin-form-group">
          <label>Nama</label>
          <input id="editName" class="admin-input">
        </div>
        <div class="admin-form-group">
          <label>Email</label>
          <input id="editEmail" type="email" class="admin-input">
        </div>
        <div class="admin-form-group">
          <label>Nomor WhatsApp</label>
          <input id="editPhone" class="admin-input" placeholder="08xxxxxxxxxx">
        </div>
        <div class="admin-form-group">
          <label>Universitas</label>
          <input id="editUniversitas" class="admin-input" placeholder="Nama Universitas">
        </div>
        <div class="admin-form-group">
          <label>Password Baru (opsional)</label>
          <input id="editPassword" type="password" class="admin-input" placeholder="Kosongkan jika tidak diubah">
        </div>
        <div class="admin-form-group">
          <label>Konfirmasi Password</label>
          <input id="editPasswordConfirm" type="password" class="admin-input">
        </div>
      </div>
      <div class="admin-modal-foot">
        <button class="btn-admin-ghost" id="cancelEditUserBtn">Batal</button>
        <button class="btn-admin-primary" id="saveUserBtn">Simpan</button>
      </div>
    </div>
  </div>

  <div class="admin-overlay" id="taskModal">
    <div class="admin-modal">
      <div class="admin-modal-head">
        <h3 id="taskModalTitle">Tugas</h3>
        <button class="modal-x" id="closeTaskBtn">✕</button>
      </div>
      <div class="admin-modal-body">
        <div class="admin-form-group">
          <label>Judul</label>
          <input id="taskTitle" class="admin-input">
        </div>
        <div class="admin-form-group">
          <label>Deskripsi</label>
          <textarea id="taskDesc" class="admin-input" rows="3"></textarea>
        </div>
        <div class="admin-form-row">
          <div class="admin-form-group">
            <label>Status</label>
            <select id="taskStatus" class="admin-input">
              <option value="todo">To Do</option>
              <option value="doing">Doing</option>
              <option value="review">Review</option>
              <option value="done">Done</option>
            </select>
          </div>
          <div class="admin-form-group">
            <label>Prioritas</label>
            <select id="taskPriority" class="admin-input">
              <option value="high">Tinggi</option>
              <option value="medium">Sedang</option>
              <option value="low">Rendah</option>
            </select>
          </div>
        </div>
        <div class="admin-form-group">
          <label>Deadline</label>
          <input id="taskDue" type="date" class="admin-input">
        </div>
      </div>
      <div class="admin-modal-foot">
        <button class="btn-admin-ghost" id="cancelTaskBtn">Batal</button>
        <button class="btn-admin-primary" id="saveTaskBtn">Simpan</button>
      </div>
    </div>
  </div>

  <div class="admin-overlay" id="adminModal">
    <div class="admin-modal">
      <div class="admin-modal-head">
        <h3 id="adminModalTitle">Tambah Admin Baru</h3>
        <button class="modal-x" id="closeAdminBtn">✕</button>
      </div>
      <div class="admin-modal-body">
        <div class="admin-form-group">
          <label>Nama Lengkap</label>
          <input id="adminName" class="admin-input" placeholder="Nama admin">
        </div>
        <div class="admin-form-group">
          <label>Email</label>
          <input id="adminEmail" type="email" class="admin-input" placeholder="email@gmail.com">
        </div>
        <div class="admin-form-group">
          <label>Kata Sandi</label>
          <input id="adminPassword" type="password" class="admin-input" placeholder="Minimal 8 karakter">
        </div>
        <div class="admin-form-group">
          <label>Konfirmasi Kata Sandi</label>
          <input id="adminPasswordConfirm" type="password" class="admin-input" placeholder="Ulangi kata sandi">
        </div>
      </div>
      <div class="admin-modal-foot">
        <button class="btn-admin-ghost" id="cancelAdminBtn">Batal</button>
        <button class="btn-admin-primary" id="saveAdminBtn">Simpan</button>
      </div>
    </div>
  </div>

  <div id="admin-toast-wrap"></div>
</div>
</body>
</html>
