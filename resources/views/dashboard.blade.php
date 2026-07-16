<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="base-url" content="{{ url('/') }}">
  <script>
    window.addEventListener('error', function(e) {
      const div = document.createElement('div');
      div.style.position = 'fixed'; div.style.top = '0'; div.style.left = '0'; div.style.width = '100%';
      div.style.background = '#ef4444'; div.style.color = '#fff'; div.style.padding = '15px';
      div.style.zIndex = '999999'; div.style.fontFamily = 'monospace'; div.style.fontSize = '14px';
      div.innerHTML = '<strong>JS Error:</strong> ' + e.message + ' at ' + e.filename + ':' + e.lineno;
      document.body.appendChild(div);
    });
    window.addEventListener('unhandledrejection', function(e) {
      const div = document.createElement('div');
      div.style.position = 'fixed'; div.style.top = '0'; div.style.left = '0'; div.style.width = '100%';
      div.style.background = '#f97316'; div.style.color = '#fff'; div.style.padding = '15px';
      div.style.zIndex = '999999'; div.style.fontFamily = 'monospace'; div.style.fontSize = '14px';
      div.innerHTML = '<strong>Promise Reject:</strong> ' + e.reason;
      document.body.appendChild(div);
    });
  </script>
  <title>TaskMate - Dashboard Siswa</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
  @vite(['resources/css/styles.css', 'resources/js/script.js'])
</head>
<body>

