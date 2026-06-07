<?php

require_once __DIR__ . '/../security/SessionService.php';

include('ProductService.php');

session_start();
SessionService::isRequireAdmin();

$productService = new ProductService();

$listProductPageLink = "/ecommerce-masterd/product/list-product-manager.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: $listProductPageLink");
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id > 0) {

    if ($productService->deleteProduct($id)) {
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Produto removido com sucesso.'];
    } else {
        $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Produto não encontrado ou já removido.'];
    }
} else {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Invalid request.'];
}

header("Location: $listProductPageLink");
exit;
