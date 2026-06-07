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

require_once __DIR__ . '/../includes/header.php';
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
                <th scope="col" hidden="<?= SessionService::isAdmin() ?>">Ações</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($orderList as $order) { ?>
                <tr class="position-relative">
                    <td>
                        <?= $order['id'] ?>
                        <a href="view-order.php?id=<?= $order['id'] ?>" class="stretched-link"></a>
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
                    <td hidden="<?= SessionService::isAdmin() ?>">
                        <a href="<?= AppConfigConst::PATH_PRODUCTS_EDIT . "?id=" . $order['id'] ?>"
                            class="btn btn-outline-dark btn-sm flex-fill">
                            <i class="bi bi-pencil me-1"></i>Editar
                        </a>
                        <button type="button" class="btn btn-outline-danger btn-sm flex-fill" data-bs-toggle="modal"
                            data-bs-target="#deleteModal" data-id="<?= $order['id'] ?>"
                            data-name="<?= htmlspecialchars($order['id']) ?>"
                            data-action="<?= AppConfigConst::PATH_PRODUCTS_DELETE ?>">
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

<?php require_once __DIR__ . '/../includes/footer.html'; ?>