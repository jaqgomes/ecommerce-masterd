<?php

class Database
{
    private string $host = AppConfigConst::DB_HOST;
    private string $dbname = AppConfigConst::DB_NAME;
    private string $username = AppConfigConst::DB_USER;
    private string $password = AppConfigConst::DB_PASS;

    public $conexao;

    public function __construct()
    {
        $this->conexao = null;
        $this->conexao = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->conexao->connect_error) {

            die("Connection failed: " . $this->conexao->connect_error);
        }
    }

}

?>