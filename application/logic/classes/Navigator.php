<?php


class Navigator
{
    private $navs;

    public function __construct()
    {
        $this->navs = array(
            PageEnum::SHEET,
            PageEnum::TASK,
            PageEnum::TASK_OF_EMPLOYEE,
            PageEnum::PLANNING,
            PageEnum::CALENDAR,
            PageEnum::EMPLOYEE
        );
    }

    public function CreateNavigationPanel($activePage, $isAdmin = false){?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="">TASKER</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-around" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <?foreach ($this->navs as $key => $data){?>
                    <li class="nav-item<?=($data["page"] == $activePage ? " active" : "")?>">
                        <a class="nav-link" href="http://<?=$_SERVER["SERVER_NAME"]?>/application/page/<?=$data["page"]?>.php"><?=$data["label"]?></a>
                    </li>
                    <?}?>
                </ul>
                <div>
                    <?if ($isAdmin == true)
                        echo("<a class='btn btn-secondary verify' href='http://".$_SERVER["SERVER_NAME"]."/application/script/php/a_logout.php'>Выход</a>");
                    else
                        echo("<a class='btn btn-secondary verify' href='http://".$_SERVER["SERVER_NAME"]."/application/script/php/a_login.php'>Администратор</a>");?>
                </div>
            </div>
        </nav>
    <?}
}