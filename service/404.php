<?php
$f_config = "../private/config.json";
$configs = json_decode(file_get_contents($f_config));
$domain = $configs->domain_name;

session_start();
$_SESSION["backTrace"] = "http://".$domain.$_SERVER["PHP_SELF"];
$is_admin = $_SESSION["isAdmin"];

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Задачи исполнителей - Tasker</title>
    <link rel="shortcut icon" href="../image/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">TASKER</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="http://<?=$domain?>/application/tasks.php">Задачи</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="http://<?=$domain?>/application/tasks_of_employee.php">Задачи исполнителей</a>
            </li>
            <?if($is_admin == true){?>
                <li class="nav-item">
                    <a class="nav-link" href="http://<?=$domain?>/application/employee.php">Исполнители</a>
                </li>
            <?}?>
        </ul>
        <div>
            <?if ($is_admin == true)
                echo("<a class='btn btn-secondary verify' href='http://".$domain."/script/php/a_logout.php'>Выход</a>");
            else
                echo("<a class='btn btn-secondary verify' href='http://".$domain."/script/php/a_login.php'>Администратор</a>");?>
        </div>
    </div>
</nav>


<div class="container text-center">
    <h1 style="margin-top: 100px">Ошибка 404</h1>
    <h3>Страница не найдена</h3>
    <img src="../image/404.gif" class="mx-auto w-25" alt="404" style="margin-top: 100px">
</div>

</body>
</html>