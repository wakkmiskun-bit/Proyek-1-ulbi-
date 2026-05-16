<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TaskMate - Kanban Board</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

  <!-- External CSS -->
  @vite(['resources/css/styles.css', 'resources/js/script.js'])
</head>
<body>
<div class="flex items-center gap-4">
<!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold shadow-lg transition duration-300 hover:scale-105">
                    Logout
                </button>
            </form>
            

</div>

<!-- LOADER -->
<div id="loader">
  <div class="loader-logo">
    <div class="loader-icon">📋</div>
    <div class="loader-wordmark">TaskMate</div>
  </div>
  <div class="loader-tagline">Task Management</div>
  <div class="loader-bar-wrap">
    <div class="loader-bar"></div>
  </div>
  <div class="loader-status" id="loaderStatus">Memuat aplikasi...</div>
</div>



<!-- APP -->
<div id="app">
  <!-- NAVBAR -->
  <nav class="navbar">
    <a href="#" class="nav-logo">
      <div class="nav-logo-icon">📋</div>
      <div class="nav-logo-text">TaskMate</div>
    </a>
    <div class="nav-divider"></div>

    <div class="nav-stats">
      <div class="stat-chip">
        <div class="stat-dot" style="background: #6366f1;"></div>
        <span>To Do</span>
        <b data-count="todo">0</b>
      </div>
      <div class="stat-chip">
        <div class="stat-dot" style="background: #f59e0b;"></div>
        <span>Doing</span>
        <b data-count="doing">0</b>
      </div>
      <div class="stat-chip">
        <div class="stat-dot" style="background: #ec4899;"></div>
        <span>Review</span>
        <b data-count="review">0</b>
      </div>
      <div class="stat-chip">
        <div class="stat-dot" style="background: #10b981;"></div>
        <span>Done</span>
        <b data-count="done">0</b>
      </div>
    </div>


    <div class="nav-right">
      <div class="search-wrap">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"></circle>
          <path d="m21 21-4.35-4.35"></path>
        </svg>
        <input type="text" id="searchInput" placeholder="Cari task...">
      </div>

      <div class="progress-ring-wrap">
        <span id="ringPct">0%</span>
        <svg class="mini-ring" width="36" height="36" viewBox="0 0 36 36">
          <circle class="ring-bg" cx="18" cy="18" r="10"></circle>
          <circle id="ringFill" class="ring-fill" cx="18" cy="18" r="10" style="stroke-dasharray:62.8; stroke-dashoffset:62.8;"></circle>
        </svg>
      </div>

      <button id="darkBtn" class="nav-btn">☀️</button>
    </div>
  </nav>

  <!-- MOBILE COL TABS -->
  <div class="mobile-col-tabs">
    <button class="mob-col-tab active" data-col="todo">
      <span>📋</span>
      <span>To Do</span>
    </button>
    <button class="mob-col-tab" data-col="doing">
      <span>⚡</span>
      <span>Doing</span>
    </button>
    <button class="mob-col-tab" data-col="review">
      <span>👁</span>
      <span>Review</span>
    </button>
    <button class="mob-col-tab" data-col="done">
      <span>✅</span>
      <span>Done</span>
    </button>
  </div>

  <!-- BOARD -->
  <div class="board-wrap">
    <div class="board">
      <!-- TO DO -->
      <div class="list todo" data-col="todo">
        <div class="list-header">
          <div class="list-header-left">
            <div class="col-dot"></div>
            <div class="col-label">To Do</div>
          </div>
          <div class="col-count" data-count="todo">0</div>
        </div>
        <div class="list-body">
          <div class="cards" id="cards-todo"></div>
          <button class="add-btn" data-col="todo">+ Tambah Task</button>
          <div class="add-box" id="addbox-todo"></div>
        </div>
      </div>

      <!-- DOING -->
      <div class="list doing" data-col="doing">
        <div class="list-header">
          <div class="list-header-left">
            <div class="col-dot"></div>
            <div class="col-label">Doing</div>
          </div>
          <div class="col-count" data-count="doing">0</div>
        </div>
        <div class="list-body">
          <div class="cards" id="cards-doing"></div>
          <button class="add-btn" data-col="doing">+ Tambah Task</button>
          <div class="add-box" id="addbox-doing"></div>
        </div>
      </div>

      <!-- REVIEW -->
      <div class="list review" data-col="review">
        <div class="list-header">
          <div class="list-header-left">
            <div class="col-dot"></div>
            <div class="col-label">Review</div>
          </div>
          <div class="col-count" data-count="review">0</div>
        </div>
        <div class="list-body">
          <div class="cards" id="cards-review"></div>
          <button class="add-btn" data-col="review">+ Tambah Task</button>
          <div class="add-box" id="addbox-review"></div>
        </div>
      </div>

      <!-- DONE -->
      <div class="list done" data-col="done">
        <div class="list-header">
          <div class="list-header-left">
            <div class="col-dot"></div>
            <div class="col-label">Done</div>
          </div>
          <div class="col-count" data-count="done">0</div>
        </div>
        <div class="list-body">
          <div class="cards" id="cards-done"></div>
          <button class="add-btn" data-col="done">+ Tambah Task</button>
          <div class="add-box" id="addbox-done"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL -->
  <div class="modal-overlay" id="cardModal">
    <div class="modal">
      <div class="modal-head">
        <h3>Edit Task</h3>
        <button id="modalCloseBtn" class="modal-close-btn">✕</button>
      </div>
      <div class="modal-body">
        <!-- Title -->
        <div class="form-group">
          <label class="form-label">Judul</label>
          <input id="mTitle" class="form-ctrl" placeholder="Nama task...">
        </div>

        <!-- Description -->
        <div class="form-group">
          <label class="form-label">Deskripsi</label>
          <textarea id="mDesc" class="form-ctrl" rows="3" placeholder="Detail tambahan..."></textarea>
        </div>

        <!-- Priority -->
        <div class="form-group">
          <label class="form-label">Prioritas</label>
          <div class="modal-priority-row">
            <div class="mp-opt high sel" data-p="high">🔴 Tinggi</div>
            <div class="mp-opt medium" data-p="medium">🟡 Sedang</div>
            <div class="mp-opt low" data-p="low">🟢 Rendah</div>
          </div>
        </div>

        <!-- Due Date -->
        <div class="form-group">
          <label class="form-label">Tenggat Waktu</label>
          <input id="mDue" class="form-ctrl" type="date">
        </div>

        <!-- Move to Column -->
        <div class="form-group">
          <label class="form-label">Pindahkan ke Kolom</label>
          <div class="move-col-row">
            <button class="move-col-btn todo" data-col="todo">📋 To Do</button>
            <button class="move-col-btn doing" data-col="doing">⚡ Doing</button>
            <button class="move-col-btn review" data-col="review">👁 Review</button>
            <button class="move-col-btn done" data-col="done">✅ Done</button>
          </div>
        </div>

        <!-- Checklist -->
        <div class="form-group">
          <label class="form-label">Checklist</label>
          <div class="checklist-list" id="clList"></div>
          <div class="cl-add-row" style="gap:8px">
            <input id="clInput" class="form-ctrl" placeholder="Item baru..." style="margin:0">
            <button id="clAddBtn" class="btn-primary" style="padding: 10px 14px;">+ Tambah</button>
          </div>
        </div>
      </div>

      <div class="modal-foot">
        <button id="modalCancelBtn" class="btn-ghost">Batal</button>
        <button id="modalSaveBtn" class="btn-primary">Simpan</button>
      </div>
    </div>
  </div>

  <!-- TOAST -->
  <div id="toast-wrap"></div>

  <!-- MOBILE BOTTOM NAV -->
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

<!-- Sortable.js Library -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<!-- External JavaScript -->
<script src="{{ asset('js/script.js') }}"></script>

</body>
</html>
