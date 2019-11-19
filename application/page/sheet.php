<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/application/logic/enum/PageEnum.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/application/logic/classes/PagePreparer.php";

$pagePreparer = new PagePreparer(PageEnum::SHEET);
$isAdmin = $pagePreparer->IsAdmin();
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

    <div class="container-fluid row mt-3 mb-4" >
        <div class="col-3"></div>
        <h2 class="col-3 text-center">Табель на:</h2>
        <div class="col-3 input-group input-group-lg">
            <div class="input-group-prepend">
                <button id="prevDate" class="btn btn-primary" type="button"><</button>
            </div>
            <input id="sheetDate" type="date" class="form-control">
            <div class="input-group-append">
                <button id="nextDate" class="btn btn-primary" type="button">></button>
            </div>
        </div>
        <div class="col-3"></div>
    </div>


    <div class="container-fluid">
        <table class="table table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>№</th>
                    <th>Должность</th>
                    <th>ФИО</th>
                    <th class="stateCol">Статус</th>
                    <th>Примечание</th>
                </tr>
            </thead>
            <tbody id="sheetData"></tbody>


            <tbody>
                <tr class="table-dark">
                    <td align="center" colspan="5">Длительное отсутствие</td>
                </tr>
            </tbody>


            <tbody id="durables"></tbody>


        </table>
    </div>

</div>



<div id="modalDurable" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalDurableLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalDurableLabel"><span id="empName"></span> - статус с диапазоном</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-white" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-row">
                    <div class="form-group flex-fill pr-1">
                        <label for="durableFrom">Дата начала</label>
                        <input id="durableFrom" type="date" class="form-control">
                    </div>
                    <div class="form-group flex-fill pl-1">
                        <label for="durableTo">Дата окончания</label>
                        <input id="durableTo" type="date" class="form-control">
                    </div>
                </div>
                <div>
                    <div class="form-group">
                        <label for="durableState">Статус</label>
                        <select id="durableState" class='form-control'></select>
                    </div>
                    <div class="form-group">
                        <label for="durableComment">Примечание</label>
                        <input id="durableComment" type="text" class="form-control" disabled>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="durableCancel" type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button id="durableSave" type="button" class="btn btn-primary">
                    <div class="spinner-border spinner-border-sm d-none" role="status"></div>
                    Установить
                </button>
            </div>
        </div>
    </div>
</div>


</body>
</html>