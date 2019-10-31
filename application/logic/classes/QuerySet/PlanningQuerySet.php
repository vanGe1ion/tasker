<?php


abstract class PlanningQuerySet implements IQuerySet
{
    public static function Create($data){
        return
            "INSERT INTO Planning VALUES (".$data["Planning_ID"].", ".$data["Task"].", '".$data["Date"]."', '".$data["Result"]."')";
    }

    public static function Read($data){}

    public static function Update($data){
        return "UPDATE Planning SET Task_ID=".$data["Task"].", Date='".$data["Date"]."',  Result='".$data["Result"]."' WHERE Planning_ID = ".$data["Planning_ID"];
    }

    public static function Delete($data){
        return "DELETE FROM Planning WHERE Planning_ID =".$data["Planning_ID"];
    }
}