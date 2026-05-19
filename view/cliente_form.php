<div class="page-hdr">
  <h1>Clientes</h1>
  <p>Cadastro de clientes habilitados para locação</p>
</div>

<div class="page-card p-0 mb-4">
  <div class="card-head">
    <h5 class="card-title"><?= isset($objEdit) ? 'Editar Cliente' : 'Novo Cliente' ?></h5>
  </div>
  <div class="card-body-pad">
    <form method="post" action="index.php?page=cliente" id="frmCliente">
      <input type="hidden" name="id" value="<?= isset($objEdit) ? $objEdit->getId() : '' ?>">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nome Completo *</label>
          <input type="text" name="nome" class="form-control" required value="<?= isset($objEdit) ? h($objEdit->getNome()) : '' ?>">
          <div class="invalid-feedback">Nome é obrigatório.</div>
        </div>
        <div class="col-md-3">
          <label class="form-label">CPF *</label>
          <input type="text" name="cpf" class="form-control" required maxlength="14" value="<?= isset($objEdit) ? h($objEdit->getCpf()) : '' ?>">
          <div class="invalid-feedback">CPF inválido.</div>
        </div>
        <div class="col-md-3">
          <label class="form-label">Telefone</label>
          <input type="text" name="telefone" class="form-control" value="<?= isset($objEdit) ? h($objEdit->getTelefone()) : '' ?>">
          <div class="invalid-feedback">Telefone inválido. Mínimo 10 dígitos.</div>
        </div>
        <div class="col-md-5">
          <label class="form-label">E-mail</label>
          <input type="email" name="email" class="form-control" value="<?= isset($objEdit) ? h($objEdit->getEmail()) : '' ?>">
          <div class="invalid-feedback">E-mail inválido.</div>
        </div>
        <div class="col-md-4">
          <label class="form-label">CNH *</label>
          <input type="text" name="cnh" class="form-control" required maxlength="20" value="<?= isset($objEdit) ? h($objEdit->getCnh()) : '' ?>">
          <div class="invalid-feedback">CNH é obrigatória.</div>
        </div>
        <div class="col-md-3">
          <label class="form-label">Vencimento CNH</label>
          <input type="date" name="vencimento_cnh" class="form-control" value="<?= isset($objEdit) ? h($objEdit->getVencimentoCnh()) : '' ?>">
          <div class="invalid-feedback">Vencimento da CNH inválido.</div>
        </div>
        <div class="col-md-7">
          <label class="form-label">Endereço</label>
          <input type="text" name="endereco" class="form-control" value="<?= isset($objEdit) ? h($objEdit->getEndereco()) : '' ?>">
          <div class="invalid-feedback">Endereço máx. 255 caracteres.</div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Cidade</label>
          <input type="text" name="cidade" class="form-control" value="<?= isset($objEdit) ? h($objEdit->getCidade()) : '' ?>">
          <div class="invalid-feedback">Cidade máx. 100 caracteres.</div>
        </div>
        <div class="col-md-2">
          <label class="form-label">UF</label>
          <input type="text" name="estado" class="form-control" maxlength="2" value="<?= isset($objEdit) ? h($objEdit->getEstado()) : '' ?>">
          <div class="invalid-feedback">UF deve ter 2 letras.</div>
        </div>
        <div class="col-md-3">
          <label class="form-label">CEP</label>
          <input type="text" name="cep" class="form-control" maxlength="9" value="<?= isset($objEdit) ? h($objEdit->getCep()) : '' ?>">
          <div class="invalid-feedback">CEP inválido. Use XXXXX-XXX.</div>
        </div>
        <div class="col-md-3 d-flex align-items-end pb-1">
          <div class="form-check">
            <input type="checkbox" name="ativo" value="1" class="form-check-input" id="chkAtivo"
                   <?= (!isset($objEdit) || $objEdit->getAtivo()) ? 'checked' : '' ?>>
            <label class="form-check-label" for="chkAtivo">Cliente Ativo</label>
          </div>
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" name="acao" value="salvar" class="btn btn-primary">
          <i class="bi bi-check-lg"></i><?= isset($objEdit) ? 'Atualizar' : 'Salvar' ?>
        </button>
        <?php if (isset($objEdit)): ?>
          <a href="index.php?page=cliente" class="btn btn-secondary">Cancelar</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<div class="page-card p-0">
  <div class="card-head">
    <h5 class="card-title">Clientes Cadastrados</h5>
    <span class="badge bg-secondary"><?= count($lista) ?></span>
  </div>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr><th>#</th><th>Nome</th><th>CPF</th><th>CNH</th><th>Cidade/UF</th><th>Status</th><th>Ações</th></tr>
      </thead>
      <tbody>
      <?php foreach ($lista as $item): ?>
        <tr>
          <td><code><?= $item->getId() ?></code></td>
          <td><strong><?= h($item->getNome()) ?></strong></td>
          <td class="text-muted"><?= h($item->getCpf()) ?></td>
          <td><code><?= h($item->getCnh()) ?></code></td>
          <td><?= h($item->getCidade()) ?><?= $item->getEstado() ? '/'.h($item->getEstado()) : '' ?></td>
          <td><?= $item->getAtivo() ? '<span class="badge bg-success">Ativo</span>' : '<span class="badge bg-secondary">Inativo</span>' ?></td>
          <td>
            <a href="index.php?page=cliente&acao=editar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            <a href="index.php?page=cliente&acao=deletar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Excluir cliente?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($lista)): ?>
        <tr><td colspan="7" class="empty-cell">Nenhum cliente cadastrado.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
