<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="base-url" content="{{ url('/') }}">
  <title>Dashboard {{ $mahasiswa->name }} - Admin Preview</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,400&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
  @vite(['resources/css/styles.css', 'resources/js/script.js'])
  <style>
    .admin-preview-bar {
      height: 48px; background: linear-gradient(135deg, #e91e63, #f06292);
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 20px; color: #fff; position: sticky; top: 0; z-index: 300;
    }
    .admin-preview-bar a { color: #fff; text-decoration: none; font-size: 13px; font-weight: 600;
      padding: 6px 14px; border-radius: 8px; background: rgba(255,255,255,0.15); }
    .admin-preview-bar a:hover { background: rgba(255,255,255,0.25); }
    .preview-user { display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 600; }
    .preview-user img, .preview-user .avatar-fallback {
      width: 32px; height: 32px; border-radius: 50%; object-fit: cover;
      border: 2px solid rgba(255,255,255,0.5); background: rgba(255,255,255,0.2);
      display: flex; align-items: center; justify-content: center; font-size: 14px;
    }
  </style>
  <script>window.TASKMATE_BOARD = { adminUserId: {{ $mahasiswa->id }} };</script>
</head>
<body>
<div id="app" class="visible">

  <div class="admin-preview-bar">
    <a href="{{ route('admin.dashboard') }}">← Kembali ke Panel Admin</a>
    <div class="preview-user">
      @if($mahasiswa->foto)
        <img src="{{ asset('storage/'.$mahasiswa->foto) }}" alt="{{ $mahasiswa->name }}">
      @else
        <div class="avatar-fallback">{{ strtoupper(substr($mahasiswa->name, 0, 1)) }}</div>
      @endif
      <span>Preview Dashboard: {{ $mahasiswa->name }} ({{ $mahasiswa->nim }})</span>
    </div>
    <span style="font-size:12px;opacity:.85">Mode Admin — lihat & kelola tugas mahasiswa</span>
  </div>

  <nav class="navbar">
    <a href="{{ route('admin.dashboard') }}" class="nav-logo">
      <div class="nav-logo-icon">📋</div>
      <div class="nav-logo-text">TaskMate</div>
    </a>
    <div class="nav-divider"></div>
    <div class="nav-stats">
      <div class="stat-chip"><div class="stat-dot" style="background:#e91e63"></div><span>To Do</span><b data-count="todo">0</b></div>
      <div class="stat-chip"><div class="stat-dot" style="background:#d97706"></div><span>Doing</span><b data-count="doing">0</b></div>
      <div class="stat-chip"><div class="stat-dot" style="background:#ec4899"></div><span>Review</span><b data-count="review">0</b></div>
      <div class="stat-chip"><div class="stat-dot" style="background:#10b981"></div><span>Done</span><b data-count="done">0</b></div>
    </div>
    <div class="nav-right">
      <div class="search-wrap">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" id="searchInput" placeholder="Cari task...">
      </div>
      <div class="progress-ring-wrap">
        <span id="ringPct">0%</span>
        <svg class="mini-ring" width="34" height="34" viewBox="0 0 36 36"><circle class="ring-bg" cx="18" cy="18" r="10"></circle><circle id="ringFill" class="ring-fill" cx="18" cy="18" r="10" style="stroke-dasharray:62.8; stroke-dashoffset:62.8;"></circle></svg>
      </div>
      <button id="darkBtn" class="nav-btn" title="Toggle tema">🌙</button>
      <div class="stat-chip"><span>{{ $mahasiswa->name }}</span><b style="font-size:11px;opacity:.8">{{ $mahasiswa->nim }}</b></div>
    </div>
  </nav>

  <div class="mobile-col-tabs">
    <button class="mob-col-tab active" data-col="todo"><span>📋</span><span>To Do</span></button>
    <button class="mob-col-tab" data-col="doing"><span>⚡</span><span>Doing</span></button>
    <button class="mob-col-tab" data-col="review"><span>👁</span><span>Review</span></button>
    <button class="mob-col-tab" data-col="done"><span>✅</span><span>Done</span></button>
  </div>

  <div class="board-wrap"><div class="board">
    @foreach(['todo'=>'To Do','doing'=>'Doing','review'=>'Review','done'=>'Done'] as $col => $label)
    <div class="list {{ $col }}" data-col="{{ $col }}">
      <div class="list-header"><div class="list-header-left"><div class="col-stripe"></div><div class="col-label">{{ $label }}</div></div><div class="col-count" data-count="{{ $col }}">0</div></div>
      <div class="list-body"><div class="cards" id="cards-{{ $col }}"></div>
        <button class="add-btn" data-col="{{ $col }}"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg> Tambah Task</button>
        <div class="add-box" id="addbox-{{ $col }}"></div>
      </div>
      <div class="col-foot"><div class="col-prog-bar"><div class="col-prog-fill"></div></div></div>
    </div>
    @endforeach
  </div></div>

  <div class="modal-overlay" id="cardModal"><div class="modal">
    <div class="modal-head"><h3>Edit Task</h3><button id="modalCloseBtn" class="modal-close-btn">✕</button></div>
    <div class="modal-body">
      <div class="form-group"><label class="form-label">Judul</label><input id="mTitle" class="form-ctrl" placeholder="Nama task..."></div>
      <div class="form-group"><label class="form-label">Deskripsi</label><textarea id="mDesc" class="form-ctrl" rows="3"></textarea></div>
      <div class="form-group"><label class="form-label">Prioritas</label><div class="modal-priority-row">
        <div class="mp-opt high sel" data-p="high">🔴 Tinggi</div><div class="mp-opt medium" data-p="medium">🟡 Sedang</div><div class="mp-opt low" data-p="low">🟢 Rendah</div>
      </div></div>
      <div class="form-group"><label class="form-label">Tenggat Waktu</label><input id="mDue" class="form-ctrl" type="date"></div>
      <div class="form-group"><label class="form-label">Pindahkan ke Kolom</label><div class="move-col-row">
        <button class="move-col-btn todo" data-col="todo">📋 To Do</button><button class="move-col-btn doing" data-col="doing">⚡ Doing</button>
        <button class="move-col-btn review" data-col="review">👁 Review</button><button class="move-col-btn done" data-col="done">✅ Done</button>
      </div></div>
      <div class="form-group"><label class="form-label">Checklist</label><div class="checklist-list" id="clList"></div>
        <div class="cl-add-row"><input id="clInput" class="form-ctrl" placeholder="Item baru..."><button id="clAddBtn" class="btn-primary" style="padding:10px 14px">+ Tambah</button></div>
      </div>
    </div>
    <div class="modal-foot"><button id="modalCancelBtn" class="btn-ghost">Batal</button><button id="modalSaveBtn" class="btn-primary">Simpan</button></div>
  </div></div>

  <div id="toast-wrap"></div>
  <div class="mobile-bottom-nav" id="mobileNav">
    @foreach(['todo','doing','review','done'] as $col)
    <button class="mob-nav-tab {{ $col==='todo'?'active':'' }}" data-col="{{ $col }}"><span>{{ ucfirst($col) }}</span></button>
    @endforeach
  </div>

</body>
</html>
