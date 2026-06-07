<?php
require_once __DIR__ . '/../security/SessionService.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['product_id'])) {
    $productId = (int) $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? max(1, (int) $_POST['quantity']) : 1;
    $action = $_POST['action'];

    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Produto não encontrado no carrinho.'];
        header('Location: cart.php');
        exit;
    }

    $cartItem = &$_SESSION['cart'][$productId];
    $currentQuantity = (int) $cartItem['quantity'];
    $stock = isset($cartItem['stock']) ? (int) $cartItem['stock'] : PHP_INT_MAX;

    if ($action === 'increase') {
        $newQuantity = min($currentQuantity + $quantity, $stock);
        if ($newQuantity === $currentQuantity) {
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Quantidade já chegou ao máximo disponível.'];
        } else {
            $cartItem['quantity'] = $newQuantity;
            $_SESSION['flash'] = ['type' => 'success', 'message' => "Adicionado $quantity item(s) ao carrinho."];
        }
    } elseif ($action === 'decrease') {
        if ($quantity >= $currentQuantity) {
            unset($_SESSION['cart'][$productId]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Produto removido completamente do carrinho.'];
        } else {
            $cartItem['quantity'] = $currentQuantity - $quantity;
            $_SESSION['flash'] = ['type' => 'success', 'message' => "Removido $quantity item(s) do carrinho."];
        }
    }

    header('Location: cart.php');
    exit;
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$cartItems = $_SESSION['cart'] ?? [];
$totalQuantity = array_sum(array_column($cartItems, 'quantity'));
$totalPrice = 0.0;
foreach ($cartItems as $item) {
    $totalPrice += $item['quantity'] * (float) $item['preco'];
}

$pageTitle = 'Carrinho';

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container my-5">
    <?php if ($flash): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
            <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
            <?= htmlspecialchars($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-header mb-0">Carrinho</h2>
    </div>

    <?php if (empty($cartItems)): ?>
        <div class="card form-card">
            <div class="empty-state">
                <div class="alert alert-info">
                    <i class="bi bi-cart3 me-2"></i>
                    <p>Seu carrinho está vazio.</p>
                    <a href="<?= AppConfigConst::PATH_PRODUCTS_LIST ?>" class="btn btn-dark">Ir as compras</a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Preço</th>
                                <th>Quantidade</th>
                                <th>Total</th>
                                <th>Ajustar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['nome']) ?></td>
                                    <td>€<?= htmlspecialchars(number_format((float) $item['preco'], 2, ',', '.')) ?></td>
                                    <td><?= (int) $item['quantity'] ?></td>
                                    <td>€<?= htmlspecialchars(number_format($item['quantity'] * (float) $item['preco'], 2, ',', '.')) ?>
                                    </td>
                                    <td>
                                        <form action="<?php AppConfigConst::PATH_CART ?>" method="POST"
                                            class="d-flex gap-2 align-items-center mb-0">
                                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                            <input type="number" name="quantity" min="1" max="<?= (int) $item['stock'] ?>"
                                                value="1" class="form-control form-control-sm" style="width: 5rem;"
                                                aria-label="Quantidade para ajustar">
                                            <button type="submit" name="action" value="decrease"
                                                class="btn btn-sm btn-outline-danger" title="Remover itens">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <button type="submit" name="action" value="increase"
                                                class="btn btn-sm btn-outline-success" title="Adicionar itens">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <p class="mb-1"><strong>Total de itens:</strong> <?= $totalQuantity ?></p>
                        <p class="mb-0"><strong>Valor total:</strong>
                            €<?= htmlspecialchars(number_format($totalPrice, 2, ',', '.')) ?></p>
                    </div>
                    <div>
                        <a href="<?= AppConfigConst::PATH_ORDER_CHECKOUT ?>" class="btn btn-dark">Finalizar Compra</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.html'; ?>