<?php
$f_config = $_SERVER["DOCUMENT_ROOT"] . "/private/config.json";
$configs = json_decode(file_get_contents($f_config));
$domain = $configs->domain_name;
$db_con = $configs->db_config;
if(!isset($_POST["queryName"]))
    header("Location:/index.php");
else {
    require "QuerySet.php";
    $db_connection = mysqli_connect($db_con->host, $db_con->login, $db_con->password, $db_con->db);
    $db_connection->set_charset("utf8");

    $queryName = $_POST["queryName"];
    $query = QuerySet::$queryName($_POST['data']);
    $res = mysqli_query($db_connection, $query);
    echo $res;
}