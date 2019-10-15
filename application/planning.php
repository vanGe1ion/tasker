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


$query = "SELECT Task_ID, Employee.Employee_ID, Fullname FROM RST_Employee_Task, Employee WHERE RST_Employee_Task.Employee_ID=Employee.Employee_ID ORDER BY Task_ID, Employee.Employee_ID";
$employee = mysqli_query($db_connection, $query);
$empBase = array();
while ($empRes = mysqli_fetch_assoc($employee)) {
    $expl = explode(" ", $empRes["Fullname"]);
    if (count($expl) == 3)
        $initials = $expl[0] . " " . mb_substr($expl[1], 0, 1) . "." . mb_substr($expl[2], 0, 1) . ".";
    else
        $initials = $expl[0] . (isset($expl[1]) ? (" " . $expl[1]) : "");
    $empBase[$empRes["Task_ID"]][$empRes["Employee_ID"]] = $initials;
}



$query = "SELECT Task_ID, Description FROM Task ORDER BY Task_ID";
$tasks = mysqli_query($db_connection, $query);
$taskDict = array();
while ($taskRes = mysqli_fetch_assoc($tasks))
    $taskDict[$taskRes["Task_ID"]] = $taskRes["Description"];



$query = "SELECT Planning_ID, Planning.Task_ID, Description, Date, Result FROM Task, Planning WHERE Planning.Task_ID = Task.Task_ID ORDER BY Planning_ID";
$planning = mysqli_query($db_connection, $query);
$planningBase = array();
while ($planningRes = mysqli_fetch_array($planning))
    $planningBase[$planningRes["Planning_ID"]] = array(
        "Task_ID" => $planningRes["Task_ID"],
        "Description" => $planningRes["Description"],
        "Date" => $planningRes["Date"],
        "Result" => $planningRes["Result"]
    );


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Журнал планерок - Tasker</title>
    <link rel="shortcut icon" href="../image/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <?if($is_admin==true){?>
        <script defer type="text/javascript" src="/script/js/planning.js"></script>
        <script defer type="text/javascript" src="/script/js/dateAdapter.js"></script>
        <script defer type="text/javascript" src="/script/js/specSymbolReplacer.js"></script>
    <?}?>
</head>
<body>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="">TASKER</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-around" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="http://<?=$domain?>/application/planning.php">Журнал планерок</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="http://<?=$domain?>/application/calendar.php">Календарь планерок</a>
            </li>
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


<br>


<div class="container" style="max-width: 80%">

    <h1 class="text-center mt-5 mb-3">Журнал планерок</h1>

    <table class="table table-hover table-striped">

        <thead class="thead-dark">
        <tr>
            <th style="width: 1px">№</th>
            <th>Задача</th>
            <th>Дата</th>
            <th>Результат</th>
            <th>Исполнители</th>
            <?if($is_admin==true){?>
                <th class='options'>Опции</th>
            <?}?>
        </tr>
        </thead>

        <tbody>
        <?foreach ($planningBase as $planningKey => $plan) {?>
            <tr id="row-<?=$planningKey?>">
                <td><?=$planningKey?></td>
                <td><?=$plan["Description"]?></td>
                <td><?=date("d.m.Y", strtotime($plan["Date"]))?></td>
                <td class="result"><?=$plan["Result"]?></td>
                <td class="employeeList">
                    <div>
                        <? if(isset($empBase[$plan["Task_ID"]]))
                            foreach ($empBase[$plan["Task_ID"]] as $empId => $empName) {?>
                                <div class="btn-group btn-group-sm mb-1">
                                    <button class="btn btn-sm btn-primary empButton"><?=$empName?></button>
                                    <button id="emp-<?=$empId?>" class="btn btn-sm btn-primary empOperation dismiss"></button>
                                </div>
                            <?}?>
                    </div>
                </td>
                <?if($is_admin == true){?>
                    <td>
                        <button class="btn btn-secondary btn-sm edit">Редактировать</button>
                        <button class="btn btn-secondary btn-sm delete">Удалить</button>
                    </td>
                <?}?>
            </tr>
        <?}

        if($is_admin == true){?>
            <tr>
                <td colspan="5"></td>
                <td>
                    <button class="btn btn-secondary btn-sm add">Новая</button>
                </td>
            </tr>
        <?}?>
        </tbody>

    </table>

</div>


<?if($is_admin == true){?>
    <script>
        var TaskDict = <?=json_encode($taskDict)?>;
        var EmpBase = <?=json_encode($empBase)?>;
    </script>
<?}?>


</body>
</html>