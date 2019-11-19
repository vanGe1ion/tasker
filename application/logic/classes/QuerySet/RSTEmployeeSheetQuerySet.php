<?php


abstract class RSTEmployeeSheetQuerySet implements IQuerySet
{
    public static function Create($data){
        return
            "INSERT INTO RST_Employee_Sheet 
              VALUES (".$data['Sheet_ID'].", ".$data['Employee_ID'].", ".$data['State_ID'].", '".$data['Comment']."')";
    }

    public static function Read($data){
        return
            "SELECT Employee_ID, RST_Employee_Sheet.State_ID, State_Name, Comment 
              FROM RST_Employee_Sheet, State 
              WHERE RST_Employee_Sheet.State_ID = State.State_ID AND Sheet_ID = " . $data['Sheet_ID'] .
              (isset($data['Employee_ID']) ? (" AND Employee_ID = " . $data['Employee_ID']) : "");
    }

    public static function Update($data){
        return
            "UPDATE RST_Employee_Sheet 
              SET State_ID=".$data['State_ID'].", Comment='".$data['Comment']."' 
              WHERE Sheet_ID = " .$data['Sheet_ID'] . " AND Employee_ID = " .$data['Employee_ID'];
    }

    public static function Delete($data){
        return
            "DELETE FROM RST_Employee_Sheet 
              WHERE Sheet_ID = " .$data['Sheet_ID'] . " AND Employee_ID = " .$data['Employee_ID'];
    }
}