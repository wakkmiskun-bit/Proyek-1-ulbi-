const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
const boardBase = document.querySelector('meta[name="admin-board-base"]')?.content || '/admin/mahasiswas';
let users = [];
let activeUser = null;
let editingTask = null;

async function apiFetch(url, options = {}) {
  const res = await fetch(url, {
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken,
      'X-Requested-With': 'XMLHttpRequest',
      ...(options.headers || {}),
    },
    ...options,
  });

  const data = await res.json().catch(() => ({}));

  if (!res.ok) {
    let msg = data.message || 'Terjadi kesalahan';
    if (data.errors) {
      msg = Object.values(data.errors).flat().join(' ');
    }
    toast(msg, '⚠️');
    throw new Error(msg);
  }

  if (res.status === 204) return null;
  return data;
}

function toast(msg, icon = 'ℹ️') {
  const wrap = document.getElementById('admin-toast-wrap');
  if (!wrap) return; // Skip toast jika element tidak ada (halaman show/edit)
  const el = document.createElement('div');
  el.className = 'admin-toast';
  el.innerHTML = `<span>${icon}</span><span>${msg}</span>`;
  wrap.appendChild(el);
  setTimeout(() => {
    el.classList.add('out');
    setTimeout(() => el.remove(), 300);
  }, 2800);
}

