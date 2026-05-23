// ===== THEME =====
const html = document.documentElement;
const saved = localStorage.getItem('phpdts-theme');
if (saved) html.setAttribute('data-theme', saved);
document.getElementById('themeToggle').addEventListener('click', () => {
  const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
  html.setAttribute('data-theme', next);
  localStorage.setItem('phpdts-theme', next);
});

// ===== CLOCK =====
function updateClock() { document.getElementById('clock').textContent = new Date().toLocaleString('zh-CN'); }
updateClock(); setInterval(updateClock, 1000);

// ===== TABS =====
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('panel-' + btn.dataset.tab).classList.add('active');
    if (btn.dataset.tab === 'database' && !window._dbLoaded) { initDbTree(); window._dbLoaded = true; }
    if (btn.dataset.tab === 'files' && !window._filesLoaded) { browseDir(''); window._filesLoaded = true; }
  });
});

// ===== TOAST =====
function toast(msg, cls) {
  const el = document.getElementById('toast');
  el.textContent = msg; el.className = 'toast ' + (cls || '');
  el.classList.add('show');
  clearTimeout(el._tid);
  el._tid = setTimeout(() => el.classList.remove('show'), 2500);
}

// ===== STATUS REFRESH =====
async function refreshStatus() {
  try {
    const d = await (await fetch('/api/status')).json();
    setServiceCard('php', d.php.running, d.php.version, d.php.pids);
    setServiceCard('mysql', d.mysql.running, d.mysql.version, d.mysql.pids);
    document.getElementById('cfgTitle').textContent = d.game.title || '--';
    document.getElementById('cfgFounder').textContent = d.game.founder || '--';
    document.getElementById('cfgDriver').textContent = d.game.database || '--';
    document.getElementById('cfgCharset').textContent = d.game.charset || '--';
    document.getElementById('cfgDbname').textContent = d.game.db_name || '--';
    document.getElementById('cfgDbhost').textContent = d.game.db_host || '--';
    document.getElementById('statFiles').textContent = d.files.count;
    document.getElementById('statSize').textContent = d.files.size_mb + ' MB';
    document.getElementById('statTime').textContent = d.server_time;
    document.getElementById('linkGame').href = d.game.url;
    document.getElementById('linkRegister').href = d.game.url + 'register.php';
    document.getElementById('linkAdmin').href = d.game.url + 'admin.php';
    document.getElementById('btnPhpStart').disabled = d.php.running;
    document.getElementById('btnPhpStop').disabled = !d.php.running;
    document.getElementById('btnMysqlStart').disabled = d.mysql.running;
    document.getElementById('btnMysqlStop').disabled = !d.mysql.running;
    document.getElementById('btnGameStart').disabled = d.php.running && d.mysql.running;
    document.getElementById('btnGameStop').disabled = !d.php.running && !d.mysql.running;
  } catch (e) { /* ignore */ }
}

function setServiceCard(name, running, version, pids) {
  const badge = document.getElementById(name + 'Status');
  badge.textContent = running ? 'online' : 'offline';
  badge.className = 'status-badge ' + (running ? 'online' : 'offline');
  document.getElementById(name + 'Version').textContent = version;
  document.getElementById(name + 'Pid').textContent = pids.length ? pids.join(', ') : (running ? 'running' : '--');
}

// ===== SERVICE CONTROL =====
async function control(what, action) {
  const u = what.charAt(0).toUpperCase() + what.slice(1);
  document.getElementById('btn' + u + 'Start').disabled = true;
  document.getElementById('btn' + u + 'Stop').disabled = true;
  try {
    const d = await (await fetch('/api/' + what + '/' + action, { method: 'POST' })).json();
    toast(d.msg, d.ok ? 'success' : 'error');
  } catch (e) { toast('Request failed', 'error'); }
  setTimeout(refreshStatus, 1500);
}

// ===== ONE-CLICK GAME =====
async function gameControl(action) {
  document.getElementById('btnGameStart').disabled = true;
  document.getElementById('btnGameStop').disabled = true;
  try {
    const d = await (await fetch('/api/game/' + action, { method: 'POST' })).json();
    toast(d.msg, d.ok ? 'success' : 'error');
  } catch (e) { toast('Request failed', 'error'); }
  setTimeout(refreshStatus, 2500);
}

