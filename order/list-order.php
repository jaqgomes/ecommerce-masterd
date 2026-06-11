<?php
require_once __DIR__ . '/../security/SessionService.php';

// Flash messages via session
session_start();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
SessionService::isRequireLogin();

include 'OrderService.php';

$pageTitle = 'Encomenda -Ecommerce MasterD';

$orderService = new OrderService();
$orderList = null;
if (SessionService::isAdmin()) {
    $orderList = $orderService->getAllOrder();
} else {
    $id_utilizador = $_SESSION['user_id'];
    $orderList = $orderService->getAllOrderByUserId($id_utilizador);
}

require_once __DIR__ . '/../' . AppConfigConst::PATH_HEADER;
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="page-header mb-0">Encomendas</h2>
</div>

<?php if ($flash): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
        <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (empty($orderList)): ?>

    <div class="card form-card">
        <div class="empty-state">
            <h4>Nenhum pedido realizado. </h4>
        </div>
    </div>

<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Cliente</th>
                <th scope="col">Data da Encomenda</th>
                <th scope="col">Total</th>
                <th scope="col">Estado</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($orderList as $order) { ?>
                <tr class="position-relative">
                    <td>
                        <?= $order['id'] ?>
                        <a href="<?= AppConfigConst::path(AppConfigConst::PATH_ORDER_VIEW) ?> ?id= <?= $order['id'] ?>" class="stretched-link"></a>
                    </td>
                    <td>
                        <?= $order['utilizador'] ?>
                    </td>
                    <td>
                        <?= $order['data_encomenda'] ?>
                    </td>
                    <td>
                        <?= $order['total'] ?>
                    </td>
                    <td>
                        <?= $order['estado'] ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<?php endif; ?>

<?php require_once __DIR__ . '/../' . AppConfigConst::PATH_FOOTER; ?>