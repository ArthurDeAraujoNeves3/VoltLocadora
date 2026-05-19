<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>VoltLocadora</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
/* ── DESIGN SYSTEM ──────────────────────────────────────────── */
:root {
  --sidebar-bg: #0d1b2a;
  --sidebar-w: 252px;
  --accent: #f59e0b;
  --accent-dim: rgba(245,159,11,.13);
  --bg: #f0f4f8;
  --surface: #ffffff;
  --text: #1a2332;
  --muted: #64748b;
  --subtle: #94a3b8;
  --border: #e2e8f0;
  --border-light: #f1f5f9;
  --success: #10b981;
  --danger: #ef4444;
  --warning: #f59e0b;
  --info: #3b82f6;
  --r-sm: 8px;
  --r: 12px;
  --r-lg: 16px;
  --shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 20px rgba(0,0,0,.05);
  --shadow-lg: 0 8px 40px rgba(0,0,0,.12);
  --transition: .15s ease;
}

*,*::before,*::after { box-sizing: border-box; }

body {
  font-family: 'Outfit', -apple-system, system-ui, sans-serif;
  background: var(--bg);
  color: var(--text);
  margin: 0;
  line-height: 1.6;
  -webkit-font-smoothing: antialiased;
  min-height: 100vh;
}

/* ── LAYOUT ─────────────────────────────────────────────────── */
.volt-layout { display: flex; min-height: 100vh; }

/* ── SIDEBAR ────────────────────────────────────────────────── */
.volt-sidebar {
  width: var(--sidebar-w);
  min-width: var(--sidebar-w);
  background: var(--sidebar-bg);
  display: flex;
  flex-direction: column;
  position: sticky;
  top: 0;
  height: 100vh;
  overflow-y: auto;
  scrollbar-width: thin;
  scrollbar-color: rgba(255,255,255,.08) transparent;
  flex-shrink: 0;
}

.volt-brand {
  display: flex;
  align-items: center;
  gap: .75rem;
  padding: 1.375rem 1.25rem 1.25rem;
  text-decoration: none;
  border-bottom: 1px solid rgba(255,255,255,.06);
  flex-shrink: 0;
}

.volt-brand-icon {
  width: 34px;
  height: 34px;
  background: var(--accent);
  border-radius: 8px;
  display: grid;
  place-items: center;
  font-size: 1rem;
  flex-shrink: 0;
  box-shadow: 0 2px 8px rgba(245,159,11,.35);
}

.volt-brand-text {
  font-size: 1.125rem;
  font-weight: 800;
  color: #fff;
  letter-spacing: -.025em;
  line-height: 1;
}

.volt-brand-sub {
  font-size: .65rem;
  color: rgba(255,255,255,.35);
  font-weight: 500;
  letter-spacing: .05em;
  text-transform: uppercase;
  margin-top: 1px;
}

.volt-nav-group {
  padding: .875rem .75rem 0;
}

.volt-nav-group + .volt-nav-group { padding-top: .5rem; }

.volt-nav-label {
  font-size: .6rem;
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: rgba(255,255,255,.22);
  padding: 0 .625rem .375rem;
}

.volt-nav { list-style: none; margin: 0; padding: 0; }
.volt-nav li { margin-bottom: 1px; }

.volt-nav a {
  display: flex;
  align-items: center;
  gap: .625rem;
  padding: .5rem .75rem;
  color: rgba(255,255,255,.48);
  border-radius: 7px;
  font-size: .875rem;
  font-weight: 500;
  text-decoration: none;
  transition: color var(--transition), background var(--transition);
}

.volt-nav a:hover {
  color: rgba(255,255,255,.82);
  background: rgba(255,255,255,.06);
}

.volt-nav a.active {
  color: var(--accent);
  background: var(--accent-dim);
  font-weight: 600;
}

.volt-nav a .bi { font-size: .875rem; width: 16px; text-align: center; flex-shrink: 0; }

.volt-sidebar-footer {
  margin-top: auto;
  padding: 1rem 1.25rem;
  border-top: 1px solid rgba(255,255,255,.06);
  font-size: .725rem;
  color: rgba(255,255,255,.2);
  text-align: center;
}

