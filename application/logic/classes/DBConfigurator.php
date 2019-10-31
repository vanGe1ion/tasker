<?php


class DBConfigurator
{
    private $connection;

    public function __construct($configPath)
    {
        $configs = json_decode(file_get_contents($configPath));
        $db_config = $configs->db_config;
        $this->connection = mysqli_connect($db_config->host, $db_config->login, $db_config->password, $db_config->db);
        $this->connection->set_charset("utf8");
    }

    public function GetConnection(){
        return $this->connection;
    }
}