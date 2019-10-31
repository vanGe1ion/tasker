<?php
session_start();
$location = "http://" . $_SERVER["SERVER_NAME"] . "/application/page/tasks.php";
if (http_response_code() == "401" && isset($_SESSION["backTrace"]))
    $location = $_SESSION["backTrace"];
?>
<meta http-equiv="refresh" content="0; url=<?=$location?>">
