<?php


class StateQuerySet implements IQuerySet
{
    public static function Create($data){}

    public static function Read($data){
        return "SELECT * FROM State" . (isset($data['State_ID']) ? (" WHERE State_ID = " . $data['State_ID']) : "");
    }

    public static function Update($data){}

    public static function Delete($data){}
}