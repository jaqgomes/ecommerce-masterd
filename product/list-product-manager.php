<?php
require_once __DIR__ . '/../security/SessionService.php';

// Flash messages via session
session_start();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
SessionService::isRequireAdmin();

include 'ProductService.php';

$pageTitle = 'Ecommerce - MasterD';

$productService = new ProductService();
$productList = $productService->getAllProduct();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="page-header mb-0">Gerenciador de Produtos</h2>
    <a href="<?= AppConfigConst::PATH_PRODUCTS_CREATE ?>" class="btn btn-dark"><i class="bi bi-plus-lg me-1"></i>Adicionar</a>
</div>

<?php if ($flash): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
        <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (empty($productList)): ?>

    <div class="card form-card">
        <div class="empty-state">
            <h4>Nenhuma produto encontrado. </h4>
            <p>Comece adicionando seu primeiro produto.</p>
            <a href="<?= AppConfigConst::PATH_PRODUCTS_CREATE ?>" class="btn btn-dark">Adicionar</a>
        </div>
    </div>

<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Preço</th>
                <th scope="col">Categoria</th>
                <th scope="col">Stock</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($productList as $product) { ?>
                <tr>
                    <td><?= $product['id'] ?></td>
                    <td><?= $product['nome'] ?></td>
                    <td><?= $product['preco'] ?></td>
                    <td><?= $product['categoria'] ?></td>
                    <td><?= $product['stock'] ?></td>
                    <td>
                        <a href="<?= AppConfigConst::PATH_PRODUCTS_EDIT . "?id=" . $product['id'] ?>" class="btn btn-outline-dark btn-sm flex-fill">
                            <i class="bi bi-pencil me-1"></i>Editar
                        </a>
                        <button type="button" class="btn btn-outline-danger btn-sm flex-fill" data-bs-toggle="modal"
                            data-bs-target="#deleteModal" data-id="<?= $product['id'] ?>"
                            data-name="<?= htmlspecialchars($product['nome']) ?>" data-action="<?= AppConfigConst::PATH_PRODUCTS_DELETE ?>">
                            <i class="bi bi-trash me-1"></i>Deletar
                        </button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php include __DIR__ . '/../includes/modal.html'; ?>

<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.html'; ?>