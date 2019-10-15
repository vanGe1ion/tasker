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


$currentDate = date("Y-m-d");
$query = array(
    "current" => "SELECT Planning_ID, Planning.Task_ID, Description, Date, Result FROM Task, Planning WHERE Planning.Task_ID = Task.Task_ID AND Date >= '" . $currentDate . "' ORDER BY Date",
    "prev" => "SELECT Planning_ID, Planning.Task_ID, Description, Date, Result FROM Task, Planning WHERE Planning.Task_ID = Task.Task_ID AND Date < '" . $currentDate . "' ORDER BY Date DESC"
);
$planningBase = array(
    "current" => array(),
    "prev" => array()
);
foreach ($query as $type => $queryString) {
    $planning = mysqli_query($db_connection, $queryString);
    while ($planningRes = mysqli_fetch_array($planning))
        $planningBase[$type][$planningRes["Date"]][$planningRes["Planning_ID"]] = array(
            "Task_ID" => $planningRes["Task_ID"],
            "Description" => $planningRes["Description"],
            "Result" => $planningRes["Result"]
        );
}
//var_dump($planningBase);
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
</head>
<body>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="">TASKER</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-around" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="http://<?=$domain?>/application/planning.php">Журнал планерок</a>
            </li>
            <li class="nav-item active">
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
    <ul class="nav nav-tabs nav-fill">
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#current-planning">Текущие планерки</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#prev-planning">Прошедшие планерки</a>
        </li>
    </ul>


    <div class="tab-content">
        <?foreach ($planningBase as $type=>$base){?>
            <div class="tab-pane fade" id="<?=$type?>-planning">

                <h1 class="text-center mt-5"><?=($type == "current" ? "Текущие" : "Прошедшие") . " планерки"?></h1>

                <table class="table table-bordered">

                    <thead class="thead-dark">
                    <tr>
                        <th>Дата</th>
                        <th style="width: 1px">№ задачи</th>
                        <th>Тема</th>
                        <th>Результат</th>
                        <th>Исполнители</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?foreach ($base as $date=>$planning){
                        $dateRowspanIndex = 0;
                        foreach ($planning as $planID=>$plan){
                            if($currentDate == $date)
                                $rowColor = "table-warning";
                            elseif ($currentDate > $date)
                                $rowColor = "table-success";
                            else
                                $rowColor = "table-primary";
                            ?>
                            <tr class='<?=$rowColor?>'>
                                <?if($dateRowspanIndex == 0){?>
                                    <td rowspan="<?=count($planning)?>"><?=date("d.m.Y", strtotime($date))?></td>
                                <?}?>
                                <td><?=$plan["Task_ID"]?></td>
                                <td><?=$plan["Description"]?></td>
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
                            </tr>
                        <?$dateRowspanIndex = 1;
                        }
                    }?>
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
