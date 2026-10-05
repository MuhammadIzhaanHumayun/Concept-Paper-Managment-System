-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2026 at 11:20 AM
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
-- Database: `concept_paper`
--

-- --------------------------------------------------------

--
-- Table structure for table `approval_workflows`
--

CREATE TABLE `approval_workflows` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED DEFAULT NULL,
  `step_order` int(10) UNSIGNED NOT NULL,
  `step_code` varchar(50) NOT NULL,
  `step_name` varchar(150) NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `approver_scope` varchar(40) NOT NULL DEFAULT 'CATEGORY_OWNER_DEPARTMENT',
  `requires_technical_review` tinyint(1) NOT NULL DEFAULT 0,
  `is_required` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `approval_workflows`
--

INSERT INTO `approval_workflows` (`id`, `category_id`, `department_id`, `step_order`, `step_code`, `step_name`, `role_id`, `approver_scope`, `requires_technical_review`, `is_required`, `created_at`) VALUES
(12, 2, 1, 2, 'ROLE_APPROVAL', 'Requesting Department GM Approval', 2, 'REQUESTING_DEPARTMENT', 0, 1, '2026-09-16 05:24:28'),
(13, 3, 1, 2, 'ROLE_APPROVAL', 'Requesting Department GM Approval', 2, 'REQUESTING_DEPARTMENT', 0, 1, '2026-09-16 05:57:07'),
(14, 3, 1, 3, 'ROLE_APPROVAL', 'SAP Technical Review', 5, 'CATEGORY_TEAM', 1, 1, '2026-09-16 05:59:03'),
(15, 3, 1, 4, 'ROLE_APPROVAL', 'SAP Team Lead Approval', 3, 'CATEGORY_TEAM', 0, 1, '2026-09-16 05:59:26'),
(16, 1, 1, 1, 'ROLE_APPROVAL', 'Infrastructure Technical Review', 5, 'CATEGORY_TEAM', 1, 1, '2026-09-16 06:16:16'),
(17, 1, 1, 2, 'ROLE_APPROVAL', 'IT GM Approval', 2, 'CATEGORY_OWNER_DEPARTMENT', 0, 1, '2026-09-16 06:16:16'),
(19, 2, 1, 1, 'ROLE_APPROVAL', 'NM Approval', 10, 'REQUESTING_DEPARTMENT', 0, 1, '2026-09-16 06:16:16'),
(20, 2, 1, 3, 'ROLE_APPROVAL', 'Application Technical Reviewer', 5, 'CATEGORY_TEAM', 1, 1, '2026-09-16 06:16:16'),
(24, 3, 1, 6, 'ROLE_APPROVAL', 'IT GM Approval', 2, 'CATEGORY_OWNER_DEPARTMENT', 0, 1, '2026-09-16 06:16:16'),
(25, 4, 1, 1, 'ROLE_APPROVAL', 'DB/Server Technical Review', 5, 'CATEGORY_TEAM', 1, 1, '2026-09-16 06:16:16'),
(26, 4, 1, 2, 'ROLE_APPROVAL', 'IT GM Approval', 2, 'CATEGORY_OWNER_DEPARTMENT', 0, 1, '2026-09-16 06:16:16'),
(27, 1, 1, 1000027, 'ROLE_APPROVAL', 'CEO Approval', 11, 'ORGANIZATION', 0, 0, '2026-09-17 04:37:58'),
(29, 1, 1, 1000029, 'ROLE_APPROVAL', 'CEO Approval', 11, 'ORGANIZATION', 0, 0, '2026-09-17 05:09:04'),
(30, 1, 1, 3, 'ROLE_APPROVAL', 'CEO Approval', 11, 'ORGANIZATION', 0, 1, '2026-09-17 05:22:20'),
(31, 2, 1, 4, 'ROLE_APPROVAL', 'GM Approval', 2, 'CATEGORY_OWNER_DEPARTMENT', 0, 1, '2026-09-21 03:00:10'),
(32, 2, 1, 5, 'ROLE_APPROVAL', 'CEO Approval', 11, 'ORGANIZATION', 0, 1, '2026-09-21 03:02:48'),
(33, 3, 1, 1, 'ROLE_APPROVAL', 'Requesting Department NM Approval', 10, 'REQUESTING_DEPARTMENT', 0, 1, '2026-09-21 03:25:30'),
(34, 3, 1, 5, 'ROLE_APPROVAL', 'IT NM Approval', 10, 'CATEGORY_OWNER_DEPARTMENT', 0, 1, '2026-09-24 04:00:23');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(10) UNSIGNED NOT NULL,
  `department_code` varchar(20) DEFAULT NULL,
  `department_name` varchar(150) NOT NULL,
  `status` enum('ACTIVE','INACTIVE') DEFAULT 'ACTIVE',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `department_code`, `department_name`, `status`, `created_at`) VALUES
(1, 'IT', 'Information Technology', 'ACTIVE', '2026-09-03 06:14:14'),
(2, 'FIN', 'Finance', 'ACTIVE', '2026-09-03 06:14:14'),
(3, 'HR', 'Human Resources', 'ACTIVE', '2026-09-03 06:14:14'),
(4, 'SAL', 'Sales', 'ACTIVE', '2026-09-03 06:14:14'),
(8, 'PROD', 'Production', 'ACTIVE', '2026-09-16 03:27:12');

