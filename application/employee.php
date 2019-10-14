<?php
$f_config = "../private/config.json";
$configs = json_decode(file_get_contents($f_config));
$db_con = $configs->db_config;
$domain = $configs->domain_name;
$db_connection = mysqli_connect($db_con->host, $db_con->login, $db_con->password, $db_con->db);
$db_connection->set_charset("utf8");

session_start();
$_SESSION["backTrace"] = "http://".$domain.$_SERVER["PHP_SELF"];
$is_admin = $_SESSION["isAdmin"];

if($is_admin != true)
    echo("<meta http-equiv='refresh' content='0; url=http://".$domain."'>");
else{
    $query = "SELECT * FROM Employee ORDER BY Employee_ID";
    $employeeList = mysqli_query($db_connection, $query);
    $empBase = array();
    while ($empRes = mysqli_fetch_assoc($employeeList))
        $empBase[$empRes["Employee_ID"]] = array($empRes["Fullname"], $empRes["Position"]);


    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="utf-8">
        <title>Исполнители - Tasker</title>
        <link rel="shortcut icon" href="../image/favicon.ico" type="image/x-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <link rel="stylesheet" type="text/css" href="/css/style.css">
        <script defer type="text/javascript" src="/script/js/specSymbolReplacer.js"></script>
        <script defer type="text/javascript" src="/script/js/employee.js"></script>
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
                    <a class="nav-link" href="http://<?=$domain?>/application/planning.php">Журнал планерок</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://<?=$domain?>/application/tasks.php">Задачи</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://<?=$domain?>/application/tasks_of_employee.php">Задачи исполнителей</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="http://<?=$domain?>/application/employee.php">Исполнители</a>
                </li>
            </ul>
            <div>
                <a class='btn btn-secondary verify' href='http://<?=$domain?>/script/php/a_logout.php'>Выход</a>
            </div>
        </div>
    </nav>


    <br>


    <div class="container" style="max-width: 80%">

        <h1 class="text-center mt-5 mb-3">Исполнители</h1>

        <table class="table table-hover table-striped">

            <thead class="thead-dark">
            <tr>
                <th style="width: 1px">№</th>
                <th>ФИО</th>
                <th>Должность</th>
                <th class="options">Опции</th>
            </tr>
            </thead>

            <tbody>
            <?foreach ($empBase as $key=>$emp) {?>
                    <tr id="row-<?=$key?>">
                        <td><?=$key?></td>
                        <td><?=$emp[0]?></td>
                        <td><?=$emp[1]?></td>
                        <td>
                            <button class="btn btn-secondary btn-sm edit">Редактировать</button>
                            <button class="btn btn-secondary btn-sm delete">Удалить</button>
                        </td>
                    </tr>
            <?}?>
                <tr>
                    <td colspan="3"></td>
                    <td>
                        <button class="btn btn-secondary btn-sm add">Новый</button>
                    </td>
                </tr>
            </tbody>

        </table>

    </div>
    </body>
    </html>
<?}?>