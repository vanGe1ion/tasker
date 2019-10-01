-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Окт 01 2019 г., 17:52
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

-- --------------------------------------------------------

--
-- Структура таблицы `RST_Employee_Task`
--

CREATE TABLE `RST_Employee_Task` (
  `Employee_ID` int(11) NOT NULL,
  `Task_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `End_Date` date NOT NULL,
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
-- Индексы таблицы `RST_Employee_Task`
--
ALTER TABLE `RST_Employee_Task`
  ADD PRIMARY KEY (`Employee_ID`,`Task_ID`),
  ADD KEY `Person_ID` (`Employee_ID`),
  ADD KEY `Task_ID` (`Task_ID`);

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
  MODIFY `Employee_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `Status`
--
ALTER TABLE `Status`
  MODIFY `Status_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `Task`
--
ALTER TABLE `Task`
  MODIFY `Task_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

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
