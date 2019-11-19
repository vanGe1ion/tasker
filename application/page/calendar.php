<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/application/logic/enum/PageEnum.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/application/logic/classes/PagePreparer.php";


$pagePreparer = new PagePreparer(PageEnum::CALENDAR);
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
    <ul class="nav nav-tabs nav-fill">
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#current-planning">Текущие планерки</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#prev-planning">Прошедшие планерки</a>
        </li>
    </ul>


    <div class="tab-content">
        <?foreach ($planningCalendarBase as $type=> $base){?>
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
                            if(date("Y-m-d") == $date)
                                $rowColor = "table-warning";
                            elseif (date("Y-m-d") > $date)
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
                                        <? if(isset($empTaskBase[$plan["Task_ID"]]))
                                            foreach ($empTaskBase[$plan["Task_ID"]] as $empId => $empName) {?>
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
