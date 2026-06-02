<?php
require_once __DIR__ . '/../security/SessionService.php';
include 'SecurityService.php';

session_start();
SessionService::isRequireAdmin();

$securityService = new SecurityService();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int) $_POST['id'] ?? 0;
    if ($id > 0) {

        if ($securityService->deleteUser($id)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Utilizador removido com sucesso.'];
        } else {
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Utilizador não encontrado ou já removido.'];
        }
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Invalid request.'];
    }
}

header("Location: " . AppConfigConst::PATH_PROFILE_MANAGER);
exit;