function esc(s) {
  return String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

function statusLabel(s) {
  return { todo: 'To Do', doing: 'Doing', review: 'Review', done: 'Done' }[s] || s;
}

function priorityLabel(p) {
  return { high: 'Tinggi', medium: 'Sedang', low: 'Rendah' }[p] || p;
}

function formatDate(d) {
  if (!d) return '-';
  return new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

function formatDateTime(d) {
  if (!d) return '-';
  return new Date(d).toLocaleString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}

function userAvatar(u, size = 36) {
  if (u.photo_url) {
    return `<img src="${esc(u.photo_url)}" alt="${esc(u.name)}" class="user-avatar" style="width:${size}px;height:${size}px;border-radius:50%;object-fit:cover">`;
  }
  return `<div class="user-avatar-fallback" style="width:${size}px;height:${size}px;border-radius:50%;background:#e0e7ff;color:#4f46e5;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:${Math.round(size * 0.4)}px">${esc((u.name || '?').charAt(0).toUpperCase())}</div>`;
}

function boardUrl(userId) {
  return `${boardBase}/${userId}/board`;
}

function openModal(id) {
  document.getElementById(id).classList.add('open');
}

function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}

function countByStatus(tasks, status) {
  return (tasks || []).filter(t => t.status === status).length;
}

async function refreshAll() {
  await loadUsers();
  await loadActivities();
}

async function loadActivities() {
  const feed = document.getElementById('activityFeed');
  try {
    const items = await apiFetch('/admin/activities');
    if (!items.length) {
      feed.innerHTML = '<div class="empty-row">Belum ada aktivitas mahasiswa.</div>';
      return;
    }
    feed.innerHTML = items.map(a => `
      <div class="activity-item">
        ${userAvatar(a.user, 32)}
        <div class="activity-body">
          <strong>${esc(a.user.name)}</strong>
          <span>${esc(a.activity_text)}</span>
          <div class="activity-meta">
            <span class="status-pill ${a.status}">${statusLabel(a.status)}</span>
            <span>${a.time}</span>
          </div>
        </div>
      </div>
    `).join('');
  } catch (_) {
    feed.innerHTML = '<div class="empty-row">Gagal memuat aktivitas.</div>';
  }
}

async function loadUsers() {
  users = await apiFetch('/admin/mahasiswas');
  renderUsers(users);
  updateHeroStats();
}

function updateHeroStats() {
  document.getElementById('statUsers').textContent = users.length;
  const totalTasks = users.reduce((sum, u) => sum + (u.tasks_count || 0), 0);
  const doneTasks = users.reduce((sum, u) => sum + (u.done_count || 0), 0);
  document.getElementById('statTasks').textContent = totalTasks;
  document.getElementById('statDone').textContent = doneTasks;
}

function renderUsers(list) {
  const tbody = document.getElementById('usersTableBody');
  if (!list.length) {
    tbody.innerHTML = '<tr><td colspan="12" class="empty-row">Belum ada mahasiswa terdaftar. Data muncul setelah mahasiswa registrasi.</td></tr>';
    return;
  }

  tbody.innerHTML = list.map(u => `
    <tr data-search="${esc([u.nim, u.name, u.email, u.phone || '', u.universitas || ''].join(' ').toLowerCase())}">
      <td>${userAvatar(u, 34)}</td>
      <td><strong>${esc(u.nim)}</strong></td>
      <td>${esc(u.name)}</td>
      <td>${esc(u.email)}</td>
      <td>${esc(u.phone || '-')}</td>
      <td>${esc(u.universitas || '-')}</td>
      <td><span class="status-pill todo">${u.todo_count || 0}</span></td>
      <td><span class="status-pill doing">${u.doing_count || 0}</span></td>
      <td><span class="status-pill review">${u.review_count || 0}</span></td>
      <td><span class="status-pill done">${u.done_count || 0}</span></td>
      <td><strong>${u.tasks_count || 0}</strong></td>
      <td class="action-cell">
        <a href="${boardUrl(u.id)}" class="btn-admin-sm primary">Dashboard</a>
        <a href="${boardBase}/${u.id}" class="btn-admin-sm">Lihat Data</a>
        <a href="${boardBase}/${u.id}/edit" class="btn-admin-sm outline">Edit</a>
      </td>
    </tr>
  `).join('');
}

function renderUserProfile(user) {
  const tasks = user.tasks || [];
  const el = document.getElementById('detailUserProfile');
  el.innerHTML = `
    ${userAvatar(user, 56)}
    <div class="detail-profile-info">
      <h4>${esc(user.name)}</h4>
      <p>NIM: <strong>${esc(user.nim)}</strong><br>
      Email: ${esc(user.email)}<br>
      WhatsApp: ${esc(user.phone || '-')}<br>
      Universitas: ${esc(user.universitas || '-')}<br>
      Terdaftar: ${formatDateTime(user.created_at)}</p>
      <div class="detail-stats-row">
        <span class="detail-stat-pill">To Do: ${countByStatus(tasks, 'todo')}</span>
        <span class="detail-stat-pill">Doing: ${countByStatus(tasks, 'doing')}</span>
        <span class="detail-stat-pill">Review: ${countByStatus(tasks, 'review')}</span>
        <span class="detail-stat-pill">Done: ${countByStatus(tasks, 'done')}</span>
        <span class="detail-stat-pill">Total: ${tasks.length}</span>
      </div>
    </div>
  `;
}

async function openUserDetail(userId) {
  activeUser = await apiFetch(`/admin/mahasiswas/${userId}`);
  document.getElementById('detailUserName').textContent = activeUser.name;
  document.getElementById('detailUserMeta').textContent = `${activeUser.nim} · ${activeUser.email}`;
  renderUserProfile(activeUser);
  renderTasks(activeUser.tasks || []);
  openModal('userDetailModal');
}

function renderTasks(tasks) {
  const tbody = document.getElementById('tasksTableBody');
  if (!tasks.length) {
    tbody.innerHTML = '<tr><td colspan="5" class="empty-row">Mahasiswa ini belum punya tugas.</td></tr>';
    return;
  }

  tbody.innerHTML = tasks.map(t => `
    <tr>
      <td><strong>${esc(t.title)}</strong></td>
      <td><span class="status-pill ${t.status}">${statusLabel(t.status)}</span></td>
      <td><span class="priority-pill ${t.priority}">${priorityLabel(t.priority)}</span></td>
      <td>${formatDate(t.due_date)}</td>
      <td class="action-cell">
        <button type="button" class="btn-admin-sm" data-task-edit="${t.id}">Edit</button>
        <button type="button" class="btn-admin-sm danger" data-task-del="${t.id}">Hapus</button>
      </td>
    </tr>
  `).join('');

  tbody.querySelectorAll('[data-task-edit]').forEach(btn => {
    btn.onclick = () => {
      const task = (activeUser.tasks || []).find(t => t.id === Number(btn.dataset.taskEdit));
      if (task) openTaskModal(task);
    };
  });

  tbody.querySelectorAll('[data-task-del]').forEach(btn => {
    btn.onclick = async () => {
      if (!confirm('Hapus tugas ini dari database?')) return;
      await apiFetch(`/admin/tasks/${btn.dataset.taskDel}`, { method: 'DELETE' });
      toast('Tugas dihapus dari database', '🗑️');
      await openUserDetail(activeUser.id);
      await refreshAll();
    };
  });
}

async function openEditUser(userId) {
  const user = await apiFetch(`/admin/mahasiswas/${userId}`);
  activeUser = user;
  document.getElementById('editNim').value = user.nim;
  document.getElementById('editName').value = user.name;
  document.getElementById('editEmail').value = user.email;
  document.getElementById('editPhone').value = user.phone || '';
  document.getElementById('editUniversitas').value = user.universitas || '';
  document.getElementById('editPassword').value = '';
  document.getElementById('editPasswordConfirm').value = '';
  openModal('editUserModal');
}

function openTaskModal(task = null) {
  editingTask = task;
  document.getElementById('taskModalTitle').textContent = task ? 'Edit Tugas' : 'Tambah Tugas';
  document.getElementById('taskTitle').value = task?.title || '';
  document.getElementById('taskDesc').value = task?.description || '';
  document.getElementById('taskStatus').value = task?.status || 'todo';
  document.getElementById('taskPriority').value = task?.priority || 'medium';
  document.getElementById('taskDue').value = task?.due_date ? String(task.due_date).slice(0, 10) : '';
  openModal('taskModal');
}

function filterUserRows(query) {
  const q = query.toLowerCase().trim();
  const rows = document.querySelectorAll('#usersTableBody tr[data-search]');
  let visible = 0;

  rows.forEach((row) => {
    const match = !q || row.dataset.search.includes(q);
    row.style.display = match ? '' : 'none';
    if (match) visible += 1;
  });

  const emptyRow = document.getElementById('usersTableEmpty');
  if (emptyRow) {
    emptyRow.style.display = rows.length && visible === 0 ? '' : 'none';
  }
}

async function handleDeepLink() {
  // Deep link handling dipindahkan ke halaman terpisah (show.blade.php dan edit.blade.php)
  // Dashboard tidak perlu lagi membaca parameter URL untuk membuka modal
  // Semua navigasi detail dan edit sekarang menggunakan href murni
  return Promise.resolve();
}

// Handle null element untuk halaman show/edit (tidak ada userSearch element di halaman tersebut)
const userSearchElement = document.getElementById('userSearch');
if (userSearchElement) {
  userSearchElement.oninput = function () {
    filterUserRows(this.value);
  };
}

document.querySelectorAll('.admin-overlay').forEach(overlay => {
  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) overlay.classList.remove('open');
  });
});

