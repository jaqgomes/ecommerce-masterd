<?php

include 'SecurityService.php';

$securityService = new SecurityService();

session_start();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$pageTitle = "Registro - Web System";
$errors = [];
$input = ['nome' => '', 'apelido' => '', 'email' => '', 'telefone' => '', 'username' => '', 'password' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input['nome'] = trim($_POST['nome'] ?? '');
    $input['apelido'] = trim($_POST['apelido'] ?? '');
    $input['email'] = trim($_POST['email'] ?? '');
    $input['telefone'] = trim($_POST['telefone'] ?? '');
    $input['username'] = trim($_POST['username'] ?? '');
    $input['password'] = trim($_POST['password'] ?? '');

    if ($input['nome'] === '') {
        $errors['nome'] = 'Nome é obrigatório';
    }

    if ($input['apelido'] === '') {
        $errors['apelido'] = 'Apelido é obrigatório';
    }

    if ($input['email'] === '') {
        $errors['email'] = 'Email é obrigatório';
    }

    if ($input['username'] === '') {
        $errors['username'] = 'Username é obrigatório';
    }
    if ($input['password'] === '') {
        $errors['password'] = 'Password é obrigatório';
    }

    if (empty($errors)) {

        $result = $securityService->registerUser(
            $input['nome'],
            $input['apelido'],
            $input['email'],
            $input['telefone'],
            $input['username'],
            $input['password']
        );

        if ($result === true) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Usuario criado com sucesso!'];
            if (SessionService::isAdmin()) {
                header("Location: " . AppConfigConst::PATH_PROFILE_MANAGER);
            } else {
                header("Location: " . AppConfigConst::PATH_LOGIN);
            }
            exit;
        } else {
            $errors['generic'] = $result;
        }

    }
}

if (SessionService::isAdmin()) {
    require_once __DIR__ . '/../includes/header.php';
} else {
    require_once __DIR__ . '/../includes/header-blank.html';
}
?>

<div class="d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 10rem);">
    <div class="row justify-content-center w-100">
        <div class="col-lg-4">
            <h2 class="page-header">Faça o seu Registro</h2>


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
                    <i class="bi bi-plus-circle me-2"></i>Criar novo registro
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="#" novalidate>
                        <div class="row g-3">
                            <!--Nome-->
                            <div class="col-12">
                                <label for="nome" class="form-titulo">Nome<span class="text-danger">*</span></label>
                                <input type="text" id="nome" name="nome"
                                    class="form-control <?= isset($errors['nome']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($input['nome']) ?>" maxlength="100">
                                <?php if (isset($errors['nome'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['nome'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!--Apelido-->
                            <div class="col-12">
                                <label for="apelido" class="form-titulo">Apelido<span
                                        class="text-danger">*</span></label>
                                <input type="text" id="apelido" name="apelido"
                                    class="form-control <?= isset($errors['apelido']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($input['apelido']) ?>" maxlength="100">
                                <?php if (isset($errors['apelido'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['apelido'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!--Email-->
                            <div class="col-12">
                                <label for="email" class="form-titulo">Email<span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email"
                                    class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($input['email']) ?>" maxlength="100">
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['email'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!--Telefone-->
                            <div class="col-12">
                                <label for="telefone" class="form-titulo">Telefone</label>
                                <input type="tel" id="telefone" name="telefone"
                                    class="form-control <?= isset($errors['telefone']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($input['telefone']) ?>" maxlength="100">
                            </div>

                            <!--Username-->
                            <div class="col-12">
                                <label for="username" class="form-titulo">Nome do usuário<span
                                        class="text-danger">*</span></label>
                                <input type="tel" id="username" name="username"
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
                                    maxlength="100">
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['password'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-2 justify-content-end">
                            <?php if (SessionService::isAdmin()): ?>
                                <a href="<?= AppConfigConst::PATH_PROFILE_MANAGER ?>" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg me-1"></i>
                                    Cancelar
                                </a>
                            <?php else: ?>
                                <a href="<?= AppConfigConst::PATH_INDEX ?>" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg me-1"></i>
                                    Cancelar
                                </a>
                            <?php endif; ?>

                            <button type="submit" class="btn btn-dark">
                                <i class="bi bi-box-arrow-in-right me-1"></i>
                                Registrar
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (SessionService::isAdmin()):
    require_once __DIR__ . '/../includes/footer.html';
else:
    require_once __DIR__ . '/../includes/footer-blank.html';
endif; ?>