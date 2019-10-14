<?php
$f_config = $_SERVER["DOCUMENT_ROOT"] . "/private/config.json";
$configs = json_decode(file_get_contents($f_config));
$domain = $configs->domain_name;
$db_con = $configs->db_config;
if(!isset($_POST["querySet"]) || !isset($_POST["queryType"]) || !isset($_POST["queryData"]))
    header("Location:/index.php");
else {
    $factoryPath = $_SERVER["DOCUMENT_ROOT"] . "/factory/QueryFactory.php";
    if(!file_exists($factoryPath))
        die();

    require_once $factoryPath;
    $querySet = $_POST["querySet"];
    $queryType = $_POST["queryType"];
    $queryData = $_POST["queryData"];

    $queryFactory = new QueryFactory();
    $queryFactory->InitializeQuery($querySet, $queryType);
    $query = $queryFactory->GetQuery($queryData);

    $db_connection = mysqli_connect($db_con->host, $db_con->login, $db_con->password, $db_con->db);
    $db_connection->set_charset("utf8");
    $res = mysqli_query($db_connection, $query);
    echo $res;
}