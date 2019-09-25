<?php
$f_config = "config.json";
$configs = json_decode(file_get_contents($f_config));
$db_con = $configs->db_config;
$task_con = $configs->default->tasks;
$db_connection = mysqli_connect($db_con->host, $db_con->login, $db_con->password, $db_con->db);

$query = "SELECT * FROM Status ORDER BY Status_ID";
$dictionary = mysqli_query($db_connection, $query);
$statusDict = array();
while ($result = mysqli_fetch_array($dictionary))
    $statusDict[$result[0]] = $result[1];


$query = "SELECT Task_ID, Description, Start_Date, End_Date, Status_Name, Result_Pointer FROM Task, Status WHERE Task.Status_ID=Status.Status_ID ORDER BY Task_ID";
$res = mysqli_query($db_connection, $query);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Задачи - Tasker</title>
    <script type="text/javascript" src="/library/jQuery/jQuery.js"></script>


    <link rel="stylesheet" type="text/css" href="/css/common.css">
    <script defer type="text/javascript" src="/script/js/task.js"></script>
    <script>
        var StatusDict = (<?=json_encode($statusDict)?>);
    </script>

</head>
<body>



<h1 align="center">База задач</h1>
<div class="Main">
    <div class="Sub">

        <table cellspacing="0">
            <tr>
                <th>Описание</th>
                <th>Дата назначения</th>
                <th>Дата завершения</th>
                <th>Текущий статус</th>
                <th class="result">Результат</th>
                <th width="1">Назначенные исполнители</th>
                <th class="options">Опции</th>
            </tr>

            <?while ($result = mysqli_fetch_assoc($res)){?>
                <tr id="row-<?=$result["Task_ID"]?>">
                    <td><?=$result["Description"]?></td>
                    <td><?=date("d.m.Y", strtotime($result["Start_Date"]))?></td>
                    <td><?=date("d.m.Y", strtotime($result["End_Date"]))?></td>
                    <td><?=$result["Status_Name"]?></td>
                    <td><?=$result["Result_Pointer"]?></td>
                    <td>
                        <button class="table"  onclick="window.location.href = 'employee-of-task.php?Task_ID=<?=$result["Task_ID"]?>&Description=<?=$result["Description"]?>';">Исполнители</button>
                    </td>
                    <td class="options">
                        <button class="table edit">Редактировать</button>
                        <button class="table delete">Удалить</button>
                    </td>
                </tr>
            <?}?>

        </table>

    </div>
</div>

<div class="footer">
    <div class="footSub">
        <button class="subMenu add">Новая задача</button>
        <button class="subMenu hide"><?=($task_con==="all"?"Только актуальные":"Все")?></button>
    </div>
    <div class="footSub">
        <button class="subMenu" onclick="window.location.href = 'employee.php';">База исполнителей</button>
    </div>
</div>

<script defer>
    $(".Main").height($(document).height() - $("h1").height() - 43 - $(".footer").height() - 10);
</script>

</body>
</html>