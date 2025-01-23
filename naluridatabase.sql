-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 23, 2025 at 09:23 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `naluridatabase`
--

-- --------------------------------------------------------

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------
CREATE TABLE `users` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `user_type` ENUM('admin','employee','patient') NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Insert data for table `users`
--
INSERT INTO `users` (`name`, `email`, `username`, `password`, `user_type`, `created_at`) VALUES
('Admin', 'admin@gmail.com', 'admin', 'admin01', 'admin', NOW()),
('Employee', 'employee@gmail.com', 'employee1', 'employee01', 'employee', NOW()),
('Patient 1', 'patient1@gmail.com', 'patient1', 'patient01', 'patient', NOW()),
('Patient 2', 'patient2@gmail.com', 'patient2', 'patient02', 'patient', NOW()),
('Patient 3', 'patient3@gmail.com', 'patient3', 'patient03', 'patient', NOW());

-- --------------------------------------------------------
-- Table structure for table `tasks`
-- --------------------------------------------------------
CREATE TABLE `tasks` (
  `task_id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `due_date` DATE NOT NULL,
  `status` ENUM('pending','completed') NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Insert data for table `tasks`
--
INSERT INTO `tasks` (`title`, `description`, `due_date`, `status`, `created_at`, `updated_at`) VALUES
('Mental Health Task 1', 'Description for task 1', '2025-12-31', 'pending', NOW(), NOW()),
('Mental Health Task 2', 'Description for task 2', '2025-12-31', 'pending', NOW(), NOW()),
('Mental Health Task 3', 'Description for task 3', '2025-12-31', 'pending', NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for table `taskanalysis`
-- --------------------------------------------------------
CREATE TABLE `taskanalysis` (
  `analysis_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `is_task_done` TINYINT(1) NOT NULL DEFAULT 0,
  `time_taken_in_hours` FLOAT NOT NULL DEFAULT 0,
  `article_watched` TINYINT(1) NOT NULL DEFAULT 0,
  `video_watched` TINYINT(1) NOT NULL DEFAULT 0,
  `books_read` TINYINT(1) NOT NULL DEFAULT 0,
  `task_id` INT(11) NOT NULL,
  PRIMARY KEY (`analysis_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`),
  FOREIGN KEY (`task_id`) REFERENCES `tasks`(`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- No data for table `taskanalysis`

-- --------------------------------------------------------
-- Table structure for table `user_tasks`
-- --------------------------------------------------------
CREATE TABLE `user_tasks` (
  `user_task_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `task_id` INT(11) NOT NULL,
  `assigned_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('pending','in_progress','completed') NOT NULL DEFAULT 'pending',
  `completed_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_task_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`),
  FOREIGN KEY (`task_id`) REFERENCES `tasks`(`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- No data for table `user_tasks`

--
-- Indexes for dumped tables
--

--
-- Indexes for table `taskanalysis`
--
ALTER TABLE `taskanalysis`
  ADD PRIMARY KEY (`analysis_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_tasks`
--
ALTER TABLE `user_tasks`
  ADD PRIMARY KEY (`user_task_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `task_id` (`task_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `taskanalysis`
--
ALTER TABLE `taskanalysis`
  MODIFY `analysis_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `user_tasks`
--
ALTER TABLE `user_tasks`
  MODIFY `user_task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `taskanalysis`
--
ALTER TABLE `taskanalysis`
  ADD CONSTRAINT `task_id` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`task_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `taskanalysis_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_tasks`
--
ALTER TABLE `user_tasks`
  ADD CONSTRAINT `user_tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_tasks_ibfk_2` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`task_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
