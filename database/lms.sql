-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2026 at 10:22 AM
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
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_qualifications`
--

CREATE TABLE `academic_qualifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `registration_id` bigint(20) UNSIGNED NOT NULL,
  `level` enum('o_level','a_level','certificate','diploma','degree','other') NOT NULL,
  `institution_name` varchar(255) NOT NULL,
  `institution_address` text NOT NULL,
  `country` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `examination_board` varchar(255) DEFAULT NULL,
  `index_number` varchar(255) DEFAULT NULL,
  `grade` varchar(255) DEFAULT NULL,
  `gpa` decimal(3,2) DEFAULT NULL,
  `major` varchar(255) DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academic_qualifications`
--

INSERT INTO `academic_qualifications` (`id`, `registration_id`, `level`, `institution_name`, `institution_address`, `country`, `start_date`, `end_date`, `examination_board`, `index_number`, `grade`, `gpa`, `major`, `document_path`, `created_at`, `updated_at`) VALUES
(6, 6, 'o_level', 'Mtopepo', 'Amani', 'Tanzania', '2022-02-20', '2025-06-20', 'NECTA', 'S0288-2828-02', NULL, NULL, NULL, NULL, '2026-06-20 11:42:51', '2026-06-20 11:42:51'),
(7, 7, 'o_level', 'Mtopepo', 'zanzibar', 'Tanzania', '2025-01-01', '2026-06-01', 'NECTA', 'S0288-2828-02', NULL, NULL, 'doctor', NULL, '2026-06-20 11:58:04', '2026-06-20 11:58:04');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `method` varchar(10) NOT NULL,
  `path` varchar(255) NOT NULL,
  `status_code` smallint(5) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `method`, `path`, `status_code`, `ip_address`, `user_agent`, `meta`, `created_at`, `updated_at`) VALUES
(1, NULL, 'POST', 'api/auth/login', 200, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '{\"route\":null}', '2026-11-05 17:16:49', '2026-11-05 17:16:49'),
(2, NULL, 'POST', 'api/auth/login', 200, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '{\"route\":null}', '2026-11-05 17:17:07', '2026-11-05 17:17:07'),
(3, 18, 'POST', 'api/inventory-items', 201, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '{\"route\":null}', '2026-11-05 17:17:08', '2026-11-05 17:17:08'),
(4, 18, 'DELETE', 'api/inventory-items/5', 200, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '{\"route\":null}', '2026-11-05 17:17:08', '2026-11-05 17:17:08'),
(5, NULL, 'POST', 'api/auth/login', 200, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '{\"route\":null}', '2026-11-05 17:22:45', '2026-11-05 17:22:45'),
(6, NULL, 'POST', 'api/auth/login', 200, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '{\"route\":null}', '2026-11-05 17:33:56', '2026-11-05 17:33:56'),
(7, NULL, 'POST', 'api/auth/login', 200, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '{\"route\":null}', '2026-11-05 17:38:51', '2026-11-05 17:38:51'),
(8, NULL, 'POST', 'api/auth/login', 200, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '{\"route\":null}', '2026-11-05 19:41:25', '2026-11-05 19:41:25'),
(9, 18, 'POST', 'api/auth/logout', 200, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '{\"route\":null}', '2026-11-05 19:53:18', '2026-11-05 19:53:18'),
(10, NULL, 'POST', 'api/auth/login', 200, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '{\"route\":null}', '2026-11-05 19:53:42', '2026-11-05 19:53:42'),
(11, NULL, 'POST', 'api/auth/login', 200, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '{\"route\":null}', '2026-11-05 20:29:01', '2026-11-05 20:29:01'),
(12, 1, 'POST', 'api/auth/logout', 200, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '{\"route\":null}', '2026-11-05 20:42:07', '2026-11-05 20:42:07'),
(13, NULL, 'POST', 'api/auth/login', 200, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '{\"route\":null}', '2026-11-05 20:42:38', '2026-11-05 20:42:38'),
(14, 18, 'POST', 'api/auth/logout', 200, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '{\"route\":null}', '2026-11-05 20:43:03', '2026-11-05 20:43:03');

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fiscal_year_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `total_requested_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `total_approved_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','submitted','under_review','approved','rejected','closed') NOT NULL DEFAULT 'draft',
  `submitted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`id`, `fiscal_year_id`, `department_id`, `title`, `description`, `total_requested_amount`, `total_approved_amount`, `status`, `submitted_by`, `submitted_at`, `reviewed_by`, `reviewed_at`, `approved_by`, `approved_at`, `rejection_reason`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 6, 'Operations Department Budget', 'Sample operational budget', 1000000.00, 900000.00, 'approved', 1, '2026-11-05 14:22:02', NULL, NULL, 1, '2026-11-05 14:22:02', NULL, '2026-11-05 14:22:02', '2026-11-05 14:22:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `budget_categories`
--

CREATE TABLE `budget_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `budget_categories`
--

INSERT INTO `budget_categories` (`id`, `name`, `code`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Office Supplies', 'OFFICE', 'Stationery and supplies', '2026-11-05 14:22:02', '2026-11-05 14:22:02'),
(2, 'Equipment', 'EQUIP', 'Equipment purchases', '2026-11-05 14:22:02', '2026-11-05 14:22:02'),
(3, 'Travel', 'TRAVEL', 'Travel and transport', '2026-11-05 14:22:02', '2026-11-05 14:22:02');

-- --------------------------------------------------------

--
-- Table structure for table `budget_expenditures`
--

CREATE TABLE `budget_expenditures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `budget_line_id` bigint(20) UNSIGNED NOT NULL,
  `expenditure_date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `status` enum('recorded','verified','rejected') NOT NULL DEFAULT 'recorded',
  `recorded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `budget_expenditures`
--

INSERT INTO `budget_expenditures` (`id`, `budget_line_id`, `expenditure_date`, `description`, `amount`, `reference_number`, `payment_method`, `status`, `recorded_by`, `verified_by`, `verified_at`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-11-05', 'Purchased stationery', 100000.00, 'EXP-001', 'bank_transfer', 'verified', 1, 1, '2026-11-05 14:22:02', '2026-11-05 14:22:02', '2026-11-05 14:22:02');

-- --------------------------------------------------------

--
-- Table structure for table `budget_lines`
--

CREATE TABLE `budget_lines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `budget_id` bigint(20) UNSIGNED NOT NULL,
  `budget_category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` decimal(12,2) NOT NULL DEFAULT 1.00,
  `unit_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `requested_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `approved_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `spent_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','approved','rejected','closed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `budget_lines`
--

INSERT INTO `budget_lines` (`id`, `budget_id`, `budget_category_id`, `name`, `description`, `quantity`, `unit_cost`, `requested_amount`, `approved_amount`, `spent_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Office Stationery', 'Stationery for the year', 100.00, 5000.00, 500000.00, 450000.00, 100000.00, 'approved', '2026-11-05 14:22:02', '2026-11-05 14:22:02');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `credit_hours` int(11) NOT NULL DEFAULT 3,
  `credit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `program_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `code`, `name`, `description`, `credit_hours`, `credit_id`, `department_id`, `program_id`, `status`, `created_at`, `updated_at`, `created_by`) VALUES
