<?php
require_once __DIR__ . '/../security/SessionService.php';
require_once __DIR__ . '/ProductService.php';

session_start();

$productService = new ProductService();
$productList = $productService->getAllProduct();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$pageTitle = 'Produtos - Ecommerce';

require_once __DIR__ . '/../' . AppConfigConst::PATH_HEADER;
?>

<?php include __DIR__ . '/../' .AppConfigConst::PATH_PRODUCTS_LIST_SECTION ?>

<?php require_once __DIR__ . '/../' . AppConfigConst::PATH_FOOTER; ?>