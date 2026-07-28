<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="base-url" content="{{ url('/') }}">
  <meta name="admin-board-base" content="{{ url('/admin/mahasiswas') }}">
  <title>TaskMate Admin</title>
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

    .admin-container {
      display: flex;
      width: 100%;
      min-height: 100vh;
    }

    /* Sidebar Styling */
    .admin-sidebar {
      width: 280px;
      background: var(--bg-sidebar);
      border-right: 1px solid var(--border-color);
      padding: 32px 24px;
      display: flex;
      flex-direction: column;
      position: fixed;
      height: 100vh;
      left: 0;
      top: 0;
      z-index: 100;
      box-shadow: none;
    }

    .admin-sidebar-header {
      margin-bottom: 40px;
    }

    .admin-sidebar-logo {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .admin-sidebar-logo-icon {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--pink-primary), #f48fb1);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 10px rgba(233, 30, 99, 0.2);
      font-size: 0px; /* Hide emoji */
    }

    .admin-sidebar-logo-icon::before {
      content: "\f3ed"; /* shield icon in fontawesome */
      font-family: "Font Awesome 6 Free";
      font-weight: 900;
      color: white;
      font-size: 18px;
    }

    .admin-sidebar-title {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 18px;
      font-weight: 800;
      color: var(--text-main);
      letter-spacing: -0.5px;
    }

    .admin-sidebar-sub {
      font-size: 11px;
      color: var(--text-muted);
      margin-top: 1px;
      opacity: 1;
    }

    .admin-sidebar-nav {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin-bottom: 24px;
      flex: 1;
    }

    .admin-sidebar-item {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 14px 20px;
      border-radius: var(--radius-md);
      color: var(--text-muted);
      background: transparent;
      border: none;
      font-size: 14px;
      font-weight: 600;
      transition: all 0.2s ease;
      cursor: pointer;
      width: 100%;
      text-align: left;
    }

    .admin-sidebar-item:hover {
      background-color: #f1f5f9;
      color: var(--text-main);
    }

    .admin-sidebar-item.active {
      background-color: var(--pink-light);
      color: var(--pink-primary);
      border: none;
    }

    .admin-sidebar-footer {
      border-top: 1px solid var(--border-color);
      padding-top: 24px;
      margin-top: auto;
    }

    .admin-sidebar-user {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 16px;
    }

    .admin-sidebar-user-avatar {
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

    .admin-sidebar-user-name {
      font-size: 14px;
      font-weight: 700;
      color: var(--text-main);
    }

    .btn-logout-sidebar {
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

    .btn-logout-sidebar:hover {
      background-color: #fee2e2;
      color: #ef4444;
      border-color: #fecaca;
    }

    /* Content Area Styling */
    .admin-content {
      margin-left: 280px;
      width: calc(100% - 280px);
      background-color: var(--bg-main);
      min-height: 100vh;
      overflow-y: auto;
    }

    .admin-nav-top {
      display: none; /* Hide old navbar */
    }

    .admin-main {
      max-width: 100%;
      padding: 40px 48px;
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
      background: white;
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

    /* Hero welcome message style */
    .admin-hero {
      margin-bottom: 24px;
    }
    .admin-hero h1 {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 24px;
      font-weight: 800;
      letter-spacing: -0.5px;
      color: var(--text-main);
      margin-bottom: 0;
    }

    /* Stats Grid Styling */
    .admin-stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
      margin-bottom: 32px;
    }

    .stat-card {
      background: white;
      padding: 24px;
      border-radius: var(--radius-lg);
      border: 1px solid var(--border-color);
      box-shadow: var(--shadow-sm);
      display: flex;
      flex-direction: column;
      transition: all 0.2s;
    }

    .stat-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-md);
    }

    .stat-card-icon {
      font-size: 24px;
      margin-bottom: 12px;
      width: 44px;
      height: 44px;
      border-radius: var(--radius-md);
      background-color: var(--pink-light);
      color: var(--pink-primary);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .stat-card-value {
      font-size: 28px;
      font-weight: 800;
      color: var(--text-main);
      font-family: 'Plus Jakarta Sans', sans-serif;
      margin-bottom: 4px;
      letter-spacing: -0.5px;
    }

    .stat-card-label {
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: var(--text-muted);
    }

    /* Content Row Layout */
    .admin-content-row {
      display: grid;
      grid-template-columns: 1fr 340px;
      gap: 24px;
    }

    .admin-panel {
      background: white;
      border: 1px solid var(--border-color);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-sm);
      overflow: hidden;
    }

    .panel-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 24px;
      border-bottom: 1px solid var(--border-color);
    }

    .panel-head h2 {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 18px;
      font-weight: 800;
      color: var(--text-main);
    }

    .panel-head .admin-search {
      display: none; /* Hide old redundant search in panel header */
    }

    /* Table styling to look extremely clean and modern */
    .admin-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }

    .admin-table th {
      background-color: #f8fafc;
      font-weight: 700;
      text-transform: uppercase;
      font-size: 11px;
      letter-spacing: 0.5px;
      color: var(--text-muted);
      border-bottom: 1px solid var(--border-color);
      padding: 16px 20px;
    }

    .admin-table td {
      padding: 16px 20px;
      border-bottom: 1px solid var(--border-color);
      color: var(--text-main);
      vertical-align: middle;
    }

    .admin-table tbody tr:hover {
      background-color: #f8fafc;
    }

    /* User Avatar and Badges */
    .user-avatar-fallback {
      background-color: var(--pink-light) !important;
      color: var(--pink-primary) !important;
    }

    .status-pill {
      font-weight: 700;
      font-size: 11px;
      border-radius: 6px;
      padding: 4px 8px;
    }

    .status-pill.todo { background-color: rgba(99, 102, 241, 0.08); color: #4f46e5; }
    .status-pill.doing { background-color: rgba(245, 158, 11, 0.08); color: #d97706; }
    .status-pill.review { background-color: rgba(236, 72, 153, 0.08); color: #db2777; }
    .status-pill.done { background-color: rgba(16, 185, 129, 0.08); color: #059669; }

    /* Action Buttons in Table */
    .btn-admin-sm, a.btn-admin-sm {
      padding: 8px 14px;
      border-radius: var(--radius-md);
      font-size: 12px;
      font-weight: 700;
      font-family: inherit;
      border: 1px solid var(--border-color);
      background: white;
      color: var(--text-muted);
      transition: all 0.2s;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      cursor: pointer;
    }

    .btn-admin-sm:hover, a.btn-admin-sm:hover {
      border-color: var(--pink-primary);
      color: var(--pink-primary);
      background-color: white;
    }

    .btn-admin-sm.primary, a.btn-admin-sm.primary {
      background-color: var(--pink-light);
      color: var(--pink-primary);
      border-color: transparent;
    }

    .btn-admin-sm.primary:hover, a.btn-admin-sm.primary:hover {
      background-color: rgba(233, 30, 99, 0.15);
      border-color: transparent;
      color: var(--pink-primary);
    }

    .btn-admin-sm.outline, a.btn-admin-sm.outline {
      border-color: var(--pink-border);
      color: var(--pink-primary);
      background-color: white;
    }

    .btn-admin-sm.outline:hover, a.btn-admin-sm.outline:hover {
      background-color: var(--pink-light);
      border-color: var(--pink-primary);
      color: var(--pink-primary);
    }

    /* Activity Feed styling */
    .activity-feed {
      padding: 16px 20px;
      max-height: 480px;
      overflow-y: auto;
    }

    .activity-item {
      display: flex;
      gap: 16px;
      align-items: flex-start;
      padding: 16px 0;
      border-bottom: 1px solid var(--border-color);
    }

    .activity-item:last-child {
      border-bottom: none;
    }

    .activity-body {
      font-size: 13px;
      line-height: 1.5;
    }

    .activity-body strong {
      color: var(--text-main);
      font-weight: 700;
    }

    .activity-body span {
      color: var(--text-muted);
    }

    .activity-meta {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-top: 6px;
    }

    .activity-meta .status-pill {
      font-size: 10px;
      padding: 2px 6px;
    }

    .activity-meta span:last-child {
      font-size: 11px;
      color: var(--text-muted);
    }

    /* Modals Styling */
    .admin-overlay {
      background: rgba(15, 23, 42, 0.4);
      backdrop-filter: blur(8px);
    }

    .admin-modal {
      border-radius: var(--radius-lg);
      border: 1px solid var(--border-color);
      box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
      padding: 0;
      overflow: hidden;
      max-width: 540px;
    }

    .admin-modal-lg {
      max-width: 860px;
    }

    .admin-modal-head {
      padding: 24px;
      border-bottom: 1px solid var(--border-color);
    }

    .admin-modal-head h3 {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 18px;
      font-weight: 800;
      color: var(--text-main);
    }

    .admin-modal-body {
      padding: 24px;
    }

    .admin-modal-foot {
      padding: 20px 24px;
      border-top: 1px solid var(--border-color);
      background-color: #f8fafc;
    }

    .admin-input, select.admin-input {
      border: 1px solid var(--border-color);
      border-radius: 8px;
      padding: 12px 14px;
      background-color: white;
      font-family: inherit;
      font-size: 14px;
      color: var(--text-main);
      outline: none;
      transition: all 0.2s;
    }

    .admin-input:focus {
      border-color: var(--pink-primary);
      box-shadow: 0 0 0 3px var(--pink-light);
    }

    .admin-form-group label {
      font-size: 12px;
      font-weight: 700;
      color: var(--text-main);
      margin-bottom: 6px;
      text-transform: none;
      letter-spacing: normal;
    }

    /* Modal primary and cancel buttons */
    .btn-admin-primary {
      background: var(--pink-primary);
      color: white;
      box-shadow: 0 4px 10px rgba(233, 30, 99, 0.15);
      border-radius: 8px;
      font-weight: 700;
      font-size: 13px;
      padding: 10px 18px;
    }

    .btn-admin-primary:hover {
      background: var(--pink-hover);
      box-shadow: 0 6px 14px rgba(233, 30, 99, 0.25);
    }

    .btn-admin-ghost {
      background: #f1f5f9;
      color: var(--text-muted);
      border: 1px solid var(--border-color);
      border-radius: 8px;
      font-weight: 700;
      font-size: 13px;
      padding: 10px 18px;
    }

    .btn-admin-ghost:hover {
      background: #e2e8f0;
      color: var(--text-main);
    }

    .btn-admin-danger {
      background-color: #fee2e2;
      color: #ef4444;
      border: 1px solid #fecaca;
      border-radius: 8px;
      font-weight: 700;
      font-size: 13px;
      padding: 10px 18px;
    }

    .btn-admin-danger:hover {
      background-color: #fca5a5;
      color: #b91c1c;
    }

    /* Detail profile layout inside modal */
    .detail-profile {
      background: #f8fafc;
      border: 1px solid var(--border-color);
      border-radius: var(--radius-md);
      padding: 20px;
    }

    .detail-profile-info h4 {
      font-size: 16px;
      font-weight: 800;
      color: var(--text-main);
    }

    .detail-profile-info p {
      font-size: 13px;
      color: var(--text-muted);
      line-height: 1.6;
    }

    .detail-stat-pill {
      background: white;
      border: 1px solid var(--border-color);
      font-weight: 600;
      font-size: 12px;
      color: var(--text-main);
      padding: 4px 10px;
      border-radius: 30px;
    }

    /* Toast Notification */
    .admin-toast {
      background-color: white;
      border: 1px solid var(--border-color);
      border-radius: var(--radius-md);
      box-shadow: var(--shadow-md);
      padding: 14px 20px;
      font-weight: 600;
      color: var(--text-main);
    }

    /* Responsive */
    @media (max-width: 1200px) {
      .admin-stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      .admin-content-row {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 768px) {
      .admin-container {
        flex-direction: column;
      }
      .admin-sidebar {
        position: static;
        width: 100%;
        height: auto;
        border-bottom: 1px solid var(--border-color);
        padding: 20px 16px;
      }
      .admin-sidebar-header {
        margin-bottom: 16px;
      }
      .admin-sidebar-nav {
        flex-direction: row;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 16px;
      }
      .admin-sidebar-item {
        width: auto;
        flex: 1;
        min-width: 130px;
        justify-content: center;
        padding: 10px 14px;
        font-size: 13px;
      }
      .admin-sidebar-footer {
        padding-top: 16px;
        margin-top: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
      }
      .admin-sidebar-user {
        margin-bottom: 0;
      }
      .admin-sidebar-footer form {
        width: auto;
        margin-top: 0 !important;
      }
      .admin-content {
        margin-left: 0;
        width: 100%;
      }
      .admin-main {
        padding: 24px 16px;
      }
      .header-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
      }
      .header-actions {
        width: 100%;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
      }
      .admin-stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
      }
    }

    @media (max-width: 480px) {
      .admin-sidebar-nav {
        flex-direction: column;
      }
      .admin-sidebar-item {
        width: 100%;
        justify-content: flex-start;
      }
      .admin-stats-grid {
        grid-template-columns: 1fr;
      }
      .header-actions {
        flex-direction: column;
        align-items: stretch;
      }
      .search-wrapper {
        width: 100%;
      }
      .search-input {
        width: 100% !important;
      }
      .btn-action-primary {
        width: 100%;
      }
    }
  </style>
</head>
<body>
<div id="admin-app" class="visible admin-container">

  <!-- Sidebar -->
  <aside class="admin-sidebar">
    <div class="admin-sidebar-header">
      <div class="admin-sidebar-logo">
        <span class="admin-sidebar-logo-icon"></span>
        <div>
          <div class="admin-sidebar-title">TaskMate Admin</div>
          <div class="admin-sidebar-sub">Panel Kontrol</div>
        </div>
      </div>
    </div>
    
    <nav class="admin-sidebar-nav">
      <button class="admin-sidebar-item active" data-nav="dashboard">
        <i class="fa-solid fa-house" style="margin-right: 12px; font-size: 16px;"></i>
        <span>Dashboard</span>
      </button>
      <button class="admin-sidebar-item" data-nav="admins">
        <i class="fa-solid fa-shield-halved" style="margin-right: 12px; font-size: 16px;"></i>
        <span>Kelola Admin</span>
      </button>
      <button class="admin-sidebar-item" onclick="window.location.href='{{ route('admin.mahasiswas.create') }}'">
        <i class="fa-solid fa-user-plus" style="margin-right: 12px; font-size: 16px;"></i>
        <span>Tambah Mahasiswa</span>
      </button>
    </nav>

    <div class="admin-sidebar-footer">
      <div class="admin-sidebar-user">
        <div class="admin-sidebar-user-avatar">{{ strtoupper(substr(auth('admin')->user()->nama, 0, 1)) }}</div>
        <div class="admin-sidebar-user-name">{{ auth('admin')->user()->nama }}</div>
      </div>
      <form method="POST" action="{{ route('admin.logout') }}" style="width:100%">
        @csrf
        <button type="submit" class="btn-logout-sidebar">
          <i class="fa-solid fa-arrow-right-from-bracket"></i>
          <span>Logout</span>
        </button>
      </form>
    </div>
  </aside>

  <!-- Content Area -->
  <div class="admin-content">
    <main class="admin-main">
      
      <!-- Section Dashboard -->
      <div id="section-dashboard" class="admin-section">
        
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
          <a href="#">Dashboard</a>
          <span>/</span>
          <a href="#" style="color: var(--text-main);">Kelola Mahasiswa</a>
        </nav>

        <!-- Header Row -->
        <div class="header-row">
          <h1 class="page-title">Kelola Akun Mahasiswa</h1>
          <div class="header-actions">
            <div class="search-wrapper">
              <i class="fa-solid fa-magnifying-glass"></i>
              <input type="text" id="userSearch" class="search-input" placeholder="Search">
            </div>
            <a href="{{ route('admin.mahasiswas.create') }}" class="btn-action-primary">
              Tambah Mahasiswa
            </a>
          </div>
        </div>

        <div class="admin-hero" style="display: none;">
          <h1>Selamat datang kembali! 👋</h1>
        </div>

        <!-- Stats Cards -->
        <div class="admin-stats-grid">
          <div class="stat-card">
            <div class="stat-card-icon"><i class="fa-solid fa-users"></i></div>
            <div class="stat-card-value" id="statUsers">{{ $stats['mahasiswa'] ?? 0 }}</div>
            <div class="stat-card-label">Total Mahasiswa</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-icon"><i class="fa-solid fa-list-check"></i></div>
            <div class="stat-card-value" id="statTasks">{{ $stats['tasks'] ?? 0 }}</div>
            <div class="stat-card-label">Total Tugas</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-icon"><i class="fa-solid fa-circle-check"></i></div>
            <div class="stat-card-value" id="statDone">{{ $stats['done'] ?? 0 }}</div>
            <div class="stat-card-label">Tugas Selesai</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-icon"><i class="fa-solid fa-chart-line"></i></div>
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
                          <div class="user-avatar-fallback" style="width:34px;height:34px;border-radius:50%;background:var(--pink-light);color:var(--pink-primary);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px">
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
                        <button type="button" class="btn-admin-sm" onclick="openUserDetail({{ $mhs->id }})">Lihat Data</button>
                        <button type="button" class="btn-admin-sm outline" onclick="openEditUser({{ $mhs->id }})">Edit</button>
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
            <div class="panel-head" style="border-bottom: 1px solid var(--border-color); margin-bottom: 0;">
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
        
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
          <a href="#">Dashboard</a>
          <span>/</span>
          <a href="#" style="color: var(--text-main);">Kelola Admin</a>
        </nav>

        <div class="header-row">
          <h1 class="page-title">Kelola Admin</h1>
          <div class="header-actions">
            <button class="btn-action-primary" id="addAdminBtn">
              + Tambah Admin Baru
            </button>
          </div>
        </div>

        <div class="admin-hero" style="display: none;">
          <h1>Kelola Admin 🛡️</h1>
          <p>Kelola data akun administrator yang memiliki akses ke panel TaskMate.</p>
        </div>

        <section class="admin-panel">
          <div class="panel-head">
            <h2>Daftar Administrator</h2>
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
          <p id="detailUserMeta" style="color: var(--text-muted); font-size: 13px; margin-top: 4px;"></p>
        </div>
        <button class="modal-x" id="closeDetailBtn">✕</button>
      </div>
      <div class="admin-modal-body">
        <div id="detailUserProfile" class="detail-profile"></div>
        <div class="detail-actions" style="display: flex; gap: 8px; margin: 20px 0; flex-wrap: wrap;">
          <button class="btn-admin-primary" id="viewBoardBtn">Lihat Dashboard User</button>
          <button class="btn-admin-ghost" id="editUserBtn">Edit Akun</button>
          <button class="btn-admin-danger" id="resetPasswordBtn" style="background-color: #fee2e2; color: #ef4444; border: 1px solid #fecaca;">Reset Password</button>
          <button class="btn-admin-danger" id="deleteUserBtn">Hapus Akun</button>
        </div>

        <div class="task-toolbar" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
          <h4 style="font-weight: 800; font-size: 15px;">Tugas Mahasiswa</h4>
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
        <div class="admin-form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
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
