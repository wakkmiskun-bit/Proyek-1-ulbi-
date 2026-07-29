// ═══════════════════════════════════════════
//  SMOOTH PAGE TRANSITIONS
// ═══════════════════════════════════════════

document.addEventListener('click', (e) => {
  const link = e.target.closest('a[href]');
  if (link && link.hostname === window.location.hostname && !link.hasAttribute('download')) {
    e.preventDefault();
    const href = link.getAttribute('href');
    document.body.style.animation = 'pageExit 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards';
    setTimeout(() => {
      window.location.href = href;
    }, 600);
  }
});

// Prevent exit animation on form submit and logout
document.addEventListener('submit', (e) => {
  const form = e.target;
  document.body.style.animation = 'pageExit 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards';
});

// ═══════════════════════════════════════════
//  APP INIT
// ═══════════════════════════════════════════
// Stagger column animation on load
window.addEventListener('load', () => {
  document.querySelectorAll('.list').forEach((l, i) => {
    l.style.opacity = '0';
    l.style.transform = 'translateY(20px)';
    l.style.transition = `opacity .5s ${i*0.1}s ease, transform .5s ${i*0.1}s ease`;
    setTimeout(() => { l.style.opacity='1'; l.style.transform='translateY(0)'; }, 50);
  });
});

// ═══════════════════════════════════════════
//  STATE & API
// ═══════════════════════════════════════════
const COLS = ['todo','doing','review','done'];
const cardStore = new Map(); // el → data object
let activeCard = null;
let activePriority = 'medium';
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
const boardConfig = window.TASKMATE_BOARD || {};
const adminUserId = boardConfig.adminUserId || null;

function toast(msg, icon = 'ℹ️') {
  const wrap = document.getElementById('toast-wrap');
  if (!wrap) return;
  const el = document.createElement('div');
  el.className = 'toast';
  el.innerHTML = `<span class="toast-icon">${icon}</span><span class="toast-msg">${msg}</span>`;
  wrap.appendChild(el);
  setTimeout(() => {
    el.style.opacity = '1';
    el.style.transform = 'translateY(0)';
  }, 10);
  setTimeout(() => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(-20px)';
    setTimeout(() => el.remove(), 300);
  }, 3500);
}

function getBaseUrl() {
  const meta = document.querySelector('meta[name="base-url"]');
  if (meta) {
    try {
      const url = new URL(meta.content);
      return url.pathname.replace(/\/$/, '');
    } catch (_) {
      return meta.content.replace(/\/$/, '');
    }
  }
  return '';
}

function getFullUrl(url) {
  if (url.startsWith('http://') || url.startsWith('https://')) {
    return url;
  }
  const base = getBaseUrl();
  const path = url.startsWith('/') ? url : '/' + url;
  return base + path;
}

function taskListUrl() {
  return adminUserId ? `/admin/mahasiswas/${adminUserId}` : '/tasks';
}

function taskCreateUrl() {
  return adminUserId ? `/admin/mahasiswas/${adminUserId}/tasks` : '/tasks';
}

function taskItemUrl(id) {
  return adminUserId ? `/admin/tasks/${id}` : `/tasks/${id}`;
}

function isChecklistComplete(checklist) {
  return Array.isArray(checklist) && checklist.length > 0 && checklist.every(c => c.done);
}

async function maybeAutoCompleteCard(card) {
  const data = cardStore.get(card);
  if (!data?.id || data.col === 'done' || !isChecklistComplete(data.checklist)) return;

  try {
    await apiFetch(taskItemUrl(data.id), {
      method: 'PUT',
      body: JSON.stringify({ ...taskPayload(data), status: 'done' }),
    });
    document.getElementById('cards-done').appendChild(card);
    data.col = 'done';
    renderCard(card);
    updateStats();
    toast('Checklist selesai — tugas otomatis pindah ke Done', '✅');
  } catch (_) {}
}

async function apiFetch(url, options = {}) {
  const fullUrl = getFullUrl(url);
  const res = await fetch(fullUrl, {
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken,
      'X-Requested-With': 'XMLHttpRequest',
      ...(options.headers || {}),
    },
    ...options,
  });

  if (res.status === 403) {
    toast('Akses ditolak — tugas ini bukan milik Anda', '🚫');
    throw new Error('Forbidden');
  }

  if (!res.ok) {
    const err = await res.json().catch(() => ({}));
    toast(err.message || 'Terjadi kesalahan pada server', '⚠️');
    throw new Error(err.message || 'Request failed');
  }

  if (res.status === 204) return null;
  return res.json();
}

function taskPayload(data) {
  return {
    title: data.title,
    mata_kuliah: data.mata_kuliah || null,
    description: data.desc || '',
    status: data.col,
    priority: data.priority,
    due_date: data.due || null,
    checklist: data.checklist || [],
  };
}

