<?php
require_once __DIR__ . '/../security/SessionService.php';

// Flash messages via session
session_start();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
SessionService::isRequireLogin();

include 'OrderService.php';

$pageTitle = 'Detalhes Encomenda -Ecommerce MasterD';

$orderService = new OrderService();
$id_encomenda = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$id_utilizador = $_SESSION['user_id'];

// Fetch order and authorize
$order = $orderService->getOrderById($id_encomenda);
if (!$order) {
    header('Location: ' . AppConfigConst::PATH_ORDER_LIST);
    exit;
}
if (!SessionService::isAdmin() && (int)$order['id_utilizador'] !== (int)$id_utilizador) {
    header('Location: ' . AppConfigConst::PATH_ORDER_LIST);
    exit;
}

// Fetch order items
if (SessionService::isAdmin()) {
    $orderDetailList = $orderService->getOrderDetailByOrderId($id_encomenda);
} else {
    $orderDetailList = $orderService->getOrderDetailByUserIdAndOrderId($id_utilizador, $id_encomenda);
}

require_once __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="page-header mb-0">Detalhes Encomenda</h2>
</div>

<div class="card mb-3">
    <div class="card-body">
        <p class="mb-1"><strong>Encomenda #:</strong> <?= htmlspecialchars($order['id']) ?></p>
        <p class="mb-1"><strong>Data:</strong> <?= htmlspecialchars($order['data_encomenda']) ?></p>
        <p class="mb-1"><strong>Total:</strong> €<?= htmlspecialchars(number_format($order['total'], 2, ',', '.')) ?></p>
        <p class="mb-1"><strong>Estado:</strong> <?= htmlspecialchars($order['estado']) ?></p>
        <p class="mb-0"><strong>Morada de entrega:</strong> <?= htmlspecialchars($order['morada'] ?? '') ?></p>
    </div>
</div>

<?php if ($flash): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
        <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Item</th>
            <th scope="col">Preço</th>
            <th scope="col">Quantidade</th>
            <th scope="col" hidden="<?= SessionService::isAdmin() ?>">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orderDetailList as $orderDetail) { ?>
            <tr>
                <td>
                    <?= $orderDetail['nome_produto'] ?>
                </td>
                <td>
                    <?= $orderDetail['quantidade'] ?>
                </td>
                <td>
                    <?= $orderDetail['preco_unitario'] ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.html'; ?>

<?php require_once __DIR__ . '/../includes/footer.html'; ?>