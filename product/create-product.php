<?php
require_once __DIR__ . '/../security/SessionService.php';


include('NewsService.php');

$productService = new ProductService();

session_start();
#ProductService::isRequireAdmin();

$listNewsPageLink = "/projeto-final/products/list-product-manager.php";

$pageTitle = 'Add News — Ecommerce';
$errors = [];
$input = ['nome' => '', 'descricao' => '', 'preco' => '', 'stock' => '', 'imagem' => '', 'data_criacao' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input['nome'] = trim($_POST['nome'] ?? '');
    $input['preco'] = trim($_POST['preco'] ?? '');

    if ($input['nome'] === '') {
        $errors['nome'] = 'Nome é obrigatório.';
    }

    if ($input['preco'] == '') {
        $errors['preco'] = 'Preco é obrigatório.';
    }

    if (empty($errors)) {

        $productService->createProduct(
            $input['nome'],
            $input['descricao'],
            $input['preco'],
            $input['stock'],
            $input['imagem'],
            $input['data_criacao']
        );

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Notícia adicionada com sucesso!'];
        header("Location: $listNewsPageLink");
        exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <h2 class="page-header">Adicionar Notícias</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Por favor, corrija os erros abaixo.
            </div>

        <?php endif; ?>

        <div class="card form-card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-2"></i>Dados da Notícia
            </div>
            <div class="card-body p-4">
                <form method="POST" action="/projeto-final/news/create-news.php" novalidate enctype="multipart/form-data">

                    <?php include __DIR__ . '/news-details.html'; ?>

                    <hr class="my-4">

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="<?= $listNewsPageLink ?>" class="btn btn-outline-secondary">
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