// ═══════════════════════════════════════════
//  BUILD ADD-BOX — only for TO DO column
// ═══════════════════════════════════════════
const TODO_BOX_COL = 'todo';
const box = document.getElementById('addbox-' + TODO_BOX_COL);
if (box) {
  box.innerHTML = `
    <input class="ab-title" placeholder="Nama task..." style="margin-bottom:8px">
    <input class="ab-matkul" placeholder="Mata kuliah (opsional)" style="margin-bottom:8px">
    <div class="priority-row">
      <div class="p-opt high" data-p="high">🔴 Tinggi</div>
      <div class="p-opt medium selected" data-p="medium">🟡 Sedang</div>
      <div class="p-opt low" data-p="low">🟢 Rendah</div>
    </div>
    <input class="ab-due" type="date" placeholder="Tenggat" style="margin-bottom:8px">
    <div class="add-box-btns">
      <button class="btn-primary ab-save">Tambah</button>
      <button class="btn-ghost ab-cancel">Batal</button>
    </div>
  `;
  // priority select in add-box
  box.querySelectorAll('.p-opt').forEach(opt => {
    opt.onclick = () => {
      box.querySelectorAll('.p-opt').forEach(o => o.classList.remove('selected'));
      opt.classList.add('selected');
    };
  });
}

// ═══════════════════════════════════════════
//  OPEN / CLOSE ADD-BOX
// ═══════════════════════════════════════════
document.querySelectorAll('.add-btn').forEach(btn => {
  btn.onclick = () => {
    const col = btn.dataset.col;
    document.querySelectorAll('.add-box').forEach(b => b.style.display = 'none');
    const box = document.getElementById('addbox-'+col);
    box.style.display = 'block';
    box.querySelector('.ab-title').focus();

    box.querySelector('.ab-save').onclick = async () => {
      const title = box.querySelector('.ab-title').value.trim();
      if (!title) return;
      const priority = box.querySelector('.p-opt.selected')?.dataset.p || 'medium';
      const due = box.querySelector('.ab-due').value;
      const matkul = box.querySelector('.ab-matkul').value.trim();
      const saveBtn = box.querySelector('.ab-save');
      saveBtn.disabled = true;
      try {
        await createCard(col, title, priority, due, [], matkul);
        box.style.display = 'none';
        box.querySelector('.ab-title').value = '';
        box.querySelector('.ab-due').value = '';
        box.querySelector('.ab-matkul').value = '';
      } finally {
        saveBtn.disabled = false;
      }
    };

    box.querySelector('.ab-cancel').onclick = () => {
      box.style.display = 'none';
      box.querySelector('.ab-title').value = '';
    };

    box.querySelector('.ab-title').onkeydown = e => {
      if (e.key === 'Enter') box.querySelector('.ab-save').click();
      if (e.key === 'Escape') box.querySelector('.ab-cancel').click();
    };
  };
});

// ═══════════════════════════════════════════
//  CREATE CARD
// ═══════════════════════════════════════════
async function createCard(col, title, priority='medium', due='', checklist=[], mata_kuliah='') {
  const targetCol = 'todo';
  const saved = await apiFetch(taskCreateUrl(), {
    method: 'POST',
    body: JSON.stringify({
      ...taskPayload({ title, mata_kuliah: mata_kuliah || null, desc: '', priority, due, checklist, col: targetCol }),
      status: targetCol,
    }),
  });
  const card = mountCard(saved);
  updateStats();
  toast(`Card ditambahkan ke ${colLabel(targetCol)}`, '✅');
  return card;
}

function mountCard(task) {
  const col = task.status;
  const card = document.createElement('div');
  card.className = 'card';
  card.dataset.taskId = task.id;
  const data = {
    id: task.id,
    title: task.title,
    mata_kuliah: task.mata_kuliah || '',
    desc: task.description || '',
    priority: task.priority,
    due: task.due_date ? String(task.due_date).slice(0, 10) : '',
    checklist: Array.isArray(task.checklist) ? [...task.checklist] : [],
    col,
  };
  cardStore.set(card, data);
  renderCard(card);
  const colEl = document.getElementById('cards-'+col);
  if (colEl) colEl.appendChild(card);
  updateStats(true);
  return card;
}

