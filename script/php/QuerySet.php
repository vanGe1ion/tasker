<?php


abstract class  QuerySet
{
    static public function EmployeeCreate($data){
        return "INSERT INTO Employee VALUES (".$data['Employee_ID'].", '".$data['Fullname']."', '".$data['Position']."')";
    }

    static public function EmployeeEdit($data){
        return "UPDATE Employee SET Fullname='".$data['Fullname']."', Position='".$data['Position']."' WHERE Employee_ID=".$data['Employee_ID'];
    }

    static public function EmployeeRemove($data){
        return "DELETE FROM Employee WHERE Employee_ID = " .$data['Employee_ID'];
    }



    static public function TaskCreate($data){
        return "INSERT INTO Task VALUES (".$data["Task_ID"].", '".$data["Description"]."', '".$data["Start_Date"]."', ".($data["End_Date"]===""?"null":("'".$data["End_Date"]."'")).", '".$data["Status"]."', '".$data["Result_Pointer"]."')";//$data["End_Date"]
    }

    static public function TaskEdit($data){
        return "UPDATE Task SET Description='".$data["Description"]."', Start_Date='".$data["Start_Date"]."', End_Date=".($data["End_Date"]===""?"null":("'".$data["End_Date"]."'")).", Status_ID='".$data["Status"]."', Result_Pointer='".$data["Result_Pointer"]."' WHERE Task_ID=".$data["Task_ID"];
    }

    static public function TaskRemove($data){
        return "DELETE FROM Task WHERE Task_ID =".$data["Task_ID"];
    }



    static public function RSTEmpTaskCreate($data){
        return "INSERT INTO RST_Employee_Task VALUES (".$data["Employee_ID"].", ".$data["Task_ID"].")";
    }

    static public function RSTEmpTaskRemove($data){
        return "DELETE FROM RST_Employee_Task WHERE Employee_ID = ".$data["Employee_ID"]." AND Task_ID = ".$data["Task_ID"];
    }
}