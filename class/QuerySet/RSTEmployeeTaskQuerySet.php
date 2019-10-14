<?php


abstract class RSTEmployeeTaskQuerySet implements IQuerySet
{
    public static function Create($data){
        return "INSERT INTO RST_Employee_Task VALUES (".$data["Employee_ID"].", ".$data["Task_ID"].")";
    }

    public static function Read($data){}

    public static function Update($data){}

    public static function Delete($data){
        return "DELETE FROM RST_Employee_Task WHERE Employee_ID = ".$data["Employee_ID"]." AND Task_ID = ".$data["Task_ID"];
    }
}