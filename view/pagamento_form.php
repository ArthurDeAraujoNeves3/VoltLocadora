<div class="page-hdr">
  <h1>Pagamentos</h1>
  <p>Controle financeiro das locações</p>
</div>

<div class="page-card p-0 mb-4">
  <div class="card-head">
    <h5 class="card-title"><?= isset($objEdit) ? 'Editar Pagamento' : 'Novo Pagamento' ?></h5>
  </div>
  <div class="card-body-pad">
    <form method="post" action="index.php?page=pagamento" id="frmPagamento">
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
          <div class="invalid-feedback">Selecione a locação.</div>
        </div>
        <div class="col-md-3">
          <label class="form-label">Valor *</label>
          <div class="input-group">
            <span class="input-group-text" style="background:#f8fafc;border:1.5px solid var(--border);border-right:none;font-size:.8rem">R$</span>
            <input type="number" step="0.01" name="valor" class="form-control" required style="border-left:none" value="<?= isset($objEdit) ? $objEdit->getValor() : '' ?>">
            <div class="invalid-feedback">Valor deve ser maior que zero.</div>
          </div>
        </div>
        <div class="col-md-3">
          <label class="form-label">Forma de Pagamento *</label>
          <select name="forma_pagamento" class="form-select" required>
            <?php foreach (['credito'=>'Crédito','debito'=>'Débito','pix'=>'PIX','dinheiro'=>'Dinheiro','boleto'=>'Boleto'] as $val => $lbl): ?>
              <option value="<?= $val ?>" <?= (isset($objEdit) && $objEdit->getFormaPagamento() === $val) ? 'selected' : '' ?>><?= $lbl ?></option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback">Forma de pagamento inválida.</div>
        </div>
        <div class="col-md-2">
          <label class="form-label">Parcelas</label>
          <input type="number" name="parcelas" class="form-control" min="1" value="<?= isset($objEdit) ? $objEdit->getParcelas() : 1 ?>">
          <div class="invalid-feedback">Mínimo 1 parcela.</div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <?php foreach (['pendente'=>'Pendente','aprovado'=>'Aprovado','recusado'=>'Recusado','estornado'=>'Estornado'] as $val => $lbl): ?>
              <option value="<?= $val ?>" <?= (isset($objEdit) && $objEdit->getStatus() === $val) ? 'selected' : '' ?>><?= $lbl ?></option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback">Status inválido.</div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Código da Transação</label>
          <input type="text" name="codigo_transacao" class="form-control" maxlength="100" value="<?= isset($objEdit) ? h($objEdit->getCodigoTransacao()) : '' ?>">
          <div class="invalid-feedback">Código de transação máx. 100 caracteres.</div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Data/Hora do Pagamento</label>
          <input type="datetime-local" name="data_pagamento" class="form-control"
                 value="<?= isset($objEdit) ? str_replace(' ', 'T', h($objEdit->getDataPagamento())) : '' ?>">
          <div class="invalid-feedback">Data/hora do pagamento inválida.</div>
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" name="acao" value="salvar" class="btn btn-primary">
          <i class="bi bi-check-lg"></i><?= isset($objEdit) ? 'Atualizar' : 'Registrar Pagamento' ?>
        </button>
        <?php if (isset($objEdit)): ?>
          <a href="index.php?page=pagamento" class="btn btn-secondary">Cancelar</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<div class="page-card p-0">
  <div class="card-head">
    <h5 class="card-title">Pagamentos Registrados</h5>
    <span class="badge bg-secondary"><?= count($lista) ?></span>
  </div>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr><th>#</th><th>Locação</th><th>Valor</th><th>Forma</th><th>Parcelas</th><th>Status</th><th>Data</th><th>Ações</th></tr>
      </thead>
      <tbody>
      <?php
      $statusColors = ['pendente'=>'warning','aprovado'=>'success','recusado'=>'danger','estornado'=>'secondary'];
      foreach ($lista as $item): ?>
        <tr>
          <td><code><?= $item->getId() ?></code></td>
          <td><code>#<?= $item->getIdLocacao() ?></code></td>
          <td><strong>R$ <?= number_format($item->getValor(), 2, ',', '.') ?></strong></td>
          <td><?= ucfirst($item->getFormaPagamento()) ?></td>
          <td><?= $item->getParcelas() ?>x</td>
          <td><span class="badge bg-<?= $statusColors[$item->getStatus()] ?? 'secondary' ?>"><?= ucfirst($item->getStatus()) ?></span></td>
          <td class="text-muted"><?= h($item->getDataPagamento()) ?></td>
          <td>
            <a href="index.php?page=pagamento&acao=editar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            <a href="index.php?page=pagamento&acao=deletar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Excluir pagamento?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($lista)): ?>
        <tr><td colspan="8" class="empty-cell">Nenhum pagamento registrado.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