<div id="app" class="visible student-app-container">

  <!-- ═══ MOBILE TOP BAR ═══ -->
  <div class="mobile-top-bar">
    <button class="menu-toggle-btn" id="mobileMenuToggleBtn" title="Buka Menu">
      <i class="fa-solid fa-bars"></i>
    </button>
    <div class="mobile-logo">
      <div class="mobile-logo-icon">+</div>
      <span class="mobile-logo-text">TaskMate</span>
    </div>
    <div class="mobile-top-right">
      <button class="mobile-icon-btn" data-tab-trigger="notifications" title="Notifikasi"><i class="fa-solid fa-bell"></i></button>
      <button class="mobile-icon-btn" id="mobileDarkBtn" title="Toggle Tema">🌙</button>
    </div>
  </div>

  <!-- ═══ SIDEBAR OVERLAY ═══ -->
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <!-- ═══ SIDEBAR ═══ -->
  <aside class="student-sidebar">
    <div class="sidebar-logo">
      <div class="sidebar-logo-icon">+</div>
      <span class="sidebar-logo-text">TaskMate</span>
    </div>
    
    <nav class="sidebar-menu">
      <button class="menu-item active" data-tab="dashboard">
        <i class="fa-solid fa-house"></i>
        <span>Dashboard</span>
      </button>
      <button class="menu-item" data-tab="tasks">
        <i class="fa-solid fa-list-check"></i>
        <span>Tugas Saya</span>
      </button>
      <button class="menu-item" data-tab="calendar">
        <i class="fa-solid fa-calendar-days"></i>
        <span>Kalender</span>
      </button>
      <button class="menu-item" data-tab="notifications">
        <i class="fa-solid fa-bell"></i>
        <span>Notifikasi</span>
      </button>
      <button class="menu-item" data-tab="settings">
        <i class="fa-solid fa-user-gear"></i>
        <span>Pengaturan</span>
      </button>
    </nav>
    
    <div class="sidebar-footer">
      <div class="sidebar-user">
        @if(auth()->user()->photo_url)
          <img src="{{ auth()->user()->photo_url }}" alt="" class="user-avatar-img">
        @else
          <div class="user-avatar-fallback">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        @endif
        <div class="user-meta">
          <span class="user-name">{{ auth()->user()->name }}</span>
          <span class="user-sub">NIM {{ auth()->user()->nim }}</span>
        </div>
      </div>
      <form method="POST" action="{{ route('logout') }}" style="margin-top:12px; width:100%">
        @csrf
        <button type="submit" class="btn-sidebar-logout">
          <i class="fa-solid fa-arrow-right-from-bracket"></i>
          <span>Logout</span>
        </button>
      </form>
    </div>
  </aside>

  <!-- ═══ MAIN CONTENT ═══ -->
  <main class="student-content">
    
    <!-- Top Header Bar -->
    <header class="student-header">
      <div class="header-left">
        <span class="student-profile-summary">{{ auth()->user()->name }} | Semester {{ auth()->user()->semester }}</span>
      </div>
      <div class="header-right">
        <button class="header-icon-btn" data-tab-trigger="notifications" title="Notifikasi"><i class="fa-solid fa-bell"></i></button>
        <button class="header-icon-btn" id="darkBtn" title="Toggle Tema">🌙</button>
        <a href="{{ route('bantuan') }}" class="header-icon-btn" title="Bantuan" style="text-decoration: none;"><i class="fa-solid fa-circle-question"></i></a>
      </div>
    </header>

    <!-- ── TAB SECTION 1: DASHBOARD HOME ── -->
    <section id="tab-dashboard" class="student-tab-section active">
      <div class="dashboard-home-hero">
        <div class="hero-welcome">
          <h1>Halo, {{ explode(' ', auth()->user()->name)[0] }}!</h1>
          <h2 class="dashboard-home-title">Dashboard Siswa - TaskMate</h2>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="dashboard-stats-row">
        <!-- Card 1: Active Tasks -->
        <div class="dashboard-stat-card card-pink" style="cursor: pointer;" onclick="document.querySelector('.sidebar-menu .menu-item[data-tab=\'tasks\']').click()">
          <div class="stat-card-left">
            <i class="fa-solid fa-clipboard-list" style="font-size: 32px;"></i>
            <div class="stat-card-text">
              <span class="stat-card-label">Tugas Aktif</span>
              <span class="stat-card-num" id="statActiveVal">{{ $activeCount }}</span>
            </div>
          </div>
        </div>

        <!-- Card 2: Progress Ring -->
        <div class="dashboard-stat-card card-gray" style="cursor: pointer;" onclick="document.querySelector('.sidebar-menu .menu-item[data-tab=\'tasks\']').click()">
          <div class="progress-ring-container">
            <div class="progress-ring-val" id="progressPctVal">{{ $completionRate }}%</div>
            <svg class="progress-ring-svg" width="80" height="80" viewBox="0 0 36 36">
              <circle class="ring-bg-circle" cx="18" cy="18" r="14" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="3"></circle>
              <circle id="progressPctRing" class="ring-fill-circle" cx="18" cy="18" r="14" fill="none" stroke="#e91e63" stroke-width="3" stroke-dasharray="88" stroke-dashoffset="{{ 88 - (88 * $completionRate / 100) }}" stroke-linecap="round"></circle>
            </svg>
          </div>
          <div class="progress-ring-text">
            <span class="ring-title" id="statDoneTotalText">{{ $doneCount }} / {{ $totalTasks }}</span>
            <span class="ring-lbl">Total Tugas</span>
            <span class="ring-lbl-sub">Progres Belajar</span>
          </div>
        </div>

        <!-- Card 3: Completed Tasks -->
        <div class="dashboard-stat-card card-pink" style="cursor: pointer;" onclick="document.querySelector('.sidebar-menu .menu-item[data-tab=\'tasks\']').click()">
          <div class="stat-card-left">
            <i class="fa-solid fa-circle-check" style="font-size: 32px;"></i>
            <div class="stat-card-text">
              <span class="stat-card-label">Tugas Selesai</span>
              <span class="stat-card-num" id="statCompletedVal">{{ $doneCount }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom Row Grid -->
      <div class="dashboard-bottom-grid">
        <!-- Left: Tasks approaching deadline -->
        <div class="dashboard-panel panel-deadline">
          <h3><i class="fa-regular fa-clock" style="margin-right: 8px;"></i> Tugas Mendekati Deadline</h3>
          <div class="deadline-tasks-list" id="dashboardDeadlineList">
            @forelse($upcomingTasks as $task)
              @php
                $diff = now()->startOfDay()->diffInDays($task->deadline, false);
                $badgeClass = $diff <= 2 ? 'badge-danger' : 'badge-warning';
                $badgeText = $diff < 0 ? 'Terlambat ' . abs($diff) . ' hari' : ($diff === 0 ? 'Hari ini' : ($diff === 1 ? 'Besok' : 'Sisa ' . $diff . ' hari'));
              @endphp
              <div class="deadline-task-item" data-task-id="{{ $task->id }}" style="cursor: pointer;" onclick="openDeadlineTaskModal({{ $task->id }}, event)">
                <div class="task-info-left">
                  <input type="checkbox" onclick="event.stopPropagation(); quickCompleteTask({{ $task->id }}, this)" class="task-chk">
                  <span class="task-title-text">{{ $task->title }}</span>
                </div>
                <div class="task-info-right">
                  <span class="task-due-text">Deadline: {{ $task->deadline->format('d M') }}</span>
                  <span class="task-due-badge {{ $badgeClass }}">{{ $badgeText }}</span>
                </div>
              </div>
            @empty
              <div class="empty-state-dashboard">Tidak ada tugas mendekati deadline.</div>
            @endforelse
          </div>
        </div>

        <!-- Right: Subjects list or Activities -->
        <div class="dashboard-panel panel-subjects">
          <h3><i class="fa-solid fa-chart-line" style="margin-right: 8px;"></i> Progres Mata Kuliah</h3>
          <div class="subjects-list">
            <div class="subject-item">
              <span class="subject-name">Sistem Tertanam (80%)</span>
              <div class="subject-bar"><div class="subject-fill" style="width: 80%"></div></div>
            </div>
            <div class="subject-item">
              <span class="subject-name">Kecerdasan Buatan (90%)</span>
              <div class="subject-bar"><div class="subject-fill" style="width: 90%"></div></div>
            </div>
            <div class="subject-item">
              <span class="subject-name">Rekayasa Perangkat Lunak (75%)</span>
              <div class="subject-bar"><div class="subject-fill" style="width: 75%"></div></div>
            </div>
            <div class="subject-item">
              <span class="subject-name">Jaringan Komputer (85%)</span>
              <div class="subject-bar"><div class="subject-fill" style="width: 85%"></div></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ── TAB SECTION 2: KANBAN BOARD (TUGAS SAYA) ── -->
    <section id="tab-tasks" class="student-tab-section">
      <!-- Search and Quick Stats Bar -->
      <div class="board-top-row">
        <div class="search-wrap">
          <i class="fa-solid fa-magnifying-glass search-icon-input"></i>
          <input type="text" id="searchInput" placeholder="Cari task...">
        </div>
        <div class="board-stats-chips">
          <div class="stat-chip"><div class="stat-dot" style="background:#e91e63"></div><span>To Do</span><b id="s-todo">0</b></div>
          <div class="stat-chip"><div class="stat-dot" style="background:#d97706"></div><span>Doing</span><b id="s-doing">0</b></div>
          <div class="stat-chip"><div class="stat-dot" style="background:#ec4899"></div><span>Review</span><b id="s-review">0</b></div>
          <div class="stat-chip"><div class="stat-dot" style="background:#10b981"></div><span>Done</span><b id="s-done">0</b></div>
        </div>
      </div>

      <!-- Warning Alert -->
      <div id="deadlineAlert" class="deadline-alert hidden" style="margin-top: 16px;"></div>

      <!-- Mobile Column Selector Tabs -->
      <div class="mobile-col-tabs">
        <button class="mob-col-tab active" data-col="todo"><span>📋 To Do</span></button>
        <button class="mob-col-tab" data-col="doing"><span>⚡ Doing</span></button>
        <button class="mob-col-tab" data-col="review"><span>👁 Review</span></button>
        <button class="mob-col-tab" data-col="done"><span>✅ Done</span></button>
      </div>

      <!-- ═══ BOARD ═══ -->
      <div class="board-wrap">
        <div class="board">

          <!-- TO DO -->
          <div class="list todo" data-col="todo">
            <div class="list-header">
              <div class="list-header-left">
                <div class="col-stripe"></div>
                <div class="col-label">To Do</div>
              </div>
              <div class="col-count" data-count="todo">0</div>
            </div>
            <div class="list-body">
              <div class="cards" id="cards-todo"></div>
              <button class="add-btn" data-col="todo">
                <i class="fa-solid fa-plus" style="margin-right: 6px;"></i> Tambah Task
              </button>
              <div class="add-box" id="addbox-todo"></div>
            </div>
            <div class="col-foot"><div class="col-prog-bar"><div class="col-prog-fill"></div></div></div>
          </div>

          <!-- DOING -->
          <div class="list doing" data-col="doing">
            <div class="list-header">
              <div class="list-header-left">
                <div class="col-stripe"></div>
                <div class="col-label">Doing</div>
              </div>
              <div class="col-count" data-count="doing">0</div>
            </div>
            <div class="list-body">
              <div class="cards" id="cards-doing"></div>
              <button class="add-btn" data-col="doing">
                <i class="fa-solid fa-plus" style="margin-right: 6px;"></i> Tambah Task
              </button>
              <div class="add-box" id="addbox-doing"></div>
            </div>
            <div class="col-foot"><div class="col-prog-bar"><div class="col-prog-fill"></div></div></div>
          </div>

          <!-- REVIEW -->
          <div class="list review" data-col="review">
            <div class="list-header">
              <div class="list-header-left">
                <div class="col-stripe"></div>
                <div class="col-label">Review</div>
              </div>
              <div class="col-count" data-count="review">0</div>
            </div>
            <div class="list-body">
              <div class="cards" id="cards-review"></div>
              <button class="add-btn" data-col="review">
                <i class="fa-solid fa-plus" style="margin-right: 6px;"></i> Tambah Task
              </button>
              <div class="add-box" id="addbox-review"></div>
            </div>
            <div class="col-foot"><div class="col-prog-bar"><div class="col-prog-fill"></div></div></div>
          </div>

          <!-- DONE -->
          <div class="list done" data-col="done">
            <div class="list-header">
              <div class="list-header-left">
                <div class="col-stripe"></div>
                <div class="col-label">Done</div>
              </div>
              <div class="col-count" data-count="done">0</div>
            </div>
            <div class="list-body">
              <div class="cards" id="cards-done"></div>
              <button class="add-btn" data-col="done">
                <i class="fa-solid fa-plus" style="margin-right: 6px;"></i> Tambah Task
              </button>
              <div class="add-box" id="addbox-done"></div>
            </div>
            <div class="col-foot"><div class="col-prog-bar"><div class="col-prog-fill"></div></div></div>
          </div>

        </div>
      </div>
    </section>

    <!-- ── TAB SECTION 3: KALENDER ── -->
    <section id="tab-calendar" class="student-tab-section">
      <div class="calendar-wrapper">
        <div class="calendar-header-row">
          <button id="prevMonthBtn" class="calendar-nav-btn"><i class="fa-solid fa-chevron-left"></i></button>
          <h2 id="calendarMonthTitle">Juli 2026</h2>
          <button id="nextMonthBtn" class="calendar-nav-btn"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
        <div class="calendar-grid-header">
          <div>Min</div><div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div>
        </div>
        <div class="calendar-grid-cells" id="calendarCells"></div>
      </div>
    </section>

    <!-- ── TAB SECTION 4: NOTIFIKASI ── -->
    <section id="tab-notifications" class="student-tab-section">
      <div class="dashboard-panel">
        <h3><i class="fa-solid fa-bell" style="color:#e91e63; margin-right:8px;"></i> Pusat Notifikasi & Pengingat</h3>
        <p style="color:var(--text2); font-size:14px; margin-bottom:20px;">Daftar peringatan otomatis berdasarkan tenggat waktu tugas Anda yang tersimpan di sistem.</p>
        <div class="notifications-feed-list" id="notificationsFeed">
          <div class="empty-state-dashboard">Memuat data pengingat...</div>
        </div>
      </div>
    </section>

    <!-- ── TAB SECTION 5: PENGATURAN PROFIL ── -->
    <section id="tab-settings" class="student-tab-section">
      <div class="dashboard-panel" style="max-width: 680px; margin: 0 auto;">
        <h3><i class="fa-solid fa-user-gear" style="color:#e91e63; margin-right:8px;"></i> Pengaturan Akun Mahasiswa</h3>
        <p style="color:var(--text2); font-size:14px; margin-bottom:24px;">Perbarui informasi biodata mahasiswa dan unggah foto profil terbaru Anda.</p>
        
        @if(session('status') === 'profile-updated')
          <div class="settings-alert-success" style="padding: 12px; background: #dcfce7; border: 1px solid #bbf7d0; color: #15803d; border-radius: 8px; margin-bottom: 20px;"><i class="fa-solid fa-circle-check"></i> Profil berhasil diperbarui!</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
          @csrf
          @method('PATCH')

          <!-- NIM (Readonly) -->
          <div class="settings-form-group" style="margin-bottom:20px;">
            <label class="settings-label" style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:8px;">Nomor Induk Mahasiswa (NIM)</label>
            <input type="text" class="settings-input" style="width:100%; padding:10px 14px; border:1px solid var(--border); border-radius:8px; background:var(--c2); cursor:not-allowed" value="{{ auth()->user()->nim }}" readonly>
            <span class="settings-tip" style="font-size:11px; color:var(--text3); display:block; margin-top:4px;">NIM dikunci secara sistem dan tidak dapat diubah secara mandiri.</span>
          </div>

          <!-- Nama -->
          <div class="settings-form-group" style="margin-bottom:20px;">
            <label class="settings-label" style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:8px;" for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" class="settings-input" style="width:100%; padding:10px 14px; border:1px solid var(--border); border-radius:8px; background:var(--c1); color:var(--text)" value="{{ auth()->user()->name }}" required>
          </div>

          <!-- Email -->
          <div class="settings-form-group" style="margin-bottom:20px;">
            <label class="settings-label" style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:8px;" for="email">Alamat Email</label>
            <input type="email" id="email" name="email" class="settings-input" style="width:100%; padding:10px 14px; border:1px solid var(--border); border-radius:8px; background:var(--c1); color:var(--text)" value="{{ auth()->user()->email }}" required>
          </div>

          <!-- Phone (WhatsApp) -->
          <div class="settings-form-group" style="margin-bottom:20px;">
            <label class="settings-label" style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:8px;" for="phone">Nomor WhatsApp</label>
            <input type="text" id="phone" name="phone" class="settings-input" style="width:100%; padding:10px 14px; border:1px solid var(--border); border-radius:8px; background:var(--c1); color:var(--text)" placeholder="Contoh: 082216151741" value="{{ auth()->user()->phone }}">
          </div>

          <!-- Universitas -->
          <div class="settings-form-group" style="margin-bottom:20px;">
            <label class="settings-label" style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:8px;" for="universitas">Nama Universitas</label>
            <input type="text" id="universitas" name="universitas" class="settings-input" style="width:100%; padding:10px 14px; border:1px solid var(--border); border-radius:8px; background:var(--c1); color:var(--text)" placeholder="Universitas Logistik dan Bisnis Internasional" value="{{ auth()->user()->universitas }}">
          </div>

          <!-- Semester -->
          <div class="settings-form-group" style="margin-bottom:20px;">
            <label class="settings-label" style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:8px;" for="semester">Semester</label>
            <select id="semester" name="semester" class="settings-input" style="width:100%; padding:10px 14px; border:1px solid var(--border); border-radius:8px; background:var(--c1); color:var(--text)">
              @for($i = 1; $i <= 8; $i++)
                <option value="{{ $i }}" {{ auth()->user()->semester == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
              @endfor
            </select>
          </div>

          <!-- Upload Foto -->
          <div class="settings-form-group" style="margin-bottom:24px;">
            <label class="settings-label" style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:8px;" for="foto">Foto Profil</label>
            <div class="settings-photo-row" style="display:flex; align-items:center; gap:16px;">
              @if(auth()->user()->photo_url)
                <img src="{{ auth()->user()->photo_url }}" alt="" class="settings-preview-img" style="width:50px; height:50px; border-radius:50%; object-fit:cover; border:2px solid #e91e63;">
              @else
                <div class="settings-preview-fallback" style="width:50px; height:50px; border-radius:50%; background:#fee2e2; color:#ef4444; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:18px;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
              @endif
              <input type="file" id="foto" name="foto" class="settings-file-input">
            </div>
            <span class="settings-tip" style="font-size:11px; color:var(--text3); display:block; margin-top:4px;">Format gambar yang didukung: JPEG, PNG, JPG (Maksimal 2MB).</span>
          </div>

          <!-- Submit Buttons -->
          <div class="settings-actions">
            <button type="submit" class="btn-settings-save" style="padding:12px 24px; background:#e91e63; color:#fff; border:none; border-radius:8px; font-weight:700; cursor:pointer; font-family:inherit;">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </section>

  </main>

  <!-- ═══ CARD MODAL (FOR TAB TUGAS SAYA) ═══ -->
  <div class="modal-overlay" id="cardModal">
    <div class="modal">
      <div class="modal-head">
        <h3>Edit Task</h3>
        <button id="modalCloseBtn" class="modal-close-btn">✕</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Judul</label>
          <input id="mTitle" class="form-ctrl" placeholder="Nama task...">
        </div>
        <div class="form-group">
          <label class="form-label">Deskripsi</label>
          <textarea id="mDesc" class="form-ctrl" rows="3" placeholder="Detail tambahan..."></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Prioritas</label>
          <div class="modal-priority-row">
            <div class="mp-opt high sel" data-p="high">🔴 Tinggi</div>
            <div class="mp-opt medium" data-p="medium">🟡 Sedang</div>
            <div class="mp-opt low" data-p="low">🟢 Rendah</div>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Tenggat Waktu</label>
          <input id="mDue" class="form-ctrl" type="date">
        </div>
        <div class="form-group">
          <label class="form-label">Pindahkan ke Kolom</label>
          <div class="move-col-row">
            <button class="move-col-btn todo" data-col="todo">📋 To Do</button>
            <button class="move-col-btn doing" data-col="doing">⚡ Doing</button>
            <button class="move-col-btn review" data-col="review">👁 Review</button>
            <button class="move-col-btn done" data-col="done">✅ Done</button>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Checklist</label>
          <div class="checklist-list" id="clList"></div>
          <div class="cl-add-row">
            <input id="clInput" class="form-ctrl" placeholder="Item baru...">
            <button id="clAddBtn" class="btn-primary" style="padding:10px 14px">+ Tambah</button>
          </div>
        </div>
      </div>
      <div class="modal-foot">
        <button id="modalCancelBtn" class="btn-ghost">Batal</button>
        <button id="modalSaveBtn" class="btn-primary">Simpan</button>
      </div>
    </div>
  </div>

  <!-- ═══ TOAST ═══ -->
  <div id="toast-wrap"></div>

  <!-- ═══ MOBILE BOTTOM NAV ═══ -->
  <div class="mobile-bottom-nav" id="mobileNav">
    <button class="mob-nav-tab active" data-tab="dashboard">
      <i class="fa-solid fa-house"></i>
      <span>Home</span>
    </button>
    <button class="mob-nav-tab" data-tab="tasks">
      <i class="fa-solid fa-list-check"></i>
      <span>Tugas</span>
    </button>
    <button class="mob-nav-tab" data-tab="calendar">
      <i class="fa-solid fa-calendar-days"></i>
      <span>Kalender</span>
    </button>
    <button class="mob-nav-tab" data-tab="notifications">
      <i class="fa-solid fa-bell"></i>
      <span>Notif</span>
    </button>
    <button class="mob-nav-tab" data-tab="settings">
      <i class="fa-solid fa-user-gear"></i>
      <span>Pengaturan</span>
    </button>
  </div>

</div>

</body>
</html>