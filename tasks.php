<?php
$f_config = "config.json";
$configs = json_decode(file_get_contents($f_config));
$db_con = $configs->db_config;
$db_connection = mysqli_connect($db_con->host, $db_con->login, $db_con->password, $db_con->db);



$query = "SELECT * FROM Status ORDER BY Status_ID";
$statusList = mysqli_query($db_connection, $query);
$stylearray = array("table-success", "table-primary", "table-warning", "table-danger");
$iter = 0;
$statusDict = array();
$statusBase = array();
while ($stRes = mysqli_fetch_assoc($statusList)) {
    $statusDict[$stRes["Status_ID"]] = $stRes["Status_Name"];
    $statusBase[$stRes["Status_Name"]] = $stylearray[$iter];
    ++$iter;
}



$query = "SELECT Task_ID, Employee.Employee_ID, Fullname FROM RST_Employee_Task, Employee WHERE RST_Employee_Task.Employee_ID=Employee.Employee_ID ORDER BY Task_ID, Employee.Employee_ID";
$employee = mysqli_query($db_connection, $query);
$empBase = array();
while ($empRes = mysqli_fetch_assoc($employee)) {
    $expl = explode(" ", $empRes["Fullname"]);
    if(count($expl) > 1)
        $initials = $expl[0]." ".mb_substr($expl[1], 0, 1).".".mb_substr($expl[2], 0, 1).".";
    else
        $initials = $expl[0];
    $empBase[$empRes["Task_ID"]][$empRes["Employee_ID"]] = $initials;
}
//var_dump($empBase);


$query = "SELECT * FROM Employee ORDER BY Employee_ID";
$employee = mysqli_query($db_connection, $query);
$empDict = array();
while ($empRes = mysqli_fetch_assoc($employee)) {
    $expl = explode(" ", $empRes["Fullname"]);
    if(count($expl) > 1)
        $initials = $expl[0]." ".mb_substr($expl[1], 0, 1).".".mb_substr($expl[2], 0, 1).".";
    else
        $initials = $expl[0];
    $empDict[$empRes["Employee_ID"]] = $initials;
}



$query = "SELECT Task_ID, Description, Start_Date, End_Date, Status_Name, Result_Pointer FROM Task, Status WHERE Task.Status_ID=Status.Status_ID ORDER BY Task_ID";
$tasks = mysqli_query($db_connection, $query);
$taskBase = array();
while ($taskRes = mysqli_fetch_assoc($tasks))
    $taskBase[$taskRes["Task_ID"]] = array($taskRes["Description"], $taskRes["Start_Date"], $taskRes["End_Date"], $taskRes["Status_Name"], $taskRes["Result_Pointer"]);





?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Задачи исполнителей - Tasker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <script defer type="text/javascript" src="/script/js/tasks.js"></script>
</head>
<body>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">TASKER</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="http://tasker/tasks.php">Задачи</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="http://tasker/tasks_of_employee.php">Задачи исполнителей</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="http://tasker/employee.php">Исполнители</a>
            </li>
        </ul>
    </div>
</nav>


<br>


<div class="container" style="max-width: 80%">

    <h1 class="text-center mt-5 mb-3">Задачи</h1>

    <table class="table table-hover table-striped">

        <thead class="thead-dark">
        <tr>
            <th>Описание</th>
            <th>Дата назначения</th>
            <th>Дата завершения</th>
            <th>Статус</th>
            <th>Результат</th>
            <th>Исполнители</th>
            <th class="options">Опции</th>
        </tr>
        </thead>

        <tbody>
        <?foreach ($taskBase as $taskKey=>$task) {?>
            <tr id="row-<?=$taskKey?>" class="<?=$statusBase[$task[3]]?>">
                <td><?=$task[0]?></td>
                <td><?=date("d.m.Y", strtotime($task[1]))?></td>
                <td><?=date("d.m.Y", strtotime($task[2]))?></td>
                <td><?=$task[3]?></td>
                <td class="result"><?=$task[4]?></td>
                <td>
                    <div style="width: 180px">
                    <? if(isset($empBase[$taskKey]))
                    foreach ($empBase[$taskKey] as $empId=>$empName) {?>
                        <div class="btn-group btn-group-sm mb-1">
                            <button class="btn btn-sm btn-primary empButton"><?=$empName?></button>
                            <button id="emp-<?=$empId?>" class="btn btn-sm btn-primary empOperation dismiss">x</button>
                        </div>
                    <?}?>
                        <div class="btn-group btn-group-sm empAdd">
                            <button class="btn btn-sm btn-success empButton">Назначить</button>
                            <button class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split empOperation" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <?foreach ($empDict as $key=>$employer){?>
                                <a id="newemp-<?=$key?>" href="#" class="dropdown-item<?if(isset($empBase[$taskKey]))echo(in_array($employer, $empBase[$taskKey])? ' disabled':'')?>"><?=$employer?></a>
                                <?}?>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <button class="btn btn-secondary btn-sm edit">Редактировать</button>
                    <button class="btn btn-secondary btn-sm delete">Удалить</button>
                </td>
            </tr>
        <?}?>
        <tr>
            <td colspan="6"></td>

            <td>
                <button class="btn btn-secondary btn-sm add">Новая</button>
            </td>
        </tr>
        </tbody>

    </table>

</div>
<script>
    var StatusDict = <?=json_encode($statusDict)?>;
    var StatusBase = <?=json_encode($statusBase)?>;
    var EmpDict = <?=json_encode($empDict)?>;
</script>

</body>
</html>