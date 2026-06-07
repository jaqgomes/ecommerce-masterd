<?php
require_once __DIR__ . '/../security/SessionService.php';
require_once __DIR__ . '/OrderService.php';
require_once __DIR__ . '/../security/SecurityService.php';

session_start();
SessionService::isRequireLogin();

$orderService = new OrderService();
$securityService = new SecurityService();

$userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
$user = $securityService->getUserById($userId);

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$cartItems = $_SESSION['cart'] ?? [];
$totalPrice = 0.0;
foreach ($cartItems as $ci) {
    $totalPrice += $ci['quantity'] * (float) $ci['preco'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    if (empty($cartItems)) {
        $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Seu carrinho está vazio.'];
        header('Location: ' . AppConfigConst::PATH_CART);
        exit;
    }

    $addressChoice = $_POST['address_choice'] ?? 'existing';
    $selectedAddress = '';
    if ($addressChoice === 'new') {
        $selectedAddress = trim($_POST['new_address'] ?? '');
        if ($selectedAddress === '') {
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Digite a nova morada.'];
            header('Location: ' . AppConfigConst::PATH_ORDER_CHECKOUT);
            exit;
        }
    } else {
        $selectedAddress = $user['morada'] ?? '';
    }

    $items = [];
    foreach ($cartItems as $ci) {
        $items[] = [
            'id' => $ci['id'],
            'quantity' => (int) $ci['quantity'],
            'preco' => (float) $ci['preco']
        ];
    }

    $orderId = $orderService->createOrderWithItems($userId, $totalPrice, $items, $selectedAddress);
    if ($orderId) {
        $_SESSION['flash'] = ['type' => 'success', 'message' => "Encomenda #$orderId criada com sucesso. Morada selecionada: $selectedAddress"];
        unset($_SESSION['cart']);
        header('Location: ' . AppConfigConst::PATH_ORDER_LIST);
        exit;
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Erro ao criar encomenda. Por favor, tente novamente.'];
    }
}

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

    <h2 class="page-header">Finalizar Compra</h2>

    <?php if (empty($cartItems)): ?>
        <div class="card form-card">
            <div class="empty-state">
                <div class="alert alert-info">
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['nome']) ?></td>
                                    <td>€<?= htmlspecialchars(number_format((float) $item['preco'], 2, ',', '.')) ?></td>
                                    <td><?= (int) $item['quantity'] ?></td>
                                    <td>€<?= htmlspecialchars(number_format($item['quantity'] * (float) $item['preco'], 2, ',', '.')) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <p><strong>Total:</strong> €<?= htmlspecialchars(number_format($totalPrice, 2, ',', '.')) ?></p>
                </div>

                <form method="POST" action="" novalidate>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="address_choice" id="address_existing" value="existing" checked>
                            <label class="form-check-label" for="address_existing">Usar morada existente: <?= htmlspecialchars($user['morada'] ?? 'Sem morada') ?></label>
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="radio" name="address_choice" id="address_new" value="new">
                            <label class="form-check-label" for="address_new">Usar nova morada</label>
                        </div>
                        <textarea name="new_address" class="form-control mt-2" placeholder="Digite a nova morada" rows="3"></textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="<?= AppConfigConst::PATH_CART ?>" class="btn btn-outline-secondary">Voltar ao Carrinho</a>
                        <button type="submit" name="confirm_order" class="btn btn-dark">Finalizar Compra</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../includes/footer.html'; ?>
