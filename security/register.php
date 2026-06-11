<?php

include 'SecurityService.php';

$securityService = new SecurityService();

session_start();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$pageTitle = "Registro - Web System";
$errors = [];
$input = ['nome' => '', 'apelido' => '', 'data_nascimento' => '', 'morada' => '', 'email' => '', 'telefone' => '', 'username' => '', 'password' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input['nome'] = trim($_POST['nome'] ?? '');
    $input['apelido'] = trim($_POST['apelido'] ?? '');
    $input['data_nascimento'] = trim($_POST['data_nascimento'] ?? '');
    $input['morada'] = trim($_POST['morada'] ?? '');
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

    if ($input['data_nascimento'] === '') {
        $errors['data_nascimento'] = 'Data Nascimento é obrigatória';
    } else {
        $birthDate = DateTime::createFromFormat('Y-m-d', $input['data_nascimento']);
        $today = new DateTime();
        if (!$birthDate) {
            $errors['data_nascimento'] = 'Data de nascimento inválida';
        } else {
            $age = $today->diff($birthDate)->y;
            if ($age < 18) {
                $errors['data_nascimento'] = 'Somente maiores de 18 anos podem se registrar';
            }
        }
    }
    if ($input['morada'] === '') {
        $errors['morada'] = 'Morada é obrigatória';
    }
    if ($input['email'] === '') {
        $errors['email'] = 'Email é obrigatório';
    } elseif (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email inválido. Informe um endereço válido.';
    }

    if ($input['telefone'] !== '' && !preg_match('/^(2|9)\d{8}$/', $input['telefone'])) {
        $errors['telefone'] = 'Telefone inválido.';
    }

    if ($input['username'] === '') {
        $errors['username'] = 'Nome do usuário é obrigatório';
    }
    if ($input['password'] === '') {
        $errors['password'] = 'Senha é obrigatório';
    }

    if (empty($errors)) {

        $result = $securityService->registerUser(
            $input['nome'],
            $input['apelido'],
            $input['data_nascimento'],
            $input['morada'],
            $input['email'],
            $input['telefone'],
            $input['username'],
            $input['password']
        );

        if ($result === true) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Usuário criado com sucesso!'];
            header("Location: " . AppConfigConst::path(AppConfigConst::PATH_LOGIN));
            exit;
        } else {
            $errors['generic'] = $result;
        }

    }
}

if (SessionService::isAdmin()) {
    require_once __DIR__ . '/../' . AppConfigConst::PATH_HEADER;
} else {
    require_once __DIR__ . '/../includes/header-blank.php';
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

                        
                        <?php include AppConfigConst::PATH_PROFILE_DETAILS; ?>

                        <hr class="my-4">

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="<?= AppConfigConst::path(AppConfigConst::PATH_INDEX) ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-1"></i>
                                Cancelar
                            </a>
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
    require_once __DIR__ . '/../' . AppConfigConst::PATH_FOOTER;
else:
    require_once __DIR__ . '/../' . AppConfigConst::PATH_FOOTER_BLANK;
endif; ?>