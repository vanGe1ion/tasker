<?php
    if(!isset($_POST["queryName"]))
        header("Location:/Error.php");
    else {
        require "QuerySet.php";
        $f_config = $_SERVER["DOCUMENT_ROOT"] . "/config.json";

        $db_con = json_decode(file_get_contents($f_config))->db_config;
        $db_connection = mysqli_connect($db_con->host, $db_con->login, $db_con->password, $db_con->db);
        $db_connection->set_charset("utf8");

        $queryName = $_POST["queryName"];
        $query = QuerySet::$queryName($_POST['data']);
        $res = mysqli_query($db_connection, $query);
        echo $res;
    }