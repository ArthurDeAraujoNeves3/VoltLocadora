<div class="page-hdr">
  <h1>Veículos</h1>
  <p>Gerenciamento da frota de veículos</p>
</div>

<div class="page-card p-0 mb-4">
  <div class="card-head">
    <h5 class="card-title"><?= isset($objEdit) ? 'Editar Veículo' : 'Novo Veículo' ?></h5>
  </div>
  <div class="card-body-pad">
    <form method="post" action="index.php?page=veiculo">
      <input type="hidden" name="id" value="<?= isset($objEdit) ? $objEdit->getId() : '' ?>">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Modelo *</label>
          <input type="text" name="modelo" class="form-control" required value="<?= isset($objEdit) ? h($objEdit->getModelo()) : '' ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Marca *</label>
          <select name="id_marca" class="form-select" required>
            <option value="">Selecione...</option>
            <?php foreach ($marcas as $m): ?>
              <option value="<?= $m->getId() ?>" <?= (isset($objEdit) && $objEdit->getIdMarca() == $m->getId()) ? 'selected' : '' ?>>
                <?= h($m->getNome()) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Categoria *</label>
          <select name="id_categoria" class="form-select" required>
            <option value="">Selecione...</option>
            <?php foreach ($categorias as $c): ?>
              <option value="<?= $c->getId() ?>" <?= (isset($objEdit) && $objEdit->getIdCategoria() == $c->getId()) ? 'selected' : '' ?>>
                <?= h($c->getNome()) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">Ano Fab. *</label>
          <input type="number" name="ano_fabricacao" class="form-control" required min="1950" max="2100" value="<?= isset($objEdit) ? $objEdit->getAnoFabricacao() : '' ?>">
        </div>
        <div class="col-md-2">
          <label class="form-label">Ano Modelo *</label>
          <input type="number" name="ano_modelo" class="form-control" required min="1950" max="2100" value="<?= isset($objEdit) ? $objEdit->getAnoModelo() : '' ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Placa *</label>
          <input type="text" name="placa" class="form-control" required maxlength="10" value="<?= isset($objEdit) ? h($objEdit->getPlaca()) : '' ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Chassi *</label>
          <input type="text" name="chassi" class="form-control" required maxlength="17" value="<?= isset($objEdit) ? h($objEdit->getChassi()) : '' ?>">
        </div>
        <div class="col-md-2">
          <label class="form-label">Cor</label>
          <input type="text" name="cor" class="form-control" value="<?= isset($objEdit) ? h($objEdit->getCor()) : '' ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Valor Diária *</label>
          <div class="input-group">
            <span class="input-group-text" style="background:#f8fafc;border:1.5px solid var(--border);border-right:none;font-size:.8rem">R$</span>
            <input type="number" step="0.01" name="valor_diaria" class="form-control" required style="border-left:none" value="<?= isset($objEdit) ? $objEdit->getValorDiaria() : '' ?>">
          </div>
        </div>
        <div class="col-md-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <?php foreach (['disponivel'=>'Disponível','locado'=>'Locado','manutencao'=>'Manutenção','inativo'=>'Inativo'] as $val => $lbl): ?>
              <option value="<?= $val ?>" <?= (isset($objEdit) && $objEdit->getStatus() === $val) ? 'selected' : '' ?>><?= $lbl ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Quilometragem</label>
          <input type="number" name="quilometragem" class="form-control" value="<?= isset($objEdit) ? $objEdit->getQuilometragem() : 0 ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Combustível</label>
          <select name="combustivel" class="form-select">
            <?php foreach (['flex'=>'Flex','gasolina'=>'Gasolina','etanol'=>'Etanol','diesel'=>'Diesel','eletrico'=>'Elétrico','hibrido'=>'Híbrido'] as $val => $lbl): ?>
              <option value="<?= $val ?>" <?= (isset($objEdit) && $objEdit->getCombustivel() === $val) ? 'selected' : '' ?>><?= $lbl ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">Portas</label>
          <input type="number" name="num_portas" class="form-control" min="2" max="6" value="<?= isset($objEdit) ? $objEdit->getNumPortas() : 4 ?>">
        </div>
        <div class="col-md-2">
          <label class="form-label">Passageiros</label>
          <input type="number" name="capacidade_passageiros" class="form-control" min="1" max="20" value="<?= isset($objEdit) ? $objEdit->getCapacidadePassageiros() : 5 ?>">
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" name="acao" value="salvar" class="btn btn-primary">
          <i class="bi bi-check-lg"></i><?= isset($objEdit) ? 'Atualizar' : 'Salvar' ?>
        </button>
        <?php if (isset($objEdit)): ?>
          <a href="index.php?page=veiculo" class="btn btn-secondary">Cancelar</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<div class="page-card p-0">
  <div class="card-head">
    <h5 class="card-title">Frota de Veículos</h5>
    <span class="badge bg-secondary"><?= count($lista) ?></span>
  </div>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr><th>#</th><th>Modelo</th><th>Placa</th><th>Marca</th><th>Categoria</th><th>Diária</th><th>KM</th><th>Status</th><th>Ações</th></tr>
      </thead>
      <tbody>
      <?php
      $statusColors = ['disponivel'=>'success','locado'=>'warning','manutencao'=>'danger','inativo'=>'secondary'];
      $statusLabels = ['disponivel'=>'Disponível','locado'=>'Locado','manutencao'=>'Manutenção','inativo'=>'Inativo'];
      foreach ($lista as $item): ?>
        <tr>
          <td><code><?= $item->getId() ?></code></td>
          <td>
            <strong><?= h($item->getModelo()) ?></strong>
            <small class="text-muted ms-1"><?= $item->getAnoModelo() ?></small>
          </td>
          <td><code><?= h($item->getPlaca()) ?></code></td>
          <td><?= isset($marcasMap[$item->getIdMarca()]) ? h($marcasMap[$item->getIdMarca()]) : '—' ?></td>
          <td><?= isset($categoriasMap[$item->getIdCategoria()]) ? h($categoriasMap[$item->getIdCategoria()]) : '—' ?></td>
          <td>R$ <?= number_format($item->getValorDiaria(), 2, ',', '.') ?></td>
          <td class="text-muted"><?= number_format($item->getQuilometragem(), 0, ',', '.') ?> km</td>
          <td><span class="badge bg-<?= $statusColors[$item->getStatus()] ?? 'secondary' ?>"><?= $statusLabels[$item->getStatus()] ?? $item->getStatus() ?></span></td>
          <td>
            <a href="index.php?page=veiculo&acao=editar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            <a href="index.php?page=veiculo&acao=deletar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Excluir veículo?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($lista)): ?>
        <tr><td colspan="9" class="empty-cell">Nenhum veículo cadastrado.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
