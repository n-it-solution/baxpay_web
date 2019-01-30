-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2019 at 07:52 PM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simple_bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `id` int(11) NOT NULL,
  `name` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`id`, `name`) VALUES
(1, 'USD'),
(2, 'EUR'),
(3, 'GBP');

-- --------------------------------------------------------

--
-- Table structure for table `direct`
--

CREATE TABLE `direct` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `direct`
--

INSERT INTO `direct` (`id`, `name`, `country`, `email`) VALUES
(4, 'Abdul Manan', 'Pakistan', NULL),
(5, 'Abdul Manan', 'Pakistan', NULL),
(6, 'Abdul Manan', 'Pakistan', NULL),
(7, 'Abdul Manan', 'Pakistan', NULL),
(8, 'Abdul Manan', 'PK', 'mirza.amanan@gmail.com5'),
(9, 'Abdul Manan', 'PK', 'mirza.amanan@gmail.com5'),
(10, 'Abdul Manan', 'PK', 'mirza.amanan@gmail.com5'),
(11, 'Abdul Manan', 'PK', 'mirza.amanan@gmail.com5'),
(12, 'Abdul Manan', 'PK', 'mirza.amanan@gmail.com5'),
(13, 'Abdul Manan', 'PK', 'mirza.amanan@gmail.com5');

-- --------------------------------------------------------

--
-- Table structure for table `payer`
--

