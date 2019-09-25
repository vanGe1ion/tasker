<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Неудача - Tasker</title>
        <script type="text/javascript" src="/library/jQuery/jQuery.js"></script>

        <link rel="stylesheet" type="text/css" href="/css/common.css">
    </head>
    <body>

        <h1 align="center">Ошибка передачи данных</h1>
        <div class="Main">
            <div class="Sub">
                <h2 style="color: firebrick" align="center">Во время перехода на новую страницу была потеряна часть данных.<br>Попробуйте еще раз</h2>
            </div>
        </div>

        <div class="footer">
            <div class="footSub">
                <button class="subMenu" onclick="window.location.href = 'index.php';">Вернуться</button>
            </div>
        </div>

        <script defer>
            $(".Main").height($(document).height() - $("h1").height() - 43 - $(".footer").height() - 10);
        </script>

    </body>
</html>