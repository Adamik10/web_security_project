-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2018 at 02:29 AM
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
  `time_stamp` varchar(33) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(1, 'Jozo', '::1', 0);

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
  `sensitive_content` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id_posts`, `id_users`, `headline`, `image_location`, `image_name`, `sensitive_content`) VALUES
('5bd7b326e5b11', '5bd78546961a5', 'Yaaaas', 'images/posts/5bd7b326e5b27.jpg', '5bd7b326e5b27', 0);

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
  `verified` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_users`, `username`, `email`, `password_hash`, `salt`, `verified`) VALUES
('5bcf189178a52', 'Pablo', 'k0kukavica@gmail.com', '$2y$12$9.xC9LCKrcJQ93o7L0GCNuV0ZlS.Ow3rY17Xyw.gsDW20ioCoa5la', '988381', 0),
('5bcf18c93dacf', 'Pabloo', 'k0kukavica@gmail.com', '$2y$12$fH1RVkYGfX4ozfN/WPooHeJEmI4U9kTCDXcamtclwEKTXjhniZ0dq', '908566', 0),
('5bcf1b589a259', 'Pablooo', 'k0kukavica@gmail.com', '$2y$12$2z80LZfFAnpblxgmAFMtiOOwsc7wdVtCgKFWirpvmOEC2HeRh9Zt.', '724353', 1),
('5bcf3ba902651', 'Kat', 'k0kukavica@gmail.com', '$2y$12$Rfm/Y45GicHm6X6sn.lHX.DMkqYXnKI7i7/F3vbsMwdFQD5UA9GpS', '484576', 0),
('5bcf3c52daa26', 'Kata', 'k0kukavica@gmail.com', '$2y$12$aOWUEHnQsuqmOWgX4ykATe73K0NEZZF5pavKHr3xp4IhtiA.dZw4a', '479769', 0),
('5bcf3ca8247e9', 'Jozo', 'k0kukavica@gmail.com', '$2y$12$Vc8k8xUKgBcO3uFui1G4iuxLhHz2W92A1Qz7ypu74uEmNbUEoQ0e2', '279296', 0),
('5bd72addc57b4', 'Maraaaaaaaaa', 'k0kukavica@gmail.com', '$2y$12$5e9qjr960HhePVCvcaZ49ue8KFJFwtg3vIKagkB7z.swI8mdpk3rK', '642614', 1),
('5bd78546961a5', 'Adamik10', 'adoantal@gmail.com', '$2y$12$twCSxcj0wVMceASKV6ief.XdpPj.t6w54LbYjqVQEi8bEFbVYua4W', '493562', 1);

-- --------------------------------------------------------

--
-- Table structure for table `verification_codes`
--

CREATE TABLE `verification_codes` (
  `id_users` varchar(200) NOT NULL,
  `verification_code` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `verification_codes`
--

INSERT INTO `verification_codes` (`id_users`, `verification_code`) VALUES
('5bcf189178a52', '5bcf189178a5b'),
('5bcf18c93dacf', '5bcf18c93dad9'),
('5bcf1b589a259', '5bcf1b589a262'),
('5bcf3ba902651', '5bcf3ba90265a'),
('5bcf3c52daa26', '5bcf3c52daa2e'),
('5bcf3ca8247e9', '5bcf3ca8247f2'),
('5bd72addc57b4', '5bd72addc57bf'),
('5bd78546961a5', '5bd78546961af');

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
  ADD UNIQUE KEY `id_posts` (`id_posts`),
  ADD UNIQUE KEY `id_users` (`id_users`);

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
  ADD UNIQUE KEY `id_posts` (`id_posts`),
  ADD UNIQUE KEY `id_users` (`id_users`);

--
-- Indexes for table `upvotes`
--
ALTER TABLE `upvotes`
  ADD PRIMARY KEY (`id_upvotes`),
  ADD UNIQUE KEY `id_upvotes` (`id_upvotes`),
  ADD UNIQUE KEY `id_posts` (`id_posts`),
  ADD UNIQUE KEY `id_users` (`id_users`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`),
  ADD UNIQUE KEY `id_users` (`id_users`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logging_in`
--
ALTER TABLE `logging_in`
  MODIFY `id_logging_in` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
