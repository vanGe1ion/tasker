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

    if(
        $queryType == "create" ||
        $queryType == "update" ||
        $queryType == "delete" ||
        $res == false
    )
        echo $res;
    else{
        $resultArray = array();
        for ($i = 0; $fetcher = mysqli_fetch_assoc($res); ++$i){
            $fieldArray = array();
            foreach ($fetcher as $item => $value)
                $fieldArray[$item] = $value;
            $resultArray[$i] = $fieldArray;
        }
        echo json_encode($resultArray);
    }
}