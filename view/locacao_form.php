<div class="page-hdr">
  <h1>Locações</h1>
  <p>Gestão de contratos de locação de veículos</p>
</div>

<div class="page-card p-0 mb-4">
  <div class="card-head">
    <h5 class="card-title"><?= isset($objEdit) ? 'Editar Locação #'.h($objEdit->getId()) : 'Nova Locação' ?></h5>
  </div>
  <div class="card-body-pad">
    <form method="post" action="index.php?page=locacao" id="frmLocacao">
      <input type="hidden" name="id" value="<?= isset($objEdit) ? $objEdit->getId() : '' ?>">
      <div class="row g-3">

        <div class="col-md-4">
          <label class="form-label">Cliente *</label>
          <select name="id_cliente" class="form-select" required>
            <option value="">Selecione o cliente...</option>
            <?php foreach ($clientes as $c): ?>
              <option value="<?= $c->getId() ?>" <?= (isset($objEdit) && $objEdit->getIdCliente() == $c->getId()) ? 'selected' : '' ?>>
                <?= h($c->getNome()) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Veículo *</label>
          <select name="id_veiculo" class="form-select" required id="selVeiculo">
            <option value="">Selecione o veículo...</option>
            <?php
            $statusLabels = ['disponivel'=>'✓ Disponível','locado'=>'✗ Locado','manutencao'=>'✗ Manutenção','inativo'=>'✗ Inativo'];
            foreach ($veiculos as $v):
              $isEdit    = isset($objEdit) && $objEdit->getIdVeiculo() == $v->getId();
              $disponivel = $v->getStatus() === 'disponivel';
              $disabled  = (!$disponivel && !$isEdit) ? 'disabled' : '';
            ?>
              <option value="<?= $v->getId() ?>"
                      data-diaria="<?= $v->getValorDiaria() ?>"
                      <?= $disabled ?>
                      <?= $isEdit ? 'selected' : '' ?>>
                <?= h($v->getModelo()) ?> — <?= h($v->getPlaca()) ?>
                <?= !$disponivel ? ' ('.$statusLabels[$v->getStatus()].') ' : '' ?>
              </option>
            <?php endforeach; ?>
          </select>
          <div class="form-text" style="font-size:.72rem;color:var(--subtle)">Veículos indisponíveis estão desabilitados.</div>
        </div>

        <div class="col-md-4">
          <label class="form-label">Funcionário *</label>
          <select name="id_funcionario" class="form-select" required>
            <option value="">Selecione o funcionário...</option>
            <?php foreach ($funcionarios as $f): ?>
              <option value="<?= $f->getId() ?>" <?= (isset($objEdit) && $objEdit->getIdFuncionario() == $f->getId()) ? 'selected' : '' ?>>
                <?= h($f->getNome()) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Agência Retirada *</label>
          <select name="id_agencia_retirada" class="form-select" required>
            <option value="">Selecione...</option>
            <?php foreach ($agencias as $a): ?>
              <option value="<?= $a->getId() ?>" <?= (isset($objEdit) && $objEdit->getIdAgenciaRetirada() == $a->getId()) ? 'selected' : '' ?>>
                <?= h($a->getNome()) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Agência Devolução *</label>
          <select name="id_agencia_devolucao" class="form-select" required>
            <option value="">Selecione...</option>
            <?php foreach ($agencias as $a): ?>
              <option value="<?= $a->getId() ?>" <?= (isset($objEdit) && $objEdit->getIdAgenciaDevolucao() == $a->getId()) ? 'selected' : '' ?>>
                <?= h($a->getNome()) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Status</label>
          <select name="status" class="form-select" id="selStatus">
            <?php foreach (['aberta'=>'Aberta','encerrada'=>'Encerrada','cancelada'=>'Cancelada'] as $val => $lbl): ?>
              <option value="<?= $val ?>" <?= (isset($objEdit) && $objEdit->getStatus() === $val) ? 'selected' : '' ?>><?= $lbl ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Data Retirada *</label>
          <input type="date" name="data_retirada" id="dataRet" class="form-control" required
                 value="<?= isset($objEdit) ? h($objEdit->getDataRetirada()) : '' ?>"
                 onchange="calcTotal()">
        </div>

        <div class="col-md-3">
          <label class="form-label">Devolução Prevista *</label>
          <input type="date" name="data_devolucao_prevista" id="dataDev" class="form-control" required
                 value="<?= isset($objEdit) ? h($objEdit->getDataDevolucaoPrevista()) : '' ?>"
                 onchange="calcTotal()">
        </div>

        <div class="col-md-3">
          <label class="form-label">Devolução Real</label>
          <input type="date" name="data_devolucao_real" class="form-control"
                 value="<?= isset($objEdit) ? h($objEdit->getDataDevolucaoReal()) : '' ?>">
        </div>

        <div class="col-md-3">
          <label class="form-label">Valor Diária *</label>
          <div class="input-group">
            <span class="input-group-text" style="background:#f8fafc;border:1.5px solid var(--border);border-right:none;font-size:.8rem;color:var(--muted)">R$</span>
            <input type="number" step="0.01" name="valor_diaria" id="valDiaria" class="form-control" required
                   style="border-left:none"
                   value="<?= isset($objEdit) ? $objEdit->getValorDiaria() : '' ?>"
                   onchange="calcTotal()" oninput="calcTotal()">
          </div>
        </div>

        <div class="col-md-3">
          <label class="form-label">Total de Dias</label>
          <input type="number" name="total_dias" id="totalDias" class="form-control" min="1" readonly
                 style="background:#f8fafc"
                 value="<?= isset($objEdit) ? $objEdit->getTotalDias() : '' ?>">
        </div>

        <div class="col-md-3">
          <label class="form-label">Valor Total</label>
          <div class="input-group">
            <span class="input-group-text" style="background:#f8fafc;border:1.5px solid var(--border);border-right:none;font-size:.8rem;color:var(--muted)">R$</span>
            <input type="number" step="0.01" name="valor_total" id="valorTotal" class="form-control"
                   style="border-left:none;background:#f8fafc" readonly
                   value="<?= isset($objEdit) ? $objEdit->getValorTotal() : '' ?>">
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Observações</label>
          <textarea name="observacoes" class="form-control" rows="2"><?= isset($objEdit) ? h($objEdit->getObservacoes()) : '' ?></textarea>
        </div>

      </div>

      <div class="d-flex gap-2 mt-4">
        <button type="submit" name="acao" value="salvar" class="btn btn-primary">
          <i class="bi bi-check-lg"></i><?= isset($objEdit) ? 'Atualizar Locação' : 'Registrar Locação' ?>
        </button>
        <?php if (isset($objEdit)): ?>
          <a href="index.php?page=locacao" class="btn btn-secondary">Cancelar</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<!-- Lista -->
