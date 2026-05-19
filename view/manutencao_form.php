<div class="page-hdr">
  <h1>Manutenções</h1>
  <p>Controle de manutenção preventiva e corretiva</p>
</div>

<div class="page-card p-0 mb-4">
  <div class="card-head">
    <h5 class="card-title"><?= isset($objEdit) ? 'Editar Manutenção' : 'Nova Manutenção' ?></h5>
  </div>
  <div class="card-body-pad">
    <form method="post" action="index.php?page=manutencao" id="frmManutencao">
      <input type="hidden" name="id" value="<?= isset($objEdit) ? $objEdit->getId() : '' ?>">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Veículo *</label>
          <select name="id_veiculo" class="form-select" required>
            <option value="">Selecione...</option>
            <?php foreach ($veiculos as $v): ?>
              <option value="<?= $v->getId() ?>" <?= (isset($objEdit) && $objEdit->getIdVeiculo() == $v->getId()) ? 'selected' : '' ?>>
                <?= h($v->getModelo()) ?> — <?= h($v->getPlaca()) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback">Selecione o veículo.</div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Mecânico/Responsável *</label>
          <select name="id_funcionario" class="form-select" required>
            <option value="">Selecione...</option>
            <?php foreach ($funcionarios as $f): ?>
              <option value="<?= $f->getId() ?>" <?= (isset($objEdit) && $objEdit->getIdFuncionario() == $f->getId()) ? 'selected' : '' ?>>
                <?= h($f->getNome()) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback">Selecione o responsável.</div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Tipo de Manutenção</label>
          <select name="tipo" class="form-select">
            <?php foreach (['preventiva'=>'Preventiva','corretiva'=>'Corretiva','revisao'=>'Revisão'] as $val => $lbl): ?>
              <option value="<?= $val ?>" <?= (isset($objEdit) && $objEdit->getTipo() === $val) ? 'selected' : '' ?>><?= $lbl ?></option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback">Tipo inválido.</div>
        </div>
        <div class="col-md-3">
          <label class="form-label">Data de Entrada *</label>
          <input type="date" name="data_entrada" class="form-control" required value="<?= isset($objEdit) ? h($objEdit->getDataEntrada()) : '' ?>">
          <div class="invalid-feedback">Data de entrada é obrigatória.</div>
        </div>
        <div class="col-md-3">
          <label class="form-label">Data de Saída</label>
          <input type="date" name="data_saida" class="form-control" value="<?= isset($objEdit) ? h($objEdit->getDataSaida()) : '' ?>">
          <div class="invalid-feedback">Data de saída deve ser posterior à data de entrada.</div>
        </div>
        <div class="col-md-3">
          <label class="form-label">Custo (R$)</label>
          <div class="input-group">
            <span class="input-group-text" style="background:#f8fafc;border:1.5px solid var(--border);border-right:none;font-size:.8rem">R$</span>
            <input type="number" step="0.01" name="custo" class="form-control" style="border-left:none" value="<?= isset($objEdit) ? $objEdit->getCusto() : 0 ?>">
            <div class="invalid-feedback">Custo não pode ser negativo.</div>
          </div>
        </div>
        <div class="col-md-3">
          <label class="form-label">KM na Entrada *</label>
          <input type="number" name="quilometragem_entrada" class="form-control" required value="<?= isset($objEdit) ? $objEdit->getQuilometragemEntrada() : 0 ?>">
          <div class="invalid-feedback">Quilometragem é obrigatória e não pode ser negativa.</div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <?php foreach (['aberta'=>'Aberta','em_andamento'=>'Em Andamento','concluida'=>'Concluída'] as $val => $lbl): ?>
              <option value="<?= $val ?>" <?= (isset($objEdit) && $objEdit->getStatus() === $val) ? 'selected' : '' ?>><?= $lbl ?></option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback">Status inválido.</div>
        </div>
        <div class="col-md-12">
          <label class="form-label">Descrição / Serviços Realizados</label>
          <textarea name="descricao" class="form-control" rows="2"><?= isset($objEdit) ? h($objEdit->getDescricao()) : '' ?></textarea>
          <div class="invalid-feedback">Descrição inválida.</div>
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" name="acao" value="salvar" class="btn btn-primary">
          <i class="bi bi-check-lg"></i><?= isset($objEdit) ? 'Atualizar' : 'Registrar Manutenção' ?>
        </button>
        <?php if (isset($objEdit)): ?>
          <a href="index.php?page=manutencao" class="btn btn-secondary">Cancelar</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<div class="page-card p-0">
  <div class="card-head">
    <h5 class="card-title">Manutenções Registradas</h5>
    <span class="badge bg-secondary"><?= count($lista) ?></span>
  </div>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr><th>#</th><th>Veículo</th><th>Responsável</th><th>Tipo</th><th>Entrada</th><th>Custo</th><th>KM</th><th>Status</th><th>Ações</th></tr>
      </thead>
      <tbody>
      <?php
      $statusColors = ['aberta'=>'warning','em_andamento'=>'info','concluida'=>'success'];
      $statusLabels = ['aberta'=>'Aberta','em_andamento'=>'Em Andamento','concluida'=>'Concluída'];
      $tipoLabels   = ['preventiva'=>'Preventiva','corretiva'=>'Corretiva','revisao'=>'Revisão'];
      foreach ($lista as $item): ?>
        <tr>
          <td><code><?= $item->getId() ?></code></td>
          <td><?= isset($veiculosMap[$item->getIdVeiculo()]) ? h($veiculosMap[$item->getIdVeiculo()]) : '—' ?></td>
          <td><?= isset($funcionariosMap[$item->getIdFuncionario()]) ? h($funcionariosMap[$item->getIdFuncionario()]) : '—' ?></td>
          <td><?= $tipoLabels[$item->getTipo()] ?? $item->getTipo() ?></td>
          <td><?= h($item->getDataEntrada()) ?></td>
          <td>R$ <?= number_format($item->getCusto(), 2, ',', '.') ?></td>
          <td class="text-muted"><?= number_format($item->getQuilometragemEntrada(), 0, ',', '.') ?> km</td>
          <td><span class="badge bg-<?= $statusColors[$item->getStatus()] ?? 'secondary' ?>"><?= $statusLabels[$item->getStatus()] ?? $item->getStatus() ?></span></td>
          <td>
            <a href="index.php?page=manutencao&acao=editar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            <a href="index.php?page=manutencao&acao=deletar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Excluir manutenção?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($lista)): ?>
        <tr><td colspan="9" class="empty-cell">Nenhuma manutenção registrada.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
