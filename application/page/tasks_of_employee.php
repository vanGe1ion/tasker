<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/application/logic/enum/PageEnum.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/application/logic/classes/PagePreparer.php";


$pagePreparer = new PagePreparer(PageEnum::TASK_OF_EMPLOYEE);
$pageData = $pagePreparer->PreparePage();
extract($pageData);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <?$pagePreparer->CreateHead()?>
</head>
<body>


<?$pagePreparer->CreateNavigation()?>


<br>


<div class="container" style="max-width: 80%">
    <ul class="nav nav-tabs">
        <?foreach ($empTabBase as $key=> $empData){
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
        <?foreach ($empTabBase as $key=> $emp){?>
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
                    <? if(isset($taskEmployeeBase[$key]))
                    foreach ($taskEmployeeBase[$key] as $taskKey=> $task) {?>
                        <tr class="<?=$statusBase[$task["Status_Name"]]?>">
                            <td><?=$taskKey?></td>
                            <td><?=$task["Description"]?></td>
                            <td><?=date("d.m.Y", strtotime($task["Start_Date"]))?></td>
                            <td><?=($task["End_Date"]===null?"":date("d.m.Y", strtotime($task["End_Date"])))?></td>
                            <td><?=$task["Status_Name"]?></td>
                            <td class="result"><?=$task["Result_Pointer"]?></td>
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