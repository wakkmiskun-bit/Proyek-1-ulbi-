<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="base-url" content="{{ url('/') }}">
  <title>Detail Mahasiswa - TaskMate Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite(['resources/css/admin.css', 'resources/js/admin.js'])
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

    .user-avatar-sidebar {
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

    /* Detail Profile Card */
    .detail-card {
      background: white;
      border: 1px solid var(--border-color);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-sm);
      padding: 32px;
      display: flex;
      gap: 32px;
      align-items: flex-start;
      margin-bottom: 32px;
    }

    .detail-avatar-container {
      flex-shrink: 0;
    }

    .detail-avatar-img {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid var(--pink-primary);
      box-shadow: 0 4px 12px rgba(233, 30, 99, 0.15);
    }

    .detail-avatar-fallback {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--pink-primary), #f48fb1);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 44px;
      font-weight: 800;
      border: 3px solid var(--pink-primary);
      box-shadow: 0 4px 12px rgba(233, 30, 99, 0.15);
    }

    .detail-info-block {
      flex: 1;
    }

    .detail-info-block h1 {
      font-size: 24px;
      font-weight: 800;
      color: var(--text-main);
      margin-bottom: 12px;
      letter-spacing: -0.5px;
    }

    .info-list {
      list-style: none;
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
      margin-bottom: 16px;
    }

    .info-list-item {
      font-size: 14px;
      color: var(--text-muted);
    }

    .info-list-item strong {
      color: var(--text-main);
      font-weight: 600;
    }

    .status-badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 20px;
      background-color: var(--pink-light);
      color: var(--pink-primary);
      font-size: 12px;
      font-weight: 700;
    }

    .detail-actions-block {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .btn-action-ghost {
      padding: 10px 18px;
      border-radius: 8px;
      background: #f1f5f9;
      color: var(--text-muted);
      border: 1px solid var(--border-color);
      font-weight: 700;
      font-size: 13px;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all 0.2s;
    }

    .btn-action-ghost:hover {
      background: #e2e8f0;
      color: var(--text-main);
    }

    /* Detail Page Grid */
    .detail-sections-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 32px;
    }

    .section-panel {
      background: white;
      border: 1px solid var(--border-color);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-sm);
      padding: 28px;
    }

    .section-panel h3 {
      font-size: 18px;
      font-weight: 800;
      color: var(--text-main);
      margin-bottom: 20px;
      letter-spacing: -0.3px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    /* Tasks table */
    .tasks-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }

    .tasks-table th {
      text-align: left;
      font-weight: 700;
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: var(--text-muted);
      padding: 12px 16px;
      border-bottom: 2px solid var(--border-color);
      background-color: #f8fafc;
    }

    .tasks-table td {
      padding: 14px 16px;
      border-bottom: 1px solid var(--border-color);
      color: var(--text-main);
    }

    .tasks-table tbody tr:hover {
      background-color: #f8fafc;
    }

    .status-pill {
      font-weight: 700;
      font-size: 11px;
      border-radius: 6px;
      padding: 4px 8px;
      display: inline-block;
    }

    .status-pill.todo { background-color: rgba(99, 102, 241, 0.08); color: #4f46e5; }
    .status-pill.doing { background-color: rgba(245, 158, 11, 0.08); color: #d97706; }
    .status-pill.review { background-color: rgba(236, 72, 153, 0.08); color: #db2777; }
    .status-pill.done { background-color: rgba(16, 185, 129, 0.08); color: #059669; }

    .priority-badge {
      font-weight: 700;
      font-size: 11px;
      border-radius: 6px;
      padding: 4px 8px;
      display: inline-block;
    }

    .priority-badge.high { background-color: #fee2e2; color: #ef4444; }
    .priority-badge.medium { background-color: #fef3c7; color: #d97706; }
    .priority-badge.low { background-color: #dcfce7; color: #059669; }

    /* Activities list */
    .activities-list {
      list-style: none;
    }

    .activity-item-detail {
      border-left: 3px solid var(--pink-primary);
      background-color: #f8fafc;
      padding: 16px;
      border-radius: 0 8px 8px 0;
      margin-bottom: 12px;
      border-top: 1px solid var(--border-color);
      border-right: 1px solid var(--border-color);
      border-bottom: 1px solid var(--border-color);
    }

    .activity-item-detail strong {
      color: var(--text-main);
      font-size: 14px;
      font-weight: 700;
    }

    .activity-item-detail .time {
      font-size: 11px;
      color: var(--text-muted);
      margin-top: 6px;
    }

    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: var(--text-muted);
    }

    .empty-state svg {
      width: 48px;
      height: 48px;
      margin-bottom: 16px;
      color: var(--text-muted);
      opacity: 0.5;
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
      .detail-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 20px;
      }
      .info-list {
        grid-template-columns: 1fr;
      }
      .detail-actions-block {
        width: 100%;
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
        <div class="user-avatar-sidebar">
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
      <a href="#" style="color: var(--text-main);">Detail Akun</a>
    </nav>

    <!-- Header Row -->
    <div class="header-row">
      <h1 class="page-title">Detail Akun Mahasiswa</h1>
      <div class="header-actions">
        <a href="{{ route('admin.mahasiswas.create') }}" class="btn-action-primary">
          Tambah Mahasiswa
        </a>
      </div>
    </div>

    <!-- Detail Card -->
    <div class="detail-card">
      <div class="detail-avatar-container">
        @if ($mahasiswa->photo_url)
          <img src="{{ $mahasiswa->photo_url }}" alt="{{ $mahasiswa->nama }}" class="detail-avatar-img">
        @else
          <div class="detail-avatar-fallback">
            {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
          </div>
        @endif
      </div>
      <div class="detail-info-block">
        <h1>{{ $mahasiswa->nama }}</h1>
        <ul class="info-list">
          <li class="info-list-item">NIM: <strong>{{ $mahasiswa->nim }}</strong></li>
          <li class="info-list-item">Email: <strong>{{ $mahasiswa->email }}</strong></li>
          <li class="info-list-item">WhatsApp: <strong>{{ $mahasiswa->phone ?? '-' }}</strong></li>
          <li class="info-list-item">Universitas: <strong>{{ $mahasiswa->universitas ?? '-' }}</strong></li>
          <li class="info-list-item" style="grid-column: span 2;">Terdaftar: <strong>{{ $mahasiswa->created_at->format('d M Y, H:i') }}</strong></li>
        </ul>
        <span class="status-badge">Mahasiswa Aktif</span>
      </div>
      <div class="detail-actions-block">
        <a href="{{ route('admin.mahasiswas.edit', $mahasiswa->id) }}" class="btn-action-primary" style="padding: 10px 18px; border-radius: 8px; font-size: 13px;">
          <i class="fa-solid fa-pen-to-square" style="margin-right: 8px;"></i> Edit Akun
        </a>
        <a href="{{ route('admin.dashboard') }}" class="btn-action-ghost">
          <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
      </div>
    </div>

    <!-- Detail Sections Grid -->
    <div class="detail-sections-grid">
      
      <!-- Statistik Tugas -->
      <div class="section-panel">
        <h3><i class="fa-solid fa-chart-pie" style="color: var(--pink-primary);"></i> Statistik Tugas</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 16px;">
          <div style="padding: 16px; background: #f8fafc; border: 1px solid var(--border-color); border-radius: 8px; text-align: center;">
            <div style="font-size: 28px; font-weight: 800; color: #4f46e5;">{{ $mahasiswa->tasks()->where('status', 'todo')->count() }}</div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px; font-weight: 700;">To Do</div>
          </div>
          <div style="padding: 16px; background: #f8fafc; border: 1px solid var(--border-color); border-radius: 8px; text-align: center;">
            <div style="font-size: 28px; font-weight: 800; color: #d97706;">{{ $mahasiswa->tasks()->where('status', 'doing')->count() }}</div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px; font-weight: 700;">Doing</div>
          </div>
          <div style="padding: 16px; background: #f8fafc; border: 1px solid var(--border-color); border-radius: 8px; text-align: center;">
            <div style="font-size: 28px; font-weight: 800; color: #db2777;">{{ $mahasiswa->tasks()->where('status', 'review')->count() }}</div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px; font-weight: 700;">Review</div>
          </div>
          <div style="padding: 16px; background: #f8fafc; border: 1px solid var(--border-color); border-radius: 8px; text-align: center;">
            <div style="font-size: 28px; font-weight: 800; color: #059669;">{{ $mahasiswa->tasks()->where('status', 'done')->count() }}</div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px; font-weight: 700;">Done</div>
          </div>
        </div>
      </div>

      <!-- Daftar Tugas -->
      <div class="section-panel">
        <h3><i class="fa-solid fa-list-check" style="color: var(--pink-primary);"></i> Daftar Tugas Mahasiswa</h3>
        @if ($mahasiswa->tasks->isNotEmpty())
          <div style="overflow-x: auto;">
            <table class="tasks-table">
              <thead>
                <tr>
                  <th>Judul</th>
                  <th>Status</th>
                  <th>Prioritas</th>
                  <th>Deadline</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($mahasiswa->tasks->sortBy('sort_order') as $task)
                  <tr>
                    <td style="font-weight: 700; color: var(--text-main);">{{ $task->title }}</td>
                    <td>
                      <span class="status-pill {{ $task->status }}">
                        @if ($task->status === 'todo')
                          To Do
                        @elseif ($task->status === 'doing')
                          Doing
                        @elseif ($task->status === 'review')
                          Review
                        @else
                          Done
                        @endif
                      </span>
                    </td>
                    <td>
                      <span class="priority-badge {{ $task->priority }}">
                        @if ($task->priority === 'high')
                          Tinggi
                        @elseif ($task->priority === 'medium')
                          Sedang
                        @else
                          Rendah
                        @endif
                      </span>
                    </td>
                    <td>
                      @if ($task->deadline)
                        {{ $task->deadline->format('d M Y') }}
                      @else
                        -
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="empty-state">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p>Mahasiswa ini belum memiliki tugas.</p>
          </div>
        @endif
      </div>

      <!-- Aktivitas Terbaru -->
      <div class="section-panel">
        <h3><i class="fa-solid fa-clock-rotate-left" style="color: var(--pink-primary);"></i> Aktivitas Terbaru</h3>
        @if ($activities->isNotEmpty())
          <ul class="activities-list">
            @foreach ($activities as $activity)
              <li class="activity-item-detail">
                <strong>{{ $activity->activity_text }}</strong>
                <div style="margin-top: 6px;">
                  <span class="status-pill {{ $activity->status_tugas }}">
                    @if ($activity->status_tugas === 'todo')
                      To Do
                    @elseif ($activity->status_tugas === 'doing')
                      Doing
                    @elseif ($activity->status_tugas === 'review')
                      Review
                    @else
                      Done
                    @endif
                  </span>
                </div>
                <div class="time"><i class="fa-regular fa-clock" style="margin-right: 4px;"></i> {{ $activity->created_at->diffForHumans() }}</div>
              </li>
            @endforeach
          </ul>
        @else
          <div class="empty-state">
            <p>Belum ada aktivitas terbaru untuk mahasiswa ini.</p>
          </div>
        @endif
      </div>

    </div>
  </main>
</body>
</html>