// ===== DATABASE TREE =====
let dbCurrentTable = '';
let dbPkColumns = [];
let dbModifiedRows = {};       // rowId -> {col: newVal, ...}
let dbOriginalRows = {};       // rowId -> {col: origVal, ...}
let dbPkRowMap = {};           // rowId -> pk values

function initDbTree() {
  const tree = document.getElementById('dbTree');
  tree.innerHTML = '<div class="db-tree-loading">Loading...</div>';
  fetch('/api/db/tree').then(r => r.json()).then(d => {
    if (!d.ok) { tree.innerHTML = '<div class="db-tree-loading" style="color:var(--red)">' + d.msg + '</div>'; return; }
    tree.innerHTML =
      '<div class="db-tree-node db-root">'
      + '<div class="db-tree-label" onclick="toggleTreeNode(this)">'
      + '<span class="tree-arrow open">▶</span>'
      + '<svg class="tree-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="6" rx="8" ry="3"/><path d="M4 6v6c0 1.66 3.58 3 8 3s8-1.34 8-3V6"/><path d="M4 12v3c0 1.66 3.58 3 8 3s8-1.34 8-3v-3"/></svg>'
      + '<span class="tree-name">' + d.database + '</span>'
      + '<span class="tree-badge">' + d.tables.length + ' tables</span>'
      + '</div>'
      + '<div class="db-tree-children open">'
      + d.tables.map(t =>
        '<div class="db-tree-node">'
        + '<div class="db-tree-label" data-table="' + t.name + '" onclick="selectTable(\'' + t.name + '\')">'
        + '<span class="tree-arrow" style="visibility:hidden">▶</span>'
        + '<svg class="tree-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="3" x2="9" y2="21"/></svg>'
        + '<span class="tree-name">' + t.name + '</span>'
        + '<span class="tree-badge">' + t.rows + '</span>'
        + '</div></div>'
      ).join('')
      + '</div></div>';
  }).catch(e => { tree.innerHTML = '<div class="db-tree-loading" style="color:var(--red)">Error</div>'; });
}

function toggleTreeNode(arrow) {
  const children = arrow.parentElement.nextElementSibling;
  if (!children) return;
  arrow.classList.toggle('open');
  children.classList.toggle('open');
}

async function selectTable(name) {
  dbCurrentTable = name;
  dbModifiedRows = {};
  dbOriginalRows = {};
  dbPkRowMap = {};
  document.getElementById('dbCurrentTable').textContent = name;
  document.getElementById('btnNewRow').disabled = false;
  document.getElementById('btnRefreshTable').disabled = false;
  document.querySelectorAll('.db-tree-label[data-table]').forEach(el => {
    el.classList.toggle('active', el.dataset.table === name);
  });

  try {
    const d = await (await fetch('/api/db/describe?table=' + name)).json();
    if (d.ok) {
      document.getElementById('dbSchema').style.display = 'block';
      document.getElementById('dbCreateSql').textContent = d.create_sql;
    }
  } catch (e) { /* ignore */ }
  loadData(1);
}

function refreshTable() { if (dbCurrentTable) loadData(window._dbPage || 1); }

function makeRowId(pk) {
  return Object.entries(pk).map(([k, v]) => k + '=' + String(v ?? 'NULL')).join('|');
}

