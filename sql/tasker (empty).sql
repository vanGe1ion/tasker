-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Ноя 12 2019 г., 16:44
-- Версия сервера: 5.7.20-log
-- Версия PHP: 7.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `tasker`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Employee`
--

CREATE TABLE `Employee` (
  `Employee_ID` int(11) NOT NULL,
  `Fullname` varchar(70) NOT NULL,
  `Position` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



--
-- Структура таблицы `Planning`
--

CREATE TABLE `Planning` (
  `Planning_ID` int(11) NOT NULL,
  `Task_ID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Result` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



--
-- Структура таблицы `RST_Employee_Sheet`
--

CREATE TABLE `RST_Employee_Sheet` (
  `Sheet_ID` int(11) NOT NULL,
  `Employee_ID` int(11) NOT NULL,
  `State_ID` int(11) NOT NULL,
  `Comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



--
-- Структура таблицы `RST_Employee_Task`
--

CREATE TABLE `RST_Employee_Task` (
  `Employee_ID` int(11) NOT NULL,
  `Task_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



--
-- Структура таблицы `Sheet`
--

CREATE TABLE `Sheet` (
  `Sheet_ID` int(11) NOT NULL,
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



--
-- Структура таблицы `State`
--

CREATE TABLE `State` (
  `State_ID` int(11) NOT NULL,
  `State_Name` varchar(10) NOT NULL,
  `Description` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `State`
--

INSERT INTO `State` (`State_ID`, `State_Name`, `Description`) VALUES
(1, 'Я', 'Явка'),
(9, 'ОТ', 'Основной отпуск'),
(10, 'ОД', 'Дополнительный отпуск'),
(15, 'ОЖ', 'Декрет'),
(16, 'ДО', 'Без содержания'),
(19, 'Б', 'Больничный'),
(26, 'В', 'Выходной');

-- --------------------------------------------------------

--
-- Структура таблицы `Status`
--

CREATE TABLE `Status` (
  `Status_ID` int(11) NOT NULL,
  `Status_Name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `Status`
--

INSERT INTO `Status` (`Status_ID`, `Status_Name`) VALUES
(1, 'Выполнено'),
(2, 'В работе'),
(3, 'Приостановлено'),
(4, 'Отменено');

-- --------------------------------------------------------

--
-- Структура таблицы `Task`
--

CREATE TABLE `Task` (
  `Task_ID` int(11) NOT NULL,
  `Description` text NOT NULL,
  `Start_Date` date NOT NULL,
  `End_Date` date DEFAULT NULL,
  `Status_ID` int(11) NOT NULL,
  `Result_Pointer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Employee`
--
ALTER TABLE `Employee`
  ADD PRIMARY KEY (`Employee_ID`);

--
-- Индексы таблицы `Planning`
--
ALTER TABLE `Planning`
  ADD PRIMARY KEY (`Planning_ID`),
  ADD KEY `Task_ID` (`Task_ID`);

--
-- Индексы таблицы `RST_Employee_Sheet`
--
ALTER TABLE `RST_Employee_Sheet`
  ADD PRIMARY KEY (`Sheet_ID`,`Employee_ID`),
  ADD KEY `Employee_ID` (`Employee_ID`),
  ADD KEY `State_ID` (`State_ID`),
  ADD KEY `Sheet_ID` (`Sheet_ID`);

--
-- Индексы таблицы `RST_Employee_Task`
--
ALTER TABLE `RST_Employee_Task`
  ADD PRIMARY KEY (`Employee_ID`,`Task_ID`),
  ADD KEY `Person_ID` (`Employee_ID`),
  ADD KEY `Task_ID` (`Task_ID`);

--
-- Индексы таблицы `Sheet`
--
ALTER TABLE `Sheet`
  ADD PRIMARY KEY (`Sheet_ID`);

--
-- Индексы таблицы `State`
--
ALTER TABLE `State`
  ADD PRIMARY KEY (`State_ID`);

--
-- Индексы таблицы `Status`
--
ALTER TABLE `Status`
  ADD PRIMARY KEY (`Status_ID`);

--
-- Индексы таблицы `Task`
--
ALTER TABLE `Task`
  ADD PRIMARY KEY (`Task_ID`),
  ADD KEY `Status_ID` (`Status_ID`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Employee`
--
ALTER TABLE `Employee`
  MODIFY `Employee_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `Planning`
--
ALTER TABLE `Planning`
  MODIFY `Planning_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `Sheet`
--
ALTER TABLE `Sheet`
  MODIFY `Sheet_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `State`
--
ALTER TABLE `State`
  MODIFY `State_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT для таблицы `Status`
--
ALTER TABLE `Status`
  MODIFY `Status_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `Task`
--
ALTER TABLE `Task`
  MODIFY `Task_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Planning`
--
ALTER TABLE `Planning`
  ADD CONSTRAINT `planning_ibfk_1` FOREIGN KEY (`Task_ID`) REFERENCES `Task` (`Task_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `RST_Employee_Sheet`
--
ALTER TABLE `RST_Employee_Sheet`
  ADD CONSTRAINT `rst_employee_sheet_ibfk_1` FOREIGN KEY (`Sheet_ID`) REFERENCES `Sheet` (`Sheet_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rst_employee_sheet_ibfk_2` FOREIGN KEY (`Employee_ID`) REFERENCES `Employee` (`Employee_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rst_employee_sheet_ibfk_3` FOREIGN KEY (`State_ID`) REFERENCES `State` (`State_ID`);

--
-- Ограничения внешнего ключа таблицы `RST_Employee_Task`
--
ALTER TABLE `RST_Employee_Task`
  ADD CONSTRAINT `rst_employee_task_ibfk_1` FOREIGN KEY (`Employee_ID`) REFERENCES `Employee` (`Employee_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rst_employee_task_ibfk_2` FOREIGN KEY (`Task_ID`) REFERENCES `Task` (`Task_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `Task`
--
ALTER TABLE `Task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`Status_ID`) REFERENCES `Status` (`Status_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
