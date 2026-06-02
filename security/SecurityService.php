<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/SessionService.php';

class SecurityService
{
    private mysqli $database;

    public function __construct()
    {
        $this->database = (new Database())->conexao;
    }

    public function registerUser($nome, $apelido, $data_nascimento, $morada, $email, $telefone, $username, $password)
    {
        $stmt = $this->database->prepare("SELECT id from utilizadores WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        if ($stmt->fetch()) {
            return 'Este nome de usuario é invalido!';
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->database->prepare("INSERT INTO utilizadores (nome, apelido, data_nascimento, morada, email, telefone, username, senha_hash) VALUES(?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssss", $nome, $apelido, $data_nascimento, $morada, $email, $telefone, $username, $password_hash);
        $stmt->execute();
        return true;

    }

    public function loginUser($username, $senha_hash)
    {
        $stmt = $this->database->prepare("SELECT id, nome, username, tipo, senha_hash FROM utilizadores where username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if (!$user || !password_verify($senha_hash, $user['senha_hash'])) {
            return 'Login Invalido.';
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nome'] = $user['nome'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['tipo'];

        return true;

    }

    public function getAllUsers()
    {
        SessionService::isRequireAdmin();
        $result = $this->database->query("SELECT * FROM utilizadores");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserById($id)
    {
        $stmt = $this->database->prepare("SELECT * FROM utilizadores WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();

    }

        public function updateUser($id, $nome, $apelido, $data_nascimento, $morada, $email, $telefone, $username, $senha_hash)
    {
        $stmt = $this->database->prepare("UPDATE utilizadores SET nome = ?, apelido = ?, data_nascimento = ?, morada = ?,
        email = ?, telefone = ?, username = ? , senha_hash = ?  WHERE id = ?");
        $stmt->bind_param("ssssssssi", $nome, $apelido, $data_nascimento, $morada, $email, $telefone, $username, $senha_hash, $id);
        return $stmt->execute();
    }

    public function deleteuser($id)
    {
        $stmt = $this->database->prepare("DELETE FROM utilizadores WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>