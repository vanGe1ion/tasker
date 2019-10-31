<?php


abstract class EmployeeQuerySet implements IQuerySet
{
    public static function Create($data){
        return "INSERT INTO Employee VALUES (".$data['Employee_ID'].", '".$data['Fullname']."', '".$data['Position']."')";
    }

    public static function Read($data){}

    public static function Update($data){
        return "UPDATE Employee SET Fullname='".$data['Fullname']."', Position='".$data['Position']."' WHERE Employee_ID=".$data['Employee_ID'];
    }

    public static function Delete($data){
        return "DELETE FROM Employee WHERE Employee_ID = " .$data['Employee_ID'];
    }
}