<div class="page-hdr">
  <h1>Funcionários</h1>
  <p>Equipe de atendimento das agências</p>
</div>

<div class="page-card p-0 mb-4">
  <div class="card-head">
    <h5 class="card-title"><?= isset($objEdit) ? 'Editar Funcionário' : 'Novo Funcionário' ?></h5>
  </div>
  <div class="card-body-pad">
    <form method="post" action="index.php?page=funcionario" id="frmFuncionario">
      <input type="hidden" name="id" value="<?= isset($objEdit) ? $objEdit->getId() : '' ?>">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nome *</label>
          <input type="text" name="nome" class="form-control" required value="<?= isset($objEdit) ? h($objEdit->getNome()) : '' ?>">
          <div class="invalid-feedback">Nome é obrigatório.</div>
        </div>
        <div class="col-md-3">
          <label class="form-label">CPF *</label>
          <input type="text" name="cpf" class="form-control" required maxlength="14" value="<?= isset($objEdit) ? h($objEdit->getCpf()) : '' ?>">
          <div class="invalid-feedback">CPF inválido.</div>
        </div>
        <div class="col-md-3">
          <label class="form-label">Cargo</label>
          <input type="text" name="cargo" class="form-control" value="<?= isset($objEdit) ? h($objEdit->getCargo()) : '' ?>">
          <div class="invalid-feedback">Cargo máx. 80 caracteres.</div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Agência *</label>
          <select name="id_agencia" class="form-select" required>
            <option value="">Selecione...</option>
            <?php foreach ($agencias as $ag): ?>
              <option value="<?= $ag->getId() ?>" <?= (isset($objEdit) && $objEdit->getIdAgencia() == $ag->getId()) ? 'selected' : '' ?>>
                <?= h($ag->getNome()) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback">Selecione a agência.</div>
        </div>
        <div class="col-md-4">
          <label class="form-label">E-mail</label>
          <input type="email" name="email" class="form-control" value="<?= isset($objEdit) ? h($objEdit->getEmail()) : '' ?>">
          <div class="invalid-feedback">E-mail inválido.</div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Telefone</label>
          <input type="text" name="telefone" class="form-control" value="<?= isset($objEdit) ? h($objEdit->getTelefone()) : '' ?>">
          <div class="invalid-feedback">Telefone inválido. Mínimo 10 dígitos.</div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Data Admissão</label>
          <input type="date" name="data_admissao" class="form-control" value="<?= isset($objEdit) ? h($objEdit->getDataAdmissao()) : '' ?>">
          <div class="invalid-feedback">Data de admissão inválida.</div>
        </div>
        <div class="col-md-4 d-flex align-items-end pb-1">
          <div class="form-check">
            <input type="checkbox" name="ativo" value="1" class="form-check-input" id="chkAtivo"
                   <?= (!isset($objEdit) || $objEdit->getAtivo()) ? 'checked' : '' ?>>
            <label class="form-check-label" for="chkAtivo">Funcionário Ativo</label>
          </div>
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" name="acao" value="salvar" class="btn btn-primary">
          <i class="bi bi-check-lg"></i><?= isset($objEdit) ? 'Atualizar' : 'Salvar' ?>
        </button>
        <?php if (isset($objEdit)): ?>
          <a href="index.php?page=funcionario" class="btn btn-secondary">Cancelar</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<div class="page-card p-0">
  <div class="card-head">
    <h5 class="card-title">Funcionários Cadastrados</h5>
    <span class="badge bg-secondary"><?= count($lista) ?></span>
  </div>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr><th>#</th><th>Nome</th><th>CPF</th><th>Cargo</th><th>Agência</th><th>Status</th><th>Ações</th></tr>
      </thead>
      <tbody>
      <?php foreach ($lista as $item): ?>
        <tr>
          <td><code><?= $item->getId() ?></code></td>
          <td><strong><?= h($item->getNome()) ?></strong></td>
          <td class="text-muted"><?= h($item->getCpf()) ?></td>
          <td><?= h($item->getCargo()) ?: '<span class="text-muted">—</span>' ?></td>
          <td><?= isset($agenciasMap[$item->getIdAgencia()]) ? h($agenciasMap[$item->getIdAgencia()]) : '<span class="text-muted">#'.$item->getIdAgencia().'</span>' ?></td>
          <td><?= $item->getAtivo() ? '<span class="badge bg-success">Ativo</span>' : '<span class="badge bg-secondary">Inativo</span>' ?></td>
          <td>
            <a href="index.php?page=funcionario&acao=editar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            <a href="index.php?page=funcionario&acao=deletar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Excluir funcionário?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($lista)): ?>
        <tr><td colspan="7" class="empty-cell">Nenhum funcionário cadastrado.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
