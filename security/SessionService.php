<?php
require_once __DIR__ . '/../config/Constants.php';

class SessionService
{
    public static function logoutUser()
    {
        $_SESSION = [];
        session_destroy();
        header("Location: " . AppConfigConst::PATH_INDEX);
        exit;
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public static function isAdmin()
    {
        return isset($_SESSION['user_id']) && (isset($_SESSION['role']) && $_SESSION['role'] === "admin");
    }

    public static function isRequireLogin()
    {
        if (!self::isLoggedIn()) {
            header("Location: " . AppConfigConst::PATH_LOGIN);
            exit;
        }
    }

    public static function isRequireAdmin()
    {
        if (!self::isAdmin()) {
            header("Location: " . AppConfigConst::PATH_INDEX);
            exit;
        }
    }

    public static function getUsername()
    {
        if (self::isLoggedIn()) {
            return isset($_SESSION['nome']) ? 'Bem vindo ' . $_SESSION['nome'] : 'Bem Vindo!';
;
        }
    }
    
}
?>
