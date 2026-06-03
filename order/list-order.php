<?php
require_once __DIR__ . '/../security/SessionService.php';

// Flash messages via session
session_start();
SessionService::isRequireLogin();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$pageTitle = 'Ecommerce - MasterD';

require_once __DIR__ . '/../includes/header.php';
?>

<?php if ($flash): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
        <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<h1>Welcome to Order Page</h1>

<?php require_once __DIR__ . '/../includes/footer.html'; ?>