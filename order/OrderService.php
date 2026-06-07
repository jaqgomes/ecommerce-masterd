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

    public function getOrderDetailByOrderId($id_encomenda)
    {
        if (SessionService::isAdmin()) {
            $stmt = $this->database->prepare("SELECT p.nome as nome_produto, ie.quantidade, ie.preco_unitario FROM itens_encomenda ie
                                          INNER JOIN encomendas e ON e.id = ie.id_encomenda
                                          INNER JOIN produtos p ON p.id = ie.id_produto
                                          WHERE ie.id_encomenda = ?");
            $stmt->bind_param("i", $id_encomenda);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
        header("Location: " . AppConfigConst::PATH_INDEX);
        exit;
    }

    public function getOrderDetailByUserIdAndOrderId($id_utilizador, $id_encomenda)
    {
        $stmt = $this->database->prepare("SELECT p.nome as nome_produto, ie.quantidade, ie.preco_unitario FROM itens_encomenda ie
                                          INNER JOIN encomendas e ON e.id = ie.id_encomenda
                                          INNER JOIN produtos p ON p.id = ie.id_produto
                                          WHERE e.id_utilizador = ? AND ie.id_encomenda = ?");
        $stmt->bind_param("ii", $id_utilizador, $id_encomenda);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function createOrderWithItems($id_utilizador, $total, $items, $morada)
    {
        $this->database->begin_transaction();

        $estado = 'pendente';
        $stmt = $this->database->prepare("INSERT INTO encomendas (id_utilizador, morada, total, estado) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            $this->database->rollback();
            return false;
        }
        $stmt->bind_param("isds", $id_utilizador, $morada, $total, $estado);
        if (!$stmt->execute()) {
            $this->database->rollback();
            return false;
        }

        $orderId = $this->database->insert_id;

        $stmtItem = $this->database->prepare("INSERT INTO itens_encomenda (id_encomenda, id_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
        if (!$stmtItem) {
            $this->database->rollback();
            return false;
        }

        foreach ($items as $it) {
            $prodId = (int) $it['id'];
            $qty = (int) $it['quantity'];
            $price = (float) $it['preco'];
            $stmtItem->bind_param("iiid", $orderId, $prodId, $qty, $price);
            if (!$stmtItem->execute()) {
                $this->database->rollback();
                return false;
            }
        }

        $this->database->commit();
        return $orderId;
    }

    public function getOrderById($id_encomenda)
    {
        $stmt = $this->database->prepare("SELECT e.*, u.nome as utilizador FROM encomendas e INNER JOIN utilizadores u ON u.id = e.id_utilizador WHERE e.id = ?");
        $stmt->bind_param("i", $id_encomenda);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

}
?>