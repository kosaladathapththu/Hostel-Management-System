-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2024 at 11:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hostel_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_name`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'adminname', 'adminuser', 'admin@example.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', '2024-10-28 05:32:03'),
(2, 'A.M.K.D.A', 'salarmyadmin', 'kosalaathapaththu1234@gmail.com', '$2y$10$IaBB0f.Asn4m7Uz3GJ985OlJuQy6VbGiT5uzUVuME.g.ayzjt8ji6', '2024-10-28 09:54:27');

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE `applicants` (
  `applicant_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `application_date` date DEFAULT NULL,
  `status` enum('waiting','approved','declined') DEFAULT 'waiting',
  `national_id` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `resident_form` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applicants`
--

INSERT INTO `applicants` (`applicant_id`, `name`, `application_date`, `status`, `national_id`, `age`, `email`, `phone`, `room_id`, `created_at`, `username`, `password`, `profile_picture`, `resident_form`) VALUES
(5, 'qaz', '2024-11-02', '', '20021465896', 21, 'qaz1@Gmail.com', '0719148762', 2, '2024-11-02 07:04:21', 'qaz', '$2y$10$24Izi7EIwwKJLlMxmFrIaOXkuig5CDgpyPsFdW.ATuKzrCA9k3nS2', 'uploads/oretawindowslogonew.png', 'uploads/corrected use case copy.drawio.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `log_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `resident_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `check_in_date` date DEFAULT NULL,
  `check_out_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `resident_id`, `room_id`, `check_in_date`, `check_out_date`, `status`, `created_at`) VALUES
(4, 12, NULL, '2024-10-27', '2024-10-31', 'approved', '2024-10-26 19:00:27'),
(5, 12, NULL, '2024-10-27', '2024-10-31', 'pending_approval', '2024-10-26 19:07:50'),
(6, 12, NULL, '2024-10-27', '2024-10-28', 'approved', '2024-10-26 19:31:06'),
(7, 13, NULL, '2024-10-27', '2024-10-29', 'approved', '2024-10-26 19:51:53'),
(8, 12, NULL, '2024-10-27', '2024-10-29', 'approved', '2024-10-26 20:08:13'),
(9, 18, NULL, '2024-10-27', '2024-10-28', 'approved', '2024-10-26 22:45:42'),
(10, 12, NULL, '2024-11-20', '2024-11-20', 'approved', '2024-11-20 10:42:00');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `national_id` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_password_set` tinyint(1) DEFAULT 0,
  `leave_balance` int(11) DEFAULT 20
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `name`, `position`, `email`, `phone`, `status`, `created_at`, `national_id`, `password`, `is_password_set`, `leave_balance`) VALUES
(1, 'A.M.Kosala Dhaneshwara Athapaththu', 'manager', 'kosalaathapaththu1234@gmail.com', '0719148762', 1, '2024-10-28 11:47:47', '200117401121', '$2y$10$FLWdLLJw88k7wTuvs/UApO4dxCHWq9/WbSrkAhxqRTovEzrWzSlVC', 1, 14),
(2, 'Unknown', 'Unknown Position', 'noemail@example.com', NULL, 1, '2024-11-02 10:48:43', NULL, '$2y$10$DUavm..Nb9G6GOkqdQKDjO9FwyiQ.ams9Gji5oWSkD5y22eq78pPS', 0, 20),
(3, 'qwas', 'cleaner', 'qwas@gmail.com', '0147852369', 0, '2024-11-02 06:37:12', '20024598766', '$2y$10$9FUZu5cUsSGNWw29b4DZLebba3Pl39511YeEE7bS5PaKYFGel//a2', 1, 20),
(4, 'Vinz', 'manager', 'zxcv@gmail.com', '1234567890', 0, '2024-11-02 06:47:00', '200322600116', '$2y$10$LwVv8OwSWIGogqSX05m4DOJG.nX0bREdHrkXN33zQpWmWr79MeV.y', 1, 13),
(5, 'madhumini', 'New Hire', 'maduu@gmail.com', '0345621798', 0, '2024-11-02 12:56:56', '', '$2y$10$rLq4y5gS4oSKdlMBod.0De0T4sOjwJPCyXECSypXZi9ynJ8V1r18O', 1, 19),
(6, 'rashmi p', 'New Hire', 'rush1234@gmail.com', '0719148762', 0, '2024-11-03 00:27:44', '', '$2y$10$FcyO0bwhJvae5xYUfecIk.n5i1lno39VxXKIoHHXUbYOnAYmVAfJe', 1, 17);

-- --------------------------------------------------------

--
-- Table structure for table `employee_vacancies`
--

CREATE TABLE `employee_vacancies` (
  `vacancy_id` int(11) NOT NULL,
  `job_title` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `status` enum('Open','Closed') NOT NULL DEFAULT 'Open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_vacancies`
