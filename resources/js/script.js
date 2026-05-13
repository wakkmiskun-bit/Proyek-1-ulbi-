// ═══════════════════════════════════════════
//  LOADER
// ═══════════════════════════════════════════
const loadMsgs = ['Memuat aplikasi...','Menyiapkan board...','Menghubungkan data...','Siap! 🎉'];
let msgIdx = 0;
const statusEl = document.getElementById('loaderStatus');
const msgInterval = setInterval(() => {
  msgIdx++;
  if (msgIdx < loadMsgs.length) statusEl.textContent = loadMsgs[msgIdx];
}, 500);

setTimeout(() => {
  clearInterval(msgInterval);
  document.getElementById('loader').classList.add('hide');
  const app = document.getElementById('app');
  app.classList.add('visible');
  // stagger column animation
  document.querySelectorAll('.list').forEach((l, i) => {
    l.style.opacity = '0';
    l.style.transform = 'translateY(20px)';
    l.style.transition = `opacity .5s ${i*0.1}s ease, transform .5s ${i*0.1}s ease`;
    setTimeout(() => { l.style.opacity='1'; l.style.transform='translateY(0)'; }, 50);
  });
}, 2200);

// ═══════════════════════════════════════════
//  STATE
// ═══════════════════════════════════════════
const COLS = ['todo','doing','review','done'];
const cardStore = new Map(); // el → data object
let activeCard = null;
let activePriority = 'medium';

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

    box.querySelector('.ab-save').onclick = () => {
      const title = box.querySelector('.ab-title').value.trim();
      if (!title) return;
      const priority = box.querySelector('.p-opt.selected')?.dataset.p || 'medium';
      const due = box.querySelector('.ab-due').value;
      createCard(col, title, priority, due);
      box.style.display = 'none';
      box.querySelector('.ab-title').value = '';
      box.querySelector('.ab-due').value = '';
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
function createCard(col, title, priority='medium', due='', checklist=[]) {
  const card = document.createElement('div');
  card.className = 'card';
  const data = { title, desc:'', priority, due, checklist: [...checklist], col };
  cardStore.set(card, data);
  renderCard(card);
  document.getElementById('cards-'+col).appendChild(card);
  updateStats();
  toast(`Card ditambahkan ke ${colLabel(col)}`, '✅');
  return card;
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
  card.querySelector('.card-del-btn').onclick = e => {
    e.stopPropagation();
    card.style.transition = 'opacity .25s, transform .25s';
    card.style.opacity = '0';
    card.style.transform = 'scale(.9)';
    setTimeout(() => { cardStore.delete(card); card.remove(); updateStats(); }, 250);
    toast('Card dihapus', '🗑️');
  };
  // Open modal
  card.onclick = () => openModal(card);
}

function moveCard(card, toCol) {
  const data = cardStore.get(card);
  const fromCol = card.closest('.list').dataset.col;
  if (fromCol === toCol) return;
  // animate out
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
    onEnd(evt) {
      const card = evt.item;
      const newCol = card.closest('.list').dataset.col;
      const data = cardStore.get(card);
      if (data && data.col !== newCol) {
        data.col = newCol;
        renderCard(card);
        toast(`Dipindahkan ke ${colLabel(newCol)}`, moveEmoji(newCol));
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

document.getElementById('modalSaveBtn').onclick = () => {
  if (!activeCard) return;
  const data = cardStore.get(activeCard);
  const newTitle = document.getElementById('mTitle').value.trim();
  if (!newTitle) { toast('Judul tidak boleh kosong', '⚠️'); return; }
  data.title = newTitle;
  data.desc = document.getElementById('mDesc').value.trim();
  data.priority = activePriority;
  data.due = document.getElementById('mDue').value;
  renderCard(activeCard);
  updateStats();
  closeModal();
  toast('Card disimpan', '✅');
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
    row.querySelector('input').onchange = e => {
      item.done = e.target.checked;
      row.classList.toggle('done', item.done);
      if (activeCard) { renderCard(activeCard); updateStats(); }
    };
    row.querySelector('.cl-del').onclick = () => {
      checklist.splice(i, 1);
      renderChecklist(checklist);
      if (activeCard) { renderCard(activeCard); updateStats(); }
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
//  DARK MODE (already dark by default, toggle to light)
// ═══════════════════════════════════════════
let isLight = false;
document.getElementById('darkBtn').onclick = () => {
  isLight = !isLight;
  if (isLight) {
    document.documentElement.style.setProperty('--c0','#f1f5fb');
    document.documentElement.style.setProperty('--c1','#ffffff');
    document.documentElement.style.setProperty('--c2','#f8fafc');
    document.documentElement.style.setProperty('--c3','#e8edf4');
    document.documentElement.style.setProperty('--c4','#d1d9e6');
    document.documentElement.style.setProperty('--border','rgba(0,0,0,0.08)');
    document.documentElement.style.setProperty('--border2','rgba(0,0,0,0.14)');
    document.documentElement.style.setProperty('--text','#111827');
    document.documentElement.style.setProperty('--text2','#4b5563');
    document.documentElement.style.setProperty('--text3','#9ca3af');
    document.getElementById('darkBtn').textContent = '🌙';
  } else {
    document.documentElement.style.setProperty('--c0','#0a0e1a');
    document.documentElement.style.setProperty('--c1','#111827');
    document.documentElement.style.setProperty('--c2','#1c2333');
    document.documentElement.style.setProperty('--c3','#242d3d');
    document.documentElement.style.setProperty('--c4','#2e3a4e');
    document.documentElement.style.setProperty('--border','rgba(255,255,255,0.07)');
    document.documentElement.style.setProperty('--border2','rgba(255,255,255,0.12)');
    document.documentElement.style.setProperty('--text','#e8edf5');
    document.documentElement.style.setProperty('--text2','#8b97b0');
    document.documentElement.style.setProperty('--text3','#5a6880');
    document.getElementById('darkBtn').textContent = '☀️';
  }
};

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
//  SAMPLE DATA
// ═══════════════════════════════════════════
createCard('todo','Riset topik skripsi','high','2025-05-15',[{text:'Cari referensi jurnal',done:true},{text:'Konsultasi dosen',done:false}]);
createCard('todo','Setup repositori GitHub','medium','2025-05-10',[]);
createCard('doing','Prototype UI aplikasi','high','2025-05-08',[{text:'Wireframe halaman utama',done:true},{text:'Desain komponen',done:true},{text:'Review dengan tim',done:false}]);
createCard('doing','Belajar React.js','medium','',[{text:'Hooks & State',done:true},{text:'Context API',done:false}]);
createCard('review','Laporan kemajuan bulanan','medium','2025-05-05',[{text:'Tulis draft',done:true},{text:'Proofreading',done:true}]);
createCard('done','Setup lingkungan pengembangan','low','',[{text:'Install Node.js',done:true},{text:'Install VS Code',done:true}]);
