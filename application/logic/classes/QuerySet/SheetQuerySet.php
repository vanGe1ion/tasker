<?php


abstract class SheetQuerySet implements IQuerySet
{
    public static function Create($data){
        return "INSERT INTO Sheet ". (!$data['Sheet_ID'] ? "(Date)" : "") ." VALUES (".($data['Sheet_ID'] ? $data['Sheet_ID'].", " : "")."'".$data['Date']."')";
    }

    public static function Read($data){
        return "SELECT Sheet_ID, Date FROM Sheet" . (isset($data['Date']) ? (" WHERE Date = '" . $data['Date']) . "'" : "");
    }

    public static function Update($data){}

    public static function Delete($data){
        return "DELETE FROM Sheet WHERE Sheet_ID = " .$data['Sheet_ID'];
    }

    public static function Max($data = null){
        return "SELECT Sheet_ID FROM Sheet ORDER BY Sheet_ID DESC LIMIT 1";
    }
}