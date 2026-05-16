<div class="page-hdr">
  <h1>Seguros</h1>
  <p>Apólices de seguro vinculadas às locações</p>
</div>

<div class="page-card p-0 mb-4">
  <div class="card-head">
    <h5 class="card-title"><?= isset($objEdit) ? 'Editar Seguro' : 'Novo Seguro' ?></h5>
  </div>
  <div class="card-body-pad">
    <form method="post" action="index.php?page=seguro">
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
          <label class="form-label">Tipo de Seguro *</label>
          <input type="text" name="tipo" class="form-control" required maxlength="80" value="<?= isset($objEdit) ? h($objEdit->getTipo()) : '' ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Nº Apólice</label>
          <input type="text" name="apolice" class="form-control" maxlength="50" value="<?= isset($objEdit) ? h($objEdit->getApolice()) : '' ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Valor da Franquia</label>
          <div class="input-group">
            <span class="input-group-text" style="background:#f8fafc;border:1.5px solid var(--border);border-right:none;font-size:.8rem">R$</span>
            <input type="number" step="0.01" name="valor_franquia" class="form-control" style="border-left:none" value="<?= isset($objEdit) ? $objEdit->getValorFranquia() : 0 ?>">
          </div>
        </div>
        <div class="col-md-3">
          <label class="form-label">Valor Diária do Seguro</label>
          <div class="input-group">
            <span class="input-group-text" style="background:#f8fafc;border:1.5px solid var(--border);border-right:none;font-size:.8rem">R$</span>
            <input type="number" step="0.01" name="valor_diaria" class="form-control" style="border-left:none" value="<?= isset($objEdit) ? $objEdit->getValorDiaria() : 0 ?>">
          </div>
        </div>
        <div class="col-md-12">
          <label class="form-label">Cobertura</label>
          <textarea name="cobertura" class="form-control" rows="2"><?= isset($objEdit) ? h($objEdit->getCobertura()) : '' ?></textarea>
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" name="acao" value="salvar" class="btn btn-primary">
          <i class="bi bi-check-lg"></i><?= isset($objEdit) ? 'Atualizar' : 'Salvar Seguro' ?>
        </button>
        <?php if (isset($objEdit)): ?>
          <a href="index.php?page=seguro" class="btn btn-secondary">Cancelar</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<div class="page-card p-0">
  <div class="card-head">
    <h5 class="card-title">Seguros Registrados</h5>
    <span class="badge bg-secondary"><?= count($lista) ?></span>
  </div>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr><th>#</th><th>Locação</th><th>Tipo</th><th>Apólice</th><th>Franquia</th><th>Diária</th><th>Ações</th></tr>
      </thead>
      <tbody>
      <?php foreach ($lista as $item): ?>
        <tr>
          <td><code><?= $item->getId() ?></code></td>
          <td><code>#<?= $item->getIdLocacao() ?></code></td>
          <td><strong><?= h($item->getTipo()) ?></strong></td>
          <td class="text-muted"><?= h($item->getApolice()) ?: '—' ?></td>
          <td>R$ <?= number_format($item->getValorFranquia(), 2, ',', '.') ?></td>
          <td>R$ <?= number_format($item->getValorDiaria(), 2, ',', '.') ?></td>
          <td>
            <a href="index.php?page=seguro&acao=editar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            <a href="index.php?page=seguro&acao=deletar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Excluir seguro?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($lista)): ?>
        <tr><td colspan="7" class="empty-cell">Nenhum seguro registrado.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