// ===== DATA LOADING WITH INLINE EDITOR =====
async function loadData(page) {
  window._dbPage = page;
  const scroll = document.getElementById('dbDataScroll');
  const pager = document.getElementById('dbPager');
  scroll.innerHTML = '<div class="db-empty-msg">Loading...</div>';
  try {
    const d = await (await fetch('/api/db/data?table=' + dbCurrentTable + '&page=' + page)).json();
    if (!d.ok) { scroll.innerHTML = '<div class="db-empty-msg" style="color:var(--red)">' + d.msg + '</div>'; return; }
    dbPkColumns = d.pk || [];
    if (d.rows.length === 0) {
      scroll.innerHTML = '<div class="db-empty-msg">No rows</div>';
    } else {
      const allCols = ['_act', ...d.columns];
      const thead = '<thead><tr><th style="width:70px">Actions</th>'
        + d.columns.map(c => '<th>' + c + (dbPkColumns.includes(c) ? ' PK' : '') + '</th>').join('')
        + '</tr></thead>';
      const tbody = '<tbody>' + d.rows.map((r, idx) => {
        const pk = dbPkColumns.length > 0
          ? Object.fromEntries(dbPkColumns.map(c => [c, r[c] !== undefined ? r[c] : null]))
          : { _idx: idx };
        const rowId = makeRowId(pk);
        dbPkRowMap[rowId] = pk;
        return '<tr id="row-' + rowId.replace(/[^a-zA-Z0-9_-]/g, '_') + '">'
          + '<td class="db-row-actions">'
          + '<button class="row-btn save" title="Save" onclick="saveRow(\'' + rowId + '\')">Save</button>'
          + '<button class="row-btn cancel" title="Cancel" onclick="cancelRow(\'' + rowId + '\')">Undo</button>'
          + '<button class="row-btn del" title="Delete" onclick="deleteRow(\'' + rowId + '\')">Del</button>'
          + '</td>'
          + d.columns.map(c => {
            const v = r[c];
            const isPk = dbPkColumns.includes(c);
            const cls = isPk ? 'editable-cell pk-cell' : 'editable-cell';
            const disp = v === null ? '<span style="color:var(--fg3)">NULL</span>' : String(v).replace(/</g, '&lt;').replace(/>/g, '&gt;');
            return '<td class="' + cls + '" data-col="' + c + '" data-row="' + rowId + '" onclick="startEdit(this)" title="Double-click to edit">' + disp + '</td>';
          }).join('') + '</tr>';
      }).join('') + '</tbody>';
      scroll.innerHTML = '<table class="db-data-table editable">' + thead + tbody + '</table>';
    }
    pager.style.display = 'flex';
    pager.innerHTML =
      '<button ' + (d.page <= 1 ? 'disabled' : '') + ' onclick="loadData(1)">First</button>'
      + '<button ' + (d.page <= 1 ? 'disabled' : '') + ' onclick="loadData(' + (d.page - 1) + ')">Prev</button>'
      + '<span>Page ' + d.page + ' / ' + d.pages + ' (' + d.total + ' rows)</span>'
      + '<button ' + (d.page >= d.pages ? 'disabled' : '') + ' onclick="loadData(' + (d.page + 1) + ')">Next</button>'
      + '<button ' + (d.page >= d.pages ? 'disabled' : '') + ' onclick="loadData(' + d.pages + ')">Last</button>';
  } catch (e) { scroll.innerHTML = '<div class="db-empty-msg" style="color:var(--red)">Error</div>'; }
}

// ===== INLINE EDITING =====
function startEdit(td) {
  if (td.classList.contains('editing')) return;
  const col = td.dataset.col;
  const rowId = td.dataset.row;
  const origHTML = td.innerHTML;

  // save original value
  const origText = td.textContent === 'NULL' ? null : td.textContent;
  if (!dbOriginalRows[rowId]) dbOriginalRows[rowId] = {};
  if (!(col in dbOriginalRows[rowId])) dbOriginalRows[rowId][col] = origText;
  if (!dbModifiedRows[rowId]) dbModifiedRows[rowId] = {};

  td.classList.add('editing');
  td.innerHTML = '<input type="text" id="editInput" value="' + (origText === null ? '' : origText.replace(/"/g, '&quot;')) + '" />';
  const input = td.querySelector('input');
  input.focus();
  input.select();

  function finish(val) {
    td.classList.remove('editing');
    const dispVal = val === '' || val === null ? '<span style="color:var(--fg3)">NULL</span>' : val.replace(/</g, '&lt;').replace(/>/g, '&gt;');
    td.innerHTML = dispVal;
    const newVal = (val === '' || val === null) ? null : val;
    const origVal = dbOriginalRows[rowId][col];
    if (newVal !== origVal) {
      dbModifiedRows[rowId][col] = newVal;
      td.classList.add('modified');
    } else {
      delete dbModifiedRows[rowId][col];
      td.classList.remove('modified');
      if (Object.keys(dbModifiedRows[rowId]).length === 0) delete dbModifiedRows[rowId];
    }
  }

  input.addEventListener('blur', () => finish(input.value));
  input.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); finish(input.value); }
    if (e.key === 'Escape') { e.preventDefault(); td.classList.remove('editing'); td.innerHTML = origHTML; }
    if (e.key === 'Tab') { e.preventDefault(); finish(input.value); }
  });
}

