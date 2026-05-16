<div class="page-hdr">
  <h1>Agências</h1>
  <p>Filiais e unidades da locadora</p>
</div>

<div class="page-card p-0 mb-4">
  <div class="card-head">
    <h5 class="card-title"><?= isset($objEdit) ? 'Editar Agência' : 'Nova Agência' ?></h5>
  </div>
  <div class="card-body-pad">
    <form method="post" action="index.php?page=agencia">
      <input type="hidden" name="id" value="<?= isset($objEdit) ? $objEdit->getId() : '' ?>">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nome *</label>
          <input type="text" name="nome" class="form-control" required value="<?= isset($objEdit) ? h($objEdit->getNome()) : '' ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">CNPJ *</label>
          <input type="text" name="cnpj" class="form-control" required maxlength="18" value="<?= isset($objEdit) ? h($objEdit->getCnpj()) : '' ?>">
        </div>
        <div class="col-md-8">
          <label class="form-label">Endereço</label>
          <input type="text" name="endereco" class="form-control" value="<?= isset($objEdit) ? h($objEdit->getEndereco()) : '' ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">CEP</label>
          <input type="text" name="cep" class="form-control" maxlength="9" value="<?= isset($objEdit) ? h($objEdit->getCep()) : '' ?>">
        </div>
        <div class="col-md-5">
          <label class="form-label">Cidade</label>
          <input type="text" name="cidade" class="form-control" value="<?= isset($objEdit) ? h($objEdit->getCidade()) : '' ?>">
        </div>
        <div class="col-md-2">
          <label class="form-label">UF</label>
          <input type="text" name="estado" class="form-control" maxlength="2" value="<?= isset($objEdit) ? h($objEdit->getEstado()) : '' ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Telefone</label>
          <input type="text" name="telefone" class="form-control" value="<?= isset($objEdit) ? h($objEdit->getTelefone()) : '' ?>">
        </div>
        <div class="col-md-5">
          <label class="form-label">E-mail</label>
          <input type="email" name="email" class="form-control" value="<?= isset($objEdit) ? h($objEdit->getEmail()) : '' ?>">
        </div>
        <div class="col-md-3 d-flex align-items-end pb-1">
          <div class="form-check">
            <input type="checkbox" name="matriz" value="1" class="form-check-input" id="chkMatriz"
                   <?= (isset($objEdit) && $objEdit->getMatriz()) ? 'checked' : '' ?>>
            <label class="form-check-label" for="chkMatriz">Unidade Matriz</label>
          </div>
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" name="acao" value="salvar" class="btn btn-primary">
          <i class="bi bi-check-lg"></i><?= isset($objEdit) ? 'Atualizar' : 'Salvar' ?>
        </button>
        <?php if (isset($objEdit)): ?>
          <a href="index.php?page=agencia" class="btn btn-secondary">Cancelar</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<div class="page-card p-0">
  <div class="card-head">
    <h5 class="card-title">Agências Cadastradas</h5>
    <span class="badge bg-secondary"><?= count($lista) ?></span>
  </div>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr><th>#</th><th>Nome</th><th>CNPJ</th><th>Cidade/UF</th><th>Telefone</th><th>Tipo</th><th>Ações</th></tr>
      </thead>
      <tbody>
      <?php foreach ($lista as $item): ?>
        <tr>
          <td><code><?= $item->getId() ?></code></td>
          <td><strong><?= h($item->getNome()) ?></strong></td>
          <td class="text-muted"><?= h($item->getCnpj()) ?></td>
          <td><?= h($item->getCidade()) ?><?= $item->getEstado() ? '/'.h($item->getEstado()) : '' ?></td>
          <td class="text-muted"><?= h($item->getTelefone()) ?: '—' ?></td>
          <td><?= $item->getMatriz() ? '<span class="badge bg-warning">Matriz</span>' : '<span class="badge bg-secondary">Filial</span>' ?></td>
          <td>
            <a href="index.php?page=agencia&acao=editar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            <a href="index.php?page=agencia&acao=deletar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Excluir agência?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($lista)): ?>
        <tr><td colspan="7" class="empty-cell">Nenhuma agência cadastrada.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
