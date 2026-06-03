<?php
require_once __DIR__ . '/../security/SessionService.php';

session_start();
SessionService::isRequireAdmin();

include('ProductService.php');

$productService = new ProductService();
$listProductPageLink = "/ecommerce-masterd/product/list-product-manager.php";

$pageTitle = 'Add Product — Ecommerce';
$errors = [];
$input = ['nome' => '', 'descricao' => '', 'categoria' => '', 'preco' => '', 'stock' => '', 'imagem' => '', 'data_criacao' => ''];

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

    if ($input['preco'] == '') {
        $errors['preco'] = 'Preço é obrigatório.';
    }

    if (!empty($_FILES['imagem']['nome'])) {
        $file = $_FILES['imagem'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors['imagem'] = 'Erro ao enviar a imagem.';

        } else {
            //$allowed = ['fotografia/jpeg', 'fotografia/png', 'fotografia/webp'];
            $allowed = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($file['type'], $allowed)) {
                $errors['imagem'] = 'A imagem deve ser JPEG, PNG ou WEBP.';
            }
        }

        $input['imagem'] = uniqid() . '-' . basename($_FILES['imagem']['name']);
        $destino = __DIR__ . '/uploads/' . $input['imagem'];

        move_uploaded_file($_FILES['imagem']['tmp_name'], $destino);

    } else {
        $input['imagem'] = null;
    }
    if (empty($errors)) {

        $productService->createProduct(
            $input['nome'],
            $input['descricao'],
            $input['categoria'],
            $input['preco'],
            $input['stock'],
            $input['imagem'],
            $input['data_criacao']
        );

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Produto adicionado com sucesso!'];
        header("Location: $listProductPageLink");
        exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <h2 class="page-header">Adicionar Produto</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Por favor, corrija os erros abaixo.
            </div>

        <?php endif; ?>

        <div class="card form-card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-2"></i>Dados da Produto
            </div>
            <div class="card-body p-4">
                <form method="POST" action="/ecommerce-masterd/product/create-product.php" novalidate
                    enctype="multipart/form-data">

                    <?php include __DIR__ . AppConfigConst::PATH_PRODUCTS_DETAILS; ?>

                    <hr class="my-4">

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="<?= $listProductPageLink ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-1"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-dark">
                            <i class="bi bi-save me-1"></i>
                            Salvar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.html'; ?>