<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/application/logic/enum/PageEnum.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/application/logic/classes/PagePreparer.php";


$pagePreparer = new PagePreparer(PageEnum::PNF404);
?>
<!DOCTYPE html>
<html lang="ru">

<?$pagePreparer->CreateHead()?>

<body>


<?$pagePreparer->CreateNavigation()?>


<br>


<div class="container text-center">
    <h1 style="margin-top: 100px">Ошибка 404</h1>
    <h3>Страница не найдена</h3>
    <img src="http://<?=$_SERVER["SERVER_NAME"]?>/application/media/image/404.gif" class="mx-auto w-25" alt="404" style="margin-top: 100px">
</div>

</body>
</html>