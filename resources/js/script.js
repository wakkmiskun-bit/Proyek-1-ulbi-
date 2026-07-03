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
  const res = await fetch(url, {
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
    description: data.desc || '',
    status: data.col,
    priority: data.priority,
    due_date: data.due || null,
    checklist: data.checklist || [],
  };
}

// ═══════════════════════════════════════════
//  BUILD ADD-BOX for each column
// ═══════════════════════════════════════════
COLS.forEach(col => {
  const box = document.getElementById('addbox-'+col);
  box.innerHTML = `
    <input class="ab-title" placeholder="Nama task..." style="margin-bottom:8px">
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
});

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
      const btn = box.querySelector('.ab-save');
      btn.disabled = true;
      try {
        await createCard(col, title, priority, due);
        box.style.display = 'none';
        box.querySelector('.ab-title').value = '';
        box.querySelector('.ab-due').value = '';
      } finally {
        btn.disabled = false;
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
async function createCard(col, title, priority='medium', due='', checklist=[]) {
  const saved = await apiFetch(taskCreateUrl(), {
    method: 'POST',
    body: JSON.stringify({
      ...taskPayload({ title, desc: '', priority, due, checklist, col }),
      status: col,
    }),
  });
  const card = mountCard(saved);
  toast(`Card ditambahkan ke ${colLabel(col)}`, '✅');
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
    desc: task.description || '',
    priority: task.priority,
    due: task.due_date ? String(task.due_date).slice(0, 10) : '',
    checklist: Array.isArray(task.checklist) ? [...task.checklist] : [],
    col,
  };
  cardStore.set(card, data);
  renderCard(card);
  document.getElementById('cards-'+col).appendChild(card);
  updateStats();
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
    document.getElementById('cards-'+col).innerHTML = '';
  });
  cardStore.clear();
  tasks.forEach(task => mountCard(task));

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
//  SORTABLE
// ═══════════════════════════════════════════
COLS.forEach(col => {
  new Sortable(document.getElementById('cards-'+col), {
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

// ═══════════════════════════════════════════
//  MODAL
// ═══════════════════════════════════════════
const modal = document.getElementById('cardModal');

function openModal(card) {
  activeCard = card;
  const data = cardStore.get(card);
  activePriority = data.priority;

  document.getElementById('mTitle').value = data.title;
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
  if (isLight) {
    root.style.setProperty('--c0','#fbf7f4');
    root.style.setProperty('--c1','#ffffff');
    root.style.setProperty('--c2','#faf4f0');
    root.style.setProperty('--c3','#f2e5dc');
    root.style.setProperty('--c4','#e8d6cb');
    root.style.setProperty('--border','rgba(61, 43, 39, 0.08)');
    root.style.setProperty('--border2','rgba(61, 43, 39, 0.12)');
    root.style.setProperty('--text','#3d2b27');
    root.style.setProperty('--text2','#705953');
    root.style.setProperty('--text3','#a8938e');
    if (btn) btn.textContent = '🌙';
  } else {
    root.style.setProperty('--c0','#1c1412');
    root.style.setProperty('--c1','#251b18');
    root.style.setProperty('--c2','#2f2320');
    root.style.setProperty('--c3','#3a2b27');
    root.style.setProperty('--c4','#463531');
    root.style.setProperty('--border','rgba(251, 247, 244, 0.08)');
    root.style.setProperty('--border2','rgba(251, 247, 244, 0.12)');
    root.style.setProperty('--text','#fbf7f4');
    root.style.setProperty('--text2','#c3b4b0');
    root.style.setProperty('--text3','#8a7672');
    if (btn) btn.textContent = '☀️';
  }
}

document.getElementById('darkBtn').onclick = () => {
  isLight = !isLight;
  applyTheme();
};

applyTheme();

// ═══════════════════════════════════════════
//  MOBILE NAV
// ═══════════════════════════════════════════
function switchMobileCol(col) {
  document.querySelectorAll('.list').forEach(l => l.classList.remove('mobile-active'));
  document.querySelector(`.list[data-col="${col}"]`).classList.add('mobile-active');
  document.querySelectorAll('.mob-nav-tab, .mob-col-tab').forEach(b => {
    b.classList.toggle('active', b.dataset.col === col);
  });
}

document.querySelectorAll('.mob-nav-tab, .mob-col-tab').forEach(btn => {
  btn.onclick = () => switchMobileCol(btn.dataset.col);
});

// ═══════════════════════════════════════════
//  STATS & RING
// ═══════════════════════════════════════════
function updateStats() {
  let counts = { todo:0, doing:0, review:0, done:0 };
  cardStore.forEach((data, card) => {
    if (card.isConnected) {
      const col = card.closest('.list')?.dataset.col;
      if (col) counts[col]++;
    }
  });
  COLS.forEach(col => {
    document.querySelector(`[data-count="${col}"]`).textContent = counts[col];
    const s = document.getElementById('s-'+col);
    if (s) s.textContent = counts[col];
  });
  const total = Object.values(counts).reduce((a,b)=>a+b, 0);
  const donePct = total ? Math.round(counts.done / total * 100) : 0;
  const circ = 62.8;
  document.getElementById('ringFill').style.strokeDashoffset = circ - (circ * donePct / 100);
  document.getElementById('ringPct').textContent = donePct + '%';
}

// ═══════════════════════════════════════════
//  TOAST
// ═══════════════════════════════════════════
function toast(msg, icon='ℹ️') {
  const wrap = document.getElementById('toast-wrap');
  const el = document.createElement('div');
  el.className = 'toast';
  el.innerHTML = `<span class="toast-icon">${icon}</span><span>${msg}</span>`;
  wrap.appendChild(el);
  setTimeout(() => {
    el.classList.add('out');
    setTimeout(() => el.remove(), 300);
  }, 2800);
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
  const d = new Date(due); const now = new Date();
  const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
  const dueDay = new Date(d.getFullYear(), d.getMonth(), d.getDate());
  const diff = Math.round((dueDay - today) / 86400000);
  let cls='', txt='';
  if (diff < 0) { cls='overdue'; txt='Terlambat '+Math.abs(diff)+' hari'; }
  else if (diff === 0) { cls='today'; txt='Hari ini'; }
  else if (diff === 1) { cls='today'; txt='Besok'; }
  else { txt=d.toLocaleDateString('id-ID',{day:'numeric',month:'short'}); }
  return `<div class="card-due ${cls}"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>${txt}</div>`;
}

// ═══════════════════════════════════════════
//  LOAD USER TASKS FROM DATABASE
// ═══════════════════════════════════════════
loadTasks().catch(() => toast('Gagal memuat tugas dari server', '⚠️'));