--

INSERT INTO `employee_vacancies` (`vacancy_id`, `job_title`, `department`, `status`, `created_at`) VALUES
(1, 'Cleaniner', 'cleaning dp', 'Open', '2024-10-28 15:12:17'),
(2, 'matron', 'hostel', 'Open', '2024-10-29 17:22:27');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time NOT NULL,
  `event_place` varchar(255) NOT NULL,
  `organizer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `title`, `description`, `start_date`, `end_date`, `start_time`, `event_place`, `organizer`) VALUES
(2, '1st event', 'celebration', '2024-10-24', '2024-10-25', '00:00:00', '', ''),
(4, 'Birthday Celebration', 'abcd celebration', '2024-10-31', '2024-10-31', '00:00:00', 'auditorium', 'event orgenizer'),
(5, 'Party', 'Party for girls', '2024-11-09', '2024-11-09', '00:00:00', '', ''),
(6, 'Dansing Event', '', '2024-11-26', '2024-11-26', '00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `guest_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `national_id` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`guest_id`, `name`, `email`, `password`, `phone`, `national_id`, `created_at`) VALUES
(1, 'A.M.Kosala Dhaneshwara Athapaththu', 'kosalaathapaththu1234@gmail.com', '$2y$10$Hou9kWzVxe79qT2IbLKqSO0nWHqNcMEmJF6bcvD7Mn1KjmWQTD126', '0719148762', NULL, '2024-10-31 17:55:48'),
(2, 'rush', 'rush1234@gmail.com', '$2y$10$Q2X.6jBSb8Tc2VHdK6gdFeZGmzcEcdIAz.t1SPhAf2ApCtQ0.9yki', '0719148762', NULL, '2024-11-03 04:55:45'),
(0, 'Kosala Athapaththu', 'pabasaramadumini58@gmail.com', '$2y$10$x.86x.qDDqLdTPOXWCNM/uOc7dyqVYEToNn0nXQmpzM3vwmMdp7jS', '0719148762', NULL, '2024-11-08 09:19:09'),
(0, 'zxcv', 'zxcv@gmail.com', '$2y$10$3HwyU0g7DSP/DR3Wos1BGOBqtkvjdpuakIp6ODJb.7SmJdQmG1KvS', '0123654987', NULL, '2024-11-24 05:14:45');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `category` enum('food','furniture','cleaning','other') DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `item_price` decimal(10,2) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`item_id`, `item_name`, `category`, `quantity`, `item_price`, `last_updated`) VALUES
(2, 'beds', 'furniture', 5, 12000.00, '2024-11-26 09:19:16'),
(3, 'chairs', 'furniture', 10, 3500.00, '2024-11-26 09:19:17'),
(4, 'refrigerator', '', 1, 85000.00, '2024-11-26 09:28:31');

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `application_id` int(11) NOT NULL,
  `vacancy_id` int(11) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(15) DEFAULT NULL,
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `cover_letter` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_applications`
--

INSERT INTO `job_applications` (`application_id`, `vacancy_id`, `applicant_name`, `contact_email`, `contact_phone`, `application_date`, `cover_letter`, `status`) VALUES
(1, 1, 'abcd', 'abcd@gmail.com', '0742397719', '2024-11-01 11:50:51', 'apply', 'approved'),
(2, 1, 'qwas', 'qwas@gmail.com', '0147852369', '2024-11-02 11:05:04', 'approve me', 'approved'),
(3, 1, 'zxcv', 'zxcv@gmail.com', '1234567890', '2024-11-02 11:16:42', 'asdfg', 'approved'),
(4, 2, 'madhumini', 'maduu@gmail.com', '0345621798', '2024-11-02 17:26:25', 'please approve my job', 'approved'),
(5, 2, 'rashmi p', 'rush1234@gmail.com', '0719148762', '2024-11-03 04:57:07', 'approve me', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `leave_applications`
--

CREATE TABLE `leave_applications` (
  `application_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_applications`
--

INSERT INTO `leave_applications` (`application_id`, `employee_id`, `start_date`, `end_date`, `status`, `reason`, `created_at`) VALUES
(1, 4, '2024-11-02', '2024-11-05', 'approved', 'sick leave', '2024-11-02 17:18:04'),
(2, 4, '2024-11-02', '2024-11-05', 'approved', 'sick leave', '2024-11-02 18:09:07'),
(3, 5, '2024-11-03', '2024-11-03', 'approved', 'no reason', '2024-11-02 19:14:21'),
(4, 6, '2024-11-03', '2024-11-05', 'approved', 'asdfgh', '2024-11-03 04:58:50'),
(5, 4, '2024-11-03', '2024-11-05', 'approved', 'asdfg', '2024-11-03 10:48:25'),
(0, 1, '2024-11-25', '2024-11-30', 'approved', 'For a wedding\r\n', '2024-11-25 08:36:26');

-- --------------------------------------------------------

--
-- Table structure for table `matrons`
--

CREATE TABLE `matrons` (
  `matron_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `second_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matrons`