/* ── MAIN ───────────────────────────────────────────────────── */
.volt-main {
  flex: 1;
  min-width: 0;
  padding: 2rem 2.25rem;
  display: flex;
  flex-direction: column;
}

/* ── FLASH ALERTS ───────────────────────────────────────────── */
.volt-flash {
  display: flex;
  align-items: center;
  gap: .75rem;
  padding: .875rem 1.125rem;
  border-radius: var(--r-sm);
  font-size: .875rem;
  font-weight: 500;
  margin-bottom: 1.5rem;
  animation: slideDown .2s ease;
}

@keyframes slideDown {
  from { opacity: 0; transform: translateY(-8px); }
  to   { opacity: 1; transform: translateY(0); }
}

.volt-flash-success { background: #f0fdf4; color: #14532d; border: 1px solid #bbf7d0; }
.volt-flash-danger  { background: #fef2f2; color: #7f1d1d; border: 1px solid #fecaca; }
.volt-flash-warning { background: #fffbeb; color: #78350f; border: 1px solid #fde68a; }

.volt-flash .bi { font-size: 1rem; flex-shrink: 0; }

.volt-flash-close {
  margin-left: auto;
  background: none;
  border: none;
  cursor: pointer;
  color: currentColor;
  opacity: .5;
  padding: 0;
  display: flex;
  align-items: center;
  font-size: 1rem;
  flex-shrink: 0;
}
.volt-flash-close:hover { opacity: 1; }

/* ── CARDS ──────────────────────────────────────────────────── */
.page-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--r);
  box-shadow: var(--shadow);
}

.card-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.125rem 1.5rem;
  border-bottom: 1px solid var(--border-light);
}

.card-head h5, .card-head .card-title {
  font-size: .9375rem;
  font-weight: 700;
  margin: 0;
  color: var(--text);
}

.card-body-pad { padding: 1.5rem; }

/* ── TABLES ─────────────────────────────────────────────────── */
.table-wrap { overflow-x: auto; border-radius: 0 0 var(--r) var(--r); }

.table {
  margin: 0;
  border-collapse: collapse;
  width: 100%;
}

.table thead th {
  background: #f8fafc;
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .08em;
  text-transform: uppercase;
  color: var(--muted);
  padding: .75rem 1.25rem;
  border-bottom: 1px solid var(--border);
  white-space: nowrap;
  border-top: none;
}

.table tbody td {
  padding: .8125rem 1.25rem;
  font-size: .875rem;
  color: var(--text);
  border-bottom: 1px solid var(--border-light);
  vertical-align: middle;
}

