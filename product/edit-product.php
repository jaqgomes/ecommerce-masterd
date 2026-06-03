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
    header('Location: /ecommerce-masterd/product/list-product-manager.php');
    exit;
}

if (!empty($product['data_criacao'])) {
    $dateObj = new DateTime($product['data_criacao']);
    $date = $dateObj->format('Y-m-d');
}

$pageTitle = 'Edit Project — Ecommerce MasterD';
$errors = [];
$input = $product;
$input['data_criacao'] = $date ?? ''; //se tiver valor em $date usa ele, se não tiver usa ''

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input['nome'] = trim($_POST['nome'] ?? '');
    $input['descricao'] = trim($_POST['descricao'] ?? '');
    $input['categoria'] = trim($_POST['categoria'] ?? '');
    $input['preco'] = trim($_POST['preco'] ?? '');
    $input['stock'] = trim($_POST['stock'] ?? '');
    $input['imagem'] = trim($_POST['imagem'] ?? '');
    $input['data_criacao'] = trim($_POST['data_criacao'] ?? '');

    if ($input['nome'] === '') {
        $errors['nome'] = 'Nome é obrigatório.';
    }

    if ($input['preco'] === '') {
        $errors['preco'] = 'Preço é obrigatório.';
    }

    if (!empty($_POST['remover_imagem']) && !empty($product['imagem'])) {

        $caminho = __DIR__ . '/uploads' . $product['imagem'];

        if (file_exists($caminho)) {
            unlink($caminho);
        }

        $input['imagem'] = null;

    } elseif (!empty($_FILES['imagem']['name'])) {

        $file = $_FILES['imagem'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors['imagem'] = 'Erro ao enviar a imagem.';

        } else {
            $allowed = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($file['type'], $allowed)) {
                $errors['imagem'] = 'A imagem deve ser JPEG, PNG e WEBP.';
            }
        }

        $input['imagem'] = uniqid() . '-' . basename($_FILES['imagem']['name']);
        $destino = __DIR__ . '/uploads/' . $input['imagem'];

        move_uploaded_file($_FILES['imagem']['tmp_name'], $destino);

    } else {
        $input['imagem'] = $product['imagem'];
    }

    if (empty($errors)) {

        $productService->updateProduct(
            $id,
            $input['nome'],
            $input['descricao'],
            $input['categoria'],
            $input['preco'],
            $input['stock'],
            $input['imagem'],
            $input['data_criacao']
        );

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Produto alterado com sucesso!'];
        header('Location: /ecommerce-masterd/product/list-product-manager.php');
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
                <form method="POST" action="<?= AppConfigConst::PATH_PRODUCTS_EDIT . "?id=" . $id ?>" novalidate
                    enctype="multipart/form-data">

                    <?php include __DIR__ . '/product-details.html'; ?>

                    <hr class="my-4">

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="<?= AppConfigConst::PATH_PRODUCTS_MANAGER ?>" class="btn btn-outline-secondary">
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