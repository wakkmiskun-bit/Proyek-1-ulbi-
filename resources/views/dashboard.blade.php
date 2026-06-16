<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>TaskMate - Kanban Board</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,400&display=swap" rel="stylesheet">
  @vite(['resources/css/styles.css', 'resources/js/script.js'])
</head>
<body>

<div id="app" class="visible">

  <!-- ═══ NAVBAR ═══ -->
  <nav class="navbar">
    <a href="#" class="nav-logo">
      <div class="nav-logo-icon">📋</div>
      <div class="nav-logo-text">TaskMate</div>
    </a>

    <div class="nav-divider"></div>

    <div class="nav-stats">
      <div class="stat-chip">
        <div class="stat-dot" style="background:#7c6af7"></div>
        <span>To Do</span>
        <b data-count="todo">0</b>
      </div>
      <div class="stat-chip">
        <div class="stat-dot" style="background:#f4a13e"></div>
        <span>Doing</span>
        <b data-count="doing">0</b>
      </div>
      <div class="stat-chip">
        <div class="stat-dot" style="background:#e05ec8"></div>
        <span>Review</span>
        <b data-count="review">0</b>
      </div>
      <div class="stat-chip">
        <div class="stat-dot" style="background:#34d399"></div>
        <span>Done</span>
        <b data-count="done">0</b>
      </div>
    </div>

    <div class="nav-right">
      <div class="search-wrap">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"></circle>
          <path d="m21 21-4.35-4.35"></path>
        </svg>
        <input type="text" id="searchInput" placeholder="Cari task...">
      </div>

      <div class="progress-ring-wrap">
        <span id="ringPct">0%</span>
        <svg class="mini-ring" width="34" height="34" viewBox="0 0 36 36">
          <circle class="ring-bg" cx="18" cy="18" r="10"></circle>
          <circle id="ringFill" class="ring-fill" cx="18" cy="18" r="10" style="stroke-dasharray:62.8; stroke-dashoffset:62.8;"></circle>
        </svg>
      </div>

      <button id="darkBtn" class="nav-btn" title="Toggle tema">☀️</button>

      <div class="stat-chip" title="Akun aktif">
        <span>{{ auth()->user()->name }}</span>
        <b style="font-size:11px;opacity:.8">{{ auth()->user()->nim }}</b>
      </div>

      <form method="POST" action="{{ route('logout') }}" style="margin:0">
        @csrf
        <button type="submit" class="btn-logout">Logout</button>
      </form>
    </div>
  </nav>

  <!-- ═══ MOBILE COL TABS ═══ -->
  <div class="mobile-col-tabs">
    <button class="mob-col-tab active" data-col="todo"><span>📋</span><span>To Do</span></button>
    <button class="mob-col-tab" data-col="doing"><span>⚡</span><span>Doing</span></button>
    <button class="mob-col-tab" data-col="review"><span>👁</span><span>Review</span></button>
    <button class="mob-col-tab" data-col="done"><span>✅</span><span>Done</span></button>
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
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Task
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
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Task
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
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Task
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
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Task
          </button>
          <div class="add-box" id="addbox-done"></div>
        </div>
        <div class="col-foot"><div class="col-prog-bar"><div class="col-prog-fill"></div></div></div>
      </div>

    </div>
  </div>

  <!-- ═══ MODAL ═══ -->
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
    <button class="mob-nav-tab active" data-col="todo">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M9 11l3 3L22 4"></path><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      <span>To Do</span>
    </button>
    <button class="mob-nav-tab" data-col="doing">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="22 12 18 12 15 20 9 4 6 12 2 12"></polyline>
      </svg>
      <span>Doing</span>
    </button>
    <button class="mob-nav-tab" data-col="review">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>
      </svg>
      <span>Review</span>
    </button>
    <button class="mob-nav-tab" data-col="done">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="20 6 9 17 4 12"></polyline>
      </svg>
      <span>Done</span>
    </button>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
</body>
</html>