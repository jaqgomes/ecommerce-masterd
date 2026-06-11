<?php
require_once __DIR__ . '/../security/SessionService.php';

session_start();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
SessionService::isRequireLogin();

include 'SecurityService.php';
$securityService = new SecurityService();

$id = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;

$profile = $securityService->getUserById($id);

$pageTitle = "Perfil do Usuário - Ecommerce";
$errors = [];
$input = $profile;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input['nome'] = trim($_POST['nome'] ?? '');
    $input['apelido'] = trim($_POST['apelido'] ?? '');
    $input['data_nascimento'] = trim($_POST['data_nascimento'] ?? '');
    $input['morada'] = trim($_POST['morada'] ?? '');
    $input['email'] = trim($_POST['email'] ?? '');
    $input['telefone'] = trim($_POST['telefone'] ?? '');
    $input['username'] = trim($_POST['username'] ?? '');
    $input['senha_hash'] = trim($_POST['senha_hash'] ?? '');

    if ($input['nome'] === '') {
        $errors['nome'] = 'Nome é obrigatório';
    }

    if ($input['apelido'] === '') {
        $errors['apelido'] = 'Apelido é obrigatório';
    }
    if ($input['data_nascimento'] === '') {
        $errors['data_nascimento'] = 'Data de Nacimento é obrigatória';
    }
    if ($input['morada'] === '') {
        $errors['morada'] = 'Morada é obrigatória';
    }

    if ($input['email'] === '') {
        $errors['email'] = 'Email é obrigatório';
    }

    if ($input['telefone'] !== '' && !preg_match('/^(2|9)\d{8}$/', $input['telefone'])) {
        $errors['telefone'] = 'Telefone inválido.';
    }

    if ($input['username'] === '') {
        $errors['username'] = 'Username é obrigatório';
    }

    if (empty($errors)) {

        $result = $securityService->updateUser(
            $id,
            $input['nome'],
            $input['apelido'],
            $input['data_nascimento'],
            $input['morada'],
            $input['email'],
            $input['telefone'],
            $input['username'],
            $input['senha_hash']
        );

        if ($result === true) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Usuário atualizado com sucesso!'];
            header("Location: " . AppConfigConst::path(AppConfigConst::PATH_PROFILE));
            exit;
        } else {
            $errors['generic'] = $result;
        }

    }

}

require_once __DIR__ . '/../' . AppConfigConst::PATH_HEADER;
?>

<div class="d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 10rem);">
    <div class="row justify-content-center w-100">
        <div class="col-lg-4">
            <h2 class="page-header">Perfil do Usuário</h2>

            <?php if ($flash): ?>
                <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
                    <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
                    <?= htmlspecialchars($flash['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Por favor, corrija os erros abaixo!
                </div>
                <?php if (!empty($errors['generic'])): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?= $errors['generic'] ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>


            <div class="card form-card">
                <div class="card-header">
                    <i class="bi bi-plus-circle me-2"></i>Detalhes registro
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="#" novalidate>

                        <?php include __DIR__ . '/../' . AppConfigConst::PATH_PROFILE_DETAILS; ?>    

                        <hr class="my-4">

                        <div class="d-flex gap-2 justify-content-end">

                            <a href="<?= AppConfigConst::path(AppConfigConst::PATH_INDEX) ?>"
                                class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-1"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-dark">
                                <i class="bi bi-box-arrow-in-right me-1"></i>
                                Atualizar
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../' . AppConfigConst::PATH_FOOTER; ?>