(11, 'PST 04107', 'Basic Computer Application', 'This is for computer Application', 3, NULL, 7, 9, 'active', '2026-06-20 09:53:22', '2026-06-20 09:53:22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `course_offerings`
--

CREATE TABLE `course_offerings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `academic_year` varchar(255) NOT NULL,
  `semester` enum('first','second') NOT NULL,
  `instructor_id` bigint(20) UNSIGNED NOT NULL,
  `max_students` int(11) NOT NULL DEFAULT 50,
  `status` enum('active','completed','cancelled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `credits`
--

CREATE TABLE `credits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` decimal(5,1) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `head_of_department` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `code`, `name`, `head_of_department`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(6, 'CM', 'Clinical Medicine', 'Dr. Mohd Hamad', 'this is', 1, '2026-06-20 09:36:49', '2026-06-20 09:39:42'),
(7, 'PHS', 'Pharmaceutical Science', 'MR Hassan Hassan', 'This is', 1, '2026-06-20 09:38:16', '2026-06-20 09:38:16'),
(8, 'CS', 'Computer Science', 'Dr. James Wilson', NULL, 1, '2026-09-16 20:49:26', '2026-09-16 20:49:26'),
(9, 'BUS', 'Business Administration', 'Prof. Sarah Johnson', NULL, 1, '2026-09-16 20:49:26', '2026-09-16 20:49:26'),
(10, 'ENG', 'Engineering', 'Dr. Michael Brown', NULL, 1, '2026-09-16 20:49:26', '2026-09-16 20:49:26'),
(11, 'MED', 'Medicine', 'Prof. Emily Davis', NULL, 1, '2026-09-16 20:49:26', '2026-09-16 20:49:26'),
(12, 'ART', 'Arts & Humanities', 'Dr. Robert Taylor', NULL, 1, '2026-09-16 20:49:26', '2026-09-16 20:49:26');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `registration_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `mime_type` varchar(255) NOT NULL,
  `file_size` int(11) NOT NULL,
  `status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_results`
--

CREATE TABLE `exam_results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_offering_id` bigint(20) UNSIGNED NOT NULL,
  `cat1_score` decimal(5,2) DEFAULT NULL,
  `cat2_score` decimal(5,2) DEFAULT NULL,
  `assignment_score` decimal(5,2) DEFAULT NULL,
  `final_exam_score` decimal(5,2) NOT NULL,
  `total_score` decimal(5,2) NOT NULL,
  `grade` varchar(3) NOT NULL,
  `gpa_points` decimal(3,2) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `remarks` text DEFAULT NULL,
  `recorded_by` bigint(20) UNSIGNED NOT NULL,
  `submitted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('tuition','direct') NOT NULL,
  `program_id` bigint(20) UNSIGNED DEFAULT NULL,
  `applicable_semester` enum('semester_1','semester_2','both') NOT NULL DEFAULT 'both',
  `semester_1_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `semester_2_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(255) NOT NULL DEFAULT 'TZS',
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fees`
--

INSERT INTO `fees` (`id`, `name`, `type`, `program_id`, `applicable_semester`, `semester_1_amount`, `semester_2_amount`, `total_amount`, `currency`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 'Tuition fee', 'tuition', 8, 'semester_1', 1000000.00, 0.00, 1000000.00, 'TZS', 'Student must pay fee', 1, '2026-06-20 10:50:19', '2026-06-20 10:50:19'),
(5, 'Tuition fee', 'tuition', 9, 'semester_1', 1000000.00, 0.00, 1000000.00, 'TZS', 'Student must pay this fee', 1, '2026-06-20 11:10:25', '2026-06-20 11:10:25'),
(6, 'Registration Fee', 'direct', NULL, 'semester_1', 50000.00, 0.00, 50000.00, 'TZS', 'This is direct cost for registration', 1, '2026-06-20 11:20:35', '2026-06-20 11:20:35');

-- --------------------------------------------------------

--
-- Table structure for table `fiscal_years`
--

CREATE TABLE `fiscal_years` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('draft','open','closed') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fiscal_years`
--

INSERT INTO `fiscal_years` (`id`, `name`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(1, '2026/2027', '2026-07-01', '2027-06-30', 'open', '2026-11-05 14:22:02', '2026-11-05 14:22:02');

-- --------------------------------------------------------

--
-- Table structure for table `hr_documents`
--

CREATE TABLE `hr_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hr_profile_id` bigint(20) UNSIGNED NOT NULL,
  `document_type` enum('contract','id','certificate','cv','appointment_letter','other') NOT NULL DEFAULT 'other',
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `uploaded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_leave_records`
--

CREATE TABLE `hr_leave_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hr_profile_id` bigint(20) UNSIGNED NOT NULL,
  `leave_type` enum('annual','sick','maternity','paternity','study','unpaid','other') NOT NULL DEFAULT 'annual',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `days` int(11) NOT NULL,
  `status` enum('pending','approved','rejected','cancelled') NOT NULL DEFAULT 'pending',
  `reason` text DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_profiles`
--

CREATE TABLE `hr_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `employee_number` varchar(255) NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `position_title` varchar(255) NOT NULL,
  `employment_type` enum('permanent','contract','part_time','visiting','intern','other') NOT NULL DEFAULT 'permanent',
  `date_of_employment` date NOT NULL,
  `contract_start_date` date DEFAULT NULL,
  `contract_end_date` date DEFAULT NULL,
  `employment_status` enum('active','on_leave','suspended','resigned','terminated','retired') NOT NULL DEFAULT 'active',
  `qualification_summary` text DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_profiles`
--

INSERT INTO `hr_profiles` (`id`, `user_id`, `employee_number`, `department_id`, `position_title`, `employment_type`, `date_of_employment`, `contract_start_date`, `contract_end_date`, `employment_status`, `qualification_summary`, `emergency_contact_name`, `emergency_contact_phone`, `notes`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'EMP-0001', 6, 'Admin Officer', 'permanent', '2025-11-05', '2025-11-05', '2026-12-20', 'active', 'Seeded profile', 'Emergency Contact', '0700000000', NULL, 1, 1, '2026-11-05 12:29:35', '2026-11-05 12:29:35', NULL),
(2, 8, 'EMP-0002', 6, 'Instructor Officer', 'permanent', '2025-11-05', '2025-11-05', '2026-12-20', 'active', 'Seeded profile', 'Emergency Contact', '0700000000', NULL, 8, 8, '2026-11-05 12:29:35', '2026-11-05 12:29:35', NULL),
(3, 9, 'EMP-0003', 6, 'Accountant Officer', 'permanent', '2025-11-05', '2025-11-05', '2026-12-20', 'active', 'Seeded profile', 'Emergency Contact', '0700000000', NULL, 9, 9, '2026-11-05 12:29:35', '2026-11-05 12:29:35', NULL),
(4, 14, 'EMP-0004', 6, 'Instructor Officer', 'permanent', '2025-11-05', '2025-11-05', '2026-12-20', 'active', 'Seeded profile', 'Emergency Contact', '0700000000', NULL, 14, 14, '2026-11-05 12:29:35', '2026-11-05 12:29:35', NULL),
(5, 16, 'EMP-0005', 6, 'Accountant Officer', 'permanent', '2025-11-05', '2025-11-05', '2026-12-20', 'active', 'Seeded profile', 'Emergency Contact', '0700000000', NULL, 16, 16, '2026-11-05 12:29:35', '2026-11-05 12:29:35', NULL),
(6, 17, 'EMP-0006', 6, 'Admin Officer', 'permanent', '2025-11-05', '2025-11-05', '2026-12-20', 'active', 'Seeded profile', 'Emergency Contact', '0700000000', NULL, 17, 17, '2026-11-05 12:29:35', '2026-11-05 12:29:35', NULL),
(7, 18, 'EMP-0007', 6, 'Operations Officer', 'permanent', '2025-11-05', '2025-11-05', '2026-12-20', 'active', 'Seeded profile', 'Emergency Contact', '0700000000', 'seeded', 18, 18, '2026-11-05 12:29:35', '2026-11-05 12:36:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `instructor_assignments`
--

CREATE TABLE `instructor_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `instructor_id` bigint(20) UNSIGNED NOT NULL,
  `academic_year` varchar(255) NOT NULL,
  `semester` enum('semester_one','semester_two') NOT NULL,
  `assigned_by` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','completed') NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructor_assignments`
--

INSERT INTO `instructor_assignments` (`id`, `course_id`, `instructor_id`, `academic_year`, `semester`, `assigned_by`, `assigned_at`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(11, 11, 8, '2026/2027', 'semester_one', 1, '2026-06-20 09:57:55', 'active', 'Assigned from Admin Portal', '2026-06-20 09:57:55', '2026-06-20 09:57:55');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_audits`
--

CREATE TABLE `inventory_audits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `audit_date` date NOT NULL,
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `inventory_item_id` bigint(20) UNSIGNED NOT NULL,
  `expected_quantity` int(11) NOT NULL,
  `counted_quantity` int(11) NOT NULL,
  `variance` int(11) NOT NULL,
  `condition` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `conducted_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_categories`
--

CREATE TABLE `inventory_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_categories`
--

INSERT INTO `inventory_categories` (`id`, `name`, `code`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Furniture', 'FURN', 'Office and classroom furniture', 1, '2026-11-05 11:21:19', '2026-11-05 11:21:19'),
(2, 'IT Equipment', 'ITEQ', 'Computers and accessories', 1, '2026-11-05 11:21:19', '2026-11-05 11:21:19'),
(3, 'Lab Equipment', 'LAB', 'Laboratory equipment', 1, '2026-11-05 11:21:19', '2026-11-05 11:21:19');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `asset_tag` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `purchase_cost` decimal(12,2) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit` varchar(255) NOT NULL DEFAULT 'piece',
  `condition` enum('new','good','fair','poor','damaged') NOT NULL DEFAULT 'good',
  `status` enum('available','assigned','under_repair','lost','disposed') NOT NULL DEFAULT 'available',
  `custodian_id` bigint(20) UNSIGNED DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_items`
--

INSERT INTO `inventory_items` (`id`, `asset_tag`, `name`, `description`, `category_id`, `location_id`, `supplier_name`, `purchase_date`, `purchase_cost`, `quantity`, `unit`, `condition`, `status`, `custodian_id`, `serial_number`, `notes`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'ZMC-IT-001', 'Dell Desktop Computer', 'Core i5, 8GB RAM', 2, 2, NULL, NULL, NULL, 10, 'unit', 'new', 'available', NULL, 'SN-DELL-001', NULL, 1, 1, '2026-11-05 11:21:19', '2026-11-05 11:21:19', NULL),
(2, 'ZMC-FN-001', 'Office Desk', 'Wooden office desk', 1, 1, NULL, NULL, NULL, 5, 'unit', 'good', 'available', NULL, NULL, NULL, 1, 1, '2026-11-05 11:21:19', '2026-11-05 11:21:19', NULL),
(3, 'ZMC-LB-001', 'Microscope', 'Biology lab microscope', 3, 2, NULL, NULL, NULL, 3, 'unit', 'good', 'available', NULL, NULL, NULL, 1, 1, '2026-11-05 11:21:19', '2026-11-05 11:21:19', NULL),
(4, 'ZMC-TEST-001', 'Test Item', NULL, 1, 2, NULL, NULL, NULL, 5, 'piece', 'new', 'available', NULL, NULL, NULL, 18, 18, '2026-11-05 11:26:32', '2026-11-05 11:26:40', '2026-11-05 11:26:40'),
(5, 'ZMC-AUDIT-001', 'Audit Test', NULL, 1, 1, NULL, NULL, NULL, 1, 'piece', 'new', 'available', NULL, NULL, NULL, 18, 18, '2026-11-05 17:17:08', '2026-11-05 17:17:08', '2026-11-05 17:17:08');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_locations`
--

CREATE TABLE `inventory_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `building` varchar(255) DEFAULT NULL,
  `room` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_locations`
--

INSERT INTO `inventory_locations` (`id`, `name`, `code`, `building`, `room`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Main Store', 'STORE', 'Administration Block', 'G01', NULL, 1, '2026-11-05 11:21:19', '2026-11-05 11:21:19'),
(2, 'Computer Lab', 'LAB1', 'Science Block', '101', NULL, 1, '2026-11-05 11:21:19', '2026-11-05 11:21:19'),
(3, 'Library', 'LIB', 'Library Block', 'L01', NULL, 1, '2026-11-05 11:21:19', '2026-11-05 11:21:19');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transactions`
--

CREATE TABLE `inventory_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inventory_item_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_type` enum('received','assigned','transferred','returned','repair','lost','disposed','audit_adjustment') NOT NULL,
  `from_location_id` bigint(20) UNSIGNED DEFAULT NULL,
  `to_location_id` bigint(20) UNSIGNED DEFAULT NULL,
  `from_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `to_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `condition` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `recorded_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_transactions`
--

INSERT INTO `inventory_transactions` (`id`, `inventory_item_id`, `transaction_type`, `from_location_id`, `to_location_id`, `from_user_id`, `to_user_id`, `quantity`, `condition`, `remarks`, `transaction_date`, `recorded_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'received', NULL, 2, NULL, NULL, 10, 'new', 'Seed data receipt', '2026-11-05 11:21:19', 1, '2026-11-05 11:21:19', '2026-11-05 11:21:19'),
(2, 2, 'received', NULL, 1, NULL, NULL, 5, 'good', 'Seed data receipt', '2026-11-05 11:21:19', 1, '2026-11-05 11:21:19', '2026-11-05 11:21:19'),
(3, 3, 'received', NULL, 2, NULL, NULL, 3, 'good', 'Seed data receipt', '2026-11-05 11:21:19', 1, '2026-11-05 11:21:19', '2026-11-05 11:21:19'),
(4, 4, 'received', NULL, 1, NULL, NULL, 2, 'new', 'Initial receipt', '2026-11-05 11:26:32', 18, '2026-11-05 11:26:32', '2026-11-05 11:26:32'),
(5, 4, 'received', 1, 2, NULL, NULL, 3, 'new', 'received more', '2026-11-05 11:26:33', 18, '2026-11-05 11:26:33', '2026-11-05 11:26:33'),
(6, 5, 'received', NULL, 1, NULL, NULL, 1, 'new', 'Initial receipt', '2026-11-05 17:17:08', 18, '2026-11-05 17:17:08', '2026-11-05 17:17:08');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(3, '2024_01_15_000001_create_departments_table', 1),
(4, '2024_01_15_000002_create_programs_table', 1),
(5, '2024_01_15_000003_create_registrations_table', 1),
(6, '2024_01_15_000004_create_academic_qualifications_table', 1),
(7, '2024_01_15_000005_create_payments_table', 1),
(8, '2024_01_15_000006_create_documents_table', 1),
(9, '2024_01_15_000007_create_courses_table', 1),
(10, '2024_01_15_000008_create_course_offerings_table', 1),
(11, '2024_01_15_000009_create_exam_results_table', 1),
(12, '2024_04_29_000001_update_users_table_add_accountant_role', 1),
(13, '2024_04_29_000002_add_created_by_to_courses_table', 1),
(14, '2024_04_29_000003_update_payments_table_add_accountant_fields', 1),
(15, '2024_04_29_000004_create_instructor_assignments_table', 1),
(16, '2026_04_30_141041_create_credits_table', 1),
(17, '2026_04_30_150620_modify_credits_value_to_decimal', 1),
(18, '2026_05_28_220714_add_fees_to_programs_table', 1),
(19, '2026_05_28_224008_create_fees_table', 1),
(20, '2026_05_29_010010_add_program_id_to_courses_table', 1),
(21, '2026_09_17_000001_update_exam_results_status_workflow', 2),
(22, '2026_09_17_000002_create_roles_table', 2),
(23, '2026_09_17_000003_create_permissions_table', 2),
(24, '2026_09_17_000004_create_role_permissions_table', 2),
(25, '2026_09_17_000005_create_role_user_table', 2),
(26, '2026_09_21_231809_create_queue_tables', 3),
(27, '2026_09_23_000001_add_performance_indexes', 3),
(28, '2026_09_23_000002_update_users_table_add_operations_role', 4),
(29, '2026_09_24_000001_create_inventory_categories_table', 5),
(30, '2026_09_24_000002_create_inventory_locations_table', 5),
(31, '2026_09_24_000003_create_inventory_items_table', 5),
(32, '2026_09_24_000004_create_inventory_transactions_table', 5),
(33, '2026_09_24_000005_create_inventory_audits_table', 5),
(34, '2026_09_25_000001_create_hr_profiles_table', 6),
(35, '2026_09_25_000002_create_hr_documents_table', 6),
(36, '2026_09_25_000003_create_hr_leave_records_table', 6),
(37, '2026_09_26_000001_create_salary_grades_table', 7),
(38, '2026_09_26_000002_create_salary_steps_table', 7),
(39, '2026_09_26_000003_create_salary_allowances_table', 7),
(40, '2026_09_26_000004_create_staff_salary_placements_table', 7),
(41, '2026_09_27_000001_create_fiscal_years_table', 8),
(42, '2026_09_27_000002_create_budget_categories_table', 8),
(43, '2026_09_27_000003_create_budgets_table', 8),
(44, '2026_09_27_000004_create_budget_lines_table', 8),
(45, '2026_09_27_000005_create_budget_expenditures_table', 8),
(46, '2026_09_28_000001_create_proposals_table', 9),
(47, '2026_09_28_000002_create_proposal_activities_table', 9),
(48, '2026_09_28_000003_create_proposal_kpis_table', 9),
(49, '2026_09_28_000004_create_proposal_attachments_table', 9),
(50, '2026_09_29_000001_create_audit_logs_table', 10);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `registration_id` bigint(20) UNSIGNED NOT NULL,
  `fee_type` enum('registration_fee','tuition_fee','library_fee','laboratory_fee','examination_fee','hostel_fee','other') NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'KES',
  `control_number` varchar(255) NOT NULL,
  `method` enum('mpesa','card','bank_transfer','cash') NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `status` enum('pending','completed','failed') NOT NULL DEFAULT 'pending',
  `failure_reason` text DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `student_id` bigint(20) UNSIGNED DEFAULT NULL,
  `processed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `registration_id`, `fee_type`, `description`, `amount`, `currency`, `control_number`, `method`, `transaction_id`, `status`, `failure_reason`, `paid_at`, `created_at`, `updated_at`, `student_id`, `processed_by`, `notes`) VALUES
(10, 6, 'tuition_fee', 'Tuition fee (Semester 1)', 1000000.00, 'TZS', 'CN2026945106', 'bank_transfer', NULL, 'pending', NULL, NULL, '2026-06-20 11:42:51', '2026-06-20 11:42:51', 11, NULL, NULL),
(11, 6, 'registration_fee', 'Registration Fee (Semester 1)', 50000.00, 'TZS', 'CN2026594643', 'bank_transfer', NULL, 'pending', NULL, NULL, '2026-06-20 11:42:51', '2026-06-20 11:42:51', 11, NULL, NULL),
(12, 7, 'tuition_fee', 'Tuition fee (Semester 1)', 1000000.00, 'TZS', 'CN2026765958', 'bank_transfer', NULL, 'pending', NULL, NULL, '2026-06-20 11:58:04', '2026-06-20 11:58:04', 12, NULL, NULL),
(13, 7, 'registration_fee', 'Registration Fee (Semester 1)', 50000.00, 'TZS', 'CN2026907342', 'bank_transfer', NULL, 'completed', NULL, '2026-09-22 09:04:24', '2026-06-20 11:58:04', '2026-09-22 09:04:24', 12, 16, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `hint` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `code`, `module`, `action`, `label`, `hint`, `created_at`, `updated_at`) VALUES
(1, 'dashboard.view', 'dashboard', 'view', 'View dashboard', 'Dashboard — View dashboard', '2026-09-16 20:47:18', '2026-09-16 23:48:48'),
(2, 'students.view', 'students', 'view', 'View students', 'Students — View students', '2026-09-16 20:47:18', '2026-09-16 23:48:48'),
(3, 'students.create', 'students', 'create', 'Add student', 'Students — Add student', '2026-09-16 20:47:18', '2026-09-16 23:48:48'),
(4, 'students.update', 'students', 'update', 'Edit student', 'Students — Edit student', '2026-09-16 20:47:18', '2026-09-16 23:48:48'),
(5, 'students.delete', 'students', 'delete', 'Delete student', 'Students — Delete student', '2026-09-16 20:47:18', '2026-09-16 23:48:48'),
(6, 'students.reset_password', 'students', 'reset_password', 'Reset student password', 'Students — Reset student password', '2026-09-16 20:47:18', '2026-09-16 23:48:48'),
(7, 'staff.view', 'staff', 'view', 'View staff', 'Staff — View staff', '2026-09-16 20:47:18', '2026-09-16 23:48:48'),
(8, 'staff.create', 'staff', 'create', 'Add staff', 'Staff — Add staff', '2026-09-16 20:47:18', '2026-09-16 23:48:48'),
(9, 'staff.update', 'staff', 'update', 'Edit staff', 'Staff — Edit staff', '2026-09-16 20:47:18', '2026-09-16 23:48:48'),
(10, 'staff.delete', 'staff', 'delete', 'Delete staff', 'Staff — Delete staff', '2026-09-16 20:47:18', '2026-09-16 23:48:48'),
(11, 'programs.view', 'programs', 'view', 'View programs', 'Programs & Departments — View programs', '2026-09-16 20:47:18', '2026-09-16 23:48:48'),
(12, 'programs.create', 'programs', 'create', 'Add program/department', 'Programs & Departments — Add program/department', '2026-09-16 20:47:18', '2026-09-16 23:48:48'),
(13, 'programs.update', 'programs', 'update', 'Edit program/department', 'Programs & Departments — Edit program/department', '2026-09-16 20:47:18', '2026-09-16 23:48:48'),
(14, 'programs.delete', 'programs', 'delete', 'Delete program/department', 'Programs & Departments — Delete program/department', '2026-09-16 20:47:18', '2026-09-16 23:48:48'),
(15, 'courses.view', 'courses', 'view', 'View courses', 'Courses — View courses', '2026-09-16 20:47:18', '2026-09-16 23:48:48'),
(16, 'courses.create', 'courses', 'create', 'Add course', 'Courses — Add course', '2026-09-16 20:47:18', '2026-09-16 23:48:48'),
(17, 'courses.update', 'courses', 'update', 'Edit course', 'Courses — Edit course', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(18, 'courses.delete', 'courses', 'delete', 'Delete course', 'Courses — Delete course', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(19, 'courses.assign_instructor', 'courses', 'assign_instructor', 'Assign instructors', 'Courses — Assign instructors', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(20, 'registrations.view', 'registrations', 'view', 'View registrations', 'Registrations — View registrations', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(21, 'registrations.create', 'registrations', 'create', 'Create registration', 'Registrations — Create registration', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(22, 'registrations.update', 'registrations', 'update', 'Edit registration', 'Registrations — Edit registration', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(23, 'registrations.delete', 'registrations', 'delete', 'Delete registration', 'Registrations — Delete registration', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(24, 'registrations.approve', 'registrations', 'approve', 'Approve registration', 'Registrations — Approve registration', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(25, 'registrations.reject', 'registrations', 'reject', 'Reject registration', 'Registrations — Reject registration', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(26, 'results.view', 'results', 'view', 'View results', 'Exam Results — View results', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(27, 'results.create', 'results', 'create', 'Create result', 'Exam Results — Create result', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(28, 'results.update', 'results', 'update', 'Edit result', 'Exam Results — Edit result', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(29, 'results.delete', 'results', 'delete', 'Delete result', 'Exam Results — Delete result', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(30, 'results.publish', 'results', 'publish', 'Publish results', 'Exam Results — Publish results', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(31, 'results.reject', 'results', 'reject', 'Reject results', 'Exam Results — Reject results', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(32, 'payments.view', 'payments', 'view', 'View payments', 'Payments — View payments', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(33, 'payments.create', 'payments', 'create', 'Record payment', 'Payments — Record payment', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(34, 'payments.verify', 'payments', 'verify', 'Verify cash payment', 'Payments — Verify cash payment', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(35, 'reports.view', 'reports', 'view', 'View reports', 'Reports — View reports', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(36, 'reports.export', 'reports', 'export', 'Export reports', 'Reports — Export reports', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(37, 'settings.view', 'settings', 'view', 'View settings', 'Settings (Fees & Credits) — View settings', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(38, 'settings.update', 'settings', 'update', 'Update settings', 'Settings (Fees & Credits) — Update settings', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(39, 'roles.view', 'roles', 'view', 'View roles', 'Roles & Permissions — View roles', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(40, 'roles.create', 'roles', 'create', 'Create role', 'Roles & Permissions — Create role', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(41, 'roles.update', 'roles', 'update', 'Update role permissions', 'Roles & Permissions — Update role permissions', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(42, 'roles.delete', 'roles', 'delete', 'Delete role', 'Roles & Permissions — Delete role', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(43, 'roles.assign', 'roles', 'assign', 'Assign roles to users', 'Roles & Permissions — Assign roles to users', '2026-09-16 20:47:18', '2026-09-16 23:48:49'),
(44, 'reports.operations.view', 'reports', 'operations.view', 'View operations reports', 'Reports — View operations reports', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(45, 'reports.financial.view', 'reports', 'financial.view', 'View financial reports', 'Reports — View financial reports', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(46, 'inventory.view', 'inventory', 'view', 'View inventory', 'Inventory — View inventory', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(47, 'inventory.create', 'inventory', 'create', 'Create inventory item', 'Inventory — Create inventory item', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(48, 'inventory.update', 'inventory', 'update', 'Update inventory item', 'Inventory — Update inventory item', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(49, 'inventory.delete', 'inventory', 'delete', 'Delete inventory item', 'Inventory — Delete inventory item', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(50, 'inventory.receive', 'inventory', 'receive', 'Receive inventory', 'Inventory — Receive inventory', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(51, 'inventory.assign', 'inventory', 'assign', 'Assign inventory', 'Inventory — Assign inventory', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(52, 'inventory.transfer', 'inventory', 'transfer', 'Transfer inventory', 'Inventory — Transfer inventory', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(53, 'inventory.return', 'inventory', 'return', 'Return inventory', 'Inventory — Return inventory', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(54, 'inventory.dispose', 'inventory', 'dispose', 'Dispose inventory', 'Inventory — Dispose inventory', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(55, 'inventory.audit', 'inventory', 'audit', 'Audit inventory', 'Inventory — Audit inventory', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(56, 'inventory.export', 'inventory', 'export', 'Export inventory', 'Inventory — Export inventory', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(57, 'hr.view', 'hr', 'view', 'View HR records', 'Human Resources — View HR records', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(58, 'hr.create', 'hr', 'create', 'Create HR record', 'Human Resources — Create HR record', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(59, 'hr.update', 'hr', 'update', 'Update HR record', 'Human Resources — Update HR record', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(60, 'hr.delete', 'hr', 'delete', 'Delete HR record', 'Human Resources — Delete HR record', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(61, 'hr.documents.manage', 'hr', 'documents.manage', 'Manage HR documents', 'Human Resources — Manage HR documents', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(62, 'hr.leave.manage', 'hr', 'leave.manage', 'Manage leave', 'Human Resources — Manage leave', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(63, 'hr.status.manage', 'hr', 'status.manage', 'Manage employment status', 'Human Resources — Manage employment status', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(64, 'hr.export', 'hr', 'export', 'Export HR data', 'Human Resources — Export HR data', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(65, 'salary_scales.view', 'salary_scales', 'view', 'View salary scales', 'Salary Scales — View salary scales', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(66, 'salary_scales.create', 'salary_scales', 'create', 'Create salary scale', 'Salary Scales — Create salary scale', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(67, 'salary_scales.update', 'salary_scales', 'update', 'Update salary scale', 'Salary Scales — Update salary scale', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(68, 'salary_scales.delete', 'salary_scales', 'delete', 'Delete salary scale', 'Salary Scales — Delete salary scale', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(69, 'salary_scales.assign', 'salary_scales', 'assign', 'Assign salary scale', 'Salary Scales — Assign salary scale', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(70, 'salary_scales.export', 'salary_scales', 'export', 'Export salary scales', 'Salary Scales — Export salary scales', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(71, 'budgets.view', 'budgets', 'view', 'View budgets', 'Budgets — View budgets', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(72, 'budgets.create', 'budgets', 'create', 'Create budget', 'Budgets — Create budget', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(73, 'budgets.update', 'budgets', 'update', 'Update budget', 'Budgets — Update budget', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(74, 'budgets.submit', 'budgets', 'submit', 'Submit budget', 'Budgets — Submit budget', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(75, 'budgets.review', 'budgets', 'review', 'Review budget', 'Budgets — Review budget', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(76, 'budgets.approve', 'budgets', 'approve', 'Approve budget', 'Budgets — Approve budget', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(77, 'budgets.reject', 'budgets', 'reject', 'Reject budget', 'Budgets — Reject budget', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(78, 'budgets.close', 'budgets', 'close', 'Close budget', 'Budgets — Close budget', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(79, 'budgets.export', 'budgets', 'export', 'Export budgets', 'Budgets — Export budgets', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(80, 'proposals.view', 'proposals', 'view', 'View proposals', 'Proposals & Plans — View proposals', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(81, 'proposals.create', 'proposals', 'create', 'Create proposal', 'Proposals & Plans — Create proposal', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(82, 'proposals.update', 'proposals', 'update', 'Update proposal', 'Proposals & Plans — Update proposal', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(83, 'proposals.submit', 'proposals', 'submit', 'Submit proposal', 'Proposals & Plans — Submit proposal', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(84, 'proposals.review', 'proposals', 'review', 'Review proposal', 'Proposals & Plans — Review proposal', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(85, 'proposals.approve', 'proposals', 'approve', 'Approve proposal', 'Proposals & Plans — Approve proposal', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(86, 'proposals.reject', 'proposals', 'reject', 'Reject proposal', 'Proposals & Plans — Reject proposal', '2026-11-05 11:08:35', '2026-11-05 11:08:35'),
(87, 'proposals.publish', 'proposals', 'publish', 'Publish proposal', 'Proposals & Plans — Publish proposal', '2026-11-05 11:08:36', '2026-11-05 11:08:36'),
(88, 'proposals.export', 'proposals', 'export', 'Export proposals', 'Proposals & Plans — Export proposals', '2026-11-05 11:08:36', '2026-11-05 11:08:36');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(3, 'App\\Models\\User', 1, 'auth_token', 'bb460eed05efc51c9fc8f0b5671a54717d9705af5149a194f8cf706c388abe36', '[\"*\"]', '2026-05-30 03:22:28', NULL, '2026-05-30 02:39:45', '2026-05-30 03:22:28'),
(8, 'App\\Models\\User', 4, 'auth_token', '3f28c84f0b1fba59311753a9146f57fd60a7e98515423df317ad1d5026201e9e', '[\"*\"]', '2026-05-30 04:25:43', NULL, '2026-05-30 03:47:30', '2026-05-30 04:25:43'),
(12, 'App\\Models\\User', 5, 'auth_token', '9672c8b399b5b19cc86e160fa1d7fdd25577b8165abb725a8f5eebe6f1a27153', '[\"*\"]', NULL, NULL, '2026-05-30 05:04:11', '2026-05-30 05:04:11'),
(17, 'App\\Models\\User', 4, 'auth_token', '98f482eab42aefa875b115cdddbb0f827e51238d378eb9d3839a37a9cc1cc158', '[\"*\"]', '2026-05-30 05:33:40', NULL, '2026-05-30 05:33:39', '2026-05-30 05:33:40'),
(37, 'App\\Models\\User', 3, 'auth_token', '8f5129578edacbfaff46ac95abf349a895178844649b190b8743ac79df4f1b10', '[\"*\"]', '2026-05-31 03:09:11', NULL, '2026-05-30 17:57:58', '2026-05-31 03:09:11'),
(56, 'App\\Models\\User', 6, 'auth_token', 'aa3358d66b6ca25c0d5b37795b692f0992e2d55f0a61fa09b0242544baad1c30', '[\"*\"]', NULL, NULL, '2026-05-31 05:39:43', '2026-05-31 05:39:43'),
(70, 'App\\Models\\User', 1, 'auth_token', '78f1e1ac09a96c17581db5e1f0f6fb92a47cda41b4cf39cba64aed2b7477375e', '[\"*\"]', '2026-06-01 01:44:11', NULL, '2026-05-31 08:45:33', '2026-06-01 01:44:11'),
(75, 'App\\Models\\User', 2, 'auth_token', '7c79a9976373e18474524f7fdb81d9556c63332e93ab0aaa027dd5a8096dc877', '[\"*\"]', '2026-06-03 03:29:22', NULL, '2026-06-01 04:34:06', '2026-06-03 03:29:22'),
(79, 'App\\Models\\User', 1, 'auth_token', '5868d09602e3923ec11d84aa78c9cca3663d0f845b10b587e7e8ab548694583f', '[\"*\"]', '2026-06-03 14:50:47', NULL, '2026-06-03 06:04:48', '2026-06-03 14:50:47'),
(81, 'App\\Models\\User', 3, 'auth_token', 'a98e3fefcb87acfe1074993c5825767f311402ff4dc3090a7d765823d59c153b', '[\"*\"]', '2026-06-05 00:58:13', NULL, '2026-06-03 14:55:02', '2026-06-05 00:58:13'),
(87, 'App\\Models\\User', 1, 'auth_token', '756ca39fe0692fd7ba6c63ee9842b1d7b967a3f3d5a1fa4ef543a34cc3c89bdb', '[\"*\"]', '2026-06-05 03:55:49', NULL, '2026-06-05 03:34:32', '2026-06-05 03:55:49'),
(90, 'App\\Models\\User', 2, 'auth_token', '2c6e796180cb09a97b64fbb13927fea94ce8aff83522133963360589512d6397', '[\"*\"]', '2026-06-06 01:15:30', NULL, '2026-06-05 04:30:22', '2026-06-06 01:15:30'),
(93, 'App\\Models\\User', 1, 'auth_token', 'fff39787a600798b23f962fb78fa15183506263cb3964aa274a44cc25a5a1340', '[\"*\"]', '2026-06-07 03:44:53', NULL, '2026-06-06 01:20:20', '2026-06-07 03:44:53'),
(95, 'App\\Models\\User', 4, 'auth_token', '61e102ed6ca0c2fb416292aff66b1456534cd4ef4f549ff251d4edb14d406193', '[\"*\"]', '2026-06-08 02:20:49', NULL, '2026-06-07 04:35:55', '2026-06-08 02:20:49'),
(96, 'App\\Models\\User', 4, 'auth_token', '76b0783e7cfb19a6a619618204c5c048039608fb4891fc905f77986c541da731', '[\"*\"]', '2026-06-08 02:52:49', NULL, '2026-06-08 02:20:53', '2026-06-08 02:52:49'),
(97, 'App\\Models\\User', 4, 'auth_token', '27f16ea0852d1adb47256b96a9ef6ee71168bed78bd8ab498010120283cd65a4', '[\"*\"]', NULL, NULL, '2026-06-08 02:52:30', '2026-06-08 02:52:30'),
(104, 'App\\Models\\User', 1, 'auth_token', '17ae997d0f9c546eb5f16e2c1e5ff7be0a0afa2cccb55b133aa3948afb30a9b4', '[\"*\"]', '2026-06-08 06:15:29', NULL, '2026-06-08 06:15:14', '2026-06-08 06:15:29'),
(129, 'App\\Models\\User', 1, 'auth_token', '362f9f78dff079c53ae1abefd872eda20b554cc13cba5001852e372f07f2078b', '[\"*\"]', '2026-06-11 03:08:24', NULL, '2026-06-10 18:50:28', '2026-06-11 03:08:24'),
(135, 'App\\Models\\User', 2, 'auth_token', '1c9458ee8aa3739da8ad9a5d587a6b359346be89c11d48872923659071bf50c6', '[\"*\"]', '2026-06-11 04:29:01', NULL, '2026-06-11 03:35:14', '2026-06-11 04:29:01'),
(144, 'App\\Models\\User', 1, 'auth_token', '334f34f4d1d8efcc5da3a209eb07c58b05c334d9bd66151c3cc2eaaa07fff434', '[\"*\"]', '2026-06-18 11:10:42', NULL, '2026-06-11 08:55:22', '2026-06-18 11:10:42'),
(147, 'App\\Models\\User', 7, 'auth_token', '65580413ad6f4f4e4887be45e0f48de28c734d6663fa94454bc710bf350ce9f0', '[\"*\"]', NULL, NULL, '2026-06-18 11:15:32', '2026-06-18 11:15:32'),
(163, 'App\\Models\\User', 2, 'auth_token', 'c12a88ce35ac04df133b031d7637208206e96116f65cca890c403ac61a0ff9d2', '[\"*\"]', '2026-06-20 06:37:24', NULL, '2026-06-20 06:33:07', '2026-06-20 06:37:24'),
(181, 'App\\Models\\User', 10, 'auth_token', '77b0974e8628116f77d4e058ce573ed3b2f038bda9ddbaec0ebda09b24039844', '[\"*\"]', NULL, NULL, '2026-06-20 10:54:38', '2026-06-20 10:54:38'),
(191, 'App\\Models\\User', 11, 'auth_token', 'ac2d517c944b0e7cefc90f05973e7a5a09858313ae0f599f79ae4774429c5480', '[\"*\"]', NULL, NULL, '2026-06-20 11:38:32', '2026-06-20 11:38:32'),
(195, 'App\\Models\\User', 12, 'auth_token', 'c3eb75d9ef9674dfab053bd2a9bd93bf4dabfcd0070babf9bb29c3c11555c841', '[\"*\"]', NULL, NULL, '2026-06-20 11:54:21', '2026-06-20 11:54:21'),
(197, 'App\\Models\\User', 8, 'auth_token', 'd0542ed446921a0e847d1e477aab4b71b0b3b2de894d7b249509a7827ed22836', '[\"*\"]', '2026-06-20 12:02:36', NULL, '2026-06-20 11:58:35', '2026-06-20 12:02:36'),
(199, 'App\\Models\\User', 1, 'auth_token', '65db80c235875f5567d4e03472cb2d0914af481a35b539ca861f157511e7f0d7', '[\"*\"]', '2026-06-21 04:32:08', NULL, '2026-06-21 04:16:50', '2026-06-21 04:32:08'),
(201, 'App\\Models\\User', 1, 'auth_token', 'a2d076457230b0bb96fe0a23cc7449abf207581eee84cd6a445fd22fe86b46d8', '[\"*\"]', '2026-06-24 07:52:28', NULL, '2026-06-21 11:26:09', '2026-06-24 07:52:28'),
(205, 'App\\Models\\User', 12, 'auth_token', '76dee42fff5ea08106f02781568befcb700a03f065f92dea45c42518e72713d5', '[\"*\"]', '2026-06-24 08:36:23', NULL, '2026-06-24 08:32:16', '2026-06-24 08:36:23'),
(209, 'App\\Models\\User', 1, 'auth_token', '249418e7f555df57d72ca60edb515024b01897d85b83455a4a71b8392ae91494', '[\"*\"]', '2026-06-24 08:48:23', NULL, '2026-06-24 08:46:48', '2026-06-24 08:48:23'),
(210, 'App\\Models\\User', 1, 'auth_token', 'bf55f728089236ae66b352819c5e8c6978711b86721717729f85d92454bfe09e', '[\"*\"]', '2026-09-16 20:50:26', NULL, '2026-09-16 20:50:25', '2026-09-16 20:50:26'),
(211, 'App\\Models\\User', 14, 'auth_token', '9139b7094cadb086fb9f2f218efd43c0e30ddc73d87831936383ca74c1d3bb6e', '[\"*\"]', '2026-09-16 20:50:37', NULL, '2026-09-16 20:50:33', '2026-09-16 20:50:37'),
(212, 'App\\Models\\User', 1, 'auth_token', '8e32545dcdaeb1505b263a9fb851814080ee79ac8d7e9ace4539bff196856495', '[\"*\"]', '2026-09-16 23:49:15', NULL, '2026-09-16 23:49:09', '2026-09-16 23:49:15'),
(213, 'App\\Models\\User', 14, 'auth_token', 'b04b33662f60ec611e306a46c108f61c2e15587577901b24b71b155125ec7f45', '[\"*\"]', '2026-09-16 23:49:15', NULL, '2026-09-16 23:49:10', '2026-09-16 23:49:15'),
(214, 'App\\Models\\User', 16, 'auth_token', '16ff2953ddecdaaa33703c28adf8543cc79af572621b078957779efa1112aa8d', '[\"*\"]', '2026-09-16 23:49:14', NULL, '2026-09-16 23:49:10', '2026-09-16 23:49:14'),
(215, 'App\\Models\\User', 15, 'auth_token', '84802ed3e2b8b2c364b189ac81f8988bf375452242113fc8d3b172de75c9bd66', '[\"*\"]', '2026-09-16 23:49:16', NULL, '2026-09-16 23:49:11', '2026-09-16 23:49:16'),
(216, 'App\\Models\\User', 1, 'auth_token', 'c0b677488eae5dcca7dea125bbc0a2fdc21ba4fbbac7ec166f748e4e8046c718', '[\"*\"]', '2026-09-21 20:24:39', NULL, '2026-09-21 20:24:34', '2026-09-21 20:24:39'),
(217, 'App\\Models\\User', 14, 'auth_token', 'd059a8ca557158db19bac13f6053e0821125f30e8bd0fc0f53bcb6107f6ac08b', '[\"*\"]', '2026-09-21 20:24:40', NULL, '2026-09-21 20:24:36', '2026-09-21 20:24:40'),
(218, 'App\\Models\\User', 16, 'auth_token', 'b65d5ed08f08fdd128925d1e57a06bdb24a86e6155bd2be10f0ff2c29ec83e81', '[\"*\"]', NULL, NULL, '2026-09-21 20:24:37', '2026-09-21 20:24:37'),
(219, 'App\\Models\\User', 15, 'auth_token', '275e1383a1713ff700523ef2751c380323026ca6922514dece38be77e1e2c533', '[\"*\"]', '2026-09-21 20:24:40', NULL, '2026-09-21 20:24:37', '2026-09-21 20:24:40'),
(220, 'App\\Models\\User', 1, 'auth_token', '70aa1ec69659e26ceec4d37da796439a037f7467acb6dea7a0031cf2d3ab78fd', '[\"*\"]', NULL, NULL, '2026-09-21 20:24:55', '2026-09-21 20:24:55'),
(236, 'App\\Models\\User', 18, 'auth_token', 'e22608b9e1fa506db52023ba40b0d153c1598aa4f0c4c8987539b39ea2f06657', '[\"*\"]', '2026-11-05 11:12:03', NULL, '2026-11-05 11:11:59', '2026-11-05 11:12:03'),
(237, 'App\\Models\\User', 15, 'auth_token', 'fbeb7e6c572b206a7594a2154a21e7161c1df79b646badb9f5341c0db63dcbf9', '[\"*\"]', '2026-11-05 11:12:05', NULL, '2026-11-05 11:12:05', '2026-11-05 11:12:05'),
(238, 'App\\Models\\User', 18, 'auth_token', '38ae8e955076708ef26439af343038608264c283da5b5c278f681bc7b7215092', '[\"*\"]', '2026-11-05 11:12:10', NULL, '2026-11-05 11:12:10', '2026-11-05 11:12:10'),
(239, 'App\\Models\\User', 1, 'auth_token', 'b305ffb121261ad17d00f75e6ab5ed25ff627c402b7d347def15d3a06b2c6014', '[\"*\"]', '2026-11-05 11:12:21', NULL, '2026-11-05 11:12:20', '2026-11-05 11:12:21'),
(240, 'App\\Models\\User', 18, 'auth_token', 'cc6113b6c62576446b20d6e61e1bed6781904123cd0d41ebf5dff41efb2812e8', '[\"*\"]', '2026-11-05 11:26:26', NULL, '2026-11-05 11:26:24', '2026-11-05 11:26:26'),
(241, 'App\\Models\\User', 18, 'auth_token', 'a5e7417ac4d9cd40e2767ec6c0cb5ea8c6ea73b9b6753da5e6c68e7a802075e5', '[\"*\"]', '2026-11-05 11:26:33', NULL, '2026-11-05 11:26:32', '2026-11-05 11:26:33'),
(242, 'App\\Models\\User', 18, 'auth_token', '6bb8c2c2dcb8cd1a747d267848b027f2af2a3e5a5e1bfd2ef1d2ffbf4af26447', '[\"*\"]', '2026-11-05 11:26:40', NULL, '2026-11-05 11:26:39', '2026-11-05 11:26:40'),
(243, 'App\\Models\\User', 18, 'auth_token', 'c896ca86b7952dca2501446cd2b2c892c2421b4e93b7495fbba2a59dbbbc7a2d', '[\"*\"]', '2026-11-05 12:34:34', NULL, '2026-11-05 12:34:33', '2026-11-05 12:34:34'),
(244, 'App\\Models\\User', 18, 'auth_token', '7697ce896307c237fc8378a96ba9ca649a11cc72c6915f5bb554d7e11bfade03', '[\"*\"]', '2026-11-05 12:34:42', NULL, '2026-11-05 12:34:42', '2026-11-05 12:34:42'),
(245, 'App\\Models\\User', 18, 'auth_token', '003c29d61fa6c6d00ca7f405707ba8f96db6aa2b3a31f7342b98132ac9ba546d', '[\"*\"]', NULL, NULL, '2026-11-05 12:35:53', '2026-11-05 12:35:53'),
(246, 'App\\Models\\User', 18, 'auth_token', '35d2803552efffc8040c3c890aba35b00fdc38d083c2d183b19eb3fea3b07402', '[\"*\"]', '2026-11-05 12:36:05', NULL, '2026-11-05 12:36:04', '2026-11-05 12:36:05'),
(247, 'App\\Models\\User', 18, 'auth_token', '37a2c524da409de04d93a63f73f808b123260c100d2729256a1a68f1141dc7a0', '[\"*\"]', '2026-11-05 12:36:11', NULL, '2026-11-05 12:36:10', '2026-11-05 12:36:11'),
(248, 'App\\Models\\User', 18, 'auth_token', 'c0011641e03d2608e0fecdbc581342e152fd8e80f20316fc8868903a8d5e20f0', '[\"*\"]', '2026-11-05 12:56:06', NULL, '2026-11-05 12:56:04', '2026-11-05 12:56:06'),
(249, 'App\\Models\\User', 18, 'auth_token', '58c5721ecd4b290c7bafb73b3fa559fbb4723aa1bc62db6e6b4fd19a4cf9b001', '[\"*\"]', '2026-11-05 12:56:15', NULL, '2026-11-05 12:56:13', '2026-11-05 12:56:15'),
(250, 'App\\Models\\User', 18, 'auth_token', '2d8e41b3cd82d598b18a5047bb4447d6c82e01a92bfe907c9497a5a11e9684a9', '[\"*\"]', '2026-11-05 12:56:23', NULL, '2026-11-05 12:56:21', '2026-11-05 12:56:23'),
(251, 'App\\Models\\User', 18, 'auth_token', '7d1cff7aa8ff25df191e3fda8ba913c350114182c621cb9237144b4c321538f2', '[\"*\"]', '2026-11-05 14:24:15', NULL, '2026-11-05 14:24:11', '2026-11-05 14:24:15'),
(252, 'App\\Models\\User', 18, 'auth_token', 'bde2e4e4aca1bcb77a9b71bc221ed1f85e33ceac87e059021bcce89b9347fbff', '[\"*\"]', '2026-11-05 14:29:26', NULL, '2026-11-05 14:29:25', '2026-11-05 14:29:26'),
(253, 'App\\Models\\User', 18, 'auth_token', '51b7cbae45ba7c2bd8a07dc024b821539784f32f8b7443313cd2100f392dfaab', '[\"*\"]', '2026-11-05 14:31:53', NULL, '2026-11-05 14:31:51', '2026-11-05 14:31:53'),
(254, 'App\\Models\\User', 18, 'auth_token', 'cb11993137e65b42fe4bd183b276831621442a9622f297ac34c20a4aac3416a1', '[\"*\"]', '2026-11-05 17:04:03', NULL, '2026-11-05 17:04:01', '2026-11-05 17:04:03'),
(255, 'App\\Models\\User', 18, 'auth_token', 'e9b37f43b2915acee1edfb0d1d8b5115c617780b7a4589ea8b053137794a643e', '[\"*\"]', '2026-11-05 17:16:50', NULL, '2026-11-05 17:16:49', '2026-11-05 17:16:50'),
(256, 'App\\Models\\User', 18, 'auth_token', '24c94a3e95611434a3e814f82d7c218f7f3533a9b8be3abebedc9f1b5d466888', '[\"*\"]', '2026-11-05 17:17:08', NULL, '2026-11-05 17:17:07', '2026-11-05 17:17:08'),
(257, 'App\\Models\\User', 18, 'auth_token', '8a1f237cbca562396b0ca26fa27ee1e8e5a95cbba1459873899c309c218bb386', '[\"*\"]', '2026-11-05 17:22:45', NULL, '2026-11-05 17:22:45', '2026-11-05 17:22:45'),
(258, 'App\\Models\\User', 18, 'auth_token', '89cb344836c0792771cb5fac20930953d756fd60f71ca8156ad1889eb7b7efa1', '[\"*\"]', '2026-11-05 17:33:56', NULL, '2026-11-05 17:33:56', '2026-11-05 17:33:57'),
(259, 'App\\Models\\User', 18, 'auth_token', '120694383c889a9fdef675d8e89bad3ce3b8c61cd5b766af61e7555e9aff100f', '[\"*\"]', '2026-11-05 17:38:52', NULL, '2026-11-05 17:38:50', '2026-11-05 17:38:52'),
(261, 'App\\Models\\User', 1, 'auth_token', '9c1f097019ebaae2701025fc8cb7dc058fb21f7709f1fd71ccdcbdf997936a44', '[\"*\"]', '2026-11-05 20:25:41', NULL, '2026-11-05 19:53:42', '2026-11-05 20:25:41');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `duration` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `requirements` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`requirements`)),
  `tuition_fee` decimal(12,2) NOT NULL DEFAULT 0.00,
  `fees` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`fees`)),
  `currency` varchar(3) NOT NULL DEFAULT 'KES',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `code`, `name`, `department_id`, `duration`, `description`, `requirements`, `tuition_fee`, `fees`, `currency`, `is_active`, `created_at`, `updated_at`) VALUES
(8, 'CM', 'Clinical Medicine', 6, 3, 'this is clinical Medicine', NULL, 0.00, NULL, 'TZS', 1, '2026-06-20 09:49:55', '2026-06-20 09:49:55'),
(9, 'PST', 'Pharmaceutical Science', 7, 3, 'this is Pharmaceutical Science', NULL, 0.00, NULL, 'TZS', 1, '2026-06-20 09:50:49', '2026-06-20 09:50:49'),
(10, 'BSC-CS', 'Bachelor of Science in Computer Science', 8, 4, 'A comprehensive program in computer science and software engineering.', NULL, 150000.00, NULL, 'KES', 1, '2026-09-16 20:49:26', '2026-09-16 20:49:26'),
(11, 'BBA', 'Bachelor of Business Administration', 9, 4, 'Business management and administration program.', NULL, 140000.00, NULL, 'KES', 1, '2026-09-16 20:49:26', '2026-09-16 20:49:26'),
(12, 'BENG', 'Bachelor of Engineering', 10, 5, 'Engineering program with multiple specializations.', NULL, 180000.00, NULL, 'KES', 1, '2026-09-16 20:49:26', '2026-09-16 20:49:26'),
(13, 'MBBS', 'Bachelor of Medicine and Surgery', 11, 6, 'Medical degree program.', NULL, 250000.00, NULL, 'KES', 1, '2026-09-16 20:49:26', '2026-09-16 20:49:26'),
(14, 'BA-ENG', 'Bachelor of Arts in English', 12, 3, 'English literature and language program.', NULL, 120000.00, NULL, 'KES', 1, '2026-09-16 20:49:26', '2026-09-16 20:49:26');

-- --------------------------------------------------------

--
-- Table structure for table `proposals`
--

CREATE TABLE `proposals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` enum('business_plan','strategic_plan','project_proposal','departmental_plan','other') NOT NULL DEFAULT 'other',
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `owner_id` bigint(20) UNSIGNED NOT NULL,
  `summary` text DEFAULT NULL,
  `background` text DEFAULT NULL,
  `objectives` text DEFAULT NULL,
  `expected_outcomes` text DEFAULT NULL,
  `implementation_period_start` date DEFAULT NULL,
  `implementation_period_end` date DEFAULT NULL,
  `total_budget_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `funding_source` varchar(255) DEFAULT NULL,
  `status` enum('draft','submitted','under_review','approved','rejected','published','closed') NOT NULL DEFAULT 'draft',
  `submitted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proposals`
--

INSERT INTO `proposals` (`id`, `reference_number`, `title`, `type`, `department_id`, `owner_id`, `summary`, `background`, `objectives`, `expected_outcomes`, `implementation_period_start`, `implementation_period_end`, `total_budget_amount`, `funding_source`, `status`, `submitted_by`, `submitted_at`, `reviewed_by`, `reviewed_at`, `approved_by`, `approved_at`, `published_at`, `rejection_reason`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'PROP-2026-0001', 'Strategic Plan for Operations Improvement', 'strategic_plan', 6, 18, 'Improve operational efficiency.', 'Background details.', 'Objectives.', 'Outcomes.', '2026-07-01', '2027-06-30', 5000000.00, 'Internal', 'approved', 18, '2026-11-05 14:27:22', NULL, NULL, 18, '2026-11-05 14:27:22', NULL, NULL, '2026-11-05 14:27:22', '2026-11-05 14:27:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `proposal_activities`
--

CREATE TABLE `proposal_activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `responsible_person_id` bigint(20) UNSIGNED DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `budget_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `status` enum('planned','in_progress','completed','delayed','cancelled') NOT NULL DEFAULT 'planned',
  `progress_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proposal_activities`
--

INSERT INTO `proposal_activities` (`id`, `proposal_id`, `title`, `description`, `responsible_person_id`, `start_date`, `end_date`, `budget_amount`, `status`, `progress_percentage`, `created_at`, `updated_at`) VALUES
(1, 1, 'Phase 1 Implementation', 'Initial phase', 18, '2026-07-01', '2026-12-31', 2500000.00, 'in_progress', 40.00, '2026-11-05 14:27:22', '2026-11-05 14:27:22');

-- --------------------------------------------------------

--
-- Table structure for table `proposal_attachments`
--

CREATE TABLE `proposal_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `uploaded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proposal_kpis`
--

CREATE TABLE `proposal_kpis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `target_value` varchar(255) DEFAULT NULL,
  `actual_value` varchar(255) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `measurement_frequency` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proposal_kpis`
--

INSERT INTO `proposal_kpis` (`id`, `proposal_id`, `name`, `target_value`, `actual_value`, `unit`, `measurement_frequency`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 'Completion Rate', '100', '40', '%', 'monthly', 'on_track', 'In progress', '2026-11-05 14:27:23', '2026-11-05 14:27:23');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `registration_number` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `national_id` varchar(255) NOT NULL,
  `national_id_type` enum('passport','national_id','birth_certificate') NOT NULL DEFAULT 'national_id',
  `national_id_expiry_date` date DEFAULT NULL,
  `program_id` bigint(20) UNSIGNED NOT NULL,
  `program_name` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `intake` varchar(255) NOT NULL,
  `study_mode` enum('full_time','part_time','distance_learning') NOT NULL,
  `guardian_name` varchar(255) NOT NULL,
  `guardian_phone` varchar(255) NOT NULL,
  `guardian_email` varchar(255) NOT NULL,
  `guardian_relationship` varchar(255) NOT NULL,
  `guardian_address` varchar(255) NOT NULL,
  `status` enum('pending','payment_completed','approved','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `approved_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `user_id`, `registration_number`, `first_name`, `last_name`, `date_of_birth`, `gender`, `phone`, `email`, `address`, `city`, `country`, `national_id`, `national_id_type`, `national_id_expiry_date`, `program_id`, `program_name`, `department`, `intake`, `study_mode`, `guardian_name`, `guardian_phone`, `guardian_email`, `guardian_relationship`, `guardian_address`, `status`, `rejection_reason`, `submitted_at`, `approved_at`, `reviewed_at`, `created_at`, `updated_at`) VALUES
(6, 11, NULL, 'Abdul-latif', 'mahzoum', '2002-02-20', 'male', '+255 625879175', 'mahzoum@zanmetro.ac.tz', 'Amani', 'Zanzibar', 'Tanzania', '9558403848343', 'national_id', NULL, 8, 'Clinical Medicine', 'Clinical Medicine', 'Main Intake', 'full_time', 'Kassim', '+255 625879175', '', 'Father', 'Amani', 'pending', NULL, '2026-06-20 11:42:51', NULL, NULL, '2026-06-20 11:42:51', '2026-06-20 11:42:51'),
(7, 12, NULL, 'Thani', 'Thani', '2003-01-01', 'other', '+255 625879175', 'thani@zanmetro.ac.tz', 'zanzibar', 'zanzibar', 'Tanzania', '0773151127', 'national_id', NULL, 9, 'Pharmaceutical Science', 'Pharmaceutical Science', 'Main Intake', 'full_time', 'khamis', '0777102010', 'thani@zanmetro.com', 'father', 'zanzibar', 'payment_completed', NULL, '2026-09-22 12:04:24', NULL, NULL, '2026-06-20 11:58:04', '2026-09-22 09:04:24');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `is_system`, `created_at`, `updated_at`) VALUES
('ACCOUNTANT', 'Accountant', 'Finance portal access to registrations and payments.', 1, '2026-09-16 23:48:49', '2026-09-16 23:48:49'),
('ADMIN', 'Administrator', 'Full access to every admin panel page and action.', 1, '2026-09-16 20:47:18', '2026-09-16 20:47:18'),
('EXAM_OFFICER', 'EXAM OFFICER', 'Manages exam results within the admin panel.', 1, '2026-09-16 20:47:18', '2026-09-16 20:47:18'),
('HOD', 'Head of Department', 'Department head with limited admin panel access.', 1, '2026-09-16 20:47:18', '2026-09-16 20:47:18'),
('OPERATIONS_PLANNER', 'Operations & Planning Officer', 'Operations and planning portal access.', 1, '2026-11-05 11:08:36', '2026-11-05 11:08:36');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` varchar(255) NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
('ACCOUNTANT', 1),
('ACCOUNTANT', 20),
('ACCOUNTANT', 32),
('ACCOUNTANT', 34),
('ACCOUNTANT', 35),
('ADMIN', 1),
('ADMIN', 2),
('ADMIN', 3),
('ADMIN', 4),
('ADMIN', 5),
('ADMIN', 6),
('ADMIN', 7),
('ADMIN', 8),
('ADMIN', 9),
('ADMIN', 10),
('ADMIN', 11),
('ADMIN', 12),
('ADMIN', 13),
('ADMIN', 14),
('ADMIN', 15),
('ADMIN', 16),
('ADMIN', 17),
('ADMIN', 18),
('ADMIN', 19),
('ADMIN', 20),
('ADMIN', 21),
('ADMIN', 22),
('ADMIN', 23),
('ADMIN', 24),
('ADMIN', 25),
('ADMIN', 26),
('ADMIN', 27),
('ADMIN', 28),
('ADMIN', 29),
('ADMIN', 30),
('ADMIN', 31),
('ADMIN', 32),
('ADMIN', 33),
('ADMIN', 34),
('ADMIN', 35),
('ADMIN', 36),
('ADMIN', 37),
('ADMIN', 38),
('ADMIN', 39),
('ADMIN', 40),
('ADMIN', 41),
('ADMIN', 42),
('ADMIN', 43),
('ADMIN', 44),
('ADMIN', 45),
('ADMIN', 46),
('ADMIN', 47),
('ADMIN', 48),
('ADMIN', 49),
('ADMIN', 50),
('ADMIN', 51),
('ADMIN', 52),
('ADMIN', 53),
('ADMIN', 54),
('ADMIN', 55),
('ADMIN', 56),
('ADMIN', 57),
('ADMIN', 58),
('ADMIN', 59),
('ADMIN', 60),
('ADMIN', 61),
('ADMIN', 62),
('ADMIN', 63),
('ADMIN', 64),
('ADMIN', 65),
('ADMIN', 66),
('ADMIN', 67),
('ADMIN', 68),
('ADMIN', 69),
('ADMIN', 70),
('ADMIN', 71),
('ADMIN', 72),
('ADMIN', 73),
('ADMIN', 74),
('ADMIN', 75),
('ADMIN', 76),
('ADMIN', 77),
('ADMIN', 78),
('ADMIN', 79),
('ADMIN', 80),
('ADMIN', 81),
('ADMIN', 82),
('ADMIN', 83),
('ADMIN', 84),
('ADMIN', 85),
('ADMIN', 86),
('ADMIN', 87),
('ADMIN', 88),
('EXAM_OFFICER', 1),
('EXAM_OFFICER', 15),
('EXAM_OFFICER', 26),
('EXAM_OFFICER', 27),
('EXAM_OFFICER', 28),
('EXAM_OFFICER', 29),
('EXAM_OFFICER', 30),
('EXAM_OFFICER', 31),
('EXAM_OFFICER', 35),
('EXAM_OFFICER', 36),
('HOD', 1),
('HOD', 2),
('HOD', 4),
('HOD', 7),
('HOD', 20),
('HOD', 24),
('HOD', 25),
('HOD', 26),
('HOD', 30),
('HOD', 31),
('HOD', 35),
('OPERATIONS_PLANNER', 36),
('OPERATIONS_PLANNER', 44),
('OPERATIONS_PLANNER', 45),
('OPERATIONS_PLANNER', 46),
('OPERATIONS_PLANNER', 47),
('OPERATIONS_PLANNER', 48),
('OPERATIONS_PLANNER', 49),
('OPERATIONS_PLANNER', 50),
('OPERATIONS_PLANNER', 51),
('OPERATIONS_PLANNER', 52),
('OPERATIONS_PLANNER', 53),
('OPERATIONS_PLANNER', 54),
('OPERATIONS_PLANNER', 55),
('OPERATIONS_PLANNER', 56),
('OPERATIONS_PLANNER', 57),
('OPERATIONS_PLANNER', 58),
('OPERATIONS_PLANNER', 59),
('OPERATIONS_PLANNER', 60),
('OPERATIONS_PLANNER', 61),
('OPERATIONS_PLANNER', 62),
('OPERATIONS_PLANNER', 63),
('OPERATIONS_PLANNER', 64),
('OPERATIONS_PLANNER', 65),
('OPERATIONS_PLANNER', 66),
('OPERATIONS_PLANNER', 67),
('OPERATIONS_PLANNER', 68),
('OPERATIONS_PLANNER', 69),
('OPERATIONS_PLANNER', 70),
('OPERATIONS_PLANNER', 71),
('OPERATIONS_PLANNER', 72),
('OPERATIONS_PLANNER', 73),
('OPERATIONS_PLANNER', 74),
('OPERATIONS_PLANNER', 75),
('OPERATIONS_PLANNER', 76),
('OPERATIONS_PLANNER', 77),
('OPERATIONS_PLANNER', 78),
('OPERATIONS_PLANNER', 79),
('OPERATIONS_PLANNER', 80),
('OPERATIONS_PLANNER', 81),
('OPERATIONS_PLANNER', 82),
('OPERATIONS_PLANNER', 83),
('OPERATIONS_PLANNER', 84),
('OPERATIONS_PLANNER', 85),
('OPERATIONS_PLANNER', 86),
('OPERATIONS_PLANNER', 87),
('OPERATIONS_PLANNER', 88);

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'ADMIN', '2026-09-16 20:47:18', '2026-09-16 20:47:18'),
(2, 14, 'EXAM_OFFICER', '2026-09-16 20:49:25', '2026-09-16 20:49:25'),
(3, 9, 'ACCOUNTANT', '2026-09-16 23:48:49', '2026-09-16 23:48:49'),
(4, 16, 'ACCOUNTANT', '2026-09-16 23:48:49', '2026-09-16 23:48:49'),
(6, 17, 'EXAM_OFFICER', '2026-09-22 05:53:20', '2026-09-22 05:53:20'),
(7, 17, 'ADMIN', '2026-11-05 11:08:36', '2026-11-05 11:08:36'),
(8, 18, 'OPERATIONS_PLANNER', '2026-11-05 11:08:38', '2026-11-05 11:08:38');

-- --------------------------------------------------------

--
-- Table structure for table `salary_allowances`
--

CREATE TABLE `salary_allowances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` enum('fixed','percentage') NOT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `percentage` decimal(8,2) DEFAULT NULL,
  `applies_to` enum('all','salary_grade','employment_type') NOT NULL DEFAULT 'all',
  `salary_grade_id` bigint(20) UNSIGNED DEFAULT NULL,
  `employment_type` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_allowances`
--

INSERT INTO `salary_allowances` (`id`, `name`, `code`, `type`, `amount`, `percentage`, `applies_to`, `salary_grade_id`, `employment_type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'House Allowance', 'HOUSE', 'fixed', 100000.00, NULL, 'all', NULL, NULL, 1, '2026-11-05 12:52:05', '2026-11-05 12:52:05'),
(2, 'Transport Allowance', 'TRANSPORT', 'percentage', NULL, 10.00, 'all', NULL, NULL, 1, '2026-11-05 12:52:05', '2026-11-05 12:52:05');

-- --------------------------------------------------------

--
-- Table structure for table `salary_grades`
--

CREATE TABLE `salary_grades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_grades`
--

INSERT INTO `salary_grades` (`id`, `name`, `code`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Grade 1', 'G1', 'Entry level', 1, '2026-11-05 12:52:05', '2026-11-05 12:52:05');

-- --------------------------------------------------------

--
-- Table structure for table `salary_steps`
--

CREATE TABLE `salary_steps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `salary_grade_id` bigint(20) UNSIGNED NOT NULL,
  `step_number` int(11) NOT NULL,
  `basic_salary` decimal(12,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_steps`
--

INSERT INTO `salary_steps` (`id`, `salary_grade_id`, `step_number`, `basic_salary`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 500000.00, 1, '2026-11-05 12:52:05', '2026-11-05 12:52:05'),
(2, 1, 2, 600000.00, 1, '2026-11-05 12:52:05', '2026-11-05 12:52:05');

-- --------------------------------------------------------

--
-- Table structure for table `staff_salary_placements`
--

CREATE TABLE `staff_salary_placements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hr_profile_id` bigint(20) UNSIGNED NOT NULL,
  `salary_grade_id` bigint(20) UNSIGNED NOT NULL,
  `salary_step_id` bigint(20) UNSIGNED NOT NULL,
  `basic_salary` decimal(12,2) NOT NULL,
  `effective_from` date NOT NULL,
  `effective_to` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff_salary_placements`
--

INSERT INTO `staff_salary_placements` (`id`, `hr_profile_id`, `salary_grade_id`, `salary_step_id`, `basic_salary`, `effective_from`, `effective_to`, `remarks`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 500000.00, '2026-01-01', NULL, 'Seeded placement', 1, '2026-11-05 12:52:05', '2026-11-05 12:52:05'),
(2, 2, 1, 1, 500000.00, '2026-01-01', NULL, 'Seeded placement', 1, '2026-11-05 12:52:05', '2026-11-05 12:52:05'),
(3, 3, 1, 1, 500000.00, '2026-01-01', NULL, 'Seeded placement', 1, '2026-11-05 12:52:05', '2026-11-05 12:52:05'),
(4, 4, 1, 1, 500000.00, '2026-01-01', NULL, 'Seeded placement', 1, '2026-11-05 12:52:05', '2026-11-05 12:52:05'),
(5, 5, 1, 1, 500000.00, '2026-01-01', NULL, 'Seeded placement', 1, '2026-11-05 12:52:05', '2026-11-05 12:52:05'),
(6, 6, 1, 1, 500000.00, '2026-01-01', NULL, 'Seeded placement', 1, '2026-11-05 12:52:05', '2026-11-05 12:52:05'),
(7, 7, 1, 1, 500000.00, '2026-01-01', NULL, 'Seeded placement', 1, '2026-11-05 12:52:05', '2026-11-05 12:56:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','instructor','admin','accountant','operations') NOT NULL DEFAULT 'student',
  `phone` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `role`, `phone`, `date_of_birth`, `gender`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'User', 'admin@college.edu', NULL, '$2y$12$rZXSr0zCvlzkHeSkC5Fa1e75fGm7gEe2B/ZfWZjvBhYKHWp5ULNPy', 'admin', '0712345678', NULL, NULL, NULL, '2026-05-30 02:31:37', '2026-05-30 02:31:37'),
(8, 'Silima', 'Haji', 'mahzoum@gmail.com', NULL, '$2y$12$JnGIMnzk.y2eQ3SAF/LYmOoz.wFeLH5SSgAnRwTldW7paVbTRC6um', 'instructor', '0611433767', NULL, NULL, NULL, '2026-06-20 09:57:30', '2026-09-22 05:48:02'),
(9, 'Naifin', 'Nassor', 'naifin@zanmetro.ac.tz', NULL, '$2y$12$NxpXiwK05NgOlTCke/d1GeT2kEnym9XjrRA0CvnxlOK1AbRW8l8Um', 'accountant', '0777514738', NULL, NULL, NULL, '2026-06-20 10:01:37', '2026-06-20 10:01:37'),
(11, 'Abdul-latif', 'mahzoum', 'mahzoum@zanmetro.ac.tz', NULL, '$2y$12$vN3hFlydNRG6WphhmmgHOOuvyW/2F0XfhTPwZIhfLvDatVO9XxsL2', 'student', NULL, NULL, 'other', NULL, '2026-06-20 11:38:32', '2026-06-20 11:38:32'),
(12, 'Thani', 'Thani', 'thani@zanmetro.ac.tz', NULL, '$2y$12$alMAoakFS596Ecpeb.Qo4.fL4bOO.blKS6k08VeCZT6caAHK74JZi', 'student', NULL, NULL, 'other', NULL, '2026-06-20 11:54:21', '2026-06-20 11:54:21'),
(14, 'John', 'Doe', 'instructor@college.edu', NULL, '$2y$12$s.c7FZ4xGjH/ploClZOckOoOiSf4gAkg7dN7zxRbukIa.KoOqBfbq', 'instructor', '0723456789', NULL, NULL, NULL, '2026-09-16 20:49:25', '2026-09-16 20:49:25'),
(15, 'Jane', 'Smith', 'student@college.edu', NULL, '$2y$12$sqcZmVLhqoLfzD28wrRzpu1cTj3N3GHH0gCpcspdR5Ez.xjGzl4o2', 'student', '0734567890', '2000-05-15', 'female', NULL, '2026-09-16 20:49:26', '2026-09-16 20:49:26'),
(16, 'Mary', 'Johnson', 'accountant@college.edu', NULL, '$2y$12$wHxRWFpS.iOut6.IzInWWOHWV9Riiazo1TOxBjgIHqugYupqme6RS', 'accountant', '0745678901', NULL, NULL, NULL, '2026-09-16 20:49:26', '2026-09-16 20:49:26'),
(17, 'Sufyan', 'Thawry', 'sufyan@collage.com', NULL, '$2y$12$2Lz.g9lwYB/zXbN8oVm4UuKmMVBC3kFkoAHOjT6Vg.wQFmzR5oP.e', 'admin', '0763538253', NULL, NULL, NULL, '2026-09-22 05:51:58', '2026-09-22 05:51:58'),
(18, 'Operations', 'Officer', 'operations@college.edu', NULL, '$2y$12$puAq3nRaxNEL9qqkM19k0euYlpyZFX2i/odWsJ0bAaKeWl8ZDqBg.', 'operations', '0756789012', NULL, NULL, NULL, '2026-11-05 11:08:38', '2026-11-05 11:08:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_qualifications`
--
ALTER TABLE `academic_qualifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `academic_qualifications_registration_id_foreign` (`registration_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `audit_logs_path_index` (`path`);

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budgets_fiscal_year_id_foreign` (`fiscal_year_id`),
  ADD KEY `budgets_department_id_foreign` (`department_id`),
  ADD KEY `budgets_submitted_by_foreign` (`submitted_by`),
  ADD KEY `budgets_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `budgets_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `budget_categories`
--
ALTER TABLE `budget_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `budget_categories_code_unique` (`code`);

--
-- Indexes for table `budget_expenditures`
--
ALTER TABLE `budget_expenditures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budget_expenditures_budget_line_id_foreign` (`budget_line_id`),
  ADD KEY `budget_expenditures_recorded_by_foreign` (`recorded_by`),
  ADD KEY `budget_expenditures_verified_by_foreign` (`verified_by`);

--
-- Indexes for table `budget_lines`
--
ALTER TABLE `budget_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budget_lines_budget_id_foreign` (`budget_id`),
  ADD KEY `budget_lines_budget_category_id_foreign` (`budget_category_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courses_code_unique` (`code`),
  ADD KEY `courses_department_id_foreign` (`department_id`),
  ADD KEY `courses_created_by_foreign` (`created_by`),
  ADD KEY `courses_credit_id_foreign` (`credit_id`),
  ADD KEY `courses_program_id_foreign` (`program_id`);

--
-- Indexes for table `course_offerings`
--
ALTER TABLE `course_offerings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_offerings_course_id_foreign` (`course_id`),
  ADD KEY `course_offerings_instructor_id_foreign` (`instructor_id`);

--
-- Indexes for table `credits`
--
ALTER TABLE `credits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `credits_code_unique` (`code`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_code_unique` (`code`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_registration_id_foreign` (`registration_id`);

--
-- Indexes for table `exam_results`
--
ALTER TABLE `exam_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_results_student_id_foreign` (`student_id`),
  ADD KEY `exam_results_course_offering_id_foreign` (`course_offering_id`),
  ADD KEY `exam_results_recorded_by_foreign` (`recorded_by`),
  ADD KEY `exam_results_submitted_by_foreign` (`submitted_by`),
  ADD KEY `exam_results_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `exam_results_status_index` (`status`),
  ADD KEY `exam_results_student_id_status_index` (`student_id`,`status`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fees_program_id_foreign` (`program_id`);

--
-- Indexes for table `fiscal_years`
--
ALTER TABLE `fiscal_years`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_documents`
--
ALTER TABLE `hr_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hr_documents_hr_profile_id_foreign` (`hr_profile_id`),
  ADD KEY `hr_documents_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `hr_leave_records`
--
ALTER TABLE `hr_leave_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hr_leave_records_hr_profile_id_foreign` (`hr_profile_id`),
  ADD KEY `hr_leave_records_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `hr_profiles`
--
ALTER TABLE `hr_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hr_profiles_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `hr_profiles_employee_number_unique` (`employee_number`),
  ADD KEY `hr_profiles_department_id_foreign` (`department_id`),
  ADD KEY `hr_profiles_created_by_foreign` (`created_by`),
  ADD KEY `hr_profiles_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `instructor_assignments`
--
ALTER TABLE `instructor_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructor_assignments_course_id_foreign` (`course_id`),
  ADD KEY `instructor_assignments_instructor_id_foreign` (`instructor_id`),
  ADD KEY `instructor_assignments_assigned_by_foreign` (`assigned_by`),
  ADD KEY `instructor_assignments_instructor_id_status_index` (`instructor_id`,`status`);

--
-- Indexes for table `inventory_audits`
--
ALTER TABLE `inventory_audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_audits_location_id_foreign` (`location_id`),
  ADD KEY `inventory_audits_inventory_item_id_foreign` (`inventory_item_id`),
  ADD KEY `inventory_audits_conducted_by_foreign` (`conducted_by`);

--
-- Indexes for table `inventory_categories`
--
ALTER TABLE `inventory_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_categories_code_unique` (`code`);

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_items_asset_tag_unique` (`asset_tag`),
  ADD KEY `inventory_items_category_id_foreign` (`category_id`),
  ADD KEY `inventory_items_location_id_foreign` (`location_id`),
  ADD KEY `inventory_items_custodian_id_foreign` (`custodian_id`),
  ADD KEY `inventory_items_created_by_foreign` (`created_by`),
  ADD KEY `inventory_items_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `inventory_locations`
--
ALTER TABLE `inventory_locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_locations_code_unique` (`code`);

--
-- Indexes for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_transactions_inventory_item_id_foreign` (`inventory_item_id`),
  ADD KEY `inventory_transactions_from_location_id_foreign` (`from_location_id`),
  ADD KEY `inventory_transactions_to_location_id_foreign` (`to_location_id`),
  ADD KEY `inventory_transactions_from_user_id_foreign` (`from_user_id`),
  ADD KEY `inventory_transactions_to_user_id_foreign` (`to_user_id`),
  ADD KEY `inventory_transactions_recorded_by_foreign` (`recorded_by`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_control_number_unique` (`control_number`),
  ADD KEY `payments_registration_id_foreign` (`registration_id`),
  ADD KEY `payments_student_id_foreign` (`student_id`),
  ADD KEY `payments_processed_by_foreign` (`processed_by`),
  ADD KEY `payments_status_index` (`status`),
  ADD KEY `payments_registration_id_status_index` (`registration_id`,`status`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_code_unique` (`code`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `programs_code_unique` (`code`),
  ADD KEY `programs_department_id_foreign` (`department_id`);

--
-- Indexes for table `proposals`
--
ALTER TABLE `proposals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `proposals_reference_number_unique` (`reference_number`),
  ADD KEY `proposals_department_id_foreign` (`department_id`),
  ADD KEY `proposals_owner_id_foreign` (`owner_id`),
  ADD KEY `proposals_submitted_by_foreign` (`submitted_by`),
  ADD KEY `proposals_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `proposals_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `proposal_activities`
--
ALTER TABLE `proposal_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_activities_proposal_id_foreign` (`proposal_id`),
  ADD KEY `proposal_activities_responsible_person_id_foreign` (`responsible_person_id`);

--
-- Indexes for table `proposal_attachments`
--
ALTER TABLE `proposal_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_attachments_proposal_id_foreign` (`proposal_id`),
  ADD KEY `proposal_attachments_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `proposal_kpis`
--
ALTER TABLE `proposal_kpis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_kpis_proposal_id_foreign` (`proposal_id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registrations_registration_number_unique` (`registration_number`),
  ADD KEY `registrations_user_id_foreign` (`user_id`),
  ADD KEY `registrations_program_id_foreign` (`program_id`),
  ADD KEY `registrations_status_index` (`status`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `role_permissions_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_user_user_id_role_id_unique` (`user_id`,`role_id`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indexes for table `salary_allowances`
--
ALTER TABLE `salary_allowances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `salary_allowances_code_unique` (`code`),
  ADD KEY `salary_allowances_salary_grade_id_foreign` (`salary_grade_id`);

--
-- Indexes for table `salary_grades`
--
ALTER TABLE `salary_grades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `salary_grades_code_unique` (`code`);

--
-- Indexes for table `salary_steps`
--
ALTER TABLE `salary_steps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `salary_steps_salary_grade_id_step_number_unique` (`salary_grade_id`,`step_number`);

--
-- Indexes for table `staff_salary_placements`
--
ALTER TABLE `staff_salary_placements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_salary_placements_hr_profile_id_foreign` (`hr_profile_id`),
  ADD KEY `staff_salary_placements_salary_grade_id_foreign` (`salary_grade_id`),
  ADD KEY `staff_salary_placements_salary_step_id_foreign` (`salary_step_id`),
  ADD KEY `staff_salary_placements_created_by_foreign` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_qualifications`
--
ALTER TABLE `academic_qualifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `budget_categories`
--
ALTER TABLE `budget_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `budget_expenditures`
--
ALTER TABLE `budget_expenditures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `budget_lines`
--
ALTER TABLE `budget_lines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `course_offerings`
--
ALTER TABLE `course_offerings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `credits`
--
ALTER TABLE `credits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_results`
--
ALTER TABLE `exam_results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `fiscal_years`
--
ALTER TABLE `fiscal_years`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hr_documents`
--
ALTER TABLE `hr_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_leave_records`
--
ALTER TABLE `hr_leave_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hr_profiles`
--
ALTER TABLE `hr_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `instructor_assignments`
--
ALTER TABLE `instructor_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `inventory_audits`
--
ALTER TABLE `inventory_audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_categories`
--
ALTER TABLE `inventory_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inventory_locations`
--
ALTER TABLE `inventory_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=264;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `proposals`
--
ALTER TABLE `proposals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `proposal_activities`
--
ALTER TABLE `proposal_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `proposal_attachments`
--
ALTER TABLE `proposal_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposal_kpis`
--
ALTER TABLE `proposal_kpis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `salary_allowances`
--
ALTER TABLE `salary_allowances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `salary_grades`
--
ALTER TABLE `salary_grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `salary_steps`
--
ALTER TABLE `salary_steps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staff_salary_placements`
--
ALTER TABLE `staff_salary_placements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `academic_qualifications`
--
ALTER TABLE `academic_qualifications`
  ADD CONSTRAINT `academic_qualifications_registration_id_foreign` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `budgets_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `budgets_fiscal_year_id_foreign` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `budgets_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `budgets_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `budget_expenditures`
--
ALTER TABLE `budget_expenditures`
  ADD CONSTRAINT `budget_expenditures_budget_line_id_foreign` FOREIGN KEY (`budget_line_id`) REFERENCES `budget_lines` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `budget_expenditures_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `budget_expenditures_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `budget_lines`
--
ALTER TABLE `budget_lines`
  ADD CONSTRAINT `budget_lines_budget_category_id_foreign` FOREIGN KEY (`budget_category_id`) REFERENCES `budget_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `budget_lines_budget_id_foreign` FOREIGN KEY (`budget_id`) REFERENCES `budgets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `courses_credit_id_foreign` FOREIGN KEY (`credit_id`) REFERENCES `credits` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `courses_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `courses_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `course_offerings`
--
ALTER TABLE `course_offerings`
  ADD CONSTRAINT `course_offerings_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_offerings_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_registration_id_foreign` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exam_results`
--
ALTER TABLE `exam_results`
  ADD CONSTRAINT `exam_results_course_offering_id_foreign` FOREIGN KEY (`course_offering_id`) REFERENCES `course_offerings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_results_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_results_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `exam_results_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_results_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `fees`
--
ALTER TABLE `fees`
  ADD CONSTRAINT `fees_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hr_documents`
--
ALTER TABLE `hr_documents`
  ADD CONSTRAINT `hr_documents_hr_profile_id_foreign` FOREIGN KEY (`hr_profile_id`) REFERENCES `hr_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hr_documents_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hr_leave_records`
--
ALTER TABLE `hr_leave_records`
  ADD CONSTRAINT `hr_leave_records_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hr_leave_records_hr_profile_id_foreign` FOREIGN KEY (`hr_profile_id`) REFERENCES `hr_profiles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hr_profiles`
--
ALTER TABLE `hr_profiles`
  ADD CONSTRAINT `hr_profiles_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hr_profiles_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hr_profiles_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hr_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `instructor_assignments`
--
ALTER TABLE `instructor_assignments`
  ADD CONSTRAINT `instructor_assignments_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `instructor_assignments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `instructor_assignments_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_audits`
--
ALTER TABLE `inventory_audits`
  ADD CONSTRAINT `inventory_audits_conducted_by_foreign` FOREIGN KEY (`conducted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_audits_inventory_item_id_foreign` FOREIGN KEY (`inventory_item_id`) REFERENCES `inventory_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_audits_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `inventory_locations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD CONSTRAINT `inventory_items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `inventory_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_items_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_items_custodian_id_foreign` FOREIGN KEY (`custodian_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_items_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `inventory_locations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_items_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD CONSTRAINT `inventory_transactions_from_location_id_foreign` FOREIGN KEY (`from_location_id`) REFERENCES `inventory_locations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_transactions_from_user_id_foreign` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_transactions_inventory_item_id_foreign` FOREIGN KEY (`inventory_item_id`) REFERENCES `inventory_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_transactions_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_transactions_to_location_id_foreign` FOREIGN KEY (`to_location_id`) REFERENCES `inventory_locations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_transactions_to_user_id_foreign` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_registration_id_foreign` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `programs`
--
ALTER TABLE `programs`
  ADD CONSTRAINT `programs_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `proposals`
--
ALTER TABLE `proposals`
  ADD CONSTRAINT `proposals_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `proposals_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `proposals_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `proposals_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `proposals_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `proposal_activities`
--
ALTER TABLE `proposal_activities`
  ADD CONSTRAINT `proposal_activities_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `proposal_activities_responsible_person_id_foreign` FOREIGN KEY (`responsible_person_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `proposal_attachments`
--
ALTER TABLE `proposal_attachments`
  ADD CONSTRAINT `proposal_attachments_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `proposal_attachments_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `proposal_kpis`
--
ALTER TABLE `proposal_kpis`
  ADD CONSTRAINT `proposal_kpis_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `registrations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salary_allowances`
--
ALTER TABLE `salary_allowances`
  ADD CONSTRAINT `salary_allowances_salary_grade_id_foreign` FOREIGN KEY (`salary_grade_id`) REFERENCES `salary_grades` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `salary_steps`
--
ALTER TABLE `salary_steps`
  ADD CONSTRAINT `salary_steps_salary_grade_id_foreign` FOREIGN KEY (`salary_grade_id`) REFERENCES `salary_grades` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff_salary_placements`
--
ALTER TABLE `staff_salary_placements`
  ADD CONSTRAINT `staff_salary_placements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `staff_salary_placements_hr_profile_id_foreign` FOREIGN KEY (`hr_profile_id`) REFERENCES `hr_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staff_salary_placements_salary_grade_id_foreign` FOREIGN KEY (`salary_grade_id`) REFERENCES `salary_grades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staff_salary_placements_salary_step_id_foreign` FOREIGN KEY (`salary_step_id`) REFERENCES `salary_steps` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