// ===== ROW OPERATIONS =====
async function saveRow(rowId) {
  if (!dbModifiedRows[rowId]) { toast('No changes', ''); return; }
  const pk = dbPkRowMap[rowId];
  if (!pk || Object.keys(pk).length === 0) { toast('Cannot save: no primary key', 'error'); return; }
  try {
    const d = await (await fetch('/api/db/update', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ table: dbCurrentTable, pk: pk, data: dbModifiedRows[rowId] }),
    })).json();
    if (d.ok) {
      delete dbModifiedRows[rowId];
      delete dbOriginalRows[rowId];
      toast('Saved', 'success');
      loadData(window._dbPage || 1);
    } else {
      toast(d.msg, 'error');
    }
  } catch (e) { toast('Save failed', 'error'); }
}

function cancelRow(rowId) {
  delete dbModifiedRows[rowId];
  delete dbOriginalRows[rowId];
  loadData(window._dbPage || 1);
}

async function deleteRow(rowId) {
  const pk = dbPkRowMap[rowId];
  if (!pk || Object.keys(pk).length === 0) { toast('Cannot delete: no primary key', 'error'); return; }
  if (!confirm('Delete this row? This cannot be undone.')) return;
  try {
    const d = await (await fetch('/api/db/delete', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ table: dbCurrentTable, pk: pk }),
    })).json();
    if (d.ok) {
      toast('Deleted', 'success');
      loadData(window._dbPage || 1);
    } else {
      toast(d.msg, 'error');
    }
  } catch (e) { toast('Delete failed', 'error'); }
}

// ===== NEW ROW =====
async function newRow() {
  if (!dbCurrentTable) return;
  // insert with default values — use first column as marker
  try {
    // get columns info
    const d = await (await fetch('/api/db/describe?table=' + dbCurrentTable)).json();
    if (!d.ok) { toast(d.msg, 'error'); return; }
    // build minimal insert with defaultable values
    const data = {};
    d.columns.forEach(col => {
      if (col.Default !== undefined && col.Default !== null && col.Default !== '') {
        // skip auto_increment columns
        if (col.Extra && col.Extra.includes('auto_increment')) return;
        data[col.Field] = col.Default;
      } else if (col.Null === 'YES' || (col.Extra && col.Extra.includes('auto_increment'))) {
        // nullable or auto_inc — skip
      } else {
        // NOT NULL without default — fill with empty string for text, 0 for numeric
        const t = col.Type.toLowerCase();
        if (t.includes('char') || t.includes('text') || t.includes('blob')) data[col.Field] = '';
        else if (t.includes('int') || t.includes('float') || t.includes('double') || t.includes('decimal')) data[col.Field] = 0;
        else data[col.Field] = '';
      }
    });
    const r = await (await fetch('/api/db/insert', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ table: dbCurrentTable, data: data }),
    })).json();
    if (r.ok) {
      toast('New row inserted', 'success');
      loadData(1);
    } else {
      toast(r.msg, 'error');
    }
  } catch (e) { toast('Insert failed', 'error'); }
}

// ===== SQL CONSOLE =====
async function execSql() {
  const sql = document.getElementById('sqlEditor').value.trim();
  if (!sql) { toast('Enter SQL first', 'error'); return; }
  document.getElementById('sqlResultCard').style.display = 'none';
  document.getElementById('btnSqlExec').disabled = true;
  try {
    const d = await (await fetch('/api/db/sql', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ sql: sql }),
    })).json();
    document.getElementById('sqlResultCard').style.display = 'block';
    if (d.ok) {
      document.getElementById('sqlResultInfo').textContent = d.rowcount + ' rows';
      document.getElementById('sqlError').style.display = 'none';
      if (d.rows.length === 0) {
        document.getElementById('sqlResultTable').innerHTML = '<tbody><tr><td class="db-empty-msg">Empty set</td></tr></tbody>';
      } else {
        document.getElementById('sqlResultTable').innerHTML =
          '<thead><tr>' + d.columns.map(c => '<th>' + c + '</th>').join('') + '</tr></thead>'
          + '<tbody>' + d.rows.map(r =>
            '<tr>' + d.columns.map(c => {
              const v = r[c];
              if (v === null) return '<td style="color:var(--fg3)">NULL</td>';
              return '<td>' + String(v).replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</td>';
            }).join('') + '</tr>'
          ).join('') + '</tbody>';
      }
    } else {
      document.getElementById('sqlResultInfo').textContent = 'Error';
      document.getElementById('sqlError').style.display = 'block';
      document.getElementById('sqlError').textContent = d.msg;
      document.getElementById('sqlResultTable').innerHTML = '<tbody></tbody>';
    }
  } catch (e) {
    document.getElementById('sqlResultCard').style.display = 'block';
    document.getElementById('sqlResultInfo').textContent = 'Error';
    document.getElementById('sqlError').style.display = 'block';
    document.getElementById('sqlError').textContent = String(e);
  }
  document.getElementById('btnSqlExec').disabled = false;
}