-- --------------------------------------------------------

--
-- Table structure for table `department_heads`
--

CREATE TABLE `department_heads` (
  `id` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department_heads`
--

INSERT INTO `department_heads` (`id`, `department_id`, `user_id`, `status`) VALUES
(1, 2, 4, 'ACTIVE'),
(5, 1, 5, 'ACTIVE');

-- --------------------------------------------------------

--
-- Table structure for table `department_project_categories`
--

CREATE TABLE `department_project_categories` (
  `department_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `team_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department_project_categories`
--

INSERT INTO `department_project_categories` (`department_id`, `category_id`, `team_id`, `created_at`) VALUES
(1, 1, 5, '2026-09-16 06:16:16'),
(1, 2, 1, '2026-09-16 05:22:57'),
(1, 3, 2, '2026-09-16 05:55:53'),
(1, 4, 8, '2026-09-16 06:16:16');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `notification_type` varchar(50) DEFAULT 'INFO',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` varchar(50) NOT NULL,
  `project_title` varchar(255) NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `requestor_id` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `priority` enum('HIGH','MEDIUM','LOW') NOT NULL DEFAULT 'MEDIUM',
  `current_state` text NOT NULL,
  `proposed_state` text NOT NULL,
  `scope_included` text NOT NULL,
  `scope_excluded` text NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('DRAFT','SUBMITTED','APPROVED','REJECTED','RETURNED_FOR_REVISION') NOT NULL DEFAULT 'DRAFT',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_id`, `project_title`, `category_id`, `requestor_id`, `department_id`, `priority`, `current_state`, `proposed_state`, `scope_included`, `scope_excluded`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(31, 'CP-DB-2026-001', 'upgrade CMS', 4, 3, 2, 'HIGH', 'fsdf', 'sdfs', 'dfsd', 'fsd', NULL, '2026-10-10', 'APPROVED', '2026-09-21 06:14:26', '2026-09-21 06:15:42'),
(32, 'CP-DB-2026-002', 'new motorcycle', 4, 3, 2, 'MEDIUM', 'asdas', 'df', 'asdf', 'sadf', NULL, '2026-09-21', 'APPROVED', '2026-09-21 06:21:10', '2026-09-21 06:28:18'),
(34, 'CP-APP-2026-001', 'new motorcycle', 2, 3, 2, 'MEDIUM', 'sdgaasg', 'asdfgfdsg', 'gsdfgds', 'dsfgsd', NULL, '2026-09-21', 'APPROVED', '2026-09-21 07:57:34', '2026-09-21 08:00:42'),
(35, 'CP-INFRA-2026-001', 'upgrade CMS', 1, 3, 2, 'LOW', 'afagasa', 'sdgasdgas', 'asgasdgs', 'adgasdgsagsdg', NULL, '2027-04-23', 'APPROVED', '2026-09-21 09:34:40', '2026-09-21 09:48:11'),
(36, 'CP-APP-2026-002', 'new motorcycle', 2, 3, 1, 'MEDIUM', 'sdfg', 'dsfg', 'sdfg', 'sdfg', NULL, '2026-09-22', 'REJECTED', '2026-09-22 03:08:02', '2026-09-22 03:09:55'),
(38, 'CP-SAP-2026-001', 'upgrade CMS', 3, 3, 1, 'MEDIUM', 'afsadsa', 'sadgasg', 'sagsag', 'sag', NULL, '2027-04-16', 'SUBMITTED', '2026-09-24 02:56:02', '2026-09-24 10:08:28'),
(39, 'CP-SAP-2026-002', 'new motorcycle', 3, 3, 2, 'MEDIUM', 'xgdf', 'gdfsgsd', 'fgs', 'dfgds', NULL, '2026-09-24', 'APPROVED', '2026-09-24 10:08:58', '2026-09-24 10:11:43'),
(40, 'CP-SAP-2026-003', 'xdgv', 3, 3, 2, 'MEDIUM', 'dfh', 'dfh', 'dfhf', 'hdf', NULL, '2026-09-26', 'REJECTED', '2026-09-25 04:56:52', '2026-09-25 04:58:21');

-- --------------------------------------------------------

--
-- Table structure for table `project_approvals`
--

CREATE TABLE `project_approvals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `workflow_id` int(10) UNSIGNED NOT NULL,
  `approver_id` int(10) UNSIGNED DEFAULT NULL,
  `status` varchar(40) NOT NULL DEFAULT 'PENDING',
  `returned_for_revision` tinyint(1) NOT NULL DEFAULT 0,
  `comments` text DEFAULT NULL,
  `return_comments` text DEFAULT NULL,
  `action_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_approvals`
--

INSERT INTO `project_approvals` (`id`, `project_id`, `workflow_id`, `approver_id`, `status`, `returned_for_revision`, `comments`, `return_comments`, `action_date`, `created_at`) VALUES
(283, 31, 25, 9, 'APPROVED', 0, 'fdghfdh', NULL, '2026-09-21 11:15:17', '2026-09-21 06:14:26'),
(284, 31, 26, 5, 'APPROVED', 0, 'afds', NULL, '2026-09-21 11:15:42', '2026-09-21 06:14:26'),
(285, 32, 25, 9, 'APPROVED', 0, 'dfsfadfs', NULL, '2026-09-21 11:26:43', '2026-09-21 06:21:10'),
(286, 32, 26, 5, 'APPROVED', 0, 'sd', 'sdfsd', '2026-09-21 11:28:17', '2026-09-21 06:21:10'),
(287, 34, 19, 14, 'APPROVED', 0, 'qrqer', NULL, '2026-09-21 12:59:31', '2026-09-21 07:57:34'),
(288, 34, 12, 4, 'APPROVED', 0, 'dsffdsfsdggdh', 'reassdsd', '2026-09-21 12:59:42', '2026-09-21 07:57:34'),
(289, 34, 20, 8, 'APPROVED', 0, 'terterwtrt', NULL, '2026-09-21 13:00:05', '2026-09-21 07:57:34'),
(290, 34, 31, 5, 'APPROVED', 0, 'wttewtr', NULL, '2026-09-21 13:00:25', '2026-09-21 07:57:34'),
(291, 34, 32, 15, 'APPROVED', 0, '', NULL, '2026-09-21 13:00:42', '2026-09-21 07:57:34'),
(292, 35, 16, 7, 'APPROVED', 0, 'done and fiixed', NULL, '2026-09-21 14:38:21', '2026-09-21 09:34:40'),
(293, 35, 17, 5, 'APPROVED', 0, 'done approved from my side.', 'review again and reduce days.', '2026-09-21 14:47:15', '2026-09-21 09:34:40'),
(294, 35, 30, 15, 'APPROVED', 0, 'all fine.', NULL, '2026-09-21 14:48:11', '2026-09-21 09:34:40'),
(295, 36, 19, 16, 'REJECTED', 0, '', NULL, '2026-09-22 08:09:55', '2026-09-22 03:08:02'),
(296, 36, 12, 5, 'NOT_REQUIRED', 0, NULL, NULL, NULL, '2026-09-22 03:08:02'),
(297, 36, 20, 8, 'NOT_REQUIRED', 0, NULL, NULL, NULL, '2026-09-22 03:08:02'),
(298, 36, 31, 5, 'NOT_REQUIRED', 0, NULL, NULL, NULL, '2026-09-22 03:08:02'),
(299, 36, 32, 15, 'NOT_REQUIRED', 0, NULL, NULL, NULL, '2026-09-22 03:08:02'),
(317, 38, 14, 10, 'PENDING', 0, NULL, NULL, NULL, '2026-09-24 10:08:28'),
(318, 38, 15, 6, 'PENDING', 0, NULL, NULL, NULL, '2026-09-24 10:08:28'),
(319, 38, 34, 16, 'PENDING', 0, NULL, NULL, NULL, '2026-09-24 10:08:28'),
(320, 38, 24, 5, 'PENDING', 0, NULL, NULL, NULL, '2026-09-24 10:08:28'),
(321, 39, 33, 14, 'APPROVED', 0, 'sfdasdasd', NULL, '2026-09-24 15:09:41', '2026-09-24 10:08:58'),
(322, 39, 13, 4, 'APPROVED', 0, 'tuyuuuu', NULL, '2026-09-24 15:09:54', '2026-09-24 10:08:58'),
(323, 39, 14, 10, 'APPROVED', 0, 'gdsfgdfgdfg', NULL, '2026-09-24 15:10:23', '2026-09-24 10:08:58'),
(324, 39, 15, 6, 'APPROVED', 0, 'asdasd', NULL, '2026-09-24 15:10:49', '2026-09-24 10:08:58'),
(325, 39, 34, 16, 'APPROVED', 0, 'tfiuft', NULL, '2026-09-24 15:11:31', '2026-09-24 10:08:58'),
(326, 39, 24, 5, 'APPROVED', 0, 'gyoiyiiuuff', NULL, '2026-09-24 15:11:43', '2026-09-24 10:08:58'),
(327, 40, 33, 14, 'APPROVED', 0, 'dgdfg', NULL, '2026-09-25 09:57:24', '2026-09-25 04:56:52'),
(328, 40, 13, 4, 'APPROVED', 0, 'fdfh', NULL, '2026-09-25 09:57:40', '2026-09-25 04:56:52'),
(329, 40, 14, 10, 'REJECTED', 0, 'hj', NULL, '2026-09-25 09:58:21', '2026-09-25 04:56:52'),
(330, 40, 15, 6, 'NOT_REQUIRED', 0, NULL, NULL, NULL, '2026-09-25 04:56:52'),
(331, 40, 34, 16, 'NOT_REQUIRED', 0, NULL, NULL, NULL, '2026-09-25 04:56:52'),
(332, 40, 24, 5, 'NOT_REQUIRED', 0, NULL, NULL, NULL, '2026-09-25 04:56:52');

-- --------------------------------------------------------

--
-- Table structure for table `project_attachments`
--

CREATE TABLE `project_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `uploaded_by` int(10) UNSIGNED NOT NULL,
  `original_file_name` varchar(255) NOT NULL,
  `stored_file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_categories`
--

CREATE TABLE `project_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_code` varchar(20) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `criteria` text NOT NULL,
  `status` enum('ACTIVE','INACTIVE') DEFAULT 'ACTIVE',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_categories`
--

INSERT INTO `project_categories` (`id`, `category_code`, `category_name`, `criteria`, `status`, `created_at`) VALUES
(1, 'INFRA', 'Infra', 'Hardware, network, server, connectivity related', 'ACTIVE', '2026-09-03 06:10:42'),
(2, 'APP', 'Application', 'New tool, portal or feature in an existing non-SAP system', 'ACTIVE', '2026-09-03 06:10:42'),
(3, 'SAP', 'SAP (ERP)', 'Any change, customization, new module or report within SAP', 'ACTIVE', '2026-09-03 06:10:42'),
(4, 'DB', 'DB/Server', 'Database performance, server configuration, access, security or migration', 'ACTIVE', '2026-09-03 06:10:42');

-- --------------------------------------------------------

--
-- Table structure for table `project_comments`
--

CREATE TABLE `project_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_history`
--

CREATE TABLE `project_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `action` varchar(100) NOT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `approval_id` int(10) UNSIGNED DEFAULT NULL,
  `workflow_id` int(10) UNSIGNED DEFAULT NULL,
  `technical_review_id` int(10) UNSIGNED DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_history`
--

INSERT INTO `project_history` (`id`, `project_id`, `user_id`, `action`, `old_status`, `new_status`, `comments`, `approval_id`, `workflow_id`, `technical_review_id`, `action_date`) VALUES
(338, 31, 3, 'PROJECT_CREATED', NULL, 'SUBMITTED', NULL, NULL, NULL, NULL, '2026-09-21 06:14:26'),
(339, 31, 9, 'APPROVAL_APPROVED', 'SUBMITTED', 'SUBMITTED', 'fdghfdh', 283, 25, 51, '2026-09-21 06:15:17'),
(340, 31, 5, 'APPROVAL_APPROVED', 'SUBMITTED', 'APPROVED', 'afds', 284, 26, NULL, '2026-09-21 06:15:42'),
(341, 32, 3, 'PROJECT_CREATED', NULL, 'SUBMITTED', NULL, NULL, NULL, NULL, '2026-09-21 06:21:10'),
(342, 32, 9, 'APPROVAL_APPROVED', 'SUBMITTED', 'SUBMITTED', 'sdfaafafdasdf', 285, 25, 52, '2026-09-21 06:25:45'),
(343, 32, 5, 'PROJECT_RETURNED_TO_PREVIOUS_APPROVER', 'SUBMITTED', 'SUBMITTED', 'sdfsd', 286, 26, NULL, '2026-09-21 06:25:59'),
(344, 32, 9, 'APPROVAL_APPROVED', 'SUBMITTED', 'SUBMITTED', 'dfsfadfs', 285, 25, 53, '2026-09-21 06:26:43'),
(345, 32, 5, 'APPROVAL_APPROVED', 'SUBMITTED', 'APPROVED', 'sd', 286, 26, NULL, '2026-09-21 06:28:18'),
(347, 34, 3, 'PROJECT_CREATED', NULL, 'SUBMITTED', NULL, NULL, NULL, NULL, '2026-09-21 07:57:34'),
(348, 34, 14, 'APPROVAL_APPROVED', 'SUBMITTED', 'SUBMITTED', 'asdasdasd', 287, 19, NULL, '2026-09-21 07:58:38'),
(349, 34, 4, 'PROJECT_RETURNED_TO_PREVIOUS_APPROVER', 'SUBMITTED', 'SUBMITTED', 'reassdsd', 288, 12, NULL, '2026-09-21 07:59:19'),
(350, 34, 14, 'APPROVAL_APPROVED', 'SUBMITTED', 'SUBMITTED', 'qrqer', 287, 19, NULL, '2026-09-21 07:59:31'),
(351, 34, 4, 'APPROVAL_APPROVED', 'SUBMITTED', 'SUBMITTED', 'dsffdsfsdggdh', 288, 12, NULL, '2026-09-21 07:59:42'),
(352, 34, 8, 'APPROVAL_APPROVED', 'SUBMITTED', 'SUBMITTED', 'terterwtrt', 289, 20, 54, '2026-09-21 08:00:05'),
(353, 34, 5, 'APPROVAL_APPROVED', 'SUBMITTED', 'SUBMITTED', 'wttewtr', 290, 31, NULL, '2026-09-21 08:00:25'),
(354, 34, 15, 'APPROVAL_APPROVED', 'SUBMITTED', 'APPROVED', '', 291, 32, NULL, '2026-09-21 08:00:42'),
(355, 35, 3, 'PROJECT_CREATED', NULL, 'SUBMITTED', NULL, NULL, NULL, NULL, '2026-09-21 09:34:40'),
(356, 35, 7, 'APPROVAL_APPROVED', 'SUBMITTED', 'SUBMITTED', 'done with conditions', 292, 16, 55, '2026-09-21 09:36:28'),
(357, 35, 5, 'PROJECT_RETURNED_TO_PREVIOUS_APPROVER', 'SUBMITTED', 'SUBMITTED', 'review again and reduce days.', 293, 17, NULL, '2026-09-21 09:37:03'),
(358, 35, 7, 'APPROVAL_APPROVED', 'SUBMITTED', 'SUBMITTED', 'done and fiixed', 292, 16, 56, '2026-09-21 09:38:21'),
(359, 35, 5, 'APPROVAL_APPROVED', 'SUBMITTED', 'SUBMITTED', 'done approved from my side.', 293, 17, NULL, '2026-09-21 09:47:15'),
(360, 35, 15, 'APPROVAL_APPROVED', 'SUBMITTED', 'APPROVED', 'all fine.', 294, 30, NULL, '2026-09-21 09:48:11'),
(361, 36, 3, 'PROJECT_CREATED', NULL, 'SUBMITTED', NULL, NULL, NULL, NULL, '2026-09-22 03:08:02'),
(362, 36, 16, 'PROJECT_REJECTED', 'SUBMITTED', 'REJECTED', '', 295, 19, NULL, '2026-09-22 03:09:55'),
(364, 38, 3, 'PROJECT_CREATED', NULL, 'SUBMITTED', NULL, NULL, NULL, NULL, '2026-09-24 02:56:02'),
(365, 38, 16, 'APPROVAL_APPROVED', 'SUBMITTED', 'SUBMITTED', 'rdurdu', 300, 33, NULL, '2026-09-24 02:58:25'),
(366, 38, 5, 'PROJECT_RETURNED_TO_PREVIOUS_APPROVER', 'SUBMITTED', 'SUBMITTED', '', 301, 13, NULL, '2026-09-24 03:51:36'),
(367, 38, 16, 'PROJECT_RETURNED_TO_REQUESTOR', 'SUBMITTED', 'RETURNED_FOR_REVISION', '', 300, 33, NULL, '2026-09-24 03:51:50'),
(368, 38, 3, 'PROJECT_RESUBMITTED', 'RETURNED_FOR_REVISION', 'SUBMITTED', NULL, NULL, NULL, NULL, '2026-09-24 03:52:12'),
(369, 38, 16, 'PROJECT_RETURNED_TO_REQUESTOR', 'SUBMITTED', 'RETURNED_FOR_REVISION', '', 305, 33, NULL, '2026-09-24 03:57:59'),
(370, 38, 3, 'PROJECT_RESUBMITTED', 'RETURNED_FOR_REVISION', 'SUBMITTED', NULL, NULL, NULL, NULL, '2026-09-24 03:58:19'),
(371, 38, 16, 'PROJECT_RETURNED_TO_REQUESTOR', 'SUBMITTED', 'RETURNED_FOR_REVISION', '', 309, 33, NULL, '2026-09-24 03:58:59'),
(372, 38, 3, 'PROJECT_RESUBMITTED', 'RETURNED_FOR_REVISION', 'SUBMITTED', NULL, NULL, NULL, NULL, '2026-09-24 04:00:44'),
(373, 38, 10, 'PROJECT_RETURNED_TO_REQUESTOR', 'SUBMITTED', 'RETURNED_FOR_REVISION', '', 313, 14, NULL, '2026-09-24 10:08:12'),
(374, 38, 3, 'PROJECT_RESUBMITTED', 'RETURNED_FOR_REVISION', 'SUBMITTED', NULL, NULL, NULL, NULL, '2026-09-24 10:08:28'),
(375, 39, 3, 'PROJECT_CREATED', NULL, 'SUBMITTED', NULL, NULL, NULL, NULL, '2026-09-24 10:08:58'),
(376, 39, 14, 'APPROVAL_APPROVED', 'SUBMITTED', 'SUBMITTED', 'sfdasdasd', 321, 33, NULL, '2026-09-24 10:09:41'),
(377, 39, 4, 'APPROVAL_APPROVED', 'SUBMITTED', 'SUBMITTED', 'tuyuuuu', 322, 13, NULL, '2026-09-24 10:09:54'),
(378, 39, 10, 'APPROVAL_APPROVED', 'SUBMITTED', 'SUBMITTED', 'gdsfgdfgdfg', 323, 14, 57, '2026-09-24 10:10:23'),
(379, 39, 6, 'APPROVAL_APPROVED', 'SUBMITTED', 'SUBMITTED', 'asdasd', 324, 15, NULL, '2026-09-24 10:10:49'),
(380, 39, 16, 'APPROVAL_APPROVED', 'SUBMITTED', 'SUBMITTED', 'tfiuft', 325, 34, NULL, '2026-09-24 10:11:31'),
(381, 39, 5, 'APPROVAL_APPROVED', 'SUBMITTED', 'APPROVED', 'gyoiyiiuuff', 326, 24, NULL, '2026-09-24 10:11:43'),
(382, 40, 3, 'PROJECT_CREATED', NULL, 'SUBMITTED', NULL, NULL, NULL, NULL, '2026-09-25 04:56:52'),
(383, 40, 14, 'APPROVAL_APPROVED', 'SUBMITTED', 'SUBMITTED', 'dgdfg', 327, 33, NULL, '2026-09-25 04:57:24'),
(384, 40, 4, 'APPROVAL_APPROVED', 'SUBMITTED', 'SUBMITTED', 'fdfh', 328, 13, NULL, '2026-09-25 04:57:40'),
(385, 40, 10, 'PROJECT_REJECTED', 'SUBMITTED', 'REJECTED', 'hj', 329, 14, NULL, '2026-09-25 04:58:21');

-- --------------------------------------------------------

--
-- Table structure for table `project_timeline_phases`
--

CREATE TABLE `project_timeline_phases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `phase_order` int(10) UNSIGNED NOT NULL,
  `phase_name` varchar(150) NOT NULL,
  `phase_description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `can_define_workflow` tinyint(1) NOT NULL DEFAULT 0,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `can_define_workflow`, `description`, `created_at`) VALUES
(1, 'Requestor', 0, 'Creates and submits project concept papers', '2026-09-03 06:06:18'),
(2, 'General Manager', 0, 'Approves Application and SAP projects for the requesting department', '2026-09-03 06:06:18'),
(3, 'Team Lead', 0, 'Provides final IT approval for projects', '2026-09-03 06:06:18'),
(5, 'Technical Team', 0, 'Performs technical review for Infra, Application, SAP and DB/Server projects', '2026-09-03 06:06:18'),
(6, 'Administrator', 0, 'Manages users, departments, categories and workflows', '2026-09-03 06:06:18'),
(10, 'National Manager', 1, NULL, '2026-09-16 04:32:29'),
(11, 'CEO', 0, NULL, '2026-09-17 04:24:56');

-- --------------------------------------------------------

--
-- Table structure for table `stakeholders`
--

CREATE TABLE `stakeholders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `stakeholder_type` enum('SPONSOR','USER','TECHNICAL_TEAM','APPROVER') NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `stakeholder_name` varchar(150) NOT NULL,
  `department_name` varchar(150) DEFAULT NULL,
  `email` varchar(190) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stakeholders`
--

INSERT INTO `stakeholders` (`id`, `project_id`, `stakeholder_type`, `user_id`, `stakeholder_name`, `department_name`, `email`, `created_at`) VALUES
(117, 31, 'SPONSOR', NULL, 'izhan', 'IT', 'it@email.com', '2026-09-21 06:14:26'),
(118, 32, 'SPONSOR', NULL, 'admin', 'IT', 'test@exampl.com', '2026-09-21 06:21:10'),
(120, 34, 'SPONSOR', NULL, 'admin', 'IT', 'test@exampl.com', '2026-09-21 07:57:34'),
(121, 35, 'SPONSOR', NULL, 'sadsad', 'Web Development', 'test@exampl.com', '2026-09-21 09:34:40'),
(122, 36, 'SPONSOR', NULL, 'admin', 'IT', 'test@exampl.com', '2026-09-22 03:08:02'),
(128, 38, 'SPONSOR', NULL, 'admin', 'IT', 'test@exampl.com', '2026-09-24 10:08:28'),
(129, 39, 'SPONSOR', NULL, 'admin', 'IT', 'test@exampl.com', '2026-09-24 10:08:58'),
(130, 40, 'SPONSOR', NULL, 'admin', 'IT', 'test@exampl.com', '2026-09-25 04:56:52');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `team_name` varchar(150) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'ACTIVE',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `department_id`, `team_name`, `status`, `created_at`) VALUES
(1, 1, 'Application', 'ACTIVE', '2026-09-16 05:54:36'),
(2, 1, 'SAP', 'ACTIVE', '2026-09-16 05:55:29'),
(5, 1, 'Infrastructure', 'ACTIVE', '2026-09-16 06:16:16'),
(8, 1, 'Database/Server', 'ACTIVE', '2026-09-16 06:16:16'),
(9, 2, 'Finance', 'ACTIVE', '2026-09-16 06:44:07');

-- --------------------------------------------------------

--
-- Table structure for table `technical_reviews`
--

CREATE TABLE `technical_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `reviewer_id` int(10) UNSIGNED NOT NULL,
  `technical_team` varchar(100) NOT NULL,
  `feasibility` enum('FEASIBLE','FEASIBLE_WITH_CONDITIONS','NOT_FEASIBLE','PENDING') NOT NULL DEFAULT 'PENDING',
  `technical_comments` text DEFAULT NULL,
  `estimated_effort` varchar(100) DEFAULT NULL,
  `expected_start_date` date DEFAULT NULL,
  `expected_end_date` date DEFAULT NULL,
  `technical_risks` text DEFAULT NULL,
  `dependencies` text DEFAULT NULL,
  `review_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `technical_reviews`
--

INSERT INTO `technical_reviews` (`id`, `project_id`, `reviewer_id`, `technical_team`, `feasibility`, `technical_comments`, `estimated_effort`, `expected_start_date`, `expected_end_date`, `technical_risks`, `dependencies`, `review_date`, `created_at`) VALUES
(51, 31, 9, 'DYNAMIC', 'FEASIBLE_WITH_CONDITIONS', 'fdghfdh', 'lots of efforts', '2026-10-01', '2026-10-10', 'dfhf', 'fdhdfghfg', '2026-09-21 11:15:17', '2026-09-21 06:15:17'),
(52, 32, 9, 'DYNAMIC', 'FEASIBLE', 'sdfaafafdasdf', '', '2026-09-21', '2026-12-03', 'afsd', 'fsdfsdf', '2026-09-21 11:25:44', '2026-09-21 06:25:44'),
(53, 32, 9, 'DYNAMIC', 'FEASIBLE_WITH_CONDITIONS', 'dfsfadfs', 'lots of efforts', '2026-09-21', '2026-09-29', 'ssdfsdf', 'fsfsdfas', '2026-09-21 11:26:43', '2026-09-21 06:26:43'),
(54, 34, 8, 'DYNAMIC', 'FEASIBLE_WITH_CONDITIONS', 'terterwtrt', 'lots of efforts', '2026-09-21', '2026-09-30', 'erwer', 'weteryewer', '2026-09-21 13:00:05', '2026-09-21 08:00:05'),
(55, 35, 7, 'DYNAMIC', 'FEASIBLE_WITH_CONDITIONS', 'done with conditions', 'lots of efforts', '2026-12-10', '2027-08-31', 'very very very much', 'lot lots lots of effforts', '2026-09-21 14:36:28', '2026-09-21 09:36:28'),
(56, 35, 7, 'DYNAMIC', 'FEASIBLE_WITH_CONDITIONS', 'done and fiixed', 'lots of efforts', '2026-09-24', '2027-01-19', 'asfsasaf', 'sadfasdfdsfasdfwegw', '2026-09-21 14:38:21', '2026-09-21 09:38:21'),
(57, 39, 10, 'DYNAMIC', 'FEASIBLE_WITH_CONDITIONS', 'gdsfgdfgdfg', 'dsfggfg', '2026-10-10', '2026-11-26', 'dsgdfsg', 'dsgsdfgd', '2026-09-24 15:10:23', '2026-09-24 10:10:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password` varchar(255) NOT NULL,
  `department_id` int(10) UNSIGNED DEFAULT NULL,
  `team_id` int(10) UNSIGNED DEFAULT NULL,
  `role_id` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') DEFAULT 'ACTIVE',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_id`, `name`, `email`, `password`, `department_id`, `team_id`, `role_id`, `status`, `created_at`, `updated_at`) VALUES
(2, 'ADM001', 'Maqbool Hassan', 'admin@example.com', '$2y$10$Nr4Dfi3hgP3Q2Da3RK.USuoI8hCl/uhJR2rdT6X1bIHU0tI1YTorO', NULL, NULL, 6, 'ACTIVE', '2026-09-03 09:07:57', '2026-09-22 05:18:34'),
(3, 'REQ001', 'Omer Ahsan', 'requestor@example.com', '$2y$10$6F1dd96m7IabJpzZMsdNGOnPu8g8T8aBaeSotk9QZ4Tb.t7W/MJly', 2, NULL, 1, 'ACTIVE', '2026-09-03 09:07:57', '2026-09-24 10:07:33'),
(4, 'DHD001', 'Ayaz Butt', 'finance.head@example.com', '$2y$10$sSd9X.Mo9jVw26NAsyDUV.zg7AZOc9WqR/2PVXW0wtEjOPrOBXEHe', 2, NULL, 2, 'ACTIVE', '2026-09-03 09:07:57', '2026-09-11 12:01:57'),
(5, 'ITH001', 'Raheel Bangash', 'it.head@example.com', '$2y$10$V1hy7hpWVtC.8TSR5NxtTuOrz8zeMBEld8N2NUoPxNQfvTHaLY4l.', 1, NULL, 2, 'ACTIVE', '2026-09-03 09:07:57', '2026-09-15 10:08:17'),
(6, 'SAP001', 'Mubashir Tariq', 'sap.lead@example.com', '$2y$10$Ihqspvvvrk2JMYNMPBQGIeOQMlCgLlptnOI3c71RB3heX2dV0uCVC', 1, 2, 3, 'ACTIVE', '2026-09-03 09:07:57', '2026-09-16 06:16:16'),
(7, 'INF001', 'jabir sheikh', 'infra@example.com', '$2y$10$3.C3YBJb55bqiCq7MVCXr.HXSV3FZ2L2OIW49ta1o61c/ihUzkVt6', 1, 5, 5, 'ACTIVE', '2026-09-03 09:07:57', '2026-09-16 06:16:16'),
(8, 'APP001', 'Majid Hussain', 'app@example.com', '$2y$10$1wUDdzsd27cCCvzCV1wyleu2nohJ6rJiIDQoIwXxRef/aXhDyXZKS', 1, 1, 5, 'ACTIVE', '2026-09-03 09:07:57', '2026-09-16 06:16:16'),
(9, 'DB001', 'Kashif Mahmood', 'db@example.com', '$2y$10$BIRh4ev45/v/lkXzC5PVkeW30AJDTJo4tD0Qo.2b.8wDU1xw9B27S', 1, 8, 5, 'ACTIVE', '2026-09-03 09:07:57', '2026-09-16 06:16:16'),
(10, 'SAP002', 'Ali Ahmed', 'sap.tech@example.com', '$2y$10$NbIpsbW5dOXkc28t9Hb32.lkyWW8gmpCcHLRjzX.a9rWL.HC.p2Yy', 1, 2, 5, 'ACTIVE', '2026-09-03 09:07:58', '2026-09-16 06:07:00'),
(11, 'EMP001', 'fahad', 'test@example.com', '$2y$10$f2f2KbC8sL/ctpthxXnFoO3tFApdv0SVF6CBe1kbObfJj6NBYJ6ri', 1, NULL, 5, 'ACTIVE', '2026-09-08 05:22:10', '2026-09-16 03:02:35'),
(12, 'SAL001', 'Hassaan Shiekh', 'salereq@example.com', '$2y$10$YG3ui8ARvP3vVxPdPS6.9e0zccdEw8vlEG2orjn4Gw4nuqZdE5blW', 4, NULL, 1, 'ACTIVE', '2026-09-15 10:27:52', '2026-09-15 10:27:52'),
(13, 'PROD001', 'Raheem Khan', 'prod.lead@example.com', '$2y$10$t4hykUMUS0.j2Ey0yDkRe.TPROEbPXymlKZbbINCdXaK96urySMHu', 4, NULL, 2, 'ACTIVE', '2026-09-16 02:54:54', '2026-09-16 03:27:42'),
(14, 'FIN004', 'fdaf', 'finance.nm@example.com', '$2y$10$OsYhFxPcaiqSi74QQhFO6OD93Lwrc.sHzCggAE78vOhrk11F87hcu', 2, NULL, 10, 'ACTIVE', '2026-09-16 05:28:05', '2026-09-21 06:56:14'),
(15, 'CEO001', 'Ahmed Omerr', 'ceo@example.com', '$2y$10$bvgvLjnD1.wlFKuR3bbwOeBJbMyY7PIjWf0TNDJIy.kbtNkahwhNC', NULL, NULL, 11, 'ACTIVE', '2026-09-17 04:37:00', '2026-09-17 05:09:47'),
(16, 'IT1154', 'Jaffar Sheikh', 'it.nm@example.com', '$2y$10$FJEUoWrwRW0ZCXsmOqlTi.TNx6dApDaorUTud8o7KVKwQcz6RJdz2', 1, NULL, 10, 'ACTIVE', '2026-09-21 06:53:03', '2026-09-21 06:53:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approval_workflows`
--
ALTER TABLE `approval_workflows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_department_category_step` (`department_id`,`category_id`,`step_order`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `idx_aw_category_id` (`category_id`),
  ADD KEY `idx_aw_department_id` (`department_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `department_name` (`department_name`),
  ADD UNIQUE KEY `department_code` (`department_code`);

--
-- Indexes for table `department_heads`
--
ALTER TABLE `department_heads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_department_head` (`department_id`),
  ADD KEY `fk_department_head_user` (`user_id`);

--
-- Indexes for table `department_project_categories`
--
ALTER TABLE `department_project_categories`
  ADD PRIMARY KEY (`department_id`,`category_id`),
  ADD UNIQUE KEY `uq_category_owner` (`category_id`),
  ADD KEY `idx_dpc_team_id` (`team_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_id` (`project_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `requestor_id` (`requestor_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `project_approvals`
--
ALTER TABLE `project_approvals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_id` (`project_id`,`workflow_id`),
  ADD KEY `workflow_id` (`workflow_id`),
  ADD KEY `approver_id` (`approver_id`);

--
-- Indexes for table `project_attachments`
--
ALTER TABLE `project_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `project_categories`
--
ALTER TABLE `project_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_code` (`category_code`);

--
-- Indexes for table `project_comments`
--
ALTER TABLE `project_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `project_history`
--
ALTER TABLE `project_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `project_timeline_phases`
--
ALTER TABLE `project_timeline_phases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `stakeholders`
--
ALTER TABLE `stakeholders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_team_department_name` (`department_id`,`team_name`),
  ADD KEY `idx_teams_department` (`department_id`);

--
-- Indexes for table `technical_reviews`
--
ALTER TABLE `technical_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `reviewer_id` (`reviewer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `idx_users_team_id` (`team_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approval_workflows`
--
ALTER TABLE `approval_workflows`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `department_heads`
--
ALTER TABLE `department_heads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `project_approvals`
--
ALTER TABLE `project_approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=333;

--
-- AUTO_INCREMENT for table `project_attachments`
--
ALTER TABLE `project_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_categories`
--
ALTER TABLE `project_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `project_comments`
--
ALTER TABLE `project_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_history`
--
ALTER TABLE `project_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=386;

--
-- AUTO_INCREMENT for table `project_timeline_phases`
--
ALTER TABLE `project_timeline_phases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `stakeholders`
--
ALTER TABLE `stakeholders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `technical_reviews`
--
ALTER TABLE `technical_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approval_workflows`
--
ALTER TABLE `approval_workflows`
  ADD CONSTRAINT `approval_workflows_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `project_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `approval_workflows_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `fk_approval_workflows_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `department_heads`
--
ALTER TABLE `department_heads`
  ADD CONSTRAINT `fk_department_head_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_department_head_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `department_project_categories`
--
ALTER TABLE `department_project_categories`
  ADD CONSTRAINT `fk_dpc_category` FOREIGN KEY (`category_id`) REFERENCES `project_categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dpc_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dpc_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `project_categories` (`id`),
  ADD CONSTRAINT `projects_ibfk_2` FOREIGN KEY (`requestor_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `projects_ibfk_3` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);

--
-- Constraints for table `project_approvals`
--
ALTER TABLE `project_approvals`
  ADD CONSTRAINT `project_approvals_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_approvals_ibfk_2` FOREIGN KEY (`workflow_id`) REFERENCES `approval_workflows` (`id`),
  ADD CONSTRAINT `project_approvals_ibfk_3` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `project_attachments`
--
ALTER TABLE `project_attachments`
  ADD CONSTRAINT `project_attachments_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_attachments_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `project_comments`
--
ALTER TABLE `project_comments`
  ADD CONSTRAINT `project_comments_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `project_history`
--
ALTER TABLE `project_history`
  ADD CONSTRAINT `project_history_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_history_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `project_timeline_phases`
--
ALTER TABLE `project_timeline_phases`
  ADD CONSTRAINT `project_timeline_phases_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stakeholders`
--
ALTER TABLE `stakeholders`
  ADD CONSTRAINT `stakeholders_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stakeholders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `fk_teams_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `technical_reviews`
--
ALTER TABLE `technical_reviews`
  ADD CONSTRAINT `technical_reviews_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `technical_reviews_ibfk_2` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
