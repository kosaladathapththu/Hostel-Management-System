-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2024 at 06:44 AM
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
(3, 'asdfghj', '2024-11-02', '', '20024598766', 22, 'hfhmn@gmail.com', '0719148762', 2, '2024-11-02 04:32:00', 'abcd', '$2y$10$dfS8Y/SlcILfJxald81PS.1S4AYju0JZQIlZyRMryvlGHyc7Kh/RO', 'uploads/burger-4k-desktop-best-wallpaper-thumb.jpg', 'uploads/corrected use case copy.drawio.pdf');

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
(9, 18, NULL, '2024-10-27', '2024-10-28', 'approved', '2024-10-26 22:45:42');

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
  `national_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `name`, `position`, `email`, `phone`, `status`, `created_at`, `national_id`) VALUES
(1, 'A.M.Kosala Dhaneshwara Athapaththu', 'manager', 'kosalaathapaththu1234@gmail.com', '0719148762', 1, '2024-10-28 11:47:47', '200117401121');

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
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `title`, `description`, `start_date`, `end_date`) VALUES
(2, '1st event', 'celebration', '2024-10-24', '2024-10-25'),
(4, 'Birthday Celebration', 'abcd celebration', '2024-10-31', '2024-10-31'),
(5, 'Party', 'Party for girls', '2024-11-09', '2024-11-09');

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
(1, 'A.M.Kosala Dhaneshwara Athapaththu', 'kosalaathapaththu1234@gmail.com', '$2y$10$Hou9kWzVxe79qT2IbLKqSO0nWHqNcMEmJF6bcvD7Mn1KjmWQTD126', '0719148762', NULL, '2024-10-31 17:55:48');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `category` enum('food','furniture','cleaning','other') DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`item_id`, `item_name`, `category`, `quantity`, `last_updated`) VALUES
(2, 'beds', 'furniture', 5, '2024-10-29 06:28:13'),
(3, 'chairs', 'furniture', 10, '2024-10-13 20:09:28');

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
  `cover_letter` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_applications`
--

INSERT INTO `job_applications` (`application_id`, `vacancy_id`, `applicant_name`, `contact_email`, `contact_phone`, `application_date`, `cover_letter`) VALUES
(1, 1, 'abcd', 'abcd@gmail.com', '0742397719', '2024-11-01 11:50:51', 'apply');

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
(11, 4, 1, 'very nice', 5, '2024-10-26 07:43:52');

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
(13, 2, 'vegitables', 5, '2024-11-02 05:05:55', 'approved', NULL);

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
(3, 7, 5, 'good', '2024-11-02 05:04:01');

-- --------------------------------------------------------

--
-- Table structure for table `residents`
--

CREATE TABLE `residents` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `national_id` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
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

