<?php


class Heading
{
    private const SCRIPTS = array(
        0 => "tasks",
        1 => "employee",
        2 => "planning",
        3 => "dateAdapter",
        4 => "specSymbolReplacer",
    );
    private $currentScripts;

    public function __construct($activePage)
    {
        switch($activePage){
            case "tasks":{
                $this->currentScripts = [0, 3, 4];
                break;
            }
            case "employee":{
                $this->currentScripts = [1, 4];
                break;
            }
            case "planning":{
                $this->currentScripts = [2, 3, 4];
                break;
            }
            default:
                $this->currentScripts = array();
        }
    }

    public function CreateHeading($pageName, $isAdmin = false){?>
        <head>
            <meta charset="utf-8">
            <title><?=$pageName?> - Tasker</title>
            <link rel="shortcut icon" href="/application/media/image/favicon.ico" type="image/x-icon">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
            <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

            <link rel="stylesheet" type="text/css" href="/application/css/style.css">

            <?if($isAdmin == true)
                foreach ($this->currentScripts as $scriptIndex){?>
                    <script defer type="text/javascript" src="/application/script/js/<?=self::SCRIPTS[$scriptIndex]?>.js"></script>
            <?}?>
        </head>
    <?}
}