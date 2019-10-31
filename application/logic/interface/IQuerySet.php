<?php


interface IQuerySet
{
    public static function Create($data);
    public static function Read($data);
    public static function Update($data);
    public static function Delete($data);
}