<?php
require_once __DIR__ . '/../security/SessionService.php';
include('ProductService.php');

session_start();

$productService = new ProductService();
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$product = $productService->getProductById($id);

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container my-5">
    <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
            <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
            <?= htmlspecialchars($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Back Button -->
    <div class="mb-4">
        <a href="list-product.php" class="btn btn-outline-dark">
            <i class="bi bi-arrow-bar-left"></i>
            Voltar
        </a>
    </div>

    <article class="view-article">
        <div class="row g-5">
            <!-- Left Column: Image -->
            <div class="col-lg-6">
                <!--Image -->
                <?php if (!empty($product['imagem'])): ?>
                    <div class="view-image-container">
                        <img src="<?= AppConfigConst::PATH_PRODUCTS_UPLOADS . htmlspecialchars($product['imagem']) ?>"
                            alt="<?= htmlspecialchars($product['nome']) ?>" class="view-image">
                    </div>
                <?php else: ?>
                    <div class="card-image-placeholder">
                        <div>
                            <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="mt-2">Sem imagem disponível</p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Data criacao -->
                <div class="view-meta mt-3">
                    <span class="view-date"><i class="bi bi-calendar3"></i>
                        <?= date('d-m-Y', strtotime($product['data_criacao'])) ?>
                    </span>
                </div>
            </div>

            <!-- Right Column: Details & Purchase -->
            <div class="col-lg-6 d-flex flex-column">
                <!-- Nome -->
                <h1 class="view-title mb-4">
                    <?= htmlspecialchars($product['nome']) ?>
                </h1>

                <!-- Preço -->
                <div class="mb-4">
                    <p class="text-muted mb-2">Preço</p>
                    <div class="price-tag">
                        €<?= htmlspecialchars(number_format($product['preco'], 2, ',', '.')) ?>
                    </div>
                </div>

                <!-- Stock Status -->
                <div class="mb-4">
                    <p class="text-muted mb-2">Disponibilidade</p>
                    <?php if ((int) $product['stock'] > 0): ?>
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i>
                            <?= (int) $product['stock'] ?> unidades disponíveis
                        </span>
                    <?php else: ?>
                        <span class="badge bg-danger">
                            <i class="bi bi-x-circle me-1"></i>
                            Fora de estoque
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Descrição -->
                <div class="mb-4">
                    <p class="text-muted mb-2">Descrição</p>
                    <div class="view-content">
                        <?= htmlspecialchars($product['descricao']) ?>
                    </div>
                </div>

                <!-- Quantity & Add to Basket - Push to bottom -->
                <div class="mt-auto">
                    <?php if ((int) $product['stock'] > 0): ?>
                        <form id="addToBasketForm" action="<?= AppConfigConst::PATH_ADD_TO_CART ?>" method="POST"
                            class="mt-4 pt-3 border-top">
                            <div class="row g-2">
                                <div class="col-auto">
                                    <label for="quantity" class="form-label">Quantidade</label>
                                    <input type="number" id="quantity" name="quantity" class="form-control" min="1"
                                        max="<?= (int) $product['stock'] ?>" value="1" required>
                                    <small class="text-muted d-block mt-1">Máx: <?= (int) $product['stock'] ?></small>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-dark btn-lg mt-4 w-100" name="product_id"
                                value="<?= (int) $product['id'] ?>">
                                <i class=" bi bi-cart-plus me-2"></i>
                                Adicionar ao Carrinho
                            </button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-lg w-100 mt-4" disabled>
                            <i class="bi bi-exclamation-circle me-2"></i>
                            Fora de Estoque
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </article>
</div>

<?php require_once __DIR__ . '/../includes/footer.html'; ?>