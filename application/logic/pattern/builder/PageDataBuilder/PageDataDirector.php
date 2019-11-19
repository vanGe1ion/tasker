<?php

require_once "PageDataBuilder.php";


class PageDataDirector
{
    private $pageDataBuilder;

    public function __construct($dbConnection)
    {
        $this->pageDataBuilder = new PageDataBuilder($dbConnection);
    }

    private function ReturnPageData(){
        return $this->pageDataBuilder->ReturnProduct();
    }

    private function ResetBuilder(){
        $this->pageDataBuilder->Reset();
    }

    public function GetTasksPageData(){
        $this->ResetBuilder();
        $this->pageDataBuilder->BuildEmployeeDropDictionary();
        $this->pageDataBuilder->BuildEmployeeByTasksBase();
        $this->pageDataBuilder->BuildStatusDictionary();
        $this->pageDataBuilder->BuildRowColorByStatusBase();
        $this->pageDataBuilder->BuildTaskContent();
        return $this->ReturnPageData();
    }

    public function GetEmployeePageData(){
        $this->ResetBuilder();
        $this->pageDataBuilder->BuildEmployeeContent();
        return $this->ReturnPageData();
    }

    public function GetTasksOfEmployeePageData(){
        $this->ResetBuilder();
        $this->pageDataBuilder->BuildRowColorByStatusBase();
        $this->pageDataBuilder->BuildEmployeeTabBase();
        $this->pageDataBuilder->BuildTasksOfEmployeeContent();
        return $this->ReturnPageData();
    }

    public function GetPlanningPageData(){
        $this->ResetBuilder();
        $this->pageDataBuilder->BuildEmployeeByTasksBase();
        $this->pageDataBuilder->BuildTaskDescriptionBase();
        $this->pageDataBuilder->BuildPlanningContent();
        return $this->ReturnPageData();
    }

    public function GetCalendarPageData(){
        $this->ResetBuilder();
        $this->pageDataBuilder->BuildEmployeeByTasksBase();
        $this->pageDataBuilder->BuildCalendarContent();
        return $this->ReturnPageData();
    }

    public function GetSheetPageData(){
        $this->ResetBuilder();
        return $this->ReturnPageData();
    }
}