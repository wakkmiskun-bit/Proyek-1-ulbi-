<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Detail Mahasiswa - TaskMate Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,400&display=swap" rel="stylesheet">
  @vite(['resources/css/admin.css', 'resources/js/admin.js'])
  <style>
    .detail-container { max-width: 1000px; margin: 24px auto; padding: 0 16px; }
    .detail-breadcrumb { display: flex; gap: 8px; align-items: center; margin-bottom: 24px; font-size: 14px; }
    .detail-breadcrumb a { color: #e91e63; text-decoration: none; }
    .detail-breadcrumb a:hover { text-decoration: underline; }
    .detail-header { display: flex; gap: 24px; align-items: flex-start; margin-bottom: 32px; padding: 24px; background: var(--surface2, #faf4f0); border-radius: 12px; border: 1px solid var(--border); }
    .detail-avatar { flex-shrink: 0; }
    .detail-avatar img { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary, #e91e63); }
    .detail-avatar-fallback { width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #e91e63, #f06292); color: white; display: flex; align-items: center; justify-content: center; font-size: 48px; font-weight: 700; border: 3px solid var(--primary, #e91e63); }
    .detail-info { flex: 1; }
    .detail-info h1 { margin: 0 0 8px 0; font-size: 24px; font-weight: 700; color: var(--text); }
    .detail-info p { margin: 4px 0; color: var(--text2); }
    .detail-info .badge { display: inline-block; padding: 4px 12px; background: rgba(233, 30, 99, 0.1); color: #e91e63; border-radius: 20px; font-size: 12px; font-weight: 600; margin-top: 8px; }
    .detail-actions { display: flex; gap: 8px; }
    .btn-back { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: var(--surface2); color: var(--text2); border: 1px solid var(--border); border-radius: 6px; text-decoration: none; font-size: 14px; cursor: pointer; font-weight: 500; }
    .btn-back:hover { background: var(--border); }
    .btn-edit { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #e91e63; color: white; border: 1px solid #e91e63; border-radius: 6px; text-decoration: none; font-size: 14px; cursor: pointer; font-weight: 500; }
    .btn-edit:hover { background: #c2185b; }
    .detail-grid { display: grid; gap: 24px; }
    .detail-section { background: var(--surface2, #faf4f0); padding: 24px; border-radius: 12px; border: 1px solid var(--border); }
    .detail-section h3 { margin: 0 0 16px 0; font-size: 18px; font-weight: 600; display: flex; gap: 8px; align-items: center; color: var(--text); }
    .detail-section .info-row { display: grid; grid-template-columns: 200px 1fr; gap: 16px; margin-bottom: 16px; align-items: start; }
    .detail-section .info-label { font-weight: 600; color: var(--text2); font-size: 14px; }
    .detail-section .info-value { font-size: 15px; color: var(--text); word-break: break-word; }
    .activities-list { list-style: none; padding: 0; margin: 0; }
    .activity-item { padding: 12px; border-left: 3px solid #e91e63; background: white; margin-bottom: 8px; border-radius: 4px; border-top: 1px solid var(--border); border-right: 1px solid var(--border); border-bottom: 1px solid var(--border); }
    .activity-item strong { color: var(--text); }
    .activity-item .text { color: var(--text2); font-size: 14px; }
    .activity-item .time { color: var(--text3); font-size: 12px; margin-top: 4px; }
    .tasks-table { width: 100%; border-collapse: collapse; }
    .tasks-table th { padding: 12px; text-align: left; font-weight: 600; background: var(--surface2); border-bottom: 2px solid var(--border); font-size: 14px; color: var(--text); }
    .tasks-table td { padding: 12px; border-bottom: 1px solid var(--border); color: var(--text); }
    .tasks-table .task-title { font-weight: 500; }
    .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .status-badge.todo { background: rgba(233, 30, 99, 0.1); color: #e91e63; }
    .status-badge.doing { background: rgba(217, 119, 6, 0.1); color: #d97706; }
    .status-badge.review { background: rgba(236, 72, 153, 0.1); color: #ec4899; }
    .status-badge.done { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .priority-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .priority-badge.high { background: #fee2e2; color: #991b1b; }
    .priority-badge.medium { background: #fef3c7; color: #92400e; }
    .priority-badge.low { background: #dcfce7; color: #166534; }
    .empty-state { padding: 32px 24px; text-align: center; color: var(--text3); }
    .empty-state svg { width: 48px; height: 48px; margin-bottom: 16px; opacity: 0.5; }
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

  <div class="detail-container">
    <!-- Breadcrumb -->
    <div class="detail-breadcrumb">
      <a href="{{ route('admin.dashboard') }}">Dashboard</a>
      <span>/</span>
      <span>Daftar Mahasiswa</span>
      <span>/</span>
      <span>{{ $mahasiswa->nama }}</span>
    </div>

    <!-- Header -->
    <div class="detail-header">
      <div class="detail-avatar">
        @if ($mahasiswa->photo_url)
          <img src="{{ $mahasiswa->photo_url }}" alt="{{ $mahasiswa->nama }}">
        @else
          <div class="detail-avatar-fallback">
            {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
          </div>
        @endif
      </div>
      <div class="detail-info" style="flex: 1;">
        <h1>{{ $mahasiswa->nama }}</h1>
        <p><strong>NIM:</strong> {{ $mahasiswa->nim }}</p>
        <p><strong>Email:</strong> {{ $mahasiswa->email }}</p>
        <p><strong>WhatsApp:</strong> {{ $mahasiswa->phone ?? '-' }}</p>
        <p><strong>Universitas:</strong> {{ $mahasiswa->universitas ?? '-' }}</p>
        <p><strong>Terdaftar:</strong> {{ $mahasiswa->created_at->format('d M Y, H:i') }}</p>
        <span class="badge">Mahasiswa Aktif</span>
      </div>
      <div class="detail-actions">
        <a href="{{ route('admin.dashboard') }}" class="btn-back">← Kembali</a>
        <a href="{{ route('admin.mahasiswas.edit', $mahasiswa->id) }}" class="btn-edit">✎ Edit</a>
      </div>
    </div>

    <!-- Detail Grid -->
    <div class="detail-grid">
      <!-- Statistik Tugas -->
      <div class="detail-section">
        <h3>📊 Statistik Tugas</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px;">
          <div style="padding: 16px; background: white; border-radius: 8px; text-align: center;">
            <div style="font-size: 28px; font-weight: 700; color: #fbbf24;">{{ $mahasiswa->tasks()->where('status', 'todo')->count() }}</div>
            <div style="font-size: 12px; color: #666; margin-top: 4px;">To Do</div>
          </div>
          <div style="padding: 16px; background: white; border-radius: 8px; text-align: center;">
            <div style="font-size: 28px; font-weight: 700; color: #60a5fa;">{{ $mahasiswa->tasks()->where('status', 'doing')->count() }}</div>
            <div style="font-size: 12px; color: #666; margin-top: 4px;">Doing</div>
          </div>
          <div style="padding: 16px; background: white; border-radius: 8px; text-align: center;">
            <div style="font-size: 28px; font-weight: 700; color: #c084fc;">{{ $mahasiswa->tasks()->where('status', 'review')->count() }}</div>
            <div style="font-size: 12px; color: #666; margin-top: 4px;">Review</div>
          </div>
          <div style="padding: 16px; background: white; border-radius: 8px; text-align: center;">
            <div style="font-size: 28px; font-weight: 700; color: #34d399;">{{ $mahasiswa->tasks()->where('status', 'done')->count() }}</div>
            <div style="font-size: 12px; color: #666; margin-top: 4px;">Done</div>
          </div>
        </div>
      </div>

      <!-- Daftar Tugas -->
      <div class="detail-section">
        <h3>📋 Daftar Tugas Mahasiswa</h3>
        @if ($mahasiswa->tasks->isNotEmpty())
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
                  <td class="task-title">{{ $task->title }}</td>
                  <td>
                    <span class="status-badge {{ $task->status }}">
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
      <div class="detail-section">
        <h3>🕐 Aktivitas Terbaru</h3>
        @if ($activities->isNotEmpty())
          <ul class="activities-list">
            @foreach ($activities as $activity)
              <li class="activity-item">
                <strong>{{ $activity->activity_text }}</strong>
                <div class="text">
                  <span class="status-badge {{ $activity->status_tugas }}" style="display: inline-block; margin-top: 4px;">
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
                <div class="time">{{ $activity->created_at->diffForHumans() }}</div>
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
  </div>
</body>
</html>
