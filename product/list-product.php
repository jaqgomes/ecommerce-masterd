<?php
require_once __DIR__ . '/../security/SessionService.php';

include 'ProductService.php';

session_start();

$productService = new ProductService();
$productList = $productService->getAllProduct();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

require_once __DIR__ . '/../includes/header.php';
?>

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
        <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="page-header mb-0">Produtos</h2>
    <?php if (SessionService::isAdmin()): ?>
        <a href="<?= AppConfigConst::PATH_PRODUCTS_MANAGER ?>" class="btn btn-dark">
            <i class="bi bi-plus-lg me-1"></i>Gerenciar</a>
    <?php endif; ?>
</div>

<?php if (empty($productList)): ?>
    <h1>Nenhum Produto</h1>
<?php else: ?>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($productList as $product) { ?>
            <div class="col">
                <div class="card h-100 position-relative">
                    <?php if (!empty($product['imagem'])): ?>
                        <img src="<?= AppConfigConst::PATH_PRODUCTS_UPLOADS . htmlspecialchars($product['imagem']) ?>"
                            class="card-img-top img-preview card-image" alt="<?= htmlspecialchars($product['nome']) ?>">
                    <?php else: ?>
                        <div class="card-img-top card-image-placeholder">
                            <span>Sem imagem</span>
                        </div>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <div class="flex-grow-1">
                            <h5 class="card-title"><?= $product['nome'] ?></h5>
                            <p class="card-text mb-3">
                                <span class="fw-semibold">Categoria:</span>
                                <?= htmlspecialchars($product['categoria'] ?: 'Sem categoria') ?>
                            </p>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="price-tag">
                                    €<?= htmlspecialchars(number_format($product['preco'], 2, ',', '.')) ?>
                                </div>
                                <?php if ((int) $product['stock'] > 0): ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-box2 me-1"></i>
                                        <?= (int) $product['stock'] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        Sem stock
                                    </span>
                                <?php endif; ?>
                            </div>

                            <hr class="my-3">
                        </div>

                        <div class="d-flex gap-2 justify-content-between">
                            <?php if ((int) $product['stock'] > 0): ?>
                                <form action="<?= AppConfigConst::PATH_ADD_TO_CART ?>" method="POST" class="mb-0">
                                    <button type="submit" class="btn btn-success btn-sm" name="product_id"
                                        value="<?= (int) $product['id'] ?>">
                                        <i class="bi bi-cart-plus me-1"></i>
                                        Carrinho
                                    </button>
                                </form>
                            <?php else: ?>
                                <button type="button" class="btn btn-secondary btn-sm" disabled>
                                    <i class="bi bi-cart-x me-1"></i>
                                    Indisponível
                                </button>
                            <?php endif; ?>
                        </div>
                        <a href="<?= AppConfigConst::PATH_PRODUCTS_VIEW . "?id=" . $product['id'] ?>" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.html'; ?>