<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/application/logic/enum/PageEnum.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/application/logic/classes/PagePreparer.php";


$pagePreparer = new PagePreparer(PageEnum::EMPLOYEE);
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

    <h1 class="text-center mt-5 mb-3">Исполнители</h1>

    <table class="table table-hover table-striped">

        <thead class="thead-dark">
        <tr>
            <th style="width: 1px">№</th>
            <th>ФИО</th>
            <th>Должность</th>
            <?if($isAdmin==true){?>
                <th class='options'>Опции</th>
            <?}?>
        </tr>
        </thead>

        <tbody>
        <?foreach ($empBase as $key=> $emp) {?>
                <tr id="row-<?=$key?>">
                    <td><?=$key?></td>
                    <td><?=$emp["Fullname"]?></td>
                    <td><?=$emp["Position"]?></td>
                    <?if($isAdmin == true){?>
                        <td>
                            <button class="btn btn-secondary btn-sm edit">Редактировать</button>
                            <button class="btn btn-secondary btn-sm delete">Удалить</button>
                        </td>
                    <?}?>
                </tr>
        <?}?>
        <?if($isAdmin == true){?>
        <tr>
            <td colspan="3"></td>
            <td>
                <button class="btn btn-secondary btn-sm add">Новая</button>
            </td>
        </tr>
        <?}?>
        </tbody>

    </table>

</div>
</body>
</html>
