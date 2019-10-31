<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/application/logic/classes/DBConfigurator.php";
$f_config = $_SERVER["DOCUMENT_ROOT"] . "/application/private/config.json";
$db_connection = (new DBConfigurator($f_config))->GetConnection();


if(!isset($_POST["querySet"]) || !isset($_POST["queryType"]) || !isset($_POST["queryData"]))
    header("Location:/index.php");
else {
    $factoryPath = $_SERVER["DOCUMENT_ROOT"] . "/application/logic/pattern/factory/QueryFactory.php";
    if(!file_exists($factoryPath))
        die();

    require_once $factoryPath;
    $querySet = $_POST["querySet"];
    $queryType = $_POST["queryType"];
    $queryData = $_POST["queryData"];

    $queryFactory = new QueryFactory();
    $queryFactory->InitializeQuery($querySet, $queryType);
    $query = $queryFactory->GetQuery($queryData);


    $res = mysqli_query($db_connection, $query);
    echo $res;
}