CREATE TABLE `payer` (
  `id` int(11) NOT NULL,
  `type` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` longtext COLLATE utf8mb4_unicode_ci,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payer`
--

INSERT INTO `payer` (`id`, `type`, `website`, `first_name`, `last_name`, `email`, `country`, `user_id`) VALUES
(1, 'c', NULL, 'asd', 'fgh', 'mirza.amanan@gmail.com', 'PK', 21),
(2, 'i', NULL, 'Abdul', 'Manan', 'mirza.amanan@gmail.com1', 'PK', 19),
(3, 'i', NULL, 'Abdul', 'Manan', 'mirza.amanan@gmail.com5', 'PK', 21);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `currency_id` int(11) NOT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direct_id` int(11) DEFAULT NULL,
  `attachments` longtext COLLATE utf8mb4_unicode_ci,
  `due_date` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `sender_id`, `receiver_id`, `currency_id`, `date`, `amount`, `description`, `type`, `direct_id`, `attachments`, `due_date`) VALUES
(35, NULL, 18, 1, '2018/12/26 18:06:19', '11', '11', '0', 4, NULL, NULL),
(36, NULL, 18, 1, '2018/12/26 18:07:32', '11', '11', '0', 5, NULL, NULL),
(37, NULL, 19, 1, '2018/12/26 18:09:23', '11.19', '11', '0', 6, NULL, NULL),
(38, 19, 18, 1, '2018/12/26 18:09:44', '10', 'Hello', '1', NULL, NULL, NULL),
(39, 18, 19, 1, '2018/12/26 18:10:26', '510.19', 'Accept this', '4', NULL, NULL, NULL),
(40, 19, 21, 1, '2018/12/26 18:19:53', '11', '11', '1', NULL, NULL, NULL),
(41, 19, 21, 2, '2018/12/26 18:19:53', '8.7', '11', '1', NULL, NULL, NULL),
(42, 19, 21, 3, '2018/12/26 18:19:53', '100.56768768', '11', '1', NULL, NULL, NULL),
(45, 21, 19, 3, '2018/12/26 18:19:53', '11', '111', '2', NULL, NULL, NULL),
(46, 20, 21, 1, '2019/01/23 10:48:31', '1', '111', '3', NULL, NULL, NULL),
(47, 20, 21, 3, '2019/01/23 12:41:00', '50', '1111', '3', NULL, NULL, NULL),
(48, 20, 21, 1, '2019/01/23 13:06:28', '50', 'uhjio', '3', NULL, NULL, NULL),
(49, 21, 21, 3, '2019/01/23 13:24:47', '3', 'gbrgr', '2', NULL, NULL, '2019/01/30'),
(50, 20, 21, 3, '2019/01/23 13:42:04', '50', '123', '3', NULL, NULL, NULL),
(51, 20, 21, 3, '2019/01/23 13:47:51', '50', '123', '3', NULL, NULL, NULL),
(52, 20, 21, 3, '2019/01/23 13:47:59', '50', '123', '3', NULL, NULL, NULL),
(53, 20, 21, 3, '2019/01/23 13:49:22', '50', '123', '3', NULL, NULL, NULL),
(54, 20, 21, 3, '2019/01/23 13:51:52', '50', '123', '3', NULL, 'assets/uploads/attachments/012320190151520152.jpg', NULL),
(55, 20, 21, 3, '2019/01/23 13:55:13', '50', '123', '3', NULL, 'assets/uploads/attachments/012320190155130113.jpg', '2019/01/30'),
(56, 20, 21, 2, '2019/01/26 16:24:27', '50', '1234', '2', 7, NULL, NULL),
(57, 21, 21, 1, '2019/01/26 17:37:28', '1', '12345', '3', NULL, NULL, '2019/01/27'),
(63, NULL, 21, 1, '2019/01/26 18:03:23', '101', 'bb', '4', 13, NULL, '2019/01/31');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `phoneNumber` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_expire` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL,
  `reset_request` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`, `phoneNumber`, `firstName`, `lastName`, `gender`, `type`, `code`, `client_token`, `api_key`, `api_expire`, `status`, `reset_request`) VALUES
(18, 'mirza.amanan@gmail.com2', 'mirza.amanan@gmail.com2', 'mirza.amanan@gmail.com2', 'mirza.amanan@gmail.com2', 1, NULL, '$2y$13$KrRCYDMYBSvlDuKw8/qxZOVjPca0ps1BPn7UuLKo/ILSFgSq2boVm', '2019-01-05 14:49:13', '769915', '2019-01-03 16:37:41', 'a:0:{}', '30004210772', 'Abdul', 'Manan', 'm', 'p', '609245', 'nl3IFxgS8Z', '0gWxRRr0C5', NULL, 1, NULL),
(19, 'mirza.amanan1@gmail.com', 'mirza.amanan1@gmail.com', 'mirza.amanan1@gmail.com', 'mirza.amanan1@gmail.com', 1, NULL, '$2y$13$fqCC6CADae4e/he.r3Jntuk7YTqxpSl8CHj2Dw.MBX9CifjiEQTRe', '2019-01-06 15:55:51', NULL, NULL, 'a:0:{}', '92300042107711', 'Abdul', 'Manan', 'm', 'p', '780277', 'h7HspCk0Sp', 'zWMx3aKzjU', NULL, 2, 1),
(20, 'mirza.amanan@gmail.com1', 'mirza.amanan@gmail.com1', 'mirza.amanan@gmail.com1', 'mirza.amanan@gmail.com1', 1, NULL, '$2y$13$8mThTM39DbBrtNk35.Uk1e1bfCODieF85Pj18l8UjBJsqsCFC4GfO', '2019-01-13 15:19:56', NULL, NULL, 'a:0:{}', '3000421077', 'Abdul', 'Manan', 'f', 'c', '552553', 'Yreq5ZwIhc', 'Pinn4c3OgC', NULL, 2, NULL),
(21, 'mirza.amanan@gmail.com', 'mirza.amanan@gmail.com', 'mirza.amanan@gmail.com', 'mirza.amanan@gmail.com', 1, NULL, '$2y$13$Z6GwwQ8g41y1JxjkGXhUWuQOhKYkXFjdNh6uRGXCl4sPRBJ1d/d4G', '2019-01-26 09:00:25', NULL, NULL, 'a:0:{}', '923000421077', 'Abdul', 'Manan', 'm', 'p', '911906', 'diTPrSj6oj', 'J6Ploeu04X', NULL, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `direct`
--
ALTER TABLE `direct`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payer`
--
ALTER TABLE `payer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_41CB5B99A76ED395` (`user_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_723705D1F624B39D` (`sender_id`),
  ADD KEY `IDX_723705D1CD53EDB6` (`receiver_id`),
  ADD KEY `IDX_723705D138248176` (`currency_id`),
  ADD KEY `IDX_723705D1A8230609` (`direct_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D64992FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_8D93D649A0D96FBF` (`email_canonical`),
  ADD UNIQUE KEY `UNIQ_8D93D649E85E83E4` (`phoneNumber`),
  ADD UNIQUE KEY `UNIQ_8D93D649C05FB297` (`confirmation_token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `direct`
--
ALTER TABLE `direct`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `payer`
--
ALTER TABLE `payer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payer`
--
ALTER TABLE `payer`
  ADD CONSTRAINT `FK_41CB5B99A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `FK_723705D138248176` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`),
  ADD CONSTRAINT `FK_723705D1A8230609` FOREIGN KEY (`direct_id`) REFERENCES `direct` (`id`),
  ADD CONSTRAINT `FK_723705D1CD53EDB6` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_723705D1F624B39D` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