// ═══════════════════════════════════════════
//  LOAD USER TASKS & CHECK REMINDERS
// ═══════════════════════════════════════════
async function loadTasks() {
  let tasks;
  if (adminUserId) {
    const userData = await apiFetch(taskListUrl());
    tasks = userData.tasks || [];
  } else {
    tasks = await apiFetch('/tasks');
  }
  COLS.forEach(col => {
    const el = document.getElementById('cards-'+col);
    if (el) el.innerHTML = '';
  });
  cardStore.clear();
  tasks.forEach(task => mountCard(task));

  // Render home elements
  renderDashboardDeadlineList();
  renderCalendar();
  renderNotificationsFeed();

  // Logika Pengecekan Reminder Otomatis Setelah Memuat Tugas
  if (!adminUserId) {
    try {
      const reminderData = await apiFetch('/tasks-reminders');
      const alertEl = document.getElementById('deadlineAlert');
      const s = reminderData.summary;

      if (alertEl) {
        if (s.total_overdue > 0) {
          alertEl.className = 'deadline-alert danger';
          alertEl.innerHTML = `🔴 <strong>${s.total_overdue} tugas sudah lewat deadline!</strong> Segera kerjakan ya.`;
        } else if (s.total_h2 > 0) {
          alertEl.className = 'deadline-alert warning';
          alertEl.innerHTML = `⏰ <strong>${s.total_h2} tugas deadline H-2!</strong> Jangan lupa dikerjakan — pengingat WhatsApp juga akan dikirim.`;
        } else if (s.total_h5 > 0) {
          alertEl.className = 'deadline-alert info';
          alertEl.innerHTML = `📅 <strong>${s.total_h5} tugas deadline H-5.</strong> Masih ada waktu, yuk mulai dikerjakan!`;
        } else if (s.total_upcoming > 0) {
          alertEl.className = 'deadline-alert warning';
          alertEl.innerHTML = `🟡 Ada ${s.total_upcoming} tugas mendekati deadline.`;
        } else {
          alertEl.className = 'deadline-alert hidden';
          alertEl.innerHTML = '';
        }
      }

      if (s.total_overdue > 0) {
        toast(`Kamu memiliki ${s.total_overdue} tugas yang TELAT!`, '🔴');
      } else if (s.total_h2 > 0) {
        toast(`Ingat! ${s.total_h2} tugas deadline dalam 2 hari — jangan lupa dikerjakan!`, '⏰');
      } else if (s.total_h5 > 0) {
        toast(`${s.total_h5} tugas deadline dalam 5 hari — yuk mulai dikerjakan!`, '📅');
      }
    } catch (error) {
      console.error('Gagal mengambil data pengingat tugas:', error);
    }
  }
}

