<?php


class PageDataBuilder
{
    private $dataProduct;
    private $dbConnection;

    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
        $this->Reset();
    }

    public function Reset() {
        $this->dataProduct = array();
    }

    public function ReturnProduct(){
        return $this->dataProduct;
    }

    public function BuildEmployeeDropDictionary(){
        $query = "SELECT * FROM Employee ORDER BY Employee_ID";
        $employee = mysqli_query($this->dbConnection, $query);
        $empDropDict = array();
        while ($empRes = mysqli_fetch_assoc($employee)) {
            $expl = explode(" ", $empRes["Fullname"]);
            if(count($expl) == 3)
                $initials = $expl[0]." ".mb_substr($expl[1], 0, 1).".".mb_substr($expl[2], 0, 1).".";
            else
                $initials = $expl[0].(isset($expl[1])?(" ".$expl[1]):"");
            $empDropDict[$empRes["Employee_ID"]] = $initials;
        }

        $this->dataProduct["empDropDict"] = $empDropDict;
    }

    public function BuildEmployeeByTasksBase(){
        $query = "SELECT Task_ID, Employee.Employee_ID, Fullname FROM RST_Employee_Task, Employee WHERE RST_Employee_Task.Employee_ID=Employee.Employee_ID ORDER BY Task_ID, Employee.Employee_ID";
        $employee = mysqli_query($this->dbConnection, $query);
        $empTaskBase = array();
        while ($empRes = mysqli_fetch_assoc($employee)) {
            $expl = explode(" ", $empRes["Fullname"]);
            if (count($expl) == 3)
                $initials = $expl[0] . " " . mb_substr($expl[1], 0, 1) . "." . mb_substr($expl[2], 0, 1) . ".";
            else
                $initials = $expl[0] . (isset($expl[1]) ? (" " . $expl[1]) : "");
            $empTaskBase[$empRes["Task_ID"]][$empRes["Employee_ID"]] = $initials;
        }

        $this->dataProduct["empTaskBase"] = $empTaskBase;
    }

    public function BuildStatusDictionary(){
        $query = "SELECT * FROM Status ORDER BY Status_ID";
        $statusList = mysqli_query($this->dbConnection, $query);
        $statusDict = array();
        while ($stRes = mysqli_fetch_assoc($statusList)) {
            $statusDict[$stRes["Status_ID"]] = $stRes["Status_Name"];
        }

        $this->dataProduct["statusDict"] = $statusDict;
    }

    public function BuildRowColorByStatusBase(){
        if (!isset($this->dataProduct["statusDict"]))
            $this->BuildStatusDictionary();
        $statusDict = $this->dataProduct["statusDict"];
        $stylearray = array("table-success", "table-primary", "table-warning", "table-danger");
        $statusBase = array();
        foreach ($statusDict as $key=>$status) {
            $statusBase[$status] = $stylearray[$key - 1];
        }

        $this->dataProduct["statusBase"] = $statusBase;
    }

    public function BuildEmployeeTabBase(){
        $query = "SELECT * FROM Employee ORDER BY Fullname";
        $employeeList = mysqli_query($this->dbConnection, $query);
        $empTabBase = array();
        while ($empRes = mysqli_fetch_assoc($employeeList))
            $empTabBase[$empRes["Employee_ID"]] = array($empRes["Fullname"], $empRes["Position"]);

        $this->dataProduct["empTabBase"] = $empTabBase;
    }

    public function BuildTaskDescriptionBase(){
        $query = "SELECT Task_ID, Description FROM Task ORDER BY Task_ID";
        $tasks = mysqli_query($this->dbConnection, $query);
        $taskDescrBase = array();
        while ($taskRes = mysqli_fetch_assoc($tasks))
            $taskDescrBase[$taskRes["Task_ID"]] = $taskRes["Description"];

        $this->dataProduct["taskDescrBase"] = $taskDescrBase;
    }

    public function BuildTaskContent(){
        $query = "SELECT Task_ID, Description, Start_Date, End_Date, Status_Name, Result_Pointer FROM Task, Status WHERE Task.Status_ID=Status.Status_ID ORDER BY Task_ID";
        $tasks = mysqli_query($this->dbConnection, $query);
        $taskBase = array();
        while ($taskRes = mysqli_fetch_assoc($tasks))
            $taskBase[$taskRes["Task_ID"]] = array(
                "Description" => $taskRes["Description"],
                "Start_Date" => $taskRes["Start_Date"],
                "End_Date" => $taskRes["End_Date"],
                "Status_Name" => $taskRes["Status_Name"],
                "Result_Pointer" => $taskRes["Result_Pointer"]
            );

        $this->dataProduct["taskBase"] = $taskBase;
    }

    public function BuildEmployeeContent(){
        $query = "SELECT * FROM Employee ORDER BY Employee_ID";
        $employeeList = mysqli_query($this->dbConnection, $query);
        $empBase = array();
        while ($empRes = mysqli_fetch_assoc($employeeList))
            $empBase[$empRes["Employee_ID"]] = array(
                "Fullname" => $empRes["Fullname"],
                "Position" => $empRes["Position"]
            );

        $this->dataProduct["empBase"] = $empBase;
    }

    public function BuildTasksOfEmployeeContent(){
        $query = "SELECT Employee.Employee_ID, Task.Task_ID, Description, Start_Date, End_Date, Status_Name, Result_Pointer FROM RST_Employee_Task, Task, Employee, Status WHERE RST_Employee_Task.Task_ID=Task.Task_ID AND Task.Status_ID=Status.Status_ID AND RST_Employee_Task.Employee_ID = Employee.Employee_ID ORDER BY Fullname";
        $tasks = mysqli_query($this->dbConnection, $query);
        $taskEmployeeBase = array();
        while ($taskRes = mysqli_fetch_assoc($tasks))
            $taskEmployeeBase[$taskRes["Employee_ID"]][$taskRes["Task_ID"]] = array(
                "Description" => $taskRes["Description"],
                "Start_Date" => $taskRes["Start_Date"],
                "End_Date" => $taskRes["End_Date"],
                "Status_Name" => $taskRes["Status_Name"],
                "Result_Pointer" => $taskRes["Result_Pointer"]
            );

        $this->dataProduct["taskEmployeeBase"] = $taskEmployeeBase;
    }

    public function BuildPlanningContent(){
        $query = "SELECT Planning_ID, Planning.Task_ID, Description, Date, Result FROM Task, Planning WHERE Planning.Task_ID = Task.Task_ID ORDER BY Planning_ID";
        $planning = mysqli_query($this->dbConnection, $query);
        $planningBase = array();
        while ($planningRes = mysqli_fetch_array($planning))
            $planningBase[$planningRes["Planning_ID"]] = array(
                "Task_ID" => $planningRes["Task_ID"],
                "Description" => $planningRes["Description"],
                "Date" => $planningRes["Date"],
                "Result" => $planningRes["Result"]
            );

        $this->dataProduct["planningBase"] = $planningBase;
    }

    public function BuildCalendarContent(){
        $currentDate = date("Y-m-d");
        $query = array(
            "current" => "SELECT Planning_ID, Planning.Task_ID, Description, Date, Result FROM Task, Planning WHERE Planning.Task_ID = Task.Task_ID AND Date >= '" . $currentDate . "' ORDER BY Date",
            "prev" => "SELECT Planning_ID, Planning.Task_ID, Description, Date, Result FROM Task, Planning WHERE Planning.Task_ID = Task.Task_ID AND Date < '" . $currentDate . "' ORDER BY Date DESC"
        );
        $planningCalendarBase = array(
            "current" => array(),
            "prev" => array()
        );
        foreach ($query as $type => $queryString) {
            $planning = mysqli_query($this->dbConnection, $queryString);
            while ($planningRes = mysqli_fetch_array($planning))
                $planningCalendarBase[$type][$planningRes["Date"]][$planningRes["Planning_ID"]] = array(
                    "Task_ID" => $planningRes["Task_ID"],
                    "Description" => $planningRes["Description"],
                    "Result" => $planningRes["Result"]
                );
        }

        $this->dataProduct["planningCalendarBase"] = $planningCalendarBase;
    }
}