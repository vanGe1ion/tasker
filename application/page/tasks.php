<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/application/logic/enum/PageEnum.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/application/logic/classes/PagePreparer.php";


$pagePreparer = new PagePreparer(PageEnum::TASK);
$isAdmin = $pagePreparer->IsAdmin();
$pageData = $pagePreparer->PreparePage();
extract($pageData);
?>
<!DOCTYPE html>
<html lang="ru">

<?$pagePreparer->CreateHead()?>

<body>


<?$pagePreparer->CreateNavigation()?>


<br>


<div class="container" style="max-width: 80%">

    <h1 class="text-center mt-5 mb-3">Задачи</h1>

    <table class="table table-hover table-striped">

        <thead class="thead-dark">
        <tr>
            <th style="width: 1px">№</th>
            <th>Описание</th>
            <th>Дата назначения</th>
            <th>Дата завершения</th>
            <th>Статус</th>
            <th>Результат</th>
            <th>Исполнители</th>
            <?if($isAdmin==true){?>
                <th class='options'>Опции</th>
            <?}?>
        </tr>
        </thead>

        <tbody>
        <?foreach ($taskBase as $taskKey=> $task) {?>
            <tr id="row-<?=$taskKey?>" class="<?=$statusBase[$task["Status_Name"]]?>">
                <td><?=$taskKey?></td>
                <td><?=$task["Description"]?></td>
                <td><?=date("d.m.Y", strtotime($task["Start_Date"]))?></td>
                <td><?=($task["End_Date"]===null?"":date("d.m.Y", strtotime($task["End_Date"])))?></td>
                <td><?=$task["Status_Name"]?></td>
                <td class="result"><?=$task["Result_Pointer"]?></td>
                <td class='employeeList'>
                    <div>
                    <? if(isset($empTaskBase[$taskKey]))
                    foreach ($empTaskBase[$taskKey] as $empId=>$empName) {?>
                        <div class="btn-group btn-group-sm mb-1">
                            <button class="btn btn-sm btn-primary empButton"><?=$empName?></button>
                            <button id="emp-<?=$empId?>" class="btn btn-sm btn-primary empOperation dismiss<?=$isAdmin==true?"":" disabled"?>">x</button>
                        </div>
                    <?}
                    if($isAdmin == true){?>
                        <div class="btn-group btn-group-sm empAdd">
                            <button class="btn btn-sm btn-success empButton">Назначить</button>
                            <button class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split empOperation" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <?foreach ($empDropDict as $key=>$employer){?>
                                <a id="newemp-<?=$key?>" href="#" class="dropdown-item<?if(isset($empTaskBase[$taskKey]))echo(in_array($employer, $empTaskBase[$taskKey])? ' d-none':'')?>"><?=$employer?></a>
                                <?}?>
                            </div>
                        </div>
                    <?}?>
                    </div>
                </td>
                <?if($isAdmin == true){?>
                    <td>
                        <button class="btn btn-secondary btn-sm edit">Редактировать</button>
                        <button class="btn btn-secondary btn-sm delete">Удалить</button>
                    </td>
                <?}?>
            </tr>
        <?}

        if($isAdmin == true){?>
            <tr>
                <td colspan="7"></td>
                <td>
                    <button class="btn btn-secondary btn-sm add">Новая</button>
                </td>
            </tr>
        <?}?>
        </tbody>

    </table>

</div>

<?if($isAdmin == true){?>
    <script>
        var StatusDict = <?=json_encode($statusDict)?>;
        var StatusBase = <?=json_encode($statusBase)?>;
        var EmpDict = <?=json_encode($empDropDict)?>;
    </script>
<?}?>
</body>
</html>
