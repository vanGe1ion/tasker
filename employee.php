<?php
$f_config = "config.json";
$configs = json_decode(file_get_contents($f_config));
$db_con = $configs->db_config;
$db_connection = mysqli_connect($db_con->host, $db_con->login, $db_con->password, $db_con->db);

$query = "SELECT * FROM Employee ORDER BY Employee_ID";

$res = mysqli_query($db_connection, $query);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Сотрудники - Tasker</title>
    <script type="text/javascript" src="/library/jQuery/jQuery.js"></script>

    <link rel="stylesheet" type="text/css" href="/css/common.css">
    <script defer type="text/javascript" src="/script/js/employee.js"></script>
</head>
<body>



    <h1 align="center">База исполнителей</h1>
    <div class="Main">
        <div class="Sub">

            <table cellspacing="0">
                <tr>
                    <th>ФИО</th>
                    <th>Должность</th>
                    <th width="1">Задачи исполнителя</th>
                    <th class="options">Опции</th>
                </tr>

                <?while ($result = mysqli_fetch_assoc($res)){?>
                    <tr id="row-<?=$result["Employee_ID"]?>">
                        <td><?=$result["Fullname"]?></td>
                        <td><?=$result["Position"]?></td>
                        <td>
                            <button class="table"  onclick="window.location.href = 'task-of-employer.php?Employee_ID=<?=$result["Employee_ID"]?>&Fullname=<?=$result["Fullname"]?>';">Задачи</button>
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
            <button class="subMenu add">Новый исполнитель</button>
        </div>
        <div class="footSub">
            <button class="subMenu" onclick="window.location.href = 'task.php';">База задач</button>
        </div>
    </div>


    <script defer>
        $(".Main").height($(document).height() - $("h1").height() - 43 - $(".footer").height() - 10);
    </script>


</body>
</html>