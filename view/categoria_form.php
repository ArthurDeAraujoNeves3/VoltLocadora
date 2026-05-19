<div class="page-hdr">
  <h1>Categorias</h1>
  <p>Classificação dos tipos de veículos</p>
</div>

<div class="page-card p-0 mb-4">
  <div class="card-head">
    <h5 class="card-title"><?= isset($objEdit) ? 'Editar Categoria' : 'Nova Categoria' ?></h5>
  </div>
  <div class="card-body-pad">
    <form method="post" action="index.php?page=categoria" id="frmCategoria">
      <input type="hidden" name="id" value="<?= isset($objEdit) ? $objEdit->getId() : '' ?>">
      <div class="row g-3">
        <div class="col-md-5">
          <label class="form-label">Nome *</label>
          <input type="text" name="nome" class="form-control" required
                 value="<?= isset($objEdit) ? h($objEdit->getNome()) : '' ?>">
          <div class="invalid-feedback">Nome é obrigatório.</div>
        </div>
        <div class="col-md-3">
          <label class="form-label">Código *</label>
          <input type="text" name="codigo" class="form-control" required maxlength="20"
                 value="<?= isset($objEdit) ? h($objEdit->getCodigo()) : '' ?>">
          <div class="invalid-feedback">Código é obrigatório (máx. 20 caracteres).</div>
        </div>
        <div class="col-md-12">
          <label class="form-label">Descrição</label>
          <textarea name="descricao" class="form-control" rows="2"><?= isset($objEdit) ? h($objEdit->getDescricao()) : '' ?></textarea>
          <div class="invalid-feedback">Descrição inválida.</div>
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" name="acao" value="salvar" class="btn btn-primary">
          <i class="bi bi-check-lg"></i><?= isset($objEdit) ? 'Atualizar' : 'Salvar' ?>
        </button>
        <?php if (isset($objEdit)): ?>
          <a href="index.php?page=categoria" class="btn btn-secondary">Cancelar</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<div class="page-card p-0">
  <div class="card-head">
    <h5 class="card-title">Categorias Cadastradas</h5>
    <span class="badge bg-secondary"><?= count($lista) ?></span>
  </div>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr><th>#</th><th>Nome</th><th>Código</th><th>Descrição</th><th>Ações</th></tr>
      </thead>
      <tbody>
      <?php foreach ($lista as $item): ?>
        <tr>
          <td><code><?= $item->getId() ?></code></td>
          <td><strong><?= h($item->getNome()) ?></strong></td>
          <td><span class="badge bg-secondary"><?= h($item->getCodigo()) ?></span></td>
          <td class="text-muted"><?= h($item->getDescricao()) ?: '—' ?></td>
          <td>
            <a href="index.php?page=categoria&acao=editar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
            <a href="index.php?page=categoria&acao=deletar&id=<?= $item->getId() ?>" class="btn btn-sm btn-outline-danger ms-1" title="Excluir" onclick="return confirm('Excluir categoria?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($lista)): ?>
        <tr><td colspan="5" class="empty-cell">Nenhuma categoria cadastrada.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
