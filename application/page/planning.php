<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/application/logic/enum/PageEnum.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/application/logic/classes/PagePreparer.php";


$pagePreparer = new PagePreparer(PageEnum::PLANNING);
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

    <h1 class="text-center mt-5 mb-3">Журнал планерок</h1>

    <table class="table table-hover table-striped">

        <thead class="thead-dark">
        <tr>
            <th style="width: 1px">№</th>
            <th>Задача</th>
            <th>Дата</th>
            <th>Результат</th>
            <th>Исполнители</th>
            <?if($isAdmin==true){?>
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
                        <? if(isset($empTaskBase[$plan["Task_ID"]]))
                            foreach ($empTaskBase[$plan["Task_ID"]] as $empId => $empName) {?>
                                <div class="btn-group btn-group-sm mb-1">
                                    <button class="btn btn-sm btn-primary empButton"><?=$empName?></button>
                                    <button id="emp-<?=$empId?>" class="btn btn-sm btn-primary empOperation dismiss"></button>
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
                <td colspan="5"></td>
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
        var TaskDict = <?=json_encode($taskDescrBase)?>;
        var EmpBase = <?=json_encode($empTaskBase)?>;
    </script>
<?}?>


</body>
</html>