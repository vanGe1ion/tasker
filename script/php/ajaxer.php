<?php
    if(!isset($_POST["query"]))
        header("Location:/Error.php");
    else {
        $f_config = $_SERVER["DOCUMENT_ROOT"] . "/config.json";

        $db_con = json_decode(file_get_contents($f_config))->db_config;
        $db_connection = mysqli_connect($db_con->host, $db_con->login, $db_con->password, $db_con->db);

        $res = mysqli_query($db_connection, $_POST["query"]);
        echo $res;
    }