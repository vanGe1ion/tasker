<?php
    if(!isset($_GET["Employee_ID"]))
        header("Location:Error.php");
    else{
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
        $dictionary = mysqli_query($db_connection, $query);
        $taskDict = array();
        while ($result = mysqli_fetch_assoc($dictionary))
            $taskDict[$result['Task_ID']] = array(
                'Task_ID' =>        $result['Task_ID'],
                'Description' =>    $result['Description'],
                'Start_Date' =>     $result['Start_Date'],
                'End_Date' =>       $result['End_Date'] ,
                'Status_Name' =>    $result['Status_Name'],
                'Result_Pointer' => $result['Result_Pointer']
            );


        $query = "SELECT Task.Task_ID, Description, Start_Date, End_Date, Status_Name, Result_Pointer FROM Task, Status, RST_Employee_Task WHERE Task.Status_ID=Status.Status_ID AND RST_Employee_Task.Task_ID=Task.Task_ID AND Employee_ID=".$_GET["Employee_ID"]." ORDER BY Task.Task_ID";
        $res = mysqli_query($db_connection, $query);
        ?>
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <title>Назначенные задачи - Tasker</title>
            <script type="text/javascript" src="/library/jQuery/jQuery.js"></script>


            <link rel="stylesheet" type="text/css" href="/css/common.css">
            <script defer type="text/javascript" src="/script/js/task-of-employer.js"></script>
            <script>
                var Employee_ID = <?=$_GET["Employee_ID"]?>;
                var StatusDict = (<?=json_encode($statusDict)?>);
                var TaskDict = (<?=json_encode($taskDict)?>);
            </script>

        </head>
        <body>



        <h1 align="center">Задачи исполнителя: <?=$_GET["Fullname"]?></h1>
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
                                <button class="table delete">Освободить</button>
                            </td>
                        </tr>
                    <?}?>

                </table>

            </div>
        </div>

        <div class="footer">
            <div class="footSub">
                <button class="subMenu add">Назначить задачу</button>
                <button class="subMenu hide"><?=($task_con==="all"?"Только актуальные":"Все")?></button>
            </div>
            <div class="footSub">
                <button class="subMenu" onclick="window.location.href = 'employee.php';">База исполнителей</button>
                <button class="subMenu" onclick="window.location.href = 'task.php';">База задач</button>
            </div>
        </div>


        <script defer>
            $(".Main").height($(document).height() - $("h1").height() - 43 - $(".footer").height() - 10);
        </script>

        </body>
        </html>
    <?}?>