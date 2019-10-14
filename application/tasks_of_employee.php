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



$query = "SELECT * FROM Status ORDER BY Status_ID";
$statusList = mysqli_query($db_connection, $query);
$stylearray = array("table-success", "table-primary", "table-warning", "table-danger");
$iter = 0;
$statusBase = array();
while ($stRes = mysqli_fetch_assoc($statusList)) {
    $statusBase[$stRes["Status_Name"]] = $stylearray[$iter];
    ++$iter;
}



$query = "SELECT * FROM Employee ORDER BY Fullname";
$employeeList = mysqli_query($db_connection, $query);
$empBase = array();
while ($empRes = mysqli_fetch_assoc($employeeList))
    $empBase[$empRes["Employee_ID"]] = array($empRes["Fullname"], $empRes["Position"]);



$query = "SELECT Employee.Employee_ID, Task.Task_ID, Description, Start_Date, End_Date, Status_Name, Result_Pointer FROM RST_Employee_Task, Task, Employee, Status WHERE RST_Employee_Task.Task_ID=Task.Task_ID AND Task.Status_ID=Status.Status_ID AND RST_Employee_Task.Employee_ID = Employee.Employee_ID ORDER BY Fullname";
$tasks = mysqli_query($db_connection, $query);
$taskBase = array();
while ($taskRes = mysqli_fetch_assoc($tasks))
    $taskBase[$taskRes["Employee_ID"]][$taskRes["Task_ID"]] = array($taskRes["Description"], $taskRes["Start_Date"], $taskRes["End_Date"], $taskRes["Status_Name"], $taskRes["Result_Pointer"]);

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
                <a class="nav-link" href="http://<?=$domain?>/application/planning.php">Журнал планерок</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="http://<?=$domain?>/application/tasks.php">Задачи</a>
            </li>
            <li class="nav-item active">
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
    <ul class="nav nav-tabs">
        <?foreach ($empBase as $key=>$empData){
            $expl = explode(" ", $empData[0]);
            if(count($expl) == 3)
                $initials = $expl[0]." ".mb_substr($expl[1], 0, 1).".".mb_substr($expl[2], 0, 1).".";
            else
                $initials = $expl[0].(isset($expl[1])?(" ".$expl[1]):"");
        ?>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#employee-<?=$key?>"><?=$initials?></a>
        </li>
        <?}?>
    </ul>


    <div class="tab-content">
        <?foreach ($empBase as $key=>$emp){?>
        <div class="tab-pane fade" id="employee-<?=$key?>">

            <h1 class="text-center mt-5"><?=$emp[0]?></h1>
            <h3 class="text-center mb-3">(<?=$emp[1]?>)</h3>

            <table class="table table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 1px">№</th>
                        <th>Описание</th>
                        <th>Дата назначения</th>
                        <th>Дата завершения</th>
                        <th>Статус</th>
                        <th>Результат</th>
                    </tr>
                </thead>
                <tbody>
                    <? if(isset($taskBase[$key]))
                    foreach ($taskBase[$key] as $taskKey=>$task) {?>
                        <tr class="<?=$statusBase[$task[3]]?>">
                            <td><?=$taskKey?></td>
                            <td><?=$task[0]?></td>
                            <td><?=date("d.m.Y", strtotime($task[1]))?></td>
                            <td><?=($task[2]===null?"":date("d.m.Y", strtotime($task[2])))?></td>
                            <td><?=$task[3]?></td>
                            <td class="result"><?=$task[4]?></td>
                        </tr>
                    <?}?>
                </tbody>
            </table>
        </div>
        <?}?>
    </div>
</div>


<script defer>
    $(".nav-tabs .nav-link:eq(0)").addClass("active");
    $(".tab-pane:eq(0)").addClass("active show");
</script>
</body>
</html>