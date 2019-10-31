<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/application/logic/interface/IQuerySet.php";


class QueryFactory
{
    private $querySetClass;
    private $queryMethod;


    public function __construct() {
        $this->querySetClass = null;
        $this->queryMethod = null;
    }

    public function InitializeQuery($querySetClassName, $queryMethodName){
        $classPath = $_SERVER["DOCUMENT_ROOT"] ."/application/logic/classes/QuerySet/".$querySetClassName.".php";
        if(file_exists($classPath)) {
            require_once $classPath;
            $this->querySetClass = $querySetClassName;
        }
        else die();

        $queryMethod = ucfirst($queryMethodName);
        if(method_exists($querySetClassName, $queryMethod)){
            $this->queryMethod = $queryMethod;
        }
        else die();
    }

    public function GetQuery($queryData) {
        $querySet = $this->querySetClass;
        $queryMethod = $this->queryMethod;

        if($querySet !== null && $queryMethod !== null){
            return $querySet::$queryMethod($queryData);
        }
        else
            die();
    }
}