document.getElementById('closeDetailBtn')?.addEventListener('click', () => closeModal('userDetailModal'));
document.getElementById('closeEditUserBtn')?.addEventListener('click', () => closeModal('editUserModal'));
document.getElementById('cancelEditUserBtn')?.addEventListener('click', () => closeModal('editUserModal'));
document.getElementById('closeTaskBtn')?.addEventListener('click', () => closeModal('taskModal'));
document.getElementById('cancelTaskBtn')?.addEventListener('click', () => closeModal('taskModal'));

document.getElementById('viewBoardBtn')?.addEventListener('click', () => {
  if (activeUser) window.location.href = boardUrl(activeUser.id);
});

document.getElementById('editUserBtn')?.addEventListener('click', async () => {
  if (activeUser) await openEditUser(activeUser.id);
});

document.getElementById('saveUserBtn')?.addEventListener('click', async () => {
  if (!activeUser) return;
  const payload = {
    nim: document.getElementById('editNim').value.trim(),
    name: document.getElementById('editName').value.trim(),
    email: document.getElementById('editEmail').value.trim(),
    phone: document.getElementById('editPhone').value.trim(),
    universitas: document.getElementById('editUniversitas').value.trim(),
  };
  const pwd = document.getElementById('editPassword').value;
  const pwdConfirm = document.getElementById('editPasswordConfirm').value;
  if (pwd) {
    payload.password = pwd;
    payload.password_confirmation = pwdConfirm;
  }

  await apiFetch(`/admin/mahasiswas/${activeUser.id}`, {
    method: 'PUT',
    body: JSON.stringify(payload),
  });

  toast('Data mahasiswa diperbarui di database', '✅');
  closeModal('editUserModal');
  await refreshAll();
});