// ═══════════════════════════════════════════
//  HELPERS
// ═══════════════════════════════════════════
function esc(s) {
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
function colLabel(col) {
  return { todo:'To Do', doing:'Doing', review:'Review', done:'Done' }[col] || col;
}
function moveEmoji(col) {
  return { todo:'📋', doing:'⚡', review:'👁', done:'✅' }[col] || '↗';
}
function priorityLabel(p) {
  return { high:'🔴 Tinggi', medium:'🟡 Sedang', low:'🟢 Rendah' }[p] || p;
}
function buildTrail(curCol) {
  return COLS.map(col => {
    const idx = COLS.indexOf(col);
    const curIdx = COLS.indexOf(curCol);
    const isActive = idx <= curIdx;
    return `<div class="trail-dot ${col} ${isActive?'active':''}"></div>`;
  }).join('');
}

function dueBadge(due) {
  if (!due) return '';
  const d = new Date(due);
  const today = new Date();
  today.setHours(0,0,0,0);
  const dueDay = new Date(d.getFullYear(), d.getMonth(), d.getDate());
  const diff = Math.round((dueDay - today) / 86400000);
  
  let extraClass = '';
  if (diff < 0) {
    extraClass = ' overdue';
  } else if (diff === 0) {
    extraClass = ' today';
  }
  
  const formatted = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
  return `<div class="card-due${extraClass}"><i class="fa-regular fa-calendar" style="font-size:10px"></i> ${formatted}</div>`;
}

function renderCard(card) {
  const data = cardStore.get(card);
  const col = card.closest('.list')?.dataset.col || data.col;
  const done = data.checklist.filter(c=>c.done).length;
  const total = data.checklist.length;
  const pct = total ? Math.round(done/total*100) : 0;
  const dueStr = dueBadge(data.due);

  const colIdx = COLS.indexOf(col);
  // move left/right buttons
  const canLeft = colIdx > 0;
  const canRight = colIdx < COLS.length - 1;

  card.innerHTML = `
    <div class="card-top">
      <div class="card-title">${esc(data.title)}</div>
      <div class="card-actions">
        <div class="move-btns">
          <button class="move-btn move-left" title="Pindah ke kiri" ${canLeft?'':'disabled'}>◀</button>
          <button class="move-btn move-right" title="Pindah ke kanan" ${canRight?'':'disabled'}>▶</button>
        </div>
        <button class="card-del-btn" title="Hapus">✕</button>
      </div>
    </div>
    <div class="priority-badge ${data.priority}">${priorityLabel(data.priority)}</div>
    ${total > 0 ? `<div class="card-progress-wrap">
      <div class="card-progress-row">
        <span class="card-progress-label">Progress</span>
        <span class="card-progress-val">${done}/${total}</span>
      </div>
      <div class="prog-bar"><div class="prog-fill" style="width:${pct}%"></div></div>
    </div>` : ''}
    ${dueStr}
    <div class="move-trail">${buildTrail(col)}</div>
  `;

  // Move left
  card.querySelector('.move-left').onclick = e => {
    e.stopPropagation();
    const curCol = card.closest('.list').dataset.col;
    const idx = COLS.indexOf(curCol);
    if (idx > 0) moveCard(card, COLS[idx-1]);
  };
  // Move right
  card.querySelector('.move-right').onclick = e => {
    e.stopPropagation();
    const curCol = card.closest('.list').dataset.col;
    const idx = COLS.indexOf(curCol);
    if (idx < COLS.length-1) moveCard(card, COLS[idx+1]);
  };
  // Delete
  card.querySelector('.card-del-btn').onclick = async e => {
    e.stopPropagation();
    const data = cardStore.get(card);
    if (!data?.id) return;
    try {
      await apiFetch(taskItemUrl(data.id), { method: 'DELETE' });
      card.style.transition = 'opacity .25s, transform .25s';
      card.style.opacity = '0';
      card.style.transform = 'scale(.9)';
      setTimeout(() => { cardStore.delete(card); card.remove(); updateStats(); }, 250);
      toast('Card dihapus', '🗑️');
    } catch (_) {}
  };
  // Open modal
  card.onclick = () => openModal(card);
}

async function moveCard(card, toCol) {
  const data = cardStore.get(card);
  const fromCol = card.closest('.list').dataset.col;
  if (fromCol === toCol || !data?.id) return;

  try {
    await apiFetch(taskItemUrl(data.id), {
      method: 'PUT',
      body: JSON.stringify({ ...taskPayload(data), status: toCol }),
    });
  } catch (_) {
    return;
  }

  card.style.transition = 'opacity .2s, transform .2s';
  card.style.opacity = '0'; card.style.transform = 'translateX(30px)';
  setTimeout(() => {
    document.getElementById('cards-'+toCol).appendChild(card);
    data.col = toCol;
    card.style.transform = 'translateX(-20px)';
    setTimeout(() => {
      card.style.opacity = '1'; card.style.transform = '';
      renderCard(card);
      updateStats();
      toast(`Dipindahkan ke ${colLabel(toCol)}`, moveEmoji(toCol));
    }, 50);
  }, 200);
}

// ═══════════════════════════════════════════
//  SORTABLE (WITH REFERENCE ERROR PROTECTION)
// ═══════════════════════════════════════════
function initSortable() {
  if (typeof Sortable === 'undefined') return;
  COLS.forEach(col => {
    const el = document.getElementById('cards-'+col);
    if (!el) return;
    new Sortable(el, {
      group: 'shared', animation: 180,
      ghostClass: 'sortable-ghost', dragClass: 'sortable-drag',
      async onEnd(evt) {
        const card = evt.item;
        const newCol = card.closest('.list').dataset.col;
        const data = cardStore.get(card);
        if (data && data.col !== newCol && data.id) {
          const prevCol = data.col;
          data.col = newCol;
          try {
            await apiFetch(taskItemUrl(data.id), {
              method: 'PUT',
              body: JSON.stringify({ ...taskPayload(data), status: newCol }),
            });
            renderCard(card);
            toast(`Dipindahkan ke ${colLabel(newCol)}`, moveEmoji(newCol));
          } catch (_) {
            data.col = prevCol;
            document.getElementById('cards-'+prevCol).appendChild(card);
            renderCard(card);
          }
        }
        updateStats();
      }
    });
  });
}

// Coba jalankan langsung atau tunggu load window
if (typeof Sortable !== 'undefined') {
  initSortable();
} else {
  window.addEventListener('load', initSortable);
}

// ═══════════════════════════════════════════
//  MODAL
// ═══════════════════════════════════════════
const modal = document.getElementById('cardModal');

function openModal(card) {
  activeCard = card;
  const data = cardStore.get(card);
  activePriority = data.priority;

  document.getElementById('mTitle').value = data.title;
  const mkEl = document.getElementById('mMataKuliah');
  if (mkEl) mkEl.value = data.mata_kuliah || '';
  document.getElementById('mDesc').value = data.desc;
  document.getElementById('mDue').value = data.due || '';

  // priority
  document.querySelectorAll('.mp-opt').forEach(b => {
    b.classList.toggle('sel', b.dataset.p === data.priority);
  });

  // move col buttons
  const curCol = card.closest('.list').dataset.col;
  document.querySelectorAll('.move-col-btn').forEach(b => {
    b.classList.toggle('current', b.dataset.col === curCol);
    b.onclick = () => {
      if (b.dataset.col === curCol) return;
      moveCard(activeCard, b.dataset.col);
      closeModal();
    };
  });

  renderChecklist(data.checklist);
  modal.classList.add('open');
  document.getElementById('mTitle').focus();
}

function closeModal() { modal.classList.remove('open'); activeCard = null; }

document.getElementById('modalCloseBtn').onclick = closeModal;
document.getElementById('modalCancelBtn').onclick = closeModal;
modal.onclick = e => { if (e.target === modal) closeModal(); };

// Priority select in modal
document.querySelectorAll('.mp-opt').forEach(btn => {
  btn.onclick = () => {
    activePriority = btn.dataset.p;
    document.querySelectorAll('.mp-opt').forEach(b => b.classList.toggle('sel', b === btn));
  };
});

document.getElementById('modalSaveBtn').onclick = async () => {
  if (!activeCard) return;
  const data = cardStore.get(activeCard);
  const newTitle = document.getElementById('mTitle').value.trim();
  if (!newTitle) { toast('Judul tidak boleh kosong', '⚠️'); return; }
  data.title = newTitle;
  const mkEl = document.getElementById('mMataKuliah');
  data.mata_kuliah = mkEl ? mkEl.value.trim() || null : null;
  data.desc = document.getElementById('mDesc').value.trim();
  data.priority = activePriority;
  data.due = document.getElementById('mDue').value;
  if (isChecklistComplete(data.checklist)) data.col = 'done';

  if (!data.id) return;

  try {
    await apiFetch(taskItemUrl(data.id), {
      method: 'PUT',
      body: JSON.stringify(taskPayload(data)),
    });
    renderCard(activeCard);
    updateStats();
    closeModal();
    toast('Card disimpan', '✅');
  } catch (_) {}
};

// ═══════════════════════════════════════════
//  CHECKLIST in modal
// ═══════════════════════════════════════════
function renderChecklist(checklist) {
  const list = document.getElementById('clList');
  list.innerHTML = '';
  checklist.forEach((item, i) => {
    const row = document.createElement('div');
    row.className = 'cl-item' + (item.done ? ' done' : '');
    row.innerHTML = `
      <input type="checkbox" ${item.done?'checked':''}>
      <span>${esc(item.text)}</span>
      <button class="cl-del">×</button>
    `;
    row.querySelector('input').onchange = async e => {
      item.done = e.target.checked;
      row.classList.toggle('done', item.done);
      if (activeCard) {
        renderCard(activeCard);
        updateStats();
        await maybeAutoCompleteCard(activeCard);
      }
    };
    row.querySelector('.cl-del').onclick = async () => {
      checklist.splice(i, 1);
      renderChecklist(checklist);
      if (activeCard) {
        renderCard(activeCard);
        updateStats();
        await maybeAutoCompleteCard(activeCard);
      }
    };
    list.appendChild(row);
  });
}

document.getElementById('clAddBtn').onclick = addClItem;
document.getElementById('clInput').onkeydown = e => { if (e.key === 'Enter') addClItem(); };

function addClItem() {
  if (!activeCard) return;
  const inp = document.getElementById('clInput');
  const text = inp.value.trim();
  if (!text) return;
  const data = cardStore.get(activeCard);
  data.checklist.push({ text, done: false });
  inp.value = '';
  renderChecklist(data.checklist);
  renderCard(activeCard);
  updateStats();
}

// ═══════════════════════════════════════════
//  SEARCH
// ═══════════════════════════════════════════
document.getElementById('searchInput').oninput = function() {
  const q = this.value.toLowerCase().trim();
  cardStore.forEach((data, card) => {
    const match = !q || data.title.toLowerCase().includes(q) || data.desc.toLowerCase().includes(q);
    card.style.display = match ? '' : 'none';
  });
};

// ═══════════════════════════════════════════
//  THEME (default: light / professional)
// ═══════════════════════════════════════════
let isLight = true;

function applyTheme() {
  const root = document.documentElement;
  const btn = document.getElementById('darkBtn');
  const mobBtn = document.getElementById('mobileDarkBtn');
  if (isLight) {
    root.style.setProperty('--c0','#f8fafc');
    root.style.setProperty('--c1','#ffffff');
    root.style.setProperty('--c2','#f1f5f9');
    root.style.setProperty('--c3','#e2e8f0');
    root.style.setProperty('--c4','#cbd5e1');
    root.style.setProperty('--border','rgba(0, 0, 0, 0.06)');
    root.style.setProperty('--border2','rgba(0, 0, 0, 0.1)');
    root.style.setProperty('--text','#0f172a');
    root.style.setProperty('--text2','#475569');
    root.style.setProperty('--text3','#94a3b8');
    if (btn) btn.textContent = '🌙';
    if (mobBtn) mobBtn.textContent = '🌙';
  } else {
    root.style.setProperty('--c0','#121212');
    root.style.setProperty('--c1','#1e1e1e');
    root.style.setProperty('--c2','#2d2d2d');
    root.style.setProperty('--c3','#3d3d3d');
    root.style.setProperty('--c4','#4d4d4d');
    root.style.setProperty('--border','rgba(255, 255, 255, 0.08)');
    root.style.setProperty('--border2','rgba(255, 255, 255, 0.12)');
    root.style.setProperty('--text','#f5f5f7');
    root.style.setProperty('--text2','#a1a1a6');
    root.style.setProperty('--text3','#86868b');
    if (btn) btn.textContent = '☀️';
    if (mobBtn) mobBtn.textContent = '☀️';
  }
}

const toggleTheme = () => {
  isLight = !isLight;
  applyTheme();
};

const darkBtn = document.getElementById('darkBtn');
if (darkBtn) darkBtn.onclick = toggleTheme;

const mobileDarkBtn = document.getElementById('mobileDarkBtn');
if (mobileDarkBtn) mobileDarkBtn.onclick = toggleTheme;

applyTheme();


// ═══════════════════════════════════════════
//  STUDENT SIDEBAR TAB SWITCHING
// ═══════════════════════════════════════════
document.querySelectorAll('.sidebar-menu .menu-item').forEach(btn => {
  btn.onclick = () => {
    const tabId = btn.dataset.tab;
    document.querySelectorAll('.sidebar-menu .menu-item').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    
    document.querySelectorAll('.student-tab-section').forEach(sec => sec.classList.remove('active'));
    const targetSection = document.getElementById('tab-' + tabId);
    if (targetSection) targetSection.classList.add('active');
    
    // Sync with mobile bottom navigation active state
    document.querySelectorAll('.mobile-bottom-nav .mob-nav-tab').forEach(b => {
      b.classList.toggle('active', b.dataset.tab === tabId);
    });
    
    if (tabId === 'calendar') {
      renderCalendar();
    }
    
    // Close sidebar drawer if open on mobile
    const sidebar = document.querySelector('.student-sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    if (sidebar) sidebar.classList.remove('open');
    if (overlay) overlay.classList.remove('open');
  };
});

document.querySelector('[data-tab-trigger="notifications"]')?.addEventListener('click', () => {
  const menuBtn = document.querySelector('.sidebar-menu .menu-item[data-tab="notifications"]');
  if (menuBtn) menuBtn.click();
});

// ═══════════════════════════════════════════
//  MOBILE NAV COMPATIBILITY
// ═══════════════════════════════════════════
function switchMobileCol(col) {
  document.querySelectorAll('.list').forEach(l => l.classList.remove('mobile-active'));
  const targetCol = document.querySelector(`.list[data-col="${col}"]`);
  if (targetCol) targetCol.classList.add('mobile-active');
  document.querySelectorAll('.mob-col-tab').forEach(b => {
    b.classList.toggle('active', b.dataset.col === col);
  });
}

document.querySelectorAll('.mob-col-tab').forEach(btn => {
  btn.onclick = () => switchMobileCol(btn.dataset.col);
});

// Initialize mobile column view
switchMobileCol('todo');

// Mobile bottom nav click handler to switch main tabs
document.querySelectorAll('.mobile-bottom-nav .mob-nav-tab').forEach(btn => {
  btn.onclick = () => {
    const tabId = btn.dataset.tab;
    const sidebarBtn = document.querySelector(`.sidebar-menu .menu-item[data-tab="${tabId}"]`);
    if (sidebarBtn) sidebarBtn.click();
  };
});

// ═══════════════════════════════════════════
//  MOBILE SIDEBAR DRAWER TOGGLE
// ═══════════════════════════════════════════
const mobileMenuToggleBtn = document.getElementById('mobileMenuToggleBtn');
const studentSidebar = document.querySelector('.student-sidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');

if (mobileMenuToggleBtn && studentSidebar && sidebarOverlay) {
  mobileMenuToggleBtn.onclick = () => {
    studentSidebar.classList.toggle('open');
    sidebarOverlay.classList.toggle('open');
  };
  
  sidebarOverlay.onclick = () => {
    studentSidebar.classList.remove('open');
    sidebarOverlay.classList.remove('open');
  };
}

// ═══════════════════════════════════════════
//  STATS & PROGRESS RING UPDATING
// ═══════════════════════════════════════════
function updateStats(skipWidgets = false) {
  let counts = { todo: 0, doing: 0, review: 0, done: 0 };
  cardStore.forEach((data, card) => {
    if (card.isConnected) {
      const col = card.closest('.list')?.dataset.col;
      if (col) counts[col]++;
    }
  });

  COLS.forEach(col => {
    // 1. Column header count
    const listCountEl = document.querySelector(`.list[data-col="${col}"] .col-count`);
    if (listCountEl) listCountEl.textContent = counts[col];
    
    // 2. Navbar stats (admin mode)
    const navCountEl = document.querySelector(`[data-count="${col}"]`);
    if (navCountEl) navCountEl.textContent = counts[col];
    
    // 3. Stat chips (student mode)
    const s = document.getElementById('s-'+col);
    if (s) s.textContent = counts[col];
  });

  const total = Object.values(counts).reduce((a, b) => a + b, 0);
  const activeCount = counts.todo + counts.doing + counts.review;
  const doneCount = counts.done;
  const donePct = total ? Math.round(doneCount / total * 100) : 0;

  // Mini ring compat
  const ringFill = document.getElementById('ringFill');
  if (ringFill) {
    const circ = 62.8;
    ringFill.style.strokeDashoffset = circ - (circ * donePct / 100);
  }
  const ringPct = document.getElementById('ringPct');
  if (ringPct) ringPct.textContent = donePct + '%';

  // Dashboard Home stats
  const statActiveVal = document.getElementById('statActiveVal');
  if (statActiveVal) statActiveVal.textContent = activeCount;

  const statCompletedVal = document.getElementById('statCompletedVal');
  if (statCompletedVal) statCompletedVal.textContent = doneCount;

  const statDoneTotalText = document.getElementById('statDoneTotalText');
  if (statDoneTotalText) statDoneTotalText.textContent = `${doneCount} / ${total}`;

  const progressPctVal = document.getElementById('progressPctVal');
  if (progressPctVal) progressPctVal.textContent = `${donePct}%`;

  const progressPctRing = document.getElementById('progressPctRing');
  if (progressPctRing) {
    const circ = 88;
    progressPctRing.style.strokeDashoffset = circ - (circ * donePct / 100);
  }

  // Refresh home page elements dynamically
  if (!skipWidgets) {
    renderDashboardDeadlineList();
    renderCalendar();
    renderNotificationsFeed();
  }
}

// ═══════════════════════════════════════════
//  DASHBOARD HOME DYNAMIC WIDGETS
// ═══════════════════════════════════════════
function renderDashboardDeadlineList() {
  const listEl = document.getElementById('dashboardDeadlineList');
  if (!listEl) return;

  const tasks = [];
  cardStore.forEach(data => {
    if (data.col !== 'done' && data.due) {
      tasks.push(data);
    }
  });

  tasks.sort((a, b) => new Date(a.due) - new Date(b.due));
  const upcoming = tasks.slice(0, 5);

  if (upcoming.length === 0) {
    listEl.innerHTML = '<div class="empty-state-dashboard">Tidak ada tugas mendekati deadline.</div>';
    return;
  }

  listEl.innerHTML = upcoming.map(t => {
    const d = new Date(t.due);
    const today = new Date();
    today.setHours(0,0,0,0);
    const dueDay = new Date(d.getFullYear(), d.getMonth(), d.getDate());
    const diff = Math.round((dueDay - today) / 86400000);
    const badgeClass = diff <= 2 ? 'badge-danger' : 'badge-warning';
    const badgeText = diff < 0 ? 'Terlambat ' + Math.abs(diff) + ' hari' : (diff === 0 ? 'Hari ini' : (diff === 1 ? 'Besok' : 'Sisa ' + diff + ' hari'));

    return `
      <div class="deadline-task-item" data-task-id="${t.id}" style="cursor: pointer;" onclick="openDeadlineTaskModal(${t.id}, event)">
        <div class="task-info-left">
          <input type="checkbox" onclick="event.stopPropagation(); quickCompleteTask(${t.id}, this)" class="task-chk">
          <span class="task-title-text">${esc(t.title)}</span>
        </div>
        <div class="task-info-right">
          <span class="task-due-text">Deadline: ${d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })}</span>
          <span class="task-due-badge ${badgeClass}">${badgeText}</span>
        </div>
      </div>
    `;
  }).join('');
}

window.quickCompleteTask = async function(taskId, checkbox) {
  let foundCard = null;
  let foundData = null;
  cardStore.forEach((data, card) => {
    if (data.id === Number(taskId)) {
      foundCard = card;
      foundData = data;
    }
  });

  if (!foundCard || !foundData) return;
  if (checkbox) checkbox.disabled = true;

  try {
    await apiFetch(taskItemUrl(foundData.id), {
      method: 'PUT',
      body: JSON.stringify({ ...taskPayload(foundData), status: 'done' }),
    });

    document.getElementById('cards-done').appendChild(foundCard);
    foundData.col = 'done';
    renderCard(foundCard);
    updateStats();
    renderDashboardDeadlineList();
    renderCalendar();
    renderNotificationsFeed();
    toast('Tugas diselesaikan!', '✅');
  } catch (error) {
    if (checkbox) {
      checkbox.checked = false;
      checkbox.disabled = false;
    }
  }
};

window.openDeadlineTaskModal = function(taskId, event) {
  if (event && event.target && (event.target.classList.contains('task-chk') || event.target.tagName === 'INPUT')) return;
  
  let foundCard = null;
  cardStore.forEach((data, card) => {
    if (data.id === Number(taskId)) {
      foundCard = card;
    }
  });

  if (foundCard) {
    openModal(foundCard);
  }
};

// ═══════════════════════════════════════════
//  CALENDAR GENERATOR
// ═══════════════════════════════════════════
let calCurrentDate = new Date();

function renderCalendar() {
  const cellsEl = document.getElementById('calendarCells');
  const titleEl = document.getElementById('calendarMonthTitle');
  if (!cellsEl || !titleEl) return;

  const year = calCurrentDate.getFullYear();
  const month = calCurrentDate.getMonth();

  const monthsStr = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  titleEl.textContent = `${monthsStr[month]} ${year}`;
  cellsEl.innerHTML = '';

  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();

  for (let i = 0; i < firstDay; i++) {
    const emptyCell = document.createElement('div');
    emptyCell.className = 'calendar-cell empty';
    cellsEl.appendChild(emptyCell);
  }

  const today = new Date();

  for (let day = 1; day <= daysInMonth; day++) {
    const cellDateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    const cell = document.createElement('div');
    const isToday = today.getDate() === day && today.getMonth() === month && today.getFullYear() === year;
    cell.className = `calendar-cell ${isToday ? 'today' : ''}`;

    const tasksOnDay = [];
    cardStore.forEach(data => {
      if (data.due === cellDateStr) {
        tasksOnDay.push(data);
      }
    });

    const tasksHtml = tasksOnDay.map(t => `
      <div class="calendar-task-tag ${t.col}" style="cursor: pointer;" onclick="openDeadlineTaskModal(${t.id}, event)" title="${esc(t.title)}">${esc(t.title)}</div>
    `).join('');

    cell.innerHTML = `
      <span class="calendar-cell-num">${day}</span>
      <div class="calendar-tasks-container">${tasksHtml}</div>
    `;
    cellsEl.appendChild(cell);
  }
}

document.getElementById('prevMonthBtn')?.addEventListener('click', () => {
  calCurrentDate.setMonth(calCurrentDate.getMonth() - 1);
  renderCalendar();
});
document.getElementById('nextMonthBtn')?.addEventListener('click', () => {
  calCurrentDate.setMonth(calCurrentDate.getMonth() + 1);
  renderCalendar();
});

// ═══════════════════════════════════════════
//  NOTIFICATIONS FEED
// ═══════════════════════════════════════════
function renderNotificationsFeed() {
  const feedEl = document.getElementById('notificationsFeed');
  if (!feedEl) return;

  const notifications = [];
  const today = new Date();
  today.setHours(0,0,0,0);

  cardStore.forEach(data => {
    if (data.col === 'done' || !data.due) return;

    const d = new Date(data.due);
    const dueDay = new Date(d.getFullYear(), d.getMonth(), d.getDate());
    const diff = Math.round((dueDay - today) / 86400000);

    if (diff < 0) {
      notifications.push({
        taskId: data.id,
        type: 'red',
        icon: '⚠️',
        title: `Tugas Telat: ${data.title}`,
        desc: `Tugas ini sudah melewati tenggat waktu selama ${Math.abs(diff)} hari! Segera selesaikan.`,
        time: `Deadline: ${d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}`
      });
    } else if (diff <= 2) {
      notifications.push({
        taskId: data.id,
        type: 'orange',
        icon: '⏰',
        title: `Tenggat Sangat Dekat (H-${diff}): ${data.title}`,
        desc: `Tugas mendekati deadline dalam ${diff} hari. Jangan lupa dikerjakan ya!`,
        time: `Deadline: ${d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long' })}`
      });
    } else if (diff <= 5) {
      notifications.push({
        taskId: data.id,
        type: 'blue',
        icon: '📅',
        title: `Tenggat Mendekati (H-${diff}): ${data.title}`,
        desc: `Tugas ini memiliki sisa waktu ${diff} hari untuk diselesaikan.`,
        time: `Deadline: ${d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long' })}`
      });
    }
  });

  if (notifications.length === 0) {
    feedEl.innerHTML = '<div class="empty-state-dashboard">Semua aman! Tidak ada peringatan tugas mendekati deadline.</div>';
    return;
  }

  feedEl.innerHTML = notifications.map(n => `
    <div class="feed-item" style="cursor: pointer;" onclick="openDeadlineTaskModal(${n.taskId}, event)">
      <div class="feed-icon ${n.type}">${n.icon}</div>
      <div class="feed-body">
        <div class="feed-title">${esc(n.title)}</div>
        <div class="feed-desc">${esc(n.desc)}</div>
        <div class="feed-time"><i class="fa-regular fa-calendar" style="margin-right: 4px;"></i> ${n.time}</div>
      </div>
    </div>
  `).join('');
}

// Pemicu pemuatan awal sudah dideklarasikan di bawah.

// Trigger load
loadTasks().catch(() => toast('Gagal memuat tugas dari server', '⚠️'));