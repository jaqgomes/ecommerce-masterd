<?php
require_once __DIR__ . '/../security/SessionService.php';
include __DIR__ . '/../product/ProductService.php';

session_start();

$productService = new ProductService();
$productId = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
$redirect = $_SERVER['HTTP_REFERER'] ?? AppConfigConst::PATH_PRODUCTS_LIST;

if ($productId <= 0) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Produto inválido.'];
    header("Location: $redirect");
    exit;
}

$product = $productService->getProductById($productId);
if (!$product) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Produto não encontrado.'];
    header("Location: $redirect");
    exit;
}

$stock = (int)$product['stock'];
if ($stock <= 0) {
    $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Produto sem stock no momento.'];
    header("Location: $redirect");
    exit;
}

$quantity = max(1, $quantity);
$quantity = min($quantity, $stock);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cartItem = &$_SESSION['cart'][$productId];
if (!isset($cartItem['quantity'])) {
    $cartItem = [
        'id' => $productId,
        'nome' => $product['nome'],
        'preco' => $product['preco'],
        'quantity' => 0,
        'stock' => $stock,
    ];
}

$currentQuantity = (int)$cartItem['quantity'];
$newQuantity = min($currentQuantity + $quantity, $stock);
$added = $newQuantity - $currentQuantity;
$cartItem['quantity'] = $newQuantity;

if ($added <= 0) {
    $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Já atingiu a quantidade máxima disponível no carrinho.'];
} else {
    $_SESSION['flash'] = [
        'type' => 'success',
        'message' => "Adicionado ao carrinho: $added x {$product['nome']}.",
    ];
}

header("Location: $redirect");
exit;
