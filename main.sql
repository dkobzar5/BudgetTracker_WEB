-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3309
-- Час створення: Чрв 24 2024 р., 10:17
-- Версія сервера: 8.0.19
-- Версія PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `main`
--

-- --------------------------------------------------------

--
-- Структура таблиці `accounts`
--

CREATE TABLE `accounts` (
  `account_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `account_name` varchar(100) NOT NULL DEFAULT 'MAIN',
  `balance` decimal(10,0) NOT NULL DEFAULT '0',
  `currency` varchar(3) NOT NULL DEFAULT 'USD'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `accounts`
--

INSERT INTO `accounts` (`account_id`, `user_id`, `account_name`, `balance`, `currency`) VALUES
(1, 21, 'Basic account', '1177', 'zł'),
(2, 24, 'Basic account', '2000', 'zł'),
(3, 0, 'Basic account', '0', 'zł'),
(4, 0, 'Basic account', '0', 'zł'),
(5, 27, 'Basic account', '100', 'zł'),
(6, 28, 'Basic account', '100', 'zł');

-- --------------------------------------------------------

--
-- Структура таблиці `categories`
--

CREATE TABLE `categories` (
  `categories` int UNSIGNED NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `category_color` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `categories`
--

INSERT INTO `categories` (`categories`, `category_name`, `category_color`) VALUES
(1, 'Food', '#FF5733'),
(2, 'Transportation', '#33FF57'),
(3, 'Utilities', '#5733FF'),
(4, 'Entertainment', '#FF3357'),
(5, 'Healthcare', '#33FFD1'),
(6, 'Shopping', '#D133FF'),
(7, 'Education', '#FFE333'),
(8, 'Travel', '#33FFE7'),
(9, 'Gifts', '#33C3FF'),
(10, 'Miscellaneous', '#FF3333'),
(11, 'Housing', '#33FF77');

-- --------------------------------------------------------

--
-- Структура таблиці `expenses`
--

CREATE TABLE `expenses` (
  `expense_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `account_id` int UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `expenses`
--

INSERT INTO `expenses` (`expense_id`, `user_id`, `account_id`, `category_id`, `amount`, `description`, `date`) VALUES
(1, 21, 1, 8, '11', 'Expense 52', '2024-06-13'),
(2, 21, 1, 11, '83', 'Expense 29', '2024-05-24'),
(3, 21, 1, 1, '39', 'Expense 72', '2024-06-08'),
(4, 21, 1, 3, '54', 'Expense 12', '2024-06-22'),
(5, 21, 1, 8, '24', 'Expense 25', '2024-06-06'),
(6, 21, 1, 1, '35', 'Expense 75', '2024-06-01'),
(7, 21, 1, 5, '67', 'Expense 23', '2024-06-17'),
(8, 21, 1, 2, '36', 'Expense 24', '2024-06-18'),
(9, 21, 1, 1, '58', 'Expense 89', '2024-05-31'),
(10, 21, 1, 1, '96', 'Expense 66', '2024-06-09'),
(11, 21, 1, 4, '14', 'Expense 76', '2024-06-10'),
(12, 21, 1, 10, '93', 'Expense 10', '2024-05-31'),
(13, 21, 1, 5, '73', 'Expense 47', '2024-06-17'),
(14, 21, 1, 5, '71', 'Expense 20', '2024-05-27'),
(15, 21, 1, 9, '39', 'Expense 53', '2024-06-07'),
(16, 21, 1, 11, '35', 'Expense 81', '2024-06-22'),
(17, 21, 1, 8, '23', 'Expense 16', '2024-06-18'),
(18, 21, 1, 3, '64', 'Expense 54', '2024-05-29'),
(19, 21, 1, 5, '60', 'Expense 77', '2024-06-21'),
(20, 21, 1, 1, '87', 'Expense 29', '2024-05-27'),
(33, 24, 2, 1, '123', '123', '2024-06-23'),
(34, 24, 2, 2, '200', 'Bus', '2024-06-23'),
(35, 24, 2, 1, '500', 'Food', '2024-06-23'),
(36, 24, 2, 11, '10000', 'Rental', '2024-06-23'),
(37, 24, 2, 6, '4000', 'Closes', '2024-06-23'),
(38, 24, 2, 4, '1000', 'Clubs', '2024-06-23'),
(39, 21, 1, 1, '100', 'Food', '2024-06-23'),
(40, 21, 1, 1, '100', 'Food', '2024-06-23'),
(41, 21, 1, 1, '123', '123', '2024-06-23'),
(42, 27, 5, 11, '6000', 'Rental', '2024-06-23'),
(43, 27, 5, 1, '3000', 'Food', '2024-06-23'),
(44, 27, 5, 5, '10', 'Health', '2024-06-23'),
(45, 27, 5, 5, '200', 'Badania', '2024-06-23'),
(46, 27, 5, 9, '3000', 'Gift to Vlad', '2024-06-23'),
(47, 27, 5, 7, '123', '1', '2024-06-23'),
(49, 28, 6, 11, '1000', 'Renting apartment', '2024-06-23'),
(50, 28, 6, 1, '700', 'Food', '2024-06-23'),
(51, 28, 6, 2, '60', 'Transport', '2024-06-23'),
(52, 28, 6, 4, '400', 'Girlfriend', '2024-06-23'),
(53, 28, 6, 5, '65', 'haircar', '2024-06-23'),
(54, 28, 6, 9, '400', 'Birthday', '2024-06-23'),
(55, 21, 1, 11, '500', 'Renting', '2024-06-24'),
(56, 21, 1, 1, '1000', 'aasd', '2024-06-24'),
(57, 21, 1, 3, '200', '12', '2024-06-24'),
(58, 21, 1, 4, '123', '3', '2024-06-24');

-- --------------------------------------------------------

--
-- Структура таблиці `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `password`) VALUES
(1, 'Dmytro', 'Kobzar', 'kobzardmytro5@gmail.com', '$2y$10$/UrO7QnoV6y2xbIwJhNn0eC3Zz2NagOs.i3alyYCwE47Jnfr7xx8C'),
(2, 'Dania', 'Masanoviec', 'daniamasanoviec@gmail.comas', '$2y$10$eEYVq838Nb0MnysUn37tEO3EDyc.Ddqik2SnI/9nWerUO06ykoWlW'),
(4, 'asd', 'asd', 'kasdobzardmytro5@gmail.com', '$2y$10$cQP3eUSdJsDYJ2ubj.Bi..cbRs.uSqkO05Nnyx74Rs2zhknFnIJzy'),
(8, 'Test', 'Test', 'test@gmail.com', '$2y$10$r/QGkAOLpHBgZwFwo4bdU.MqtjiqC2ms4H5mGSpby9xhiteySy80y'),
(13, 'Test L', 'Test', 'test1@gmail.com', '$2y$10$BXcWjTn7YQWriCthVVD.c.bGM73u77qrVajNRM7j2uQmXYHG3RKJq'),
(14, 'My name', 'TESTIO', 'lola@gmail.com', '$2y$10$BHNpu5TjKoaWIc7Jk/Q8wOWnDSiljsl8.8xmas3uTDP.9/PJYocBq'),
(15, 'D', 'K', 'my@gmail.com', '$2y$10$Mn1H.sQ4YgeluRN7h5RRF.WxexdS7Sg.VOpeC3iQJ9g.GVp3zRt3C'),
(17, 'Andrii', 'Timchenko', 'atimchenko@gmail.com', '$2y$10$eMr/IxMmaumP92YMDEbjkOBDaxl2yQEx9PtxpmWhaUTGQA/Wh4Isu'),
(18, 'Daniil', 'Herasymenko', 'dherasymenko@gmail.com', '$2y$10$slWIyRposLpSn7IBVezv2el8boHz1EuLHMQH5atiDvYklBr/TOah6'),
(19, 'Dmytro', 'Kobzar', 'dkobzar@edu.cdv.pl', '$2y$10$ByyxDiPXRLwayHomXjfIN.M4qjG4n99cPWuzzwojZwicZiHXrilaO'),
(21, 'Dmytro', 'Kobzar', 'test2@gmail.com', '$2y$10$zTgjGhxcLdvs4FxVYi8zO.n3L9m34U7KpwhsLOXDTgXANRYWYJX1u'),
(23, 'me', 'me', 'test3@gmail.com', '$2y$10$4l1A4D3ntPiYuiQ2zlM4rOO06Acb8ZAslvIrE6DSHPcicl.mP0VSy'),
(24, 'dd', 'dd', 'test4@gmail.com', '$2y$10$iF4WwFbCvJB5bowaPKqSxem1kM3Tfgyr.VoEhjp9Mc6bZ4H4P6UPu'),
(27, 'Andrii', 'Timchenko', 'atimchenko1@gmail.com', '$2y$10$Z1/P1.SLNidEiguIeYlFz.4O41.BApgNcefhxaZFlF7KvsYihXL3q'),
(28, 'Andrii', 'Timchenko', 'test5@gmail.com', '$2y$10$jP7CFxPGfQOzOwCITa2zouwVL7uKvgBGBc8iurWh79pw1M.NcouqS');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`account_id`);

--
-- Індекси таблиці `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categories`);

--
-- Індекси таблиці `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expense_id`);

--
-- Індекси таблиці `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблиці `categories`
--
ALTER TABLE `categories`
  MODIFY `categories` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблиці `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expense_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT для таблиці `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