INSERT INTO `residents` (`id`, `name`, `national_id`, `age`, `email`, `phone`, `room_id`, `status`, `created_at`, `username`, `password`, `profile_picture`, `resident_form`) VALUES
(1, 'Tharushi Nikethana', '20021465896', 21, 'wsfrga@gmail.com', '0123654987', 5, 'active', '2024-10-09 20:10:50', '', '', NULL, NULL),
(3, 'rashmi', '20024598766', 20, 'jgffjkyv@gmail.com', '0123657986', 5, 'active', '2024-10-09 20:13:25', '', '', NULL, NULL),
(4, 'Tharushi Nikethana', '20054572902', 18, 'jdfcgdcgkfg@gmail.com', '0456328796', 2, 'active', '2024-10-09 20:15:43', '', '', NULL, NULL),
(8, 'ABCD', '20019864268', 23, 'abcd@gmail.com', '0700000000', 1, 'active', '2024-10-09 20:19:12', '', '', NULL, NULL),
(9, 'Nisansala sadamail', '200117802545', 22, 'nisansalasadamali23@gmail.com', '070023654987', 1, 'active', '2024-10-12 07:46:58', '', '', NULL, NULL),
(10, 'Buthmi Binara', '20032598654', 22, 'qikhhjv@gmail.com', '0707896541', 2, 'active', '2024-10-14 02:02:50', '', '', NULL, NULL),
(11, 'Sasmi Sinara', '200702314569', 17, 'sinarasasmi@gmail.com', '01478963245', 2, 'active', '2024-10-23 19:12:51', '', '', NULL, NULL),
(12, 'Nikethana tharushi', '20031478956', 21, 'jbkkvj@gmail.com', '0742397719', 2, 'active', '2024-10-23 19:53:12', 'tharushi', '$2y$10$QIjFgXim36qb8ouXsRuw4eqW2rRWvdXK0IHh4A8MjBsy6ItvdEzui', 'uploads/asian-girl-with-horse-women-s-black-sleeveless-crop-top-and-blue-denim-daisy-dukes-wallpaper-thumb.jpg', NULL),
(13, 'W.rashmi', '200245698756', 22, 'rashmi13@gmail.com', '0742397719', 1, 'active', '2024-10-24 18:09:05', 'rush123', '$2y$10$rjhkfmaOpOCD3TYVE4jmUedEFRgA6L03PPsO6SVINW2e.WD6cbUkC', 'uploads/earth-zen-garden-hd-wallpaper-thumb.jpg', NULL),
(14, 'Girl 2', '20054572902', 20, 'wff@gmail.com', '0719148762', 3, '', '2024-10-24 18:24:15', 'girl2', '$2y$10$RIGb.Sq5QWfOXgkRO3GbT.lfs9gOa1WvLvi1HqmGrxxUDXIY7x0Wq', NULL, NULL),
(15, 'qwerty', '20024598766', 21, 'qwerty@gmail.com', '01236549873', 1, '', '2024-10-24 18:47:51', 'qwerty', '$2y$10$3vcl7w1CU74E5rkjF98ew.ck3u0HZ7G95uszlrYfYA8lTfg/6gzAe', NULL, NULL),
(17, 'girl4', '20019864268', 20, 'girl4@gmail.com', '0258741369', 7, 'active', '2024-10-25 16:27:39', 'girl4', '$2y$10$ktPichk0wdqn0wFTzSnM4.HbNUyFndW8HPnmj.N8lH9BWSzySvgeO', 'uploads/unnamed.png', 'uploads/1a.pdf'),
(18, 'IM Girl', '20019864268', 23, 'girl5@gmail.com', '03579514628', 7, 'active', '2024-10-25 18:24:59', 'girl5', '$2y$10$8BQCI..o5De8VJI9IThL/u811nb7LbDFruc9Mk9Nyp35KufM40llq', 'uploads/104-cat__top_cat_wallpaper.jpg', 'uploads/20230528_104637.jpg'),
(19, 'qwertyu', '200117802545', 21, 'kosalaathapaththu1234@gmail.com', '0719148762', 2, 'active', '2024-11-02 04:34:54', 'qwertyu', '$2y$10$mgDdz6DQWP2/LanXfdMzzuM8486xT6umMfubP7ZMZ4vs106CHqmGy', 'uploads/repository-open-graph-template.png', 'uploads/corrected use case copy.drawio.pdf');

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
(7, '4', 3, 'available', '2024-10-12 07:53:44');

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
(1, 1, 100000.00, 5000.00, 12000.00, 93000.00, '2024-10-29', '2024-10-28 16:17:00');

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
(7, 'No 1 Food City', 'food', '0745689325', '2024-10-27 14:55:57', 'foodcity1', '$2y$10$Cuph3iPU4uHWDKkq16iEeekOu6HG2QlukY1QXZeKIZJrZQ4yLUQ62');

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
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`applicant_id`);

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

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
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`guest_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `vacancy_id` (`vacancy_id`);

--
-- Indexes for table `meal_feedback`
--
ALTER TABLE `meal_feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `resident_id` (`resident_id`),
  ADD KEY `meal_id` (`meal_id`);

--
-- Indexes for table `meal_plans`
--
ALTER TABLE `meal_plans`
  ADD PRIMARY KEY (`meal_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `booking_id` (`booking_id`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `applicant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_vacancies`
--
ALTER TABLE `employee_vacancies`
  MODIFY `vacancy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `guest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `meal_feedback`
--
ALTER TABLE `meal_feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `meal_plans`
--
ALTER TABLE `meal_plans`
  MODIFY `meal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `room_applications`
--
ALTER TABLE `room_applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `salary`
--
ALTER TABLE `salary`
  MODIFY `salary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`);

--
-- Constraints for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD CONSTRAINT `job_applications_ibfk_1` FOREIGN KEY (`vacancy_id`) REFERENCES `employee_vacancies` (`vacancy_id`);

--
-- Constraints for table `meal_feedback`
--
ALTER TABLE `meal_feedback`
  ADD CONSTRAINT `meal_feedback_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `meal_feedback_ibfk_2` FOREIGN KEY (`meal_id`) REFERENCES `meal_plans` (`meal_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