--

INSERT INTO `matrons` (`matron_id`, `first_name`, `second_name`, `email`, `birth_date`, `city`, `password`) VALUES
(1, 'Madhumini', 'pabasara', 'pabasaramadumini58@gmail.com', '2024-11-08', 'kurunegala', '$2y$10$U51xmaFjkozJaW2jBT3qpupeWMjUcnXuViXJtU8Vdo6xTE0BBYUfC'),
(2, 'Dulari', 'Kavindya', 'dularikavindya123@gmail.com', '2024-11-14', 'maho', '$2y$10$7rhRjg557kZ1d.TORRipi.Fl4xyB2BpVCdqlRKerfgDNTVGQCtkCW');

-- --------------------------------------------------------

--
-- Table structure for table `matron_vacancys`
--

CREATE TABLE `matron_vacancys` (
  `vacancy_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `second_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `birth_date` date NOT NULL,
  `city` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('pending','approved') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matron_vacancys`
--

INSERT INTO `matron_vacancys` (`vacancy_id`, `first_name`, `second_name`, `email`, `birth_date`, `city`, `password`, `status`, `created_at`) VALUES
(1, 'Madhumini', 'pabasara', 'pabasaramadumini58@gmail.com', '2024-11-08', 'kurunegala', '$2y$10$U51xmaFjkozJaW2jBT3qpupeWMjUcnXuViXJtU8Vdo6xTE0BBYUfC', 'approved', '2024-11-08 01:28:00'),
(2, 'Dulari', 'Kavindya', 'dularikavindya123@gmail.com', '2024-11-14', 'maho', '$2y$10$7rhRjg557kZ1d.TORRipi.Fl4xyB2BpVCdqlRKerfgDNTVGQCtkCW', 'approved', '2024-11-14 15:07:02');

-- --------------------------------------------------------

--
-- Table structure for table `meal_feedback`
--

CREATE TABLE `meal_feedback` (
  `feedback_id` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `meal_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meal_feedback`
--

INSERT INTO `meal_feedback` (`feedback_id`, `resident_id`, `meal_id`, `comment`, `rating`, `submitted_at`) VALUES
(1, 14, 1, 'good', 4, '2024-10-25 22:23:44'),
(2, 14, 1, 'good', 4, '2024-10-26 07:28:10'),
(3, 14, 1, 'good', 4, '2024-10-26 07:30:28'),
(4, 14, 1, 'good', 4, '2024-10-26 07:31:01'),
(5, 14, 1, 'good', 4, '2024-10-26 07:33:13'),
(6, 14, 1, 'good', 4, '2024-10-26 07:34:30'),
(7, 14, 1, 'good', 4, '2024-10-26 07:37:58'),
(8, 14, 1, 'good', 4, '2024-10-26 07:40:05'),
(9, 14, 1, 'good', 4, '2024-10-26 07:41:25'),
(10, 14, 1, 'good', 4, '2024-10-26 07:42:34'),
(11, 4, 1, 'very nice', 5, '2024-10-26 07:43:52'),
(0, 16, 1, 'good', 4, '2024-11-20 12:46:18'),
(0, 2, 1, 'good', 5, '2024-11-20 13:17:16');

-- --------------------------------------------------------

--
-- Table structure for table `meal_plans`
--

CREATE TABLE `meal_plans` (
  `meal_id` int(11) NOT NULL,
  `meal_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `date` date NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meal_plans`
--

INSERT INTO `meal_plans` (`meal_id`, `meal_name`, `description`, `date`, `created_by`, `created_at`) VALUES
(1, 'Chicken', 'curry chicken or Develed chicken', '2024-10-26', 'Matron', '2024-10-25 21:35:41');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('requested','approved','declined') DEFAULT 'requested',
  `approved_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `supplier_id`, `item_name`, `quantity`, `order_date`, `status`, `approved_by`) VALUES