document.getElementById('deleteUserBtn')?.addEventListener('click', async () => {
  if (!activeUser || !confirm(`Hapus akun ${activeUser.name} beserta semua tugasnya dari database?`)) return;
  await apiFetch(`/admin/mahasiswas/${activeUser.id}`, { method: 'DELETE' });
  toast('Akun mahasiswa dihapus dari database', '🗑️');
  closeModal('userDetailModal');
  activeUser = null;
  await refreshAll();
});

document.getElementById('addTaskBtn')?.addEventListener('click', () => openTaskModal(null));

document.getElementById('saveTaskBtn')?.addEventListener('click', async () => {
  if (!activeUser) return;
  const payload = {
    title: document.getElementById('taskTitle').value.trim(),
    description: document.getElementById('taskDesc').value.trim(),
    status: document.getElementById('taskStatus').value,
    priority: document.getElementById('taskPriority').value,
    due_date: document.getElementById('taskDue').value || null,
    checklist: editingTask?.checklist || [],
  };

  if (!payload.title) {
    toast('Judul tugas wajib diisi', '⚠️');
    return;
  }

  if (editingTask) {
    await apiFetch(`/admin/tasks/${editingTask.id}`, {
      method: 'PUT',
      body: JSON.stringify(payload),
    });
    toast('Tugas diperbarui di database', '✅');
  } else {
    await apiFetch(`/admin/mahasiswas/${activeUser.id}/tasks`, {
      method: 'POST',
      body: JSON.stringify(payload),
    });
    toast('Tugas ditambahkan ke database', '✅');
  }

  closeModal('taskModal');
  await openUserDetail(activeUser.id);
  await refreshAll();
});

refreshAll()
  .then(() => handleDeepLink())
  .catch(() => toast('Gagal memuat data dari database', '⚠️'));

// ==================== ADMIN CRUD & TAB NAVIGATION ====================
let admins = [];
let activeAdmin = null;

// Tab switching
document.querySelectorAll('.admin-sidebar-item[data-nav]').forEach(btn => {
  btn.addEventListener('click', () => {
    const targetNav = btn.dataset.nav;
    
    document.querySelectorAll('.admin-sidebar-item[data-nav]').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    
    document.querySelectorAll('.admin-section').forEach(sec => {
      sec.style.display = 'none';
    });
    
    const targetSection = document.getElementById(`section-${targetNav}`);
    if (targetSection) {
      targetSection.style.display = '';
    }
    
    if (targetNav === 'admins') {
      loadAdmins();
    } else if (targetNav === 'dashboard') {
      refreshAll();
    }
  });
});

