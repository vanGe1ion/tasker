<?php


abstract class PageEnum
{
    public const PNF404 = array(
        "page" => "404",
        "label" => "Нету такой страницы"
    );

    public const PLANNING = array(
        "page" => "planning",
        "label" => "Журнал планерок"
    );

    public const CALENDAR = array(
        "page" => "calendar",
        "label" => "Календарь планерок"
    );

    public const TASK = array(
        "page" => "tasks",
        "label" => "Задачи"
    );

    public const TASK_OF_EMPLOYEE = array(
        "page" => "tasks_of_employee",
        "label" => "Задачи исполнителей"
    );

    public const EMPLOYEE = array(
        "page" => "employee",
        "label" => "Исполнители"
    );

    public const SHEET = array(
        "page" => "sheet",
        "label" => "Табель"
    );
}