.table tbody tr:last-child td { border-bottom: none; }
.table tbody tr:hover td { background: #fafbff; }

/* ── FORMS ──────────────────────────────────────────────────── */
.form-label {
  display: block;
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .05em;
  text-transform: uppercase;
  color: var(--muted);
  margin-bottom: .375rem;
}

.form-control, .form-select {
  font-family: 'Outfit', sans-serif;
  font-size: .875rem;
  color: var(--text);
  background: #fff;
  border: 1.5px solid var(--border);
  border-radius: var(--r-sm);
  padding: .5625rem .875rem;
  transition: border-color var(--transition), box-shadow var(--transition);
  width: 100%;
}

.form-control:focus, .form-select:focus {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(245,159,11,.15);
}

.form-control::placeholder { color: var(--subtle); font-size: .8rem; }

textarea.form-control { resize: vertical; min-height: 80px; }

.form-check-input:checked {
  background-color: var(--accent);
  border-color: var(--accent);
}

.form-check-label { font-size: .875rem; font-weight: 500; color: var(--text); }

/* ── BUTTONS ────────────────────────────────────────────────── */
.btn {
  font-family: 'Outfit', sans-serif;
  font-weight: 600;
  font-size: .875rem;
  border-radius: var(--r-sm);
  transition: all var(--transition);
  display: inline-flex;
  align-items: center;
  gap: .375rem;
  cursor: pointer;
  border: 1.5px solid transparent;
  line-height: 1.4;
}

.btn-primary {
  background: var(--accent);
  border-color: var(--accent);
  color: #fff;
  padding: .5rem 1.25rem;
  box-shadow: 0 2px 6px rgba(245,159,11,.28);
}
.btn-primary:hover {
  background: #d97706;
  border-color: #d97706;
  color: #fff;
  box-shadow: 0 4px 12px rgba(245,159,11,.35);
  transform: translateY(-1px);
}

.btn-secondary {
  background: #fff;
  border-color: var(--border);
  color: var(--muted);
  padding: .5rem 1.25rem;
}
.btn-secondary:hover { background: #f8fafc; color: var(--text); }

.btn-sm { padding: .3125rem .625rem; font-size: .8rem; }

.btn-outline-primary {
  background: transparent;
  border-color: var(--border);
  color: var(--muted);
  padding: .3125rem .625rem;
}
.btn-outline-primary:hover {
  background: var(--accent-dim);
  border-color: var(--accent);
  color: var(--accent);
}

.btn-outline-danger {
  background: transparent;
  border-color: var(--border);
  color: var(--muted);
  padding: .3125rem .625rem;
}
.btn-outline-danger:hover {
  background: #fef2f2;
  border-color: #fca5a5;
  color: var(--danger);
}

/* ── BADGES ─────────────────────────────────────────────────── */
.badge {
  font-family: 'Outfit', sans-serif;
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .02em;
  border-radius: 20px;
  padding: .25rem .625rem;
}

.badge.bg-success   { background: #dcfce7 !important; color: #14532d !important; }
.badge.bg-warning   { background: #fef9c3 !important; color: #713f12 !important; }
.badge.bg-danger    { background: #fee2e2 !important; color: #7f1d1d !important; }
.badge.bg-secondary { background: #f1f5f9 !important; color: #475569 !important; }
.badge.bg-info      { background: #dbeafe !important; color: #1e3a8a !important; }

/* ── UTILITIES ──────────────────────────────────────────────── */
.text-muted { color: var(--muted) !important; }
code { font-size: .8rem; background: #f1f5f9; color: #475569; padding: .15rem .4rem; border-radius: 4px; font-family: 'Courier New', monospace; }

.empty-cell { text-align: center; padding: 3rem !important; color: var(--subtle); font-size: .875rem; }

/* ── PAGE HEADER ────────────────────────────────────────────── */
.page-hdr { margin-bottom: 1.75rem; }
.page-hdr h1 { font-size: 1.5rem; font-weight: 800; letter-spacing: -.025em; margin: 0; }
.page-hdr p  { color: var(--muted); font-size: .875rem; margin: .2rem 0 0; }

/* ── HOME STAT CARDS ────────────────────────────────────────── */
.stat-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--r);
  box-shadow: var(--shadow);
  padding: 1.25rem 1.5rem;
  display: flex;
  align-items: flex-start;
  gap: 1rem;
  text-decoration: none;
  color: inherit;
  transition: transform var(--transition), box-shadow var(--transition);
}
.stat-card:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow-lg);
  color: inherit;
  text-decoration: none;
}
.stat-icon {
  width: 46px; height: 46px;
  border-radius: 11px;
  display: grid;
  place-items: center;
  font-size: 1.2rem;
  flex-shrink: 0;
}
.stat-num { font-size: 2rem; font-weight: 800; letter-spacing: -.04em; line-height: 1; }
.stat-lbl { font-size: .8rem; font-weight: 600; color: var(--muted); margin-top: .2rem; }

/* ── QUICK NAV CARDS ────────────────────────────────────────── */
.quick-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--r);
  padding: 1.25rem;
  text-align: center;
  text-decoration: none;
  color: inherit;
  display: block;
  transition: all var(--transition);
  box-shadow: var(--shadow);
}
.quick-card:hover {
  color: var(--accent);
  border-color: var(--accent);
  transform: translateY(-2px);
  box-shadow: var(--shadow-lg);
}
.quick-card i { display: block; font-size: 1.75rem; margin-bottom: .5rem; }
.quick-card span { font-size: .8125rem; font-weight: 600; }

/* ── VEHICLE STATUS IN SELECT ───────────────────────────────── */
select option[data-status]:not([data-status='disponivel']) { color: var(--muted); }
select option[disabled] { color: #ccc !important; }

/* ── VALIDATION ─────────────────────────────────────────────── */
.form-control.is-invalid,
.form-select.is-invalid {
  border-color: var(--danger);
  box-shadow: none;
}
.form-control.is-invalid:focus,
.form-select.is-invalid:focus {
  border-color: var(--danger);
  box-shadow: 0 0 0 3px rgba(239,68,68,.15);
}
.invalid-feedback {
  display: none;
  font-size: .72rem;
  color: var(--danger);
  margin-top: .25rem;
  font-weight: 500;
}
.is-invalid ~ .invalid-feedback,
.input-group .is-invalid ~ .invalid-feedback {
  display: block;
}

/* ── RESPONSIVE ─────────────────────────────────────────────── */
@media (max-width: 900px) {
  .volt-sidebar { display: none; }
  .volt-main { padding: 1rem; }
}
</style>
</head>
<body>
<div class="volt-layout">

<!-- SIDEBAR -->
<aside class="volt-sidebar">
  <a href="index.php" class="volt-brand" style="text-decoration:none">
    <div class="volt-brand-icon"><i class="bi bi-lightning-charge-fill" style="color:#0d1b2a"></i></div>
    <div>
      <div class="volt-brand-text">VoltLocadora</div>
      <div class="volt-brand-sub">Sistema de Frota</div>
    </div>
  </a>

  <div class="volt-nav-group">
    <div class="volt-nav-label">Cadastros</div>
    <ul class="volt-nav">
      <li><a href="index.php?page=marca"       class="<?= ($page??'')==='marca'       ? 'active' : '' ?>"><i class="bi bi-tag"></i>Marcas</a></li>
      <li><a href="index.php?page=categoria"   class="<?= ($page??'')==='categoria'   ? 'active' : '' ?>"><i class="bi bi-grid-3x3"></i>Categorias</a></li>
      <li><a href="index.php?page=agencia"     class="<?= ($page??'')==='agencia'     ? 'active' : '' ?>"><i class="bi bi-building"></i>Agências</a></li>
      <li><a href="index.php?page=funcionario" class="<?= ($page??'')==='funcionario' ? 'active' : '' ?>"><i class="bi bi-person-badge"></i>Funcionários</a></li>
      <li><a href="index.php?page=cliente"     class="<?= ($page??'')==='cliente'     ? 'active' : '' ?>"><i class="bi bi-people"></i>Clientes</a></li>
      <li><a href="index.php?page=veiculo"     class="<?= ($page??'')==='veiculo'     ? 'active' : '' ?>"><i class="bi bi-car-front"></i>Veículos</a></li>
    </ul>
  </div>

  <div class="volt-nav-group">
    <div class="volt-nav-label">Operações</div>
    <ul class="volt-nav">
      <li><a href="index.php?page=locacao"    class="<?= ($page??'')==='locacao'    ? 'active' : '' ?>"><i class="bi bi-file-text"></i>Locações</a></li>
      <li><a href="index.php?page=pagamento"  class="<?= ($page??'')==='pagamento'  ? 'active' : '' ?>"><i class="bi bi-credit-card"></i>Pagamentos</a></li>
      <li><a href="index.php?page=seguro"     class="<?= ($page??'')==='seguro'     ? 'active' : '' ?>"><i class="bi bi-shield-check"></i>Seguros</a></li>
      <li><a href="index.php?page=avaria"     class="<?= ($page??'')==='avaria'     ? 'active' : '' ?>"><i class="bi bi-exclamation-triangle"></i>Avarias</a></li>
      <li><a href="index.php?page=manutencao" class="<?= ($page??'')==='manutencao' ? 'active' : '' ?>"><i class="bi bi-wrench"></i>Manutenções</a></li>
    </ul>
  </div>

  <div class="volt-sidebar-footer">UNIFAP · TD3 Backend · 2025</div>
</aside>

<!-- MAIN -->
<main class="volt-main">

<?php
$flashIcon = ['success' => 'check-circle-fill', 'danger' => 'x-circle-fill', 'warning' => 'exclamation-triangle-fill'];
if (isset($msg)): ?>
<div class="volt-flash volt-flash-<?= h($msgType ?? 'success') ?>" id="voltFlash">
  <i class="bi bi-<?= $flashIcon[$msgType ?? 'success'] ?? 'info-circle-fill' ?>"></i>
  <span><?= h($msg) ?></span>
  <button class="volt-flash-close" onclick="document.getElementById('voltFlash').remove()"><i class="bi bi-x-lg"></i></button>
</div>
<?php endif; ?>