async function loadAdmins() {
  const tbody = document.getElementById('adminsTableBody');
  try {
    admins = await apiFetch('/admin/admins');
    renderAdmins(admins);
  } catch (_) {
    tbody.innerHTML = '<tr><td colspan="4" class="empty-row">Gagal memuat data administrator.</td></tr>';
  }
}

function renderAdmins(list) {
  const tbody = document.getElementById('adminsTableBody');
  if (!list.length) {
    tbody.innerHTML = '<tr><td colspan="4" class="empty-row">Belum ada administrator terdaftar.</td></tr>';
    return;
  }

  tbody.innerHTML = list.map(a => `
    <tr>
      <td><strong>${esc(a.nama)}</strong></td>
      <td>${esc(a.email)}</td>
      <td>${formatDateTime(a.created_at)}</td>
      <td class="action-cell">
        <button type="button" class="btn-admin-sm outline" data-admin-edit="${a.id}">Edit</button>
        <button type="button" class="btn-admin-sm danger" data-admin-del="${a.id}">Hapus</button>
      </td>
    </tr>
  `).join('');

  tbody.querySelectorAll('[data-admin-edit]').forEach(btn => {
    btn.onclick = () => {
      const admin = admins.find(a => a.id === Number(btn.dataset.adminEdit));
      if (admin) openAdminModal(admin);
    };
  });

  tbody.querySelectorAll('[data-admin-del]').forEach(btn => {
    btn.onclick = async () => {
      if (!confirm(`Hapus akun administrator ${btn.closest('tr').querySelector('strong').textContent}?`)) return;
      try {
        await apiFetch(`/admin/admins/${btn.dataset.adminDel}`, { method: 'DELETE' });
        toast('Akun administrator berhasil dihapus', '🗑️');
        await loadAdmins();
      } catch (_) {
        // error handled by apiFetch toast
      }
    };
  });
}

function openAdminModal(admin = null) {
  activeAdmin = admin;
  document.getElementById('adminModalTitle').textContent = admin ? 'Edit Akun Admin' : 'Tambah Admin Baru';
  document.getElementById('adminName').value = admin?.nama || '';
  document.getElementById('adminEmail').value = admin?.email || '';
  document.getElementById('adminPassword').value = '';
  document.getElementById('adminPasswordConfirm').value = '';
  openModal('adminModal');
}

document.getElementById('addAdminBtn')?.addEventListener('click', () => openAdminModal(null));
document.getElementById('closeAdminBtn')?.addEventListener('click', () => closeModal('adminModal'));
document.getElementById('cancelAdminBtn')?.addEventListener('click', () => closeModal('adminModal'));

document.getElementById('saveAdminBtn')?.addEventListener('click', async () => {
  const nama = document.getElementById('adminName').value.trim();
  const email = document.getElementById('adminEmail').value.trim();
  const password = document.getElementById('adminPassword').value;
  const passwordConfirm = document.getElementById('adminPasswordConfirm').value;

  if (!nama || !email) {
    toast('Nama dan Email wajib diisi', '⚠️');
    return;
  }

  if (!activeAdmin && !password) {
    toast('Kata sandi wajib diisi untuk admin baru', '⚠️');
    return;
  }

  if (password && password !== passwordConfirm) {
    toast('Konfirmasi kata sandi tidak cocok', '⚠️');
    return;
  }

  const payload = { nama, email };
  if (password) {
    payload.password = password;
    payload.password_confirmation = passwordConfirm;
  }

  try {
    if (activeAdmin) {
      await apiFetch(`/admin/admins/${activeAdmin.id}`, {
        method: 'PUT',
        body: JSON.stringify(payload),
      });
      toast('Data administrator diperbarui', '✅');
    } else {
      await apiFetch('/admin/admins', {
        method: 'POST',
        body: JSON.stringify(payload),
      });
      toast('Administrator baru berhasil ditambahkan', '✅');
    }
    closeModal('adminModal');
    await loadAdmins();
  } catch (_) {
    // error handled by apiFetch toast
  }
});
