<?php

require_once __DIR__ . '/../config/Database.php';

class ProductService
{
    private $database;

    public function __construct()
    {
        $this->database = (new Database())->conexao;
    }

    //este metodo insere um novo registro na tabela projetos usando prepared;criando uma prepared que é uma query segura; (?)sao placeholders
    //que serão substituidos pelos valores reais(ou seja, todos os campos seraoo tratados como texto);
    //executa uma query no banco de dados ($stmt);
    //utilizado quando na criacao de projetos, depois de validar os dados, depois de fazer upload da imagem

    public function createProduct($nome, $descricao, $categoria, $preco, $stock, $imagem, $data_criacao)
    {
        $stmt = $this->database->prepare("INSERT INTO produtos (nome, descricao, categoria, preco, stock, imagem, data_criacao) VALUES(?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssiss", $nome, $descricao, $categoria, $preco, $stock, $imagem, $data_criacao);
        return $stmt->execute();

    }
    //este metodo faz executa uma query no SQL(busca todos registros na tabela projetos),coverte tudo em array;
    public function getAllProduct()
    {
        $result = $this->database->query("SELECT * FROM produtos");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //o metodo getProjectId buscar um projeto pelo ID usando prepared statements
    //onde ? sera substituido pelo ID
    public function getProductById($id)
    {
        $stmt = $this->database->prepare("SELECT * FROM produtos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    //esse metodo atualiza o banco de dados , substituindo pelo que enviar. Apenas com valor do ID que passar 
    public function updateProduct($id, $nome, $descricao, $categoria, $preco, $stock, $imagem, $data_criacao)
    {
        $stmt = $this->database->prepare("UPDATE produtos SET nome = ?, descricao = ?, categoria = ?, preco = ?, stock = ?, imagem = ?, data_criacao = ?  WHERE id = ?");
        $stmt->bind_param("sssssssi", $nome, $descricao, $categoria, $preco, $stock, $imagem, $data_criacao, $id);
        return $stmt->execute();

    }
    //prepara a query SQL, apaga o projeto cujo ID corresponder ao valor enviado
    public function deleteProduct($id)
    {
        $stmt = $this->database->prepare("DELETE FROM produtos WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>