(1, 1, 'vegitables', 2, '2024-10-12 17:18:30', 'requested', NULL),
(2, 4, 'refrigerator', 1, '2024-10-12 17:43:42', 'requested', NULL),
(5, 7, 'vegitables', 7, '2024-10-27 19:54:34', '', 'foodcity1'),
(6, 7, 'Milk Powder 1Kg packets', 10, '2024-10-27 20:01:00', '', NULL),
(7, 2, 'Tea 5kg', 2, '2024-10-27 20:13:42', '', NULL),
(8, 2, 'mixed vegitables', 2, '2024-10-27 20:31:01', '', NULL),
(9, 2, 'spoons', 20, '2024-10-27 20:44:58', 'declined', NULL),
(10, 2, 'vegitables', 2, '2024-10-27 20:46:15', '', NULL),
(11, 7, 'spoons', 20, '2024-10-27 20:50:47', 'approved', NULL),
(12, 1, 'vegtables', 5, '2024-11-02 05:05:06', 'requested', NULL),
(13, 2, 'vegitables', 5, '2024-11-02 05:05:55', 'approved', NULL),
(14, 8, 'Gass Cooker', 2, '2024-11-03 10:10:35', 'requested', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_method` enum('cash','card','bank_transfer') DEFAULT NULL,
  `status` enum('pending','completed','failed') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rating_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comments` text DEFAULT NULL,
  `rated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`rating_id`, `supplier_id`, `rating`, `comments`, `rated_at`) VALUES
(1, 1, 5, 'good service', '2024-10-12 19:09:13'),
(2, 2, 5, 'fresh foods', '2024-10-27 19:17:48'),
(3, 7, 5, 'good', '2024-11-02 05:04:01'),
(4, 8, 5, 'good', '2024-11-25 08:15:41');

-- --------------------------------------------------------

--
-- Table structure for table `residents`
--

CREATE TABLE `residents` (
  `id` int(11) NOT NULL,
  `resident_name` varchar(255) DEFAULT NULL,
  `resident_id` varchar(20) DEFAULT NULL,
  `resident_DOB` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `resident_contact` varchar(20) DEFAULT NULL,
  `resident_room_no` int(11) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `resident_form` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `residents`
--

INSERT INTO `residents` (`id`, `resident_name`, `resident_id`, `resident_DOB`, `email`, `resident_contact`, `resident_room_no`, `status`, `created_at`, `username`, `password`, `profile_picture`, `resident_form`) VALUES
(1, 'Tharushi Nikethana', '20021465896', '0000-00-00', 'wsfrga@gmail.com', '0123654987', 5, 'active', '2024-10-09 20:10:50', '', '', NULL, NULL),
(3, 'rashmi', '20024598766', '0000-00-00', 'jgffjkyv@gmail.com', '0123657986', 5, 'active', '2024-10-09 20:13:25', '', '', NULL, NULL),
(4, 'Tharushi Nikethana', '20054572902', '0000-00-00', 'jdfcgdcgkfg@gmail.com', '0456328796', 2, 'active', '2024-10-09 20:15:43', '', '', NULL, NULL),
(8, 'ABCD', '20019864268', '0000-00-00', 'abcd@gmail.com', '0700000000', 1, 'active', '2024-10-09 20:19:12', '', '', NULL, NULL),
(9, 'Nisansala sadamail', '200117802545', '0000-00-00', 'nisansalasadamali23@gmail.com', '070023654987', 1, 'active', '2024-10-12 07:46:58', '', '', NULL, NULL),
(10, 'Buthmi Binara', '20032598654', '0000-00-00', 'qikhhjv@gmail.com', '0707896541', 2, 'active', '2024-10-14 02:02:50', '', '', NULL, NULL),
(11, 'Sasmi Sinara', '200702314569', '0000-00-00', 'sinarasasmi@gmail.com', '01478963245', 2, 'active', '2024-10-23 19:12:51', '', '', NULL, NULL),
(12, 'Nikethana tharushi', '20031478956', '0000-00-00', 'jbkkvj@gmail.com', '0742397719', 2, 'active', '2024-10-23 19:53:12', 'tharushi', '$2y$10$QIjFgXim36qb8ouXsRuw4eqW2rRWvdXK0IHh4A8MjBsy6ItvdEzui', 'asian-girl-with-horse-women-s-black-sleeveless-crop-top-and-blue-denim-daisy-dukes-wallpaper-thumb.jpg', NULL),
(13, 'W.rashmi', '200245698756', '0000-00-00', 'rashmi13@gmail.com', '0742397719', 1, 'active', '2024-10-24 18:09:05', 'rush123', '$2y$10$rjhkfmaOpOCD3TYVE4jmUedEFRgA6L03PPsO6SVINW2e.WD6cbUkC', 'uploads/earth-zen-garden-hd-wallpaper-thumb.jpg', NULL),
(14, 'Girl 2', '20054572902', '2003-06-10', 'wff@gmail.com', '0719148762', 9, 'active', '2024-10-24 18:24:15', 'girl2', '$2y$10$RIGb.Sq5QWfOXgkRO3GbT.lfs9gOa1WvLvi1HqmGrxxUDXIY7x0Wq', NULL, NULL),
(15, 'qwerty', '20024598766', '0000-00-00', 'qwerty@gmail.com', '01236549873', 1, '', '2024-10-24 18:47:51', 'qwerty', '$2y$10$3vcl7w1CU74E5rkjF98ew.ck3u0HZ7G95uszlrYfYA8lTfg/6gzAe', NULL, NULL),
(17, 'girl4', '20019864268', '0000-00-00', 'girl4@gmail.com', '0258741369', 7, 'active', '2024-10-25 16:27:39', 'girl4', '$2y$10$ktPichk0wdqn0wFTzSnM4.HbNUyFndW8HPnmj.N8lH9BWSzySvgeO', 'uploads/unnamed.png', 'uploads/1a.pdf'),
(18, 'IM Girl', '20019864268', '0000-00-00', 'girl5@gmail.com', '03579514628', 7, 'active', '2024-10-25 18:24:59', 'girl5', '$2y$10$8BQCI..o5De8VJI9IThL/u811nb7LbDFruc9Mk9Nyp35KufM40llq', 'uploads/104-cat__top_cat_wallpaper.jpg', 'uploads/20230528_104637.jpg'),
(19, 'qwertyu', '200117802545', '2024-11-20', 'kosalaathapaththu1234@gmail.com', '0719148762', 2, 'active', '2024-11-02 04:34:54', 'qwertyu', '$2y$10$mgDdz6DQWP2/LanXfdMzzuM8486xT6umMfubP7ZMZ4vs106CHqmGy', 'uploads/repository-open-graph-template.png', 'uploads/corrected use case copy.drawio.pdf'),
(20, NULL, NULL, NULL, 'aqtbx@gmail.com', NULL, NULL, 'active', '2024-11-20 04:58:52', 'aqtbx', '$2y$10$Waprg3yXr8GU4qXFMxYcL.nAafZwoCcIIsieCTzrWXd80kyA2GCVW', 'uploads/WIN_20241105_15_53_31_Pro.jpg', 'uploads/G1.pdf'),
(21, 'nelee', '12345678976', '0000-00-00', 'fghjk@gmail.com', '1234567890', 7, 'active', '2024-11-20 05:32:32', 'nelee', '$2y$10$bJY7nYs8A15B3vvDKPr8f.wYBckgExQab5EkweNrnivJdB.b29Fbq', 'DALL·E 2024-11-03 11.28.22 - A clean, modern dashboard design for a web application in red and white theme, inspired by the Salvation Army branding. The layout includes a red head.webp', 'uploads/G1.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `room_number` varchar(10) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `status` enum('available','occupied','maintenance') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_number`, `capacity`, `status`, `created_at`) VALUES
(1, '1', 4, '', '2024-10-11 20:06:22'),
(2, '2', 5, 'available', '2024-10-11 20:43:26'),
(5, '3', 2, 'available', '2024-10-11 20:51:21'),
(7, '4', 3, 'available', '2024-10-12 07:53:44'),
(8, '5', 2, 'available', '2024-11-08 10:30:20'),
(9, '6', 4, 'available', '2024-11-20 14:00:27');

