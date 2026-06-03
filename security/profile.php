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
            header("Location: " . AppConfigConst::PATH_PROFILE);
            exit;
        } else {
            $errors['generic'] = $result;
        }

    }

}

require_once __DIR__ . '/../includes/header.php';
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


                            <!--Data nascimento-->
                            <div class="col-12">
                                <label for="data_nascimento" class="form-titulo">Data Nascimento<span
                                        class="text-danger">*</span></label>
                                <input type="text" id="data_nascimento" name="data_nascimento"
                                    class="form-control <?= isset($errors['data_nascimento']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($input['data_nascimento']) ?>" maxlength="100">
                                <?php if (isset($errors['data_nascimento'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['data_nascimento'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!--Morada-->
                            <div class="col-12">
                                <label for="morada" class="form-titulo">Morada<span class="text-danger">*</span></label>
                                <input type="text" id="morada" name="morada"
                                    class="form-control <?= isset($errors['morada']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($input['morada']) ?>" maxlength="100">
                                <?php if (isset($errors['morada'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['morada'] ?>
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

                            <!--Senha hash-->
                            <div class="col-12">
                                <label for="senha_hash" class="conteudo-label">Senha</label>
                                <input type="password" id="senha_hash" name="senha_hash"
                                    class="form-control <?= isset($errors['senha_hash']) ? 'is-invalid' : '' ?>"
                                    maxlength="100">
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
                                Atualizar
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.html'; ?>