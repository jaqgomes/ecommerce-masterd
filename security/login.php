<?php
session_start();

require_once __DIR__ . '/../includes/header-blank.html';
include 'SecurityService.php';

$securityService = new SecurityService();

// Flash messages via session
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$pageTitle = "Login - Ecommerce";
$errors = [];
$input = ['username' => '', 'password' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input['username'] = trim($_POST['username'] ?? '');
    $input['password'] = trim($_POST['password'] ?? '');


    if ($input['username'] === '') {
        $errors['username'] = 'Nome é obrigatório';
    }

    if ($input['password'] === '') {
        $errors['password'] = 'A senha é obrigatória';
    }

    if (empty($errors)) {

        $result = $securityService->loginUser(
            $input['username'],
            $input['password']
        );

        if ($result === true) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Autenticado com Sucesso!'];
            header("Location: " . AppConfigConst::PATH_INDEX);
            exit;
        }

        $login_error = $result;
    }
}

?>

<div class="d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 10rem);">
    <div class="row justify-content-center w-100">
        <div class="col-lg-4">

            <h2 class="page-header">Login</h2>

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
            <?php endif; ?>

            <?php if (!empty($login_error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <?= $login_error ?>
                </div>
            <?php endif; ?>


            <div class="card form-card">
                <div class="card-header">
                    <i class="bi bi-plus-circle me-2"></i>Login
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="#" novalidate>
                        <div class="row g-3">
                            <!--Username-->
                            <div class="col-12">
                                <label for="username" class="form-titulo">Usuário<span
                                        class="text-danger">*</span></label>
                                <input type="text" id="username" name="username"
                                    class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($input['username']) ?>" maxlength="100">
                                <?php if (isset($errors['username'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['username'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>


                            <!--Password-->
                            <div class="col-12">
                                <label for="password" class="conteudo-label">Senha <span
                                        class="text-danger">*</span></label>
                                <input type="password" id="password" name="password"
                                    class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($input['password']) ?>" maxlength="100">
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['password'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="<?= AppConfigConst::PATH_INDEX ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-1"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-dark">
                                <i class="bi bi-box-arrow-in-right me-1"></i>
                                Login
                            </button>
                        </div>

                        <p class="footer-link">
                            Ainda não tem conta? <a href="<?= AppConfigConst::PATH_REGISTER ?>">Criar conta</a>
                        </p>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php require_once __DIR__ . '/../includes/footer-blank.html'; ?>