-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 19, 2018 at 01:03 PM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web_sec`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id_admins` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `date_of_birth` varchar(10) NOT NULL,
  `username` varchar(25) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `email` varchar(50) NOT NULL,
  `password_hash` varchar(300) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `salt` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `terms_and_conditions` tinyint(1) NOT NULL,
  `verification_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `privilege_level` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id_comments` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `id_posts` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `id_users` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `comment` varchar(300) NOT NULL,
  `time_stamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id_comments`, `id_posts`, `id_users`, `comment`, `time_stamp`) VALUES
('5bedf9bac3031', '5bedb3653ae1b', '5beda0488cbcf', 'So funny!', '2018-11-15 23:56:58'),
('5bedfa6709171', '5bedb3653ae1b', '5beda0488cbcf', 'Very najs.', '2018-11-15 23:59:51'),
('5bedfaab7062e', '5bedb3653ae1b', '5beda0488cbcf', 'cool', '2018-11-16 00:00:59'),
('5bee0426e11d9', '5bee0412443e5', '5beda0488cbcf', 'that smile is not creepy at all.', '2018-11-16 00:41:26');

-- --------------------------------------------------------

--
-- Table structure for table `logging_in`
--

CREATE TABLE `logging_in` (
  `id_logging_in` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ip` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `attempts` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `logging_in`
--

INSERT INTO `logging_in` (`id_logging_in`, `username`, `ip`, `attempts`) VALUES
(2, 'Adamik10', '::1', 0),
(3, 'alexandra', '::1', 0),
(4, 'manka', '::1', 0),
(5, 'saska', '::1', 0),
(7, 'gaga', '::1', 0),
(8, 'sasenka', '::1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id_posts` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `id_users` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `headline` varchar(140) NOT NULL,
  `image_location` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `image_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `sensitive_content` tinyint(1) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id_posts`, `id_users`, `headline`, `image_location`, `image_name`, `sensitive_content`, `datetime`) VALUES
('5bed958bb23b9', '5bed924353b16', 'This is it', 'images/posts/5bed958bb23c8.jpg', '5bed958bb23c8', 0, '2018-11-15 18:51:09'),
('5bedb3653ae1b', '5beda0488cbcf', 'Dad me', 'images/posts/5bedb3653ae25.png', '5bedb3653ae25', 0, '2018-11-15 18:56:53'),
('5bee0412443e5', '5beda0488cbcf', 'Lolol :(', 'images/posts/5bee041244402.jpg', '5bee041244402', 0, '2018-11-16 00:41:06');

-- --------------------------------------------------------

--
-- Table structure for table `upvotes`
--

CREATE TABLE `upvotes` (
  `id_upvotes` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `id_posts` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `id_users` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_users` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `username` varchar(25) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `email` varchar(50) NOT NULL,
  `password_hash` varchar(300) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `salt` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `image_location` varchar(100) DEFAULT NULL,
  `image_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_users`, `username`, `email`, `password_hash`, `salt`, `verified`, `image_location`, `image_name`) VALUES
('5bed924353b16', 'Spyro', 'adoantal2@gmail.com', '$2y$12$I5.Vbc.FseP2lydbCARMhec5/t6gseCIGPPjui8fOEYpznVDfDPVm', '476401', 1, NULL, NULL),
('5beda0488cbcf', 'sasenka', 'sasa.labusova@gmail.com', '$2y$12$Cv7X/9eOWNoxJZuV4ts7Feh9jpBI/wZylkgVRComplkyhOCkE/4bK', '172177', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `verification_codes`
--

CREATE TABLE `verification_codes` (
  `id_verification_codes` varchar(50) NOT NULL,
  `id_users` varchar(200) NOT NULL,
  `verification_code` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `verification_codes`
--

INSERT INTO `verification_codes` (`id_verification_codes`, `id_users`, `verification_code`) VALUES
('5bed80a81ff70', '5bed80a81ff66', '5bed80a81ff6d'),
('5bed820b59a95', '5bed820b59a82', '5bed820b59a8f'),
('5bed82f79397b', '5bed82f793971', '5bed82f793978'),
('5bed8847d4d1e', '5bed8847d4d12', '5bed8847d4d1b'),
('5bed8888b1c56', '5bed8888b1c49', '5bed8888b1c52'),
('5bed924353b5c', '5bed924353b16', '5bed924353b57'),
('5beda0488cbdd', '5beda0488cbcf', '5beda0488cbd6'),
('6fd165s1dv61s561v5s1dv', 'sdfvsdv5d5df2vsv', 's2df1sf5vf1s5f1s51f');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id_admins`),
  ADD UNIQUE KEY `id_admins` (`id_admins`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id_comments`),
  ADD UNIQUE KEY `id_comments` (`id_comments`),
  ADD KEY `id_posts` (`id_posts`) USING BTREE,
  ADD KEY `id_users` (`id_users`) USING BTREE;

--
-- Indexes for table `logging_in`
--
ALTER TABLE `logging_in`
  ADD PRIMARY KEY (`id_logging_in`),
  ADD UNIQUE KEY `id_logging_in` (`id_logging_in`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id_posts`),
  ADD UNIQUE KEY `id_posts` (`id_posts`) USING BTREE,
  ADD KEY `id_users` (`id_users`) USING BTREE;

--
-- Indexes for table `upvotes`
--
ALTER TABLE `upvotes`
  ADD PRIMARY KEY (`id_upvotes`),
  ADD UNIQUE KEY `id_upvotes` (`id_upvotes`),
  ADD KEY `id_posts` (`id_posts`) USING BTREE,
  ADD KEY `id_users` (`id_users`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`),
  ADD UNIQUE KEY `id_users` (`id_users`);

--
-- Indexes for table `verification_codes`
--
ALTER TABLE `verification_codes`
  ADD PRIMARY KEY (`id_verification_codes`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logging_in`
--
ALTER TABLE `logging_in`
  MODIFY `id_logging_in` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`id_posts`) REFERENCES `posts` (`id_posts`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `upvotes`
--
ALTER TABLE `upvotes`
  ADD CONSTRAINT `upvotes_ibfk_1` FOREIGN KEY (`id_posts`) REFERENCES `posts` (`id_posts`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `upvotes_ibfk_2` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE NO ACTION ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
