-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2018 at 07:51 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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
('5bee0426e11d9', '5bee0412443e5', '5beda0488cbcf', 'that smile is not creepy at all.', '2018-11-16 00:41:26'),
('5bf32f8248401', '5bee0412443e5', '5beda0488cbcf', '<script>alert(\"Hello! I am an alert box!!\");</script>', '2018-11-19 22:47:46'),
('5bf561fa92043', '5bedb3653ae1b', '5beda0488cbcf', 'haha', '2018-11-21 14:47:38'),
('5bf58e31c1b7f', '5bedb3653ae1b', '5bf56858bd93f', 'pretty!', '2018-11-21 17:56:17'),
('5bf6eea7000db', '5bf6edd742e11', '5bf6bf8dbfd0d', 'Test test test', '2018-11-22 19:00:07'),
('5bf6f37787a31', '5bf6f31fb54d0', '5bf6bf8dbfd0d', 'Me neither brother, me neither', '2018-11-22 19:20:39');

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
(8, 'sasenka', '::1', 0),
(9, 'alenka', '::1', 0),
(10, 'ahoj', '::1', 0),
(11, '<script>alert(\"Hello', '::1', 0),
(12, 'haha\'haha\'', '::1', 0),
(13, 'Spyro', '::1', 0);

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
('5bedb3653ae1b', '5beda0488cbcf', 'Dad me', 'images/posts/5bedb3653ae25.png', '5bedb3653ae25', 0, '2018-11-15 18:56:53'),
('5bee0412443e5', '5beda0488cbcf', 'Lolol :(', 'images/posts/5bee041244402.jpg', '5bee041244402', 0, '2018-11-16 00:41:06'),
('5bf6c4ca6b7cf', '5bf6bf8dbfd0d', 'Sneezy boi hihi \r\n&lt;script&gt;alert(\'gotcha!\');&lt;/script&gt;', 'images/posts/5bf6c4ca6b7ee.gif', '5bf6c4ca6b7ee', 0, '2018-11-22 16:01:30'),
('5bf6c78b74ecf', '5bf6bf8dbfd0d', 'Hihihi no exe file allowed', 'images/posts/5bf6c78b74eee.jpeg', '5bf6c78b74eee', 0, '2018-11-22 16:13:15'),
('5bf6e98092933', '5bf6bf8dbfd0d', 'Me at the Angular exam haha', 'images/posts/5bf6e98092965.gif', '5bf6e98092965', 0, '2018-11-22 18:38:08'),
('5bf6edd742e11', '5bf6bf8dbfd0d', 'Literally every single time', 'images/posts/5bf6edd742fef.jpg', '5bf6edd742fef', 0, '2018-11-22 18:56:39'),
('5bf6f31fb54d0', '5bf6bf8dbfd0d', 'I don\'t know what to think about this', 'images/posts/5bf6f31fb54ea.jpg', '5bf6f31fb54ea', 0, '2018-11-22 19:19:11');

-- --------------------------------------------------------

--
-- Table structure for table `security_logs`
--

CREATE TABLE `security_logs` (
  `id_security_logs` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `description` varchar(50) NOT NULL,
  `ip_address` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `user_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `time_of_attack` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `user_image_location` varchar(100) NOT NULL DEFAULT 'images/users/default.png',
  `user_image_name` varchar(50) NOT NULL DEFAULT 'default',
  `banned` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_users`, `username`, `email`, `password_hash`, `salt`, `verified`, `user_image_location`, `user_image_name`, `banned`) VALUES
('5beda0488cbcf', 'sasenka', 'sasa.labusova@gmail.com', '$2y$12$Cv7X/9eOWNoxJZuV4ts7Feh9jpBI/wZylkgVRComplkyhOCkE/4bK', '172177', 1, 'images/users/5bf5823c1763f.jpg', '5bf5823c1763f', 0),
('5bf56858bd93f', 'haha\'haha\'', 'alexandra.labusova@gmail.com', '$2y$12$HIR5iUl0oO1NK.S0HDwot.VBLTI81UU4U4ToJY9SWq3Gh1MVguqg6', '765077', 1, 'images/users/5bf5837293dfb.JPG', '5bf5837293dfb', 0),
('5bf6bf8dbfd0d', 'Spyro', 'adoantal@gmail.com', '$2y$12$aST9mVQRvLSzeAnnUUI9EuLU9cDYJooDGZAS0sUUPGQbZpYalaAyi', '149032', 1, 'images/users/default.png', 'default', 0);

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
('5bf6bf8dbfd20', '5bf6bf8dbfd0d', '5bf6bf8dbfd1b');

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
-- Indexes for table `security_logs`
--
ALTER TABLE `security_logs`
  ADD PRIMARY KEY (`id_security_logs`),
  ADD UNIQUE KEY `id_security_logs` (`id_security_logs`);

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
  MODIFY `id_logging_in` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
