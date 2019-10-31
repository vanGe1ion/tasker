<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/application/logic/classes/DBConfigurator.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/application/logic/pattern/builder/PageDataBuilder/PageDataDirector.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/application/logic/classes/Navigator.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/application/logic/classes/Heading.php";


class PagePreparer
{
    private const CONFIG_PATH = "C:/OSPanel/domains/tasker/application/private/config.json";
    private $currentPage;
    private $isAdmin;
    private $pageDataDirector;
    private $heading;
    private $navigator;


    public function __construct(array $currentPage) {
        $this->currentPage = $currentPage;

        session_start();
        $_SESSION["backTrace"] = "http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"];
        $this->isAdmin = $_SESSION["isAdmin"];

        $this->heading = new Heading($this->currentPage["page"]);
        $this->navigator = new Navigator();

        $configurator = new DBConfigurator($this::CONFIG_PATH);
        $db_connection = $configurator->GetConnection();
        $this->pageDataDirector = new PageDataDirector($db_connection);
    }

    public function IsAdmin(){
        return ($this->isAdmin == true ? true : false);
    }

    public function PreparePage(){
        $page = $this->UnderScore2Pascal($this->currentPage["page"]);
        $directorTask ="Get" . $page . "PageData";
        return $this->pageDataDirector->$directorTask();
    }

    public function CreateNavigation(){
        $this->navigator->CreateNavigationPanel($this->currentPage["page"], $this->isAdmin);
    }

    public function CreateHead(){
        $this->heading->CreateHeading($this->currentPage["label"], $this->isAdmin);
    }

    private function UnderScore2Pascal(string $str){
        $explode = explode("_", $str);
        $result = "";
        foreach($explode as $part)
            $result .= ucwords($part);
        return $result;
    }
}