// Ctrl+Enter in SQL editor
document.getElementById('sqlEditor').addEventListener('keydown', function(e) {
  if (e.ctrlKey && e.key === 'Enter') { e.preventDefault(); execSql(); }
});

// ===== FILE BROWSER =====
const fileNav = document.getElementById('fileNav');
const currentPath = document.getElementById('currentPath');
const fileCount = document.getElementById('fileCount');
const fileList = document.getElementById('fileList');
const filePreview = document.getElementById('filePreview');

function formatSize(bytes) {
  if (bytes < 1024) return bytes + ' B';
  if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
  return (bytes / 1048576).toFixed(1) + ' MB';
}

async function browseDir(path) {
  filePreview.style.display = 'none';
  fileList.style.display = '';
  fileList.innerHTML = '<div class="file-empty">Loading...</div>';
  currentPath.textContent = '/' + (path || '');
  try {
    const d = await (await fetch('/api/files?path=' + encodeURIComponent(path || ''))).json();
    if (!d.ok) { fileList.innerHTML = '<div class="file-empty">Error: ' + d.msg + '</div>'; return; }
    updateBreadcrumb(path || '');
    const items = d.items || [];
    fileCount.textContent = items.length + ' items';
    if (items.length === 0) {
      fileList.innerHTML = '<div class="file-empty">Empty directory</div>'; return;
    }
    fileList.innerHTML = items.map(item => {
      const icon = item.is_dir
        ? '<svg class="fi-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg>'
        : '<svg class="fi-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>';
      const cls = item.is_dir ? 'file-item dir-item' : 'file-item';
      const onclick = item.is_dir ? `browseDir('${item.path}')` : `previewFile('${item.path}')`;
      return `<div class="${cls}" onclick="${onclick}">${icon}<span class="fi-name">${item.name}</span><span class="fi-size">${item.is_dir ? '' : formatSize(item.size)}</span></div>`;
    }).join('');
  } catch (e) { fileList.innerHTML = '<div class="file-empty">Network error</div>'; }
}

function updateBreadcrumb(path) {
  fileNav.innerHTML = '<button class="file-nav-btn" onclick="browseDir(\'\')">/ (root)</button>';
  if (!path) { fileNav.querySelector('button').classList.add('active'); return; }
  let parts = path.split('/'), acc = '';
  parts.forEach((part, i) => {
    acc += (i > 0 ? '/' : '') + part;
    const isLast = i === parts.length - 1;
    fileNav.innerHTML += '<button class="file-nav-btn' + (isLast ? ' active' : '') + '" onclick="browseDir(\'' + acc + '\')">' + part + '</button>';
  });
}

async function previewFile(path) {
  try {
    const d = await (await fetch('/api/files?path=' + encodeURIComponent(path))).json();
    if (!d.ok) { toast('Cannot read file', 'error'); return; }
    fileList.style.display = 'none';
    filePreview.style.display = 'block';
    document.getElementById('previewName').textContent = d.name;
    document.getElementById('previewContent').textContent = d.content || '(empty)';
  } catch (e) { toast('Failed to preview', 'error'); }
}

function closePreview() {
  filePreview.style.display = 'none';
  fileList.style.display = '';
}

// ===== INIT =====
refreshStatus();
setInterval(refreshStatus, 10000);

// preload file browser on first Files tab click
window._filesLoaded = false;
