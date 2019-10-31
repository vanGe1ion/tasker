<?php


abstract class TaskQuerySet implements IQuerySet
{
    public static function Create($data){
        return
            "INSERT INTO Task VALUES (".$data["Task_ID"].", '".$data["Description"]."', '".$data["Start_Date"]."', ".($data["End_Date"]===""?"null":("'".$data["End_Date"]."'")).", '".$data["Status"]."', '".$data["Result_Pointer"]."')";
    }

    public static function Read($data){}

    public static function Update($data){
        return "UPDATE Task SET Description='".$data["Description"]."', Start_Date='".$data["Start_Date"]."', End_Date=".($data["End_Date"]===""?"null":("'".$data["End_Date"]."'")).", Status_ID='".$data["Status"]."', Result_Pointer='".$data["Result_Pointer"]."' WHERE Task_ID=".$data["Task_ID"];
    }

    public static function Delete($data){
        return "DELETE FROM Task WHERE Task_ID =".$data["Task_ID"];
    }
}