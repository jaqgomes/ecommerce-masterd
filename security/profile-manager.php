<?php
require_once __DIR__ . '/../security/SessionService.php';

include 'SecurityService.php';
$securityService = new SecurityService();

session_start();
SessionService::isRequireAdmin();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$users = $securityService->getAllUsers();

$pageTitle = "Gerenciador de Usuário - Web System";
$errors = [];

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="page-header mb-0">Gerenciador de Usuários</h2>
    <a href="<?= AppConfigConst::PATH_REGISTER ?>" class="btn btn-dark">
        <i class="bi bi-plus-lg me-1"></i>Adicionar</a>
</div>

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
        <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (empty($users)): ?>
    <div class="card form-card">
        <div class="empty-state">
            <i class="bi bi-plus-lg me-1"></i>
            <h4>Nenhuma utilizador encontrado.</h4>
            <p>Comece adicionando seu primeiro utilizador. </p>
            <a href="<?= AppConfigConst::PATH_REGISTER ?>" class="btn btn-dark">Adicionar utilizador</a>
        </div>

    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Apelido</th>
                    <th scope="col">Email</th>
                    <th scope="col">Telefone</th>
                    <th scope="col">Username</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>


            <tbody>
                <?php foreach ($users as $user) { ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['nome'] ?></td>
                        <td><?= $user['apelido'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['telefone'] ?></td>
                        <td><?= $user['username'] ?></td>
                        <td><?= $user['role'] ?></td>

                        <td>
                            <a href="<?= AppConfigConst::PATH_PROFILE?>?id=<?= $user['id'] ?>" class="btn btn-outline-dark btn-sm flex-fill">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </a>
                            <button type="button" class="btn btn-outline-danger btn-sm flex-fill" data-bs-toggle="modal"
                                data-bs-target="#deleteModal" data-id="<?= $user['id'] ?>"
                                data-name="<?= htmlspecialchars($user['nome']) ?>"
                                data-action="<?= AppConfigConst::ACTION_PROFILE_DELETE?>">
                                <i class="bi bi-trash me-1"></i>Delete
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php include __DIR__ . '/../includes/modal.html'; ?>

    <?php endif; ?>

    <?php require_once __DIR__ . '/../includes/footer.html'; ?>