-- --------------------------------------------------------

--
-- Table structure for table `room_applications`
--

CREATE TABLE `room_applications` (
  `application_id` int(11) NOT NULL,
  `guest_name` varchar(50) NOT NULL,
  `guest_email` varchar(50) NOT NULL,
  `room_id` int(11) NOT NULL,
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_applications`
--

INSERT INTO `room_applications` (`application_id`, `guest_name`, `guest_email`, `room_id`, `application_date`, `status`) VALUES
(1, 'tn', 'tnd@gmail.com', 1, '2024-10-31 18:31:48', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `salary`
--

CREATE TABLE `salary` (
  `salary_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `base_salary` decimal(10,2) DEFAULT NULL,
  `allowances` decimal(10,2) DEFAULT NULL,
  `deductions` decimal(10,2) DEFAULT NULL,
  `total_salary` decimal(10,2) DEFAULT NULL,
  `salary_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salary`
--

INSERT INTO `salary` (`salary_id`, `employee_id`, `base_salary`, `allowances`, `deductions`, `total_salary`, `salary_date`, `created_at`) VALUES
(1, 1, 100000.00, 5000.00, 12000.00, 93000.00, '2024-10-29', '2024-10-28 16:17:00'),
(2, 3, 35000.00, 2000.00, 100.00, 36900.00, '2024-11-02', '2024-11-02 15:43:51'),
(3, 5, 85000.00, 5000.00, 10000.00, 80000.00, '2024-11-03', '2024-11-02 18:58:59'),
(4, 4, 30000.00, 2500.00, 1000.00, 31500.00, '2024-11-25', '2024-11-25 06:07:36');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(100) DEFAULT NULL,
  `category` enum('food','furniture','utilities','repair_services') DEFAULT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `supplier_name`, `category`, `contact`, `created_at`, `username`, `password`) VALUES
