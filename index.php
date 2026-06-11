<?php
require_once __DIR__ . '/security/SessionService.php';
require_once __DIR__ . '/product/ProductService.php';

session_start();

$productService = new ProductService();
$productList = $productService->getAllProduct();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$pageTitle = 'Página Inicial - Ecommerce';

require_once __DIR__ . '/includes/header.php';
?>

<?php include __DIR__ . '/product/product-list-section.php'; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
