<div class="page-hdr">
  <h1>Marcas</h1>
  <p>Cadastro de marcas de veículos</p>
</div>

<div class="page-card p-0 mb-4">
  <div class="card-head">
    <h5 class="card-title"><?= isset($objEdit) ? 'Editar Marca' : 'Nova Marca' ?></h5>
  </div>
  <div class="card-body-pad">
    <form method="post" action="index.php?page=marca">
      <input type="hidden" name="id" value="<?= isset($objEdit) ? $objEdit->getId() : '' ?>">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nome *</label>
          <input type="text" name="nome" class="form-control" required
                 value="<?= isset($objEdit) ? h($objEdit->getNome()) : '' ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">País de Origem</label>
          <input type="text" name="pais_origem" class="form-control"
                 value="<?= isset($objEdit) ? h($objEdit->getPaisOrigem()) : '' ?>">
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" name="acao" value="salvar" class="btn btn-primary">
          <i class="bi bi-check-lg"></i><?= isset($objEdit) ? 'Atualizar' : 'Salvar' ?>
        </button>
        <?php if (isset($objEdit)): ?>
          <a href="index.php?page=marca" class="btn btn-secondary">Cancelar</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<div class="page-card p-0">
  <div class="card-head">
    <h5 class="card-title">Marcas Cadastradas</h5>
    <span class="badge bg-secondary"><?= count($lista) ?></span>
  </div>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr><th>#</th><th>Nome</th><th>País</th><th>Ações</th></tr>
      </thead>
      <tbody>
      <?php foreach ($lista as $item): ?>
        <tr>
          <td><code><?= $item->getId() ?></code></td>
          <td><strong><?= h($item->getNome()) ?></strong></td>
          <td><?= h($item->getPaisOrigem()) ?: '<span class="text-muted">—</span>' ?></td>
          <td>
            <a href="index.php?page=marca&acao=editar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
            <a href="index.php?page=marca&acao=deletar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-danger ms-1" title="Excluir" onclick="return confirm('Excluir marca?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($lista)): ?>
        <tr><td colspan="4" class="empty-cell">Nenhuma marca cadastrada.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
