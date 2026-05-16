<div class="page-hdr">
  <h1>Dashboard</h1>
  <p>Visão geral do sistema de locação</p>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
  <div class="col-sm-6 col-lg-3">
    <a href="index.php?page=veiculo" class="stat-card">
      <div class="stat-icon" style="background:#fef9c3;color:#92400e"><i class="bi bi-car-front"></i></div>
      <div>
        <div class="stat-num"><?= $statsVeiculos ?? 0 ?></div>
        <div class="stat-lbl">Veículos Total</div>
      </div>
    </a>
  </div>
  <div class="col-sm-6 col-lg-3">
    <a href="index.php?page=veiculo" class="stat-card">
      <div class="stat-icon" style="background:#dcfce7;color:#14532d"><i class="bi bi-check-circle"></i></div>
      <div>
        <div class="stat-num" style="color:#16a34a"><?= $statsDisponiveis ?? 0 ?></div>
        <div class="stat-lbl">Disponíveis</div>
      </div>
    </a>
  </div>
  <div class="col-sm-6 col-lg-3">
    <a href="index.php?page=locacao" class="stat-card">
      <div class="stat-icon" style="background:#dbeafe;color:#1e40af"><i class="bi bi-file-text"></i></div>
      <div>
        <div class="stat-num" style="color:#2563eb"><?= $statsAbertas ?? 0 ?></div>
        <div class="stat-lbl">Locações Abertas</div>
      </div>
    </a>
  </div>
  <div class="col-sm-6 col-lg-3">
    <a href="index.php?page=cliente" class="stat-card">
      <div class="stat-icon" style="background:#f3e8ff;color:#6b21a8"><i class="bi bi-people"></i></div>
      <div>
        <div class="stat-num" style="color:#7c3aed"><?= $statsClientes ?? 0 ?></div>
        <div class="stat-lbl">Clientes</div>
      </div>
    </a>
  </div>
</div>

<!-- Quick nav -->
<div class="page-card p-0 mb-4">
  <div class="card-head" style="border-radius: 12px 12px 0 0;">
    <h5 class="card-title">Acesso Rápido</h5>
  </div>
  <div class="card-body-pad">
    <div class="row g-3">
      <div class="col-6 col-md-4 col-lg-2">
        <a href="index.php?page=locacao" class="quick-card">
          <i class="bi bi-file-text" style="color:#2563eb"></i>
          <span>Nova Locação</span>
        </a>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
        <a href="index.php?page=veiculo" class="quick-card">
          <i class="bi bi-car-front" style="color:#d97706"></i>
          <span>Veículos</span>
        </a>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
        <a href="index.php?page=cliente" class="quick-card">
          <i class="bi bi-person-plus" style="color:#7c3aed"></i>
          <span>Clientes</span>
        </a>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
        <a href="index.php?page=pagamento" class="quick-card">
          <i class="bi bi-credit-card" style="color:#0891b2"></i>
          <span>Pagamentos</span>
        </a>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
        <a href="index.php?page=manutencao" class="quick-card">
          <i class="bi bi-wrench" style="color:#dc2626"></i>
          <span>Manutenção</span>
        </a>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
        <a href="index.php?page=avaria" class="quick-card">
          <i class="bi bi-exclamation-triangle" style="color:#ea580c"></i>
          <span>Avarias</span>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Locações recentes -->
<?php if (!empty($locacoesList)): ?>
<div class="page-card p-0">
  <div class="card-head">
    <h5 class="card-title">Locações Recentes</h5>
    <a href="index.php?page=locacao" class="btn btn-sm btn-secondary">Ver todas</a>
  </div>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr><th>#</th><th>Status</th><th>Retirada</th><th>Devolução Prevista</th><th>Valor Total</th><th></th></tr>
      </thead>
      <tbody>
      <?php
      $statusColors = ['aberta'=>'info','encerrada'=>'success','cancelada'=>'secondary'];
      $recentes = array_slice($locacoesList, 0, 5);
      foreach ($recentes as $l): ?>
        <tr>
          <td><code>#<?= $l->getId() ?></code></td>
          <td><span class="badge bg-<?= $statusColors[$l->getStatus()] ?? 'secondary' ?>"><?= ucfirst($l->getStatus()) ?></span></td>
          <td><?= h($l->getDataRetirada()) ?></td>
          <td><?= h($l->getDataDevolucaoPrevista()) ?></td>
          <td><strong>R$ <?= number_format($l->getValorTotal(), 2, ',', '.') ?></strong></td>
          <td><a href="index.php?page=locacao&acao=editar&id=<?= $l->getId() ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>
