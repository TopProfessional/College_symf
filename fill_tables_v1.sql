-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 17 2022 г., 13:07
-- Версия сервера: 10.3.22-MariaDB
-- Версия PHP: 7.4.5

-- SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
-- START TRANSACTION;
-- SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `college_symfony_last`
--

-- --------------------------------------------------------
INSERT INTO `user` (`id`, `email`, `roles`, `username`, `password`) VALUES
(2, 'admin@ukr.net', '[\"ROLE_ADMIN\"]', 'admin', '$argon2id$v=19$m=65536,t=4,p=1$nHHtbwvcGuZM+XJ8EJDQJA$QkTNuvJX0cKB6lqOMzP8ZkMFhgrC1D3ozE1jQdrLy+A'),
(3, 'koli@ukr.net', '[\"ROLE_TEACHER\"]', 'koli', '$argon2id$v=19$m=65536,t=4,p=1$0PHJeJ+vokZaPYswNRlYcA$Y0FVTwh5qil7vQtYWZc5dj4pbHX76GeAropiNqWE44c'),
(4, 'frank@ukr.net', '[\"ROLE_TEACHER\"]', 'Frank', '$argon2id$v=19$m=65536,t=4,p=1$qEQqHuz91BGSrfZRl0qkOg$KmLvO1/Whba1kVCvh4vZMklUjxZz/knH4mW554CYMJM'),
(13, 'Joe@ukr.net', '[\"ROLE_TEACHER\"]', 'Joe', '$argon2id$v=19$m=65536,t=4,p=1$k7X/txFUYCLqb8b+EN23hA$Ynl/8gNOESq7OkRIZuUhOebrcm5+hLpLE9JyYa1nW6E'),
(14, 'hugo1@ukr.net', '[\"ROLE_TEACHER\"]', 'Hugo', '$argon2id$v=19$m=65536,t=4,p=1$z/VMFQsqU0yPFExWK7wsSA$fPRZQdEvbVV4wm52r08p7T49n8i8WT0hzLIs7e73TIo'),
(21, 'flo@ukr.net', '[\"ROLE_STUDENT\"]', 'Flo', '$argon2id$v=19$m=65536,t=4,p=1$zmhcn0vBpGM7DW6RH0cEGw$61VUXHWdhke0t3Ouk1z5z8xFNPpGX3Ohdjyv0FoNgeE'),
(22, 'jik@ukr.net', '[\"ROLE_STUDENT\"]', 'Jik', '$argon2id$v=19$m=65536,t=4,p=1$8HmSpMErKsK1fditP9mw3A$0jTROkMyF0Ufy/maAmD+ceweur1WZZHdd+m3UdqBwo0');

-- --------------------------------------------------------
INSERT INTO `user_class` (`id`, `name`) VALUES
(1, 'A-3'),
(2, 'F-4');

-- --------------------------------------------------------
INSERT INTO `student` (`id`, `user_id`, `classes_id`, `age`, `photo`, `start_date`) VALUES
(13, 21, 1, 24, 'error-61b8d291252c6-61cc69656effc.png', '2016-01-01'),
(14, 22, 1, 34, 'main_page_photo-food16-61cdb3ec5ad0d.jpeg', '2016-01-01');

-- --------------------------------------------------------
INSERT INTO `teacher` (`id`, `user_id`, `salary`) VALUES
(1, 3, 500),
(2, 4, 300),
(4, 13, 400),
(5, 14, 200);

-- --------------------------------------------------------

INSERT INTO `course` (`id`, `name`, `description`) VALUES
(1, 'biology', 'some biology'),
(2, 'math', 'some mathematics'),
(3, 'chemistry', 'some chemistry');

-- --------------------------------------------------------
INSERT INTO `course_student` (`course_id`, `student_id`) VALUES
(2, 13),
(2, 14);

-- --------------------------------------------------------
INSERT INTO `course_teacher` (`course_id`, `teacher_id`) VALUES
(1, 1),
(2, 2),
(2, 4),
(2, 5),
(3, 1),
(3, 4);

-- --------------------------------------------------------
-- INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
-- ('DoctrineMigrations\\Version20211213081846', '2021-12-13 11:21:11', 9040),
-- ('DoctrineMigrations\\Version20211228144515', '2021-12-28 17:45:58', 1006),
-- ('DoctrineMigrations\\Version20211229135041', '2021-12-29 16:56:05', 826),
-- ('DoctrineMigrations\\Version20220103120739', '2022-01-03 15:19:25', 2675);

-- --------------------------------------------------------
INSERT INTO `mark` (`id`, `teacher_id`, `student_id`, `course_id`, `mark`, `date`) VALUES
(3, 5, 14, 1, 5, '2017-01-01');

-- --------------------------------------------------------
INSERT INTO `user_class_teacher` (`user_class_id`, `teacher_id`) VALUES
(1, 2),
(2, 4);