(1, 'Keels', 'food', '0700000030', '2024-10-12 08:42:22', 'supplier_1', 'hashed_password_here'),
(2, 'Cargils', 'food', '0700000023', '2024-10-12 08:45:26', 'cargils', '$2y$10$t1ginWzIv5sJ19mkb8v.OergzAHmqvHiCm.p0tUWpz5Qyp6A6qZgi'),
(3, 'TN furnitures', 'furniture', '0745689325', '2024-10-12 09:00:17', 'supplier_3', NULL),
(4, 'Rajana Electricals', 'repair_services', '0123697856', '2024-10-12 09:03:19', 'supplier_4', NULL),
(7, 'No 1 Food City', 'food', '0745689325', '2024-10-27 14:55:57', 'foodcity1', '$2y$10$Cuph3iPU4uHWDKkq16iEeekOu6HG2QlukY1QXZeKIZJrZQ4yLUQ62'),
(8, 'mnk', 'utilities', '0700000000', '2024-11-03 05:28:08', 'mnk_67274930da09a', '$2y$10$WAB5QosVpWVsjSmKc31TA.CU8o2xklocZxfv2qRb5d.mlMzb1tnji');

-- --------------------------------------------------------

--
-- Table structure for table `transactionss`
--

CREATE TABLE `transactionss` (
  `trant_id` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `trant_payment_receipt` varchar(255) DEFAULT NULL,
  `trant_payment_date` date DEFAULT NULL,
  `trant_month` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactionss`
--

INSERT INTO `transactionss` (`trant_id`, `resident_id`, `trant_payment_receipt`, `trant_payment_date`, `trant_month`, `amount`) VALUES
(1, 1, 'uploads/Screenshot (2).png', '2024-11-08', '2024-11', 14000.00),
(2, 1, 'uploads/104-cat__top_cat_wallpaper.jpg', '2024-11-08', '2024-10', 14000.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','staff') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`applicant_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `resident_id` (`resident_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `employee_vacancies`
--
ALTER TABLE `employee_vacancies`
  ADD PRIMARY KEY (`vacancy_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `matrons`
--
ALTER TABLE `matrons`
  ADD PRIMARY KEY (`matron_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `matron_vacancys`
--
ALTER TABLE `matron_vacancys`
  ADD PRIMARY KEY (`vacancy_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `residents`
--
ALTER TABLE `residents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `room_applications`
--
ALTER TABLE `room_applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `salary`
--
ALTER TABLE `salary`
  ADD PRIMARY KEY (`salary_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `transactionss`
--
ALTER TABLE `transactionss`
  ADD PRIMARY KEY (`trant_id`),
  ADD KEY `resident_id` (`resident_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `applicant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employee_vacancies`
--
ALTER TABLE `employee_vacancies`
  MODIFY `vacancy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `matrons`
--
ALTER TABLE `matrons`
  MODIFY `matron_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `matron_vacancys`
--
ALTER TABLE `matron_vacancys`
  MODIFY `vacancy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `room_applications`
--
ALTER TABLE `room_applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `salary`
--
ALTER TABLE `salary`
  MODIFY `salary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transactionss`
--
ALTER TABLE `transactionss`
  MODIFY `trant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`);

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`);

--
-- Constraints for table `room_applications`
--
ALTER TABLE `room_applications`
  ADD CONSTRAINT `room_applications_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`);

--
-- Constraints for table `salary`
--
ALTER TABLE `salary`
  ADD CONSTRAINT `salary_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE;

--
-- Constraints for table `transactionss`
--
ALTER TABLE `transactionss`
  ADD CONSTRAINT `transactionss_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
