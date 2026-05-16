<div class="page-hdr">
  <h1>Avarias</h1>
  <p>Registro de danos ocorridos nos veículos</p>
</div>

<div class="page-card p-0 mb-4">
  <div class="card-head">
    <h5 class="card-title"><?= isset($objEdit) ? 'Editar Avaria' : 'Nova Avaria' ?></h5>
  </div>
  <div class="card-body-pad">
    <form method="post" action="index.php?page=avaria">
      <input type="hidden" name="id" value="<?= isset($objEdit) ? $objEdit->getId() : '' ?>">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Locação *</label>
          <select name="id_locacao" class="form-select" required>
            <option value="">Selecione...</option>
            <?php foreach ($locacoes as $l): ?>
              <option value="<?= $l->getId() ?>" <?= (isset($objEdit) && $objEdit->getIdLocacao() == $l->getId()) ? 'selected' : '' ?>>
                #<?= $l->getId() ?> — <?= h($l->getDataRetirada()) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Funcionário Responsável *</label>
          <select name="id_funcionario" class="form-select" required>
            <option value="">Selecione...</option>
            <?php foreach ($funcionarios as $f): ?>
              <option value="<?= $f->getId() ?>" <?= (isset($objEdit) && $objEdit->getIdFuncionario() == $f->getId()) ? 'selected' : '' ?>>
                <?= h($f->getNome()) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Status da Avaria</label>
          <select name="status" class="form-select">
            <?php foreach (['pendente'=>'Pendente','em_reparo'=>'Em Reparo','concluida'=>'Concluída'] as $val => $lbl): ?>
              <option value="<?= $val ?>" <?= (isset($objEdit) && $objEdit->getStatus() === $val) ? 'selected' : '' ?>><?= $lbl ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Valor do Reparo</label>
          <div class="input-group">
            <span class="input-group-text" style="background:#f8fafc;border:1.5px solid var(--border);border-right:none;font-size:.8rem">R$</span>
            <input type="number" step="0.01" name="valor_reparo" class="form-control" style="border-left:none" value="<?= isset($objEdit) ? $objEdit->getValorReparo() : 0 ?>">
          </div>
        </div>
        <div class="col-md-4 d-flex align-items-end pb-1">
          <div class="form-check">
            <input type="checkbox" name="cobrado_cliente" value="1" class="form-check-input" id="chkCobrado"
                   <?= (isset($objEdit) && $objEdit->getCobradoCliente()) ? 'checked' : '' ?>>
            <label class="form-check-label" for="chkCobrado">Cobrado do Cliente</label>
          </div>
        </div>
        <div class="col-md-12">
          <label class="form-label">Descrição da Avaria *</label>
          <textarea name="descricao" class="form-control" rows="3" required><?= isset($objEdit) ? h($objEdit->getDescricao()) : '' ?></textarea>
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" name="acao" value="salvar" class="btn btn-primary">
          <i class="bi bi-check-lg"></i><?= isset($objEdit) ? 'Atualizar' : 'Registrar Avaria' ?>
        </button>
        <?php if (isset($objEdit)): ?>
          <a href="index.php?page=avaria" class="btn btn-secondary">Cancelar</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<div class="page-card p-0">
  <div class="card-head">
    <h5 class="card-title">Avarias Registradas</h5>
    <span class="badge bg-secondary"><?= count($lista) ?></span>
  </div>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr><th>#</th><th>Locação</th><th>Funcionário</th><th>Descrição</th><th>Valor</th><th>Cobrado</th><th>Status</th><th>Ações</th></tr>
      </thead>
      <tbody>
      <?php
      $statusColors = ['pendente'=>'warning','em_reparo'=>'info','concluida'=>'success'];
      $statusLabels = ['pendente'=>'Pendente','em_reparo'=>'Em Reparo','concluida'=>'Concluída'];
      foreach ($lista as $item): ?>
        <tr>
          <td><code><?= $item->getId() ?></code></td>
          <td><code>#<?= $item->getIdLocacao() ?></code></td>
          <td><?= isset($funcionariosMap[$item->getIdFuncionario()]) ? h($funcionariosMap[$item->getIdFuncionario()]) : '—' ?></td>
          <td class="text-muted" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= h(mb_substr($item->getDescricao(), 0, 60)) ?>…</td>
          <td>R$ <?= number_format($item->getValorReparo(), 2, ',', '.') ?></td>
          <td><?= $item->getCobradoCliente() ? '<span class="badge bg-warning">Sim</span>' : '<span class="badge bg-secondary">Não</span>' ?></td>
          <td><span class="badge bg-<?= $statusColors[$item->getStatus()] ?? 'secondary' ?>"><?= $statusLabels[$item->getStatus()] ?? $item->getStatus() ?></span></td>
          <td>
            <a href="index.php?page=avaria&acao=editar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            <a href="index.php?page=avaria&acao=deletar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Excluir avaria?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($lista)): ?>
        <tr><td colspan="8" class="empty-cell">Nenhuma avaria registrada.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
