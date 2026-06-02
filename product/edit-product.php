<?php
require_once __DIR__ . '/../security/SessionService.php';

include('ProductService.php');

session_start();
SessionService::isRequireAdmin();

$productService = new ProductService();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$product = $productService->getproductById($id);

if (!$product) {
    http_response_code(404);
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Product not found.'];
    header('Location: /ecommerce-masterd/products/list-product-manager.php');
    exit;
}

//metodo usado para validar e convertar data para exibir na tela
if (!empty($product['data_criacao'])) {
    $dateObj = new DateTime($product['data_criacao']);
    $date = $dateObj->format('Y-m-d');
}

$pageTitle = 'Edit Project — Ecommerce MasterD';
$errors = [];
$input = $product;
$input['date'] = $date ?? ''; //se tiver valor em $date usa ele, se não tiver usa ''

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input['nome'] = trim($_POST['nome'] ?? '');
    $input['descricao'] = trim($_POST['descricao'] ?? '');
    $input['preco'] = trim($_POST['preco'] ?? '');

    if ($input['nome'] === '') {
        $errors['nome'] = 'Nome is required.';
    }

    if ($input['descricao'] === '') {
        $errors['descricao'] = 'Descricao is required.';
    }

    if ($input['preco'] === '') {
        $errors['preco'] = 'Preco is required.';
    }

    if (empty($errors)) {

        $productService->updateProduct(
            $id,
            $input['nome'],
            $input['descricao'],
            $input['preco'],
            $input['stock'],
            $input['imagem'],
            $input['data_criacao']
        );

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Produto alterado com sucesso!'];
        header('Location: /ecommerce-masterd/products/list-product-manager.php');
        exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <h2 class="page-header">Editar Produto</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Por favor, corrija os erros abaixo.
            </div>

        <?php endif; ?>

        <div class="card form-card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-2"></i>Dados Produto
            </div>
            <div class="card-body p-4">
                <form method="POST" action="ecommerce-masterd/products/edit-product.php?id=<?= $id ?>" novalidate
                    enctype="multipart/form-data">

                    <?php include __DIR__ . '/product-details.html'; ?>

                    <hr class="my-4">

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="ecommerce-masterd/products/list-products-manager.php" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-1"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-dark">
                            <i class="bi bi-save me-1"></i>
                            Atualizar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.html'; ?>