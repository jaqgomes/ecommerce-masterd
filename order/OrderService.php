<?php

require_once __DIR__ . '/../config/Database.php';

class OrderService
{
    private $database;

    public function __construct()
    {
        $this->database = (new Database())->conexao;
    }

    public function getAllOrder()
    {
        if (SessionService::isAdmin()) {
            $result = $this->database->query("SELECT e.id, u.nome as utilizador, e.data_encomenda, e.estado, e.total FROM encomendas e
                                          INNER JOIN utilizadores u ON u.id = e.id_utilizador");
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    public function getAllOrderByUserId($id_utilizador)
    {
        $stmt = $this->database->prepare("SELECT e.id, u.nome as utilizador, e.data_encomenda, e.estado, e.total FROM encomendas e
                                          INNER JOIN utilizadores u ON u.id = e.id_utilizador
                                          WHERE u.id = ?");
        $stmt->bind_param("i", $id_utilizador);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrderDetailByUserId($id_utilizador, $id_encomenda)
    {
        $stmt = $this->database->prepare("SELECT p.nome as nome_produto, ie.quantidade, ie.preco_unitario FROM itens_encomenda ie
                                          INNER JOIN encomendas e ON e.id = ie.id_encomenda
                                          INNER JOIN produtos p ON p.id = ie.id_produto
                                          WHERE e.id_utilizador = ? AND ie.id_encomenda = ?");
        $stmt->bind_param("ii", $id_utilizador, $id_encomenda);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

}
?>