<div class="page-card p-0">
  <div class="card-head">
    <h5 class="card-title">Locações Registradas</h5>
    <span class="badge bg-secondary"><?= count($lista) ?></span>
  </div>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr><th>#</th><th>Cliente</th><th>Veículo</th><th>Retirada</th><th>Dev. Prevista</th><th>Valor Total</th><th>Status</th><th>Ações</th></tr>
      </thead>
      <tbody>
      <?php
      $statusColors = ['aberta'=>'info','encerrada'=>'success','cancelada'=>'secondary'];
      foreach ($lista as $item): ?>
        <tr>
          <td><code>#<?= $item->getId() ?></code></td>
          <td><?= isset($clientesMap[$item->getIdCliente()]) ? h($clientesMap[$item->getIdCliente()]) : '<span class="text-muted">#'.$item->getIdCliente().'</span>' ?></td>
          <td><?= isset($veiculosMap[$item->getIdVeiculo()]) ? h($veiculosMap[$item->getIdVeiculo()]) : '<span class="text-muted">#'.$item->getIdVeiculo().'</span>' ?></td>
          <td><?= h($item->getDataRetirada()) ?></td>
          <td><?= h($item->getDataDevolucaoPrevista()) ?></td>
          <td><strong>R$ <?= number_format($item->getValorTotal(), 2, ',', '.') ?></strong></td>
          <td><span class="badge bg-<?= $statusColors[$item->getStatus()] ?? 'secondary' ?>"><?= ucfirst($item->getStatus()) ?></span></td>
          <td>
            <a href="index.php?page=locacao&acao=editar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
            <a href="index.php?page=locacao&acao=deletar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-danger ms-1" title="Excluir" onclick="return confirm('Excluir locação #<?= $item->getId() ?>?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($lista)): ?>
        <tr><td colspan="8" class="empty-cell"><i class="bi bi-file-text d-block mb-1" style="font-size:1.5rem;opacity:.3"></i>Nenhuma locação registrada.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function calcTotal() {
  const ret    = document.getElementById('dataRet').value;
  const dev    = document.getElementById('dataDev').value;
  const diaria = parseFloat(document.getElementById('valDiaria').value) || 0;
  if (ret && dev) {
    const d1   = new Date(ret + 'T00:00:00');
    const d2   = new Date(dev + 'T00:00:00');
    const dias = Math.max(1, Math.round((d2 - d1) / 86400000));
    document.getElementById('totalDias').value  = dias;
    document.getElementById('valorTotal').value = (dias * diaria).toFixed(2);
  }
}

// Preencher diária ao selecionar veículo
document.getElementById('selVeiculo').addEventListener('change', function() {
  const opt    = this.options[this.selectedIndex];
  const diaria = parseFloat(opt.dataset.diaria) || 0;
  if (diaria > 0) {
    document.getElementById('valDiaria').value = diaria.toFixed(2);
    calcTotal();
  }
});
</script>
