-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2022 at 08:07 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `packult`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT 0 COMMENT 'phone_code',
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `admin_name`, `email`, `country_id`, `phone`, `password`, `address`, `role_id`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'test@mypcot.com', 1, '9090909090', 'c8eaee21df1cba8d7fdc2c0a47894af6', NULL, 1, '1', 1, 0, NULL, '2022-01-30 12:12:01', '2022-04-21 06:03:48'),
(2, 'Admin Manager', 'manager@admin.com', 1, '7977586379', 'e10adc3949ba59abbe56e057f20f883e', 'Borivali', 2, '1', 0, 1, NULL, '2022-01-31 15:10:41', '2022-04-05 15:09:48'),
(3, 'Sagar Thokal', 'sagar@gmail.com', 1, '1234567890', '26bf3df6ebf89c0c85ef026c26610aea', 'Borivali Tata Power', 3, '1', 0, 0, NULL, '2022-02-02 00:52:01', '2022-02-15 18:03:53'),
(4, 'Pradyumn', 'pradyumn@mypcot.com', 1, '1234567891', 'a6942ac343d25a25e732e839246bc98d', 'Santacruz', 2, '0', 1, 0, NULL, '2022-04-21 06:06:31', '2022-04-21 06:06:44');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_thumb_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Customer' COMMENT 'customer|vendor',
  `seo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `banner_image`, `banner_thumb_image`, `type`, `seo_url`, `meta_title`, `meta_description`, `meta_keyword`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Food', 'banner_1.jpg', 'banner_thumb_1.jpg', 'Customer', 'food', NULL, NULL, NULL, '1', 1, 1, NULL, '2022-03-28 05:27:46', '2022-03-29 14:22:39'),
(2, 'Foods', 'banner_2.jpg', 'banner_thumb_2.jpg', 'Vendor', 'foods', NULL, NULL, NULL, '0', 1, 1, NULL, '2022-03-28 05:34:26', '2022-03-29 14:22:59'),
(3, 'demo', NULL, NULL, 'Customer', 'demo', NULL, NULL, NULL, '0', 1, 0, NULL, '2022-03-31 15:49:22', '2022-03-31 15:49:22'),
(4, 'rr', NULL, NULL, 'Customer', 'rr', NULL, NULL, NULL, '0', 1, 0, NULL, '2022-03-31 15:49:42', '2022-03-31 15:49:42'),
(5, 'tts', NULL, NULL, 'Vendor', 'tts', NULL, NULL, NULL, '0', 1, 0, NULL, '2022-03-31 15:52:13', '2022-03-31 15:52:13'),
(6, 'ttsqq', NULL, NULL, 'Vendor', 'ttsqq', NULL, NULL, NULL, '0', 1, 0, NULL, '2022-03-31 15:58:03', '2022-03-31 15:58:03'),
(7, 'ttsqqq', NULL, NULL, 'Vendor', 'ttsqqq', NULL, NULL, NULL, '0', 1, 0, NULL, '2022-03-31 15:58:31', '2022-03-31 15:58:31'),
(8, 'demo12', NULL, NULL, 'Customer', 'demo12', NULL, NULL, NULL, '0', 1, 0, NULL, '2022-03-31 16:00:58', '2022-03-31 16:00:58');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_thumb_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `category_image`, `category_thumb_image`, `seo_url`, `meta_title`, `meta_description`, `meta_keyword`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Food', NULL, NULL, NULL, NULL, NULL, NULL, '0', 0, 0, NULL, '2022-03-21 11:59:03', '2022-04-01 15:05:02'),
(2, 'Soft Food', 'category_2.jpg', 'category_thumb_2.jpg', NULL, NULL, NULL, NULL, '0', 0, 0, NULL, '2022-03-25 13:50:53', '2022-03-29 11:58:11'),
(3, 'Fast Food', 'category_3.jpg', 'category_thumb_3.jpg', NULL, NULL, NULL, NULL, '0', 0, 0, NULL, '2022-03-28 13:44:52', '2022-03-28 13:44:52'),
(4, 'Solid bis', 'category_4.jpg', 'category_thumb_4.jpg', NULL, NULL, NULL, NULL, '0', 0, 0, NULL, '2022-03-31 12:16:15', '2022-03-31 12:16:15'),
(5, 'Test Category', 'category_5.jpg', 'category_thumb_5.jpg', NULL, NULL, NULL, NULL, '0', 1, 0, NULL, '2022-04-19 07:20:38', '2022-04-19 07:20:38'),
(6, 'Food test', 'category_6.jpg', 'category_thumb_6.jpg', NULL, NULL, NULL, NULL, '0', 1, 0, NULL, '2022-04-19 07:25:44', '2022-04-19 07:25:45');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `city_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_id` int(11) NOT NULL DEFAULT 0,
  `country_id` int(11) NOT NULL DEFAULT 0,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `city_name`, `state_id`, `country_id`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Mumbai', 1, 1, '0', 0, 1, NULL, '2022-03-24 06:58:59', '2022-04-05 11:52:22'),
(2, 'Pune', 1, 1, '0', 0, 0, NULL, '2022-04-05 11:42:20', '2022-04-05 11:42:20');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_logo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_thumb_logo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website_url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `incorporation_year` int(11) NOT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatapp_no` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `company_name`, `company_logo`, `company_thumb_logo`, `website_url`, `incorporation_year`, `short_description`, `whatapp_no`, `seo_url`, `meta_title`, `meta_description`, `meta_keyword`, `is_featured`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Mypcot', NULL, NULL, NULL, 2022, NULL, NULL, 'mypcot', NULL, NULL, NULL, '0', '0', 0, 0, NULL, '2022-03-21 12:00:09', '2022-03-21 12:00:09');

-- --------------------------------------------------------

--
-- Table structure for table `contact_enquiries`
--

CREATE TABLE `contact_enquiries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_code` char(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_length` char(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_id` int(11) NOT NULL DEFAULT 0,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `country_name`, `phone_code`, `phone_length`, `currency_id`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'India', '91', '10', 1, '1', 1, 0, NULL, '2022-03-22 06:25:20', '2022-03-23 08:49:19'),
(2, 'America', '1', '10', 2, '1', 0, 0, NULL, '2022-03-23 08:56:45', '2022-03-23 08:56:51');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `currency_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_symbol` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` char(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate` decimal(8,3) NOT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `currency_name`, `currency_symbol`, `currency_code`, `exchange_rate`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Rupees', 'INR', 'INR', '74.000', '1', 0, 1, NULL, '2022-03-22 06:25:00', '2022-04-05 13:29:51'),
(2, 'Dollar', 'USD', 'USD', '1.000', '1', 0, 0, NULL, '2022-03-23 08:56:25', '2022-03-23 08:56:30'),
(3, 'Rupees Test', 'INR1', 'INR1', '74.000', '0', 1, 0, NULL, '2022-04-21 05:05:07', '2022-04-21 05:05:07');

-- --------------------------------------------------------

--
-- Table structure for table `customer_enquiries`
--

CREATE TABLE `customer_enquiries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `enquiry_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general' COMMENT 'general|engine',
  `order_id` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `sub_category_id` int(11) NOT NULL DEFAULT 0,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `shelf_life` int(11) NOT NULL DEFAULT 0,
  `product_weight` decimal(8,3) NOT NULL,
  `storage_condition_id` int(11) NOT NULL DEFAULT 0,
  `packaging_machine_id` int(11) NOT NULL DEFAULT 0,
  `product_form_id` int(11) NOT NULL DEFAULT 0,
  `packing_type_id` int(11) NOT NULL DEFAULT 0,
  `packaging_treatment_id` int(11) NOT NULL DEFAULT 0,
  `address` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int(11) NOT NULL DEFAULT 0,
  `city_id` int(11) NOT NULL DEFAULT 0,
  `state_id` int(11) NOT NULL DEFAULT 0,
  `pincode` int(11) NOT NULL DEFAULT 0,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `quote_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'enquired' COMMENT 'enquired|map_to_vendor|accept_cust|closed',
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `seo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_enquiries`
--

INSERT INTO `customer_enquiries` (`id`, `description`, `enquiry_type`, `order_id`, `category_id`, `sub_category_id`, `product_id`, `shelf_life`, `product_weight`, `storage_condition_id`, `packaging_machine_id`, `product_form_id`, `packing_type_id`, `packaging_treatment_id`, `address`, `country_id`, `city_id`, `state_id`, `pincode`, `quantity`, `user_id`, `quote_type`, `status`, `seo_url`, `meta_title`, `meta_description`, `meta_keyword`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'testing', 'general', 1245, 1, 2, 1, 25, '25.000', 1, 1, 2, 2, 1, 'khar', 1, 1, 1, 543562, 15, 1, 'map_to_vendor', '0', NULL, NULL, NULL, NULL, NULL, '2022-04-13 08:36:46', '2022-04-13 08:36:46'),
(2, 'test', 'general', 1245, 2, 1, 1, 25, '25.000', 1, 1, 2, 1, 1, 'Khar', 1, 1, 1, 54356, 15, 1, 'map_to_vendor', '0', NULL, NULL, NULL, NULL, NULL, '2022-04-13 08:40:29', '2022-04-13 08:40:29');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `type`, `value`, `created_at`, `updated_at`) VALUES
(1, 'about_us', '<p>new<strong> data</strong></p>', NULL, '2022-04-20 12:12:01'),
(2, 'terms_condition', '<p>sdsgsdg &nbsp;<i>uuuiuiu</i></p>', NULL, '2022-04-16 12:40:37'),
(3, 'privacy_policy', '<p>fsdfklk;lfk ;d f;skf <strong>foiusdf </strong>oisudfiou 656</p>', NULL, '2022-04-05 08:14:34'),
(4, 'fb_link', 'https://www.facebook.com/1', NULL, '2022-04-05 08:17:00'),
(5, 'insta_link', 'https://www.instagram.com/2', NULL, '2022-04-05 08:17:00'),
(6, 'twitter_link', 'https://www.twitter.com/3', NULL, '2022-04-05 08:17:00'),
(7, 'meta_title', NULL, NULL, '2022-04-04 11:21:58'),
(8, 'meta_keywords', NULL, NULL, '2022-04-04 11:21:58'),
(9, 'meta_description', NULL, NULL, '2022-04-04 11:21:58'),
(10, 'system_email', 'info@mypcot.com', NULL, '2022-04-04 11:21:58'),
(11, 'system_name', 'RRPL', NULL, NULL),
(12, 'trigger_email_notification', '1', NULL, '2022-04-14 08:13:07'),
(13, 'trigger_sms_notification', '1', NULL, '2022-03-31 04:42:07'),
(14, 'trigger_whatsapp_notification', '1', NULL, '2022-03-31 04:42:05');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language_code` char(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `language_title`, `language_code`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', '1', 0, 0, NULL, NULL, NULL),
(2, 'Hindi', 'hin', '1', 0, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `measurement_units`
--

CREATE TABLE `measurement_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unit_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_symbol` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `measurement_units`
--

INSERT INTO `measurement_units` (`id`, `unit_name`, `unit_symbol`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Kilogram', 'kg', '0', 1, 1, NULL, '2022-04-12 14:33:32', '2022-04-12 14:33:54');

-- --------------------------------------------------------

--
-- Table structure for table `message_emails`
--

CREATE TABLE `message_emails` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mail_key` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` enum('all','customer','vendor') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all' COMMENT 'all|customer|vendor',
  `language_id` int(11) NOT NULL DEFAULT 0,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cc_email` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `trigger` enum('both','admin','batch') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'both',
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message_emails`
--

INSERT INTO `message_emails` (`id`, `mail_key`, `user_type`, `language_id`, `title`, `from_name`, `from_email`, `to_name`, `cc_email`, `subject`, `label`, `content`, `trigger`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'USER_FORGOT_PASS', 'all', 0, 'User- Forgot Password', 'Rosefield B2B', 'noreply@rosefieldb2b.com', '', '', 'Forgot Password - Rosefield B2B new', 'demo  new', '<p>new tester data packult new data test successfull</p>', 'both', '1', 0, 1, NULL, NULL, '2022-04-20 12:27:41'),
(2, 'WEBINAR_REMINDER_REGI_USERS', 'all', 0, 'Reminder template for webinar', 'Rosefield B2B', 'noreply@rosefieldb2b.com', NULL, NULL, 'Reminder: {webinar_name} starts soon, hope to see you there', NULL, '<table style=\"width:100%;background-color:#fff\">\r\n', 'both', '1', 0, 0, NULL, NULL, '2022-04-20 08:29:00'),
(3, 'NEW_QUERY_INFORM_ADMIN', 'all', 0, 'Inform Admin team for new query entered from the website', '', '', NULL, NULL, 'There is a new query added from the website, I understand you want to have a look on it', 'Rosefield B2B', '<figure class=\"table\"><table><tbody><tr><td><i><strong>Testing Packult Demo</strong></i></td></tr></tbody></table></figure>', 'both', '0', 0, 1, NULL, NULL, '2022-04-20 08:23:11');

-- --------------------------------------------------------

--
-- Table structure for table `message_notifications`
--

CREATE TABLE `message_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_type` enum('all','customer','vendor') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all' COMMENT 'all|customer|vendor',
  `language_id` int(11) NOT NULL DEFAULT 0,
  `page_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('M','F','All') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'All',
  `notification_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notification_thumb_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notification_date` datetime DEFAULT NULL,
  `trigger` enum('both','admin','batch') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'both',
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message_notifications`
--

INSERT INTO `message_notifications` (`id`, `user_type`, `language_id`, `page_name`, `title`, `body`, `gender`, `notification_image`, `notification_thumb_image`, `notification_date`, `trigger`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'customer', 0, NULL, 'Test Notification edit', 'Testing notification edit', 'All', 'notification_1.jpg', 'notification_thumb_1.jpg', '2022-04-19 00:33:45', 'both', '1', 1, 1, NULL, '2022-04-19 18:59:25', '2022-04-19 19:19:09'),
(2, 'all', 0, NULL, 'Test Notifications', 'Testing notification2', 'All', 'notification_2.jpg', 'notification_thumb_2.jpg', NULL, 'both', '0', 1, 0, NULL, '2022-04-19 19:00:56', '2022-04-19 19:00:57');

-- --------------------------------------------------------

--
-- Table structure for table `message_sms`
--

CREATE TABLE `message_sms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` enum('all','customer','vendor') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all' COMMENT 'all|customer|vendor',
  `language_id` int(11) NOT NULL DEFAULT 0,
  `params` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operation` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `trigger` enum('both','admin','batch') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'both',
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message_sms`
--

INSERT INTO `message_sms` (`id`, `type`, `user_type`, `language_id`, `params`, `operation`, `message`, `trigger`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'text', 'all', 1, 'customername', 'customer_registration', 'Dear $$customername$$, Thank you for registering with us.', 'both', '1', 0, 0, NULL, NULL, '2022-04-18 06:46:20'),
(2, 'text', 'all', 2, 'customername', 'customer_registration', 'Dear $$customername$$, Thank you for registering with us.', 'both', '0', 0, 0, NULL, NULL, NULL),
(3, 'text', 'all', 1, 'OTP|', 'otp_request', 'Welcome to Kika, your OTP is $$OTP$$. Do not share OTP to any other person.', 'both', '0', 0, 0, NULL, NULL, NULL),
(4, 'text', 'all', 2, 'OTP|', 'otp_request', 'Welcome to Kika, your OTP is $$OTP$$. Do not share OTP to any other person.', 'both', '0', 0, 0, NULL, NULL, NULL),
(5, 'text', 'all', 1, 'amount|salesid|', 'customer_order_creation', 'Order Placed: Your order no $$salesid$$ worth amount $$amount$$ has been placed.', 'both', '0', 0, 0, NULL, NULL, NULL),
(6, 'text', 'all', 2, 'amount|salesid|', 'customer_order_creation', 'Order Placed: Your order no $$salesid$$ worth amount $$amount$$ has been placed.', 'both', '0', 0, 0, NULL, NULL, NULL),
(7, 'text', 'all', 1, 'amount|salesid|', 'customer_order_acceptance', 'Order Processed: Your order id $$salesid$$ worth amount $$amount$$ has been accepted by kika retailer and will be delivered to you very soon.', 'both', '0', 0, 0, NULL, NULL, NULL),
(8, 'text', 'all', 2, 'amount|salesid|', 'customer_order_acceptance', 'Order Processed: Your order id $$salesid$$ worth amount $$amount$$ has been accepted by kika retailer and will be delivered to you very soon.', 'both', '0', 0, 0, NULL, NULL, NULL),
(9, 'text', 'all', 1, 'amount|salesid|date|', 'customer_order_delivery', 'Order Delivered:Your order id $$salesid$$ has been delivered to you on $$date$$ by our delivery person.', 'both', '0', 0, 0, NULL, NULL, NULL),
(10, 'text', 'all', 2, 'amount|salesid|date|', 'customer_order_delivery', 'Order Delivered:Your order id $$salesid$$ has been delivered to you on $$date$$ by our delivery person.', 'both', '0', 0, 0, NULL, NULL, NULL),
(11, 'text', 'all', 1, 'retailername', 'retailer_registration', 'Thankyou $$retailername$$ you are now registered with us,kika team will reach you soon for verification process.', 'both', '0', 0, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `message_whatsapps`
--

CREATE TABLE `message_whatsapps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` enum('all','customer','vendor') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all' COMMENT 'all|customer|vendor',
  `language_id` int(11) NOT NULL DEFAULT 0,
  `params` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operation` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('M','F','All') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'All',
  `file_attached` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trigger` enum('both','admin','batch') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'both',
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message_whatsapps`
--

INSERT INTO `message_whatsapps` (`id`, `type`, `user_type`, `language_id`, `params`, `operation`, `message`, `gender`, `file_attached`, `trigger`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'text', 'all', 1, 'customername', 'customer_registration', 'Dear $$customername$$, Thank you for registering with us.', 'All', 'whatsapp_file_1.jpg', 'both', '1', 0, 1, NULL, NULL, '2022-04-19 04:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2022_03_19_101003_create_product_forms_table', 1),
(2, '2022_03_19_102043_create_packing_types_table', 2),
(3, '2022_03_19_102349_create_packaging_machines_table', 3),
(4, '2022_03_19_103432_create_packaging_treatments_table', 4),
(6, '2022_03_19_112546_create_vendors_table', 6),
(8, '2022_03_19_121417_create_subscriptions_table', 8),
(10, '2022_03_19_131720_create_admins_table', 10),
(11, '2022_03_19_132141_create_user_subscription_payments_table', 11),
(12, '2022_03_19_132237_create_roles_table', 12),
(14, '2022_03_19_132526_create_contact_enquiries_table', 14),
(15, '2022_03_19_132635_create_user_addresses_table', 15),
(16, '2022_03_19_133823_create_categories_table', 16),
(17, '2022_03_19_133934_create_permissions_table', 17),
(18, '2022_03_19_134057_create_password_resets_table', 18),
(20, '2022_03_19_135212_create_otps_table', 20),
(21, '2022_03_19_135331_create_failed_jobs_table', 21),
(22, '2022_03_19_135621_create_personal_access_tokens_table', 22),
(23, '2022_03_19_135720_create_companies_table', 23),
(24, '2022_03_19_140136_create_countries_table', 24),
(25, '2022_03_19_140211_create_states_table', 25),
(26, '2022_03_19_140335_create_cities_table', 26),
(27, '2022_03_19_140424_create_languages_table', 27),
(28, '2022_03_19_140512_create_currencies_table', 28),
(30, '2022_03_21_134342_create_vendor_material_mappings_table', 30),
(31, '2022_03_21_163853_create_sub_categories_table', 31),
(32, '2022_03_19_132412_create_customer_enquiries_table', 32),
(34, '2022_03_19_131223_create_banners_table', 34),
(39, '2022_03_19_203202_create_products_table', 39),
(40, '2022_03_19_120748_create_users_table', 40),
(41, '2022_03_24_191844_create_vendor_warehouses_table', 41),
(42, '2022_03_21_121440_create_vendor_quotations_table', 42),
(44, '2022_04_04_180439_create_storage_conditions_table', 44),
(46, '2022_03_19_130740_create_orders_table', 46),
(47, '2022_03_21_121733_create_vendor_payments_table', 47),
(48, '2022_03_19_135047_create_general_settings_table', 48),
(49, '2022_04_11_162744_create_reviews_table', 49),
(50, '2022_04_12_175915_create_measurement_units_table', 50),
(51, '2022_04_14_152400_create_message_sms_table', 51),
(53, '2022_04_14_153058_create_message_whatsapps_table', 53),
(54, '2022_04_14_153143_create_message_notifications_table', 54),
(55, '2022_03_19_105655_create_packaging_materials_table', 55),
(56, '2022_03_19_203846_create_recommendation_engines_table', 56),
(57, '2022_04_14_153020_create_message_emails_table', 57);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `vendor_id` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `sub_category_id` int(11) NOT NULL DEFAULT 0,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `shelf_life` int(11) NOT NULL DEFAULT 0,
  `product_weight` decimal(8,3) NOT NULL,
  `measurement_unit_id` int(11) NOT NULL DEFAULT 0,
  `storage_condition_id` int(11) NOT NULL DEFAULT 0,
  `packaging_machine_id` int(11) NOT NULL DEFAULT 0,
  `product_form_id` int(11) NOT NULL DEFAULT 0,
  `packing_type_id` int(11) NOT NULL DEFAULT 0,
  `packaging_treatment_id` int(11) NOT NULL DEFAULT 0,
  `country_id` int(11) NOT NULL DEFAULT 0,
  `currency_id` int(11) NOT NULL DEFAULT 0,
  `sub_total` decimal(8,3) NOT NULL,
  `grand_total` decimal(8,3) NOT NULL,
  `customer_pending_payment` decimal(8,3) NOT NULL,
  `customer_payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending|semi_paid|fully_paid',
  `vendor_pending_payment` decimal(8,3) NOT NULL,
  `vendor_payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending|semi_paid|fully_paid',
  `order_details` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Json Data',
  `product_details` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Json Data',
  `shipping_details` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Json Data',
  `order_delivery_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending|processing|out_for_delivery|delivered',
  `processing_datetime` datetime NOT NULL,
  `out_for_delivery_datetime` datetime NOT NULL,
  `delivery_datetime` datetime NOT NULL,
  `delivered_by` int(11) NOT NULL DEFAULT 0,
  `user_choice` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Json Data',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `vendor_id`, `category_id`, `sub_category_id`, `product_id`, `shelf_life`, `product_weight`, `measurement_unit_id`, `storage_condition_id`, `packaging_machine_id`, `product_form_id`, `packing_type_id`, `packaging_treatment_id`, `country_id`, `currency_id`, `sub_total`, `grand_total`, `customer_pending_payment`, `customer_payment_status`, `vendor_pending_payment`, `vendor_payment_status`, `order_details`, `product_details`, `shipping_details`, `order_delivery_status`, `processing_datetime`, `out_for_delivery_datetime`, `delivery_datetime`, `delivered_by`, `user_choice`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 1, '30.000', 0, 1, 1, 1, 1, 1, 1, 1, '20000.000', '50000.000', '7000.000', 'pending', '10000.000', 'pending', NULL, NULL, NULL, 'processing', '2022-04-07 10:27:12', '2022-04-06 16:29:34', '2022-04-06 16:29:34', 0, NULL, 0, 1, NULL, NULL, '2022-04-12 07:32:09'),
(2, 1, 1, 1, 1, 1, 1, '350.000', 0, 1, 1, 1, 1, 1, 1, 1, '20000.000', '50000.000', '0.000', 'fully_paid', '17000.000', 'pending', NULL, NULL, NULL, 'processing', '2022-04-07 10:27:12', '2022-04-06 16:29:34', '2022-04-06 16:29:34', 0, NULL, 0, 1, NULL, NULL, '2022-04-08 12:17:55');

-- --------------------------------------------------------

--
-- Table structure for table `order_payments`
--

CREATE TABLE `order_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `order_id` int(11) NOT NULL DEFAULT 0,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `vendor_id` int(11) NOT NULL DEFAULT 0,
  `payment_mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash' COMMENT 'cash|bank_transfer|cheque|demand_draft',
  `amount` decimal(8,3) NOT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending|semi_paid|fully_paid',
  `transaction_date` date DEFAULT NULL,
  `remark` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_id` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_key` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_payment_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_payment_thumb_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_payments`
--

INSERT INTO `order_payments` (`id`, `user_id`, `order_id`, `product_id`, `vendor_id`, `payment_mode`, `amount`, `payment_status`, `transaction_date`, `remark`, `gateway_id`, `gateway_key`, `order_payment_image`, `order_payment_thumb_image`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 'cash', '1000.000', 'semi_paid', '2022-04-14', 'good', NULL, NULL, NULL, NULL, 1, 0, '2022-04-07 04:52:21', '2022-04-07 04:52:21'),
(2, 1, 2, 1, 1, 'cash', '50000.000', 'fully_paid', '2022-04-15', 'test', NULL, NULL, NULL, NULL, 1, 0, '2022-04-08 10:57:23', '2022-04-08 10:57:23'),
(3, 1, 1, 1, 1, 'cash', '1000.000', 'semi_paid', '2022-04-21', 'bad', NULL, NULL, NULL, NULL, 1, 0, '2022-04-11 14:34:32', '2022-04-11 14:34:32'),
(4, 1, 1, 1, 1, 'cash', '1000.000', 'semi_paid', '2022-04-12', '', NULL, NULL, 'order_payment_4.jpg', 'order_payment_thumb_4.jpg', 1, 0, '2022-04-12 07:32:08', '2022-04-12 07:32:09');

-- --------------------------------------------------------

--
-- Table structure for table `otps`
--

CREATE TABLE `otps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mobile_no` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_no_with_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp_code` int(11) NOT NULL,
  `expiry_time` datetime NOT NULL,
  `workflow` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verify_count` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packaging_machines`
--

CREATE TABLE `packaging_machines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `packaging_machine_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `packaging_machine_description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `packaging_machine_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `packaging_machine_thumb_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packaging_machines`
--

INSERT INTO `packaging_machines` (`id`, `packaging_machine_name`, `packaging_machine_description`, `packaging_machine_image`, `packaging_machine_thumb_image`, `meta_title`, `meta_description`, `meta_keyword`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'HHFS', 'Packaging Machine HHFS', 'packaging_machine_1.jpg', 'packaging_machine_thumb_1.jpg', NULL, NULL, NULL, '1', 1, 1, NULL, '2022-03-30 06:22:27', '2022-03-30 06:26:17');

-- --------------------------------------------------------

--
-- Table structure for table `packaging_materials`
--

CREATE TABLE `packaging_materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `packaging_material_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `material_description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `packing_type_id` int(11) NOT NULL DEFAULT 0,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `shelf_life` int(11) NOT NULL DEFAULT 0,
  `approx_price` decimal(8,3) NOT NULL DEFAULT 0.000,
  `wvtr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cof` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gsm` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `special_feature` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packaging_materials`
--

INSERT INTO `packaging_materials` (`id`, `packaging_material_name`, `material_description`, `packing_type_id`, `product_id`, `shelf_life`, `approx_price`, `wvtr`, `otr`, `cof`, `sit`, `gsm`, `special_feature`, `meta_title`, `meta_description`, `meta_keyword`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'BOPP PLAIN PCT1', 'BOPP PLAIN PCT second description1', 0, 0, 30, '999.000', 'g/m2/24 hr', 'cc/m2/24 hr', '0.00-1.00', '5N/24mm', 'g/m2', 'best quality', NULL, NULL, NULL, '0', 1, 0, NULL, '2022-04-19 10:56:03', '2022-04-19 10:56:03');

-- --------------------------------------------------------

--
-- Table structure for table `packaging_treatments`
--

CREATE TABLE `packaging_treatments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `packaging_treatment_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `packaging_treatment_description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `packaging_treatment_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `packaging_treatment_thumb_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packaging_treatments`
--

INSERT INTO `packaging_treatments` (`id`, `packaging_treatment_name`, `packaging_treatment_description`, `packaging_treatment_image`, `packaging_treatment_thumb_image`, `meta_title`, `meta_description`, `meta_keyword`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Aseptic Filling', 'Aseptic filling treatment description', 'packaging_treatment_1.jpg', 'packaging_treatment_thumb_1.jpg', NULL, NULL, NULL, '1', 1, 1, NULL, '2022-03-30 07:34:20', '2022-03-30 07:37:47');

-- --------------------------------------------------------

--
-- Table structure for table `packing_types`
--

CREATE TABLE `packing_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `packing_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `packing_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packing_types`
--

INSERT INTO `packing_types` (`id`, `packing_name`, `packing_description`, `meta_title`, `meta_description`, `meta_keyword`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Primary', 'Packing type description', NULL, NULL, NULL, '1', 1, 1, NULL, '2022-03-29 13:48:40', '2022-03-29 13:54:34'),
(2, 'Secondary', 'Secondary Packing Type', NULL, NULL, NULL, '0', 1, 0, NULL, '2022-03-29 13:54:18', '2022-03-29 13:54:18');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_be_considered` enum('Yes','No') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `codename`, `parent_status`, `description`, `to_be_considered`, `created_at`, `updated_at`) VALUES
(1, 'Staff', 'staff', 'parent', '', 'Yes', NULL, NULL),
(2, 'Add', 'staff_add', '1', '', 'Yes', NULL, NULL),
(3, 'Edit', 'staff_edit', '1', '', 'Yes', NULL, NULL),
(4, 'View', 'staff_view', '1', '', 'Yes', NULL, NULL),
(5, 'Status', 'staff_status', '1', '', 'Yes', NULL, NULL),
(6, 'Delete', 'staff_delete', '1', '', 'Yes', NULL, NULL),
(7, 'Category', 'category', 'parent', '', 'Yes', NULL, NULL),
(8, 'Add', 'category_add', '7', '', 'Yes', NULL, NULL),
(9, 'Edit', 'category_edit', '7', '', 'Yes', NULL, NULL),
(10, 'View', 'category_view', '7', '', 'Yes', NULL, NULL),
(11, 'Status', 'category_status', '7', '', 'Yes', NULL, NULL),
(12, 'Delete', 'category_delete', '7', '', 'Yes', NULL, NULL),
(13, 'City', 'city', 'parent', '', 'Yes', NULL, NULL),
(14, 'Add', 'city_add', '13', '', 'Yes', NULL, NULL),
(15, 'Edit', 'city_edit', '13', '', 'Yes', NULL, NULL),
(16, 'View', 'city_view', '13', '', 'No', NULL, NULL),
(17, 'Status', 'city_status', '13', '', 'Yes', NULL, NULL),
(18, 'Delete', 'city_delete', '13', '', 'Yes', NULL, NULL),
(19, 'Fright Price', 'fright_price', 'parent', '', 'Yes', NULL, NULL),
(20, 'Add', 'fright_price_add', '19', '', 'Yes', NULL, NULL),
(21, 'Edit', 'fright_price_edit', '19', '', 'Yes', NULL, NULL),
(22, 'View', 'fright_price_view', '19', '', 'No', NULL, NULL),
(23, 'Status', 'fright_price_status', '19', '', 'Yes', NULL, NULL),
(24, 'Delete', 'fright_price_delete', '19', '', 'Yes', NULL, NULL),
(25, 'Company', 'company', 'parent', '', 'Yes', NULL, NULL),
(26, 'Add', 'company_add', '25', '', 'Yes', NULL, NULL),
(27, 'Edit', 'company_edit', '25', '', 'Yes', NULL, NULL),
(28, 'View', 'company_view', '25', '', 'No', NULL, NULL),
(29, 'Status', 'company_status', '25', '', 'Yes', NULL, NULL),
(30, 'Delete', 'company_delete', '25', '', 'Yes', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_category_id` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `product_form_id` int(11) NOT NULL DEFAULT 0,
  `packaging_treatment_id` int(11) NOT NULL DEFAULT 0,
  `product_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_thumb_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `machine_data` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Json Data',
  `storage_condition_data` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Json Data',
  `product_form_data` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Json Data',
  `packaging_treatment_data` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Json Data',
  `meta_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `product_description`, `sub_category_id`, `category_id`, `product_form_id`, `packaging_treatment_id`, `product_image`, `product_thumb_image`, `machine_data`, `storage_condition_data`, `product_form_data`, `packaging_treatment_data`, `meta_title`, `meta_description`, `meta_keyword`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Biscuits', 'Parle Biscuit Company', 2, 1, 1, 1, 'product_1.jpg', 'product_thumb_1.jpg', '0', '0', '0', '0', NULL, NULL, NULL, '0', 1, 1, NULL, '2022-03-30 10:39:22', '2022-03-30 11:02:35');

-- --------------------------------------------------------

--
-- Table structure for table `product_forms`
--

CREATE TABLE `product_forms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_form_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_form_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_form_thumb_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_forms`
--

INSERT INTO `product_forms` (`id`, `product_form_name`, `short_description`, `product_form_image`, `product_form_thumb_image`, `meta_title`, `meta_description`, `meta_keyword`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Solid', 'Solid product Form', 'product_form_1.jpg', 'product_form_thumb_1.jpg', NULL, NULL, NULL, '1', 1, 1, NULL, '2022-03-29 10:43:05', '2022-03-29 13:02:40'),
(2, 'Soft', 'Soft product Form', NULL, NULL, NULL, NULL, NULL, '0', 1, 0, NULL, '2022-03-29 12:14:23', '2022-03-29 12:14:23');

-- --------------------------------------------------------

--
-- Table structure for table `recommendation_engines`
--

CREATE TABLE `recommendation_engines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `engine_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `structure_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `min_shelf_life` int(11) NOT NULL DEFAULT 0,
  `max_shelf_life` int(11) NOT NULL DEFAULT 0,
  `min_weight` decimal(8,3) NOT NULL DEFAULT 0.000,
  `max_weight` decimal(8,3) NOT NULL DEFAULT 0.000,
  `approx_price` decimal(8,3) NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `product_form_id` int(11) NOT NULL DEFAULT 0,
  `packing_type_id` int(11) NOT NULL DEFAULT 0,
  `packaging_machine_id` int(11) NOT NULL DEFAULT 0,
  `packaging_treatment_id` int(11) NOT NULL DEFAULT 0,
  `packaging_material_id` int(11) NOT NULL DEFAULT 0,
  `vendor_id` int(11) NOT NULL DEFAULT 0,
  `display_shelf_life` int(11) NOT NULL DEFAULT 0,
  `meta_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `rating` int(11) NOT NULL DEFAULT 0,
  `title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `review` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approval_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending|approved|rejected',
  `approved_on` datetime DEFAULT NULL,
  `approved_by` int(11) NOT NULL DEFAULT 0 COMMENT 'Admin Id',
  `admin_remark` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `title`, `review`, `approval_status`, `approved_on`, `approved_by`, `admin_remark`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 4, 'Best product', 'Best product in quality and price is also under budget. i like this product.', 'accepted', '2022-04-11 17:20:40', 1, '', '0', NULL, NULL, '2022-04-11 11:50:40');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `permission`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '[]', '1', 0, 0, NULL, NULL, NULL),
(2, 'Manager', '[\"\"]', '1', 1, 0, NULL, '2022-02-04 05:42:03', '2022-03-16 11:55:20'),
(3, 'Data Entry', '[\"7\",\"4\",\"11\",\"12\",\"18\",\"15\",\"1\",\"13\",\"17\"]', '1', 1, 0, NULL, '2022-02-04 05:41:58', '2022-03-03 04:52:40');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `state_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT 1,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `state_name`, `country_id`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Maharashtra', 1, '0', 0, 1, NULL, '2022-03-22 06:37:26', '2022-04-05 12:39:35');

-- --------------------------------------------------------

--
-- Table structure for table `storage_conditions`
--

CREATE TABLE `storage_conditions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `storage_condition_title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `storage_condition_description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `storage_conditions`
--

INSERT INTO `storage_conditions` (`id`, `storage_condition_title`, `storage_condition_description`, `seo_url`, `meta_title`, `meta_description`, `meta_keyword`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Cold', 'store at 0 degree', NULL, NULL, NULL, NULL, '1', 0, 1, NULL, '2022-04-04 13:29:01', '2022-04-04 13:34:11'),
(2, 'Hot', 'Store at 45 degree', NULL, NULL, NULL, NULL, '0', 1, 0, NULL, '2022-04-04 13:33:59', '2022-04-04 13:33:59');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly' COMMENT 'monthly|quarterly|semi_yearly|yearly',
  `amount` decimal(8,3) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `type`, `amount`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'monthly', '399.000', 1, 1, NULL, NULL, '2022-04-01 11:42:50'),
(2, 'quarterly', '699.000', 1, 1, NULL, NULL, '2022-04-01 11:43:01'),
(3, 'semi_yearly', '799.000', 1, 0, NULL, NULL, NULL),
(4, 'yearly', '999.000', 1, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `sub_category_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_category_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_category_thumb_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `category_id`, `sub_category_name`, `sub_category_image`, `sub_category_thumb_image`, `seo_url`, `meta_title`, `meta_description`, `meta_keyword`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Milk', 'sub_category_1.jpg', 'sub_category_thumb_1.jpg', NULL, NULL, NULL, NULL, '0', 0, 1, NULL, '2022-03-25 13:57:16', '2022-03-29 07:58:21'),
(2, 1, 'Fast Food', 'sub_category_2.jpg', 'sub_category_thumb_2.jpg', NULL, NULL, NULL, NULL, '0', 1, 1, NULL, '2022-03-29 07:18:21', '2022-03-29 07:58:52'),
(3, 1, 'Break Fast', 'sub_category_3.jpg', 'sub_category_thumb_3.jpg', NULL, NULL, NULL, NULL, '0', 1, 1, NULL, '2022-03-29 07:20:05', '2022-03-29 07:51:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_country_id` int(11) NOT NULL DEFAULT 0 COMMENT 'phone_code',
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp_country_id` int(11) NOT NULL DEFAULT 0 COMMENT 'whatsapp_phone_code',
  `whatsapp_no` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language_id` int(11) NOT NULL DEFAULT 0,
  `currency_id` int(11) NOT NULL DEFAULT 0,
  `approval_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending|accepted|rejected',
  `approved_on` datetime NOT NULL,
  `approved_by` int(11) NOT NULL DEFAULT 0 COMMENT 'Admin Id',
  `admin_remark` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscription_id` int(11) NOT NULL DEFAULT 0,
  `subscription_start` datetime DEFAULT NULL,
  `subscription_end` datetime DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal' COMMENT 'normal|premium',
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `sms_notification` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `email_notification` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `whatsapp_notification` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `mkey` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `msalt` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone_country_id`, `phone`, `whatsapp_country_id`, `whatsapp_no`, `language_id`, `currency_id`, `approval_status`, `approved_on`, `approved_by`, `admin_remark`, `subscription_id`, `subscription_start`, `subscription_end`, `type`, `status`, `sms_notification`, `email_notification`, `whatsapp_notification`, `email_verified_at`, `mkey`, `msalt`, `password`, `remember_token`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Mahesh', 'mahesh@gmail.com', 1, '1245785412', 1, '2154874521', 0, 1, 'pending', '2022-04-05 23:54:17', 1, '', 0, NULL, NULL, 'normal', '0', '1', '1', '1', NULL, NULL, NULL, '', NULL, 1, 1, NULL, '2022-03-31 08:14:00', '2022-04-05 18:24:17'),
(2, 'kamlesh', 'kamlesh@gmail.com', 1, '8785875481', 1, '2157874521', 0, 1, 'accepted', '2022-04-06 00:00:05', 1, NULL, 0, NULL, NULL, 'normal', '1', '1', '1', '1', NULL, NULL, NULL, '', NULL, 1, 1, NULL, '2022-03-31 08:15:54', '2022-04-05 18:30:10');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL DEFAULT 0,
  `country_id` int(11) NOT NULL DEFAULT 0,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pincode` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `city_id`, `state_id`, `country_id`, `address`, `pincode`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 2, 'Juhu', '400049', '1', 1, 1, NULL, '2022-03-24 10:22:26', '2022-03-24 11:30:20'),
(2, 1, 1, 1, 1, 'Khar', '400050', '0', 1, 0, NULL, '2022-03-24 11:17:52', '2022-03-24 11:17:52'),
(3, 1, 1, 1, 1, 'Bandra', '400051', '0', 1, 0, NULL, '2022-03-24 11:44:42', '2022-03-24 11:44:42');

-- --------------------------------------------------------

--
-- Table structure for table `user_subscription_payments`
--

CREATE TABLE `user_subscription_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `subscription_id` int(11) NOT NULL DEFAULT 0,
  `amount` decimal(8,3) NOT NULL,
  `subscription_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly' COMMENT 'monthly|quarterly|semi_yearly|yearly',
  `payment_mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cod' COMMENT 'cash|bank_transfer',
  `payment_reference` int(11) NOT NULL DEFAULT 0,
  `payment_unique_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `payment_details` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Json Data',
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending|paid|failed',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_subscription_payments`
--

INSERT INTO `user_subscription_payments` (`id`, `user_id`, `subscription_id`, `amount`, `subscription_type`, `payment_mode`, `payment_reference`, `payment_unique_id`, `payment_details`, `payment_status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '0.000', 'monthly', 'cash', 0, '0', NULL, 'pending', 1, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pincode` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_country_id` int(11) NOT NULL DEFAULT 0 COMMENT 'phone_code',
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp_country_id` int(11) NOT NULL DEFAULT 0 COMMENT 'whatsapp_phone_code',
  `whatsapp_no` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_id` int(11) NOT NULL DEFAULT 0,
  `is_featured` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `approval_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending|accepted|rejected',
  `approved_on` datetime NOT NULL,
  `approved_by` int(11) NOT NULL DEFAULT 0 COMMENT 'Admin Id',
  `admin_remark` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `vendor_name`, `vendor_email`, `vendor_address`, `pincode`, `phone_country_id`, `phone`, `whatsapp_country_id`, `whatsapp_no`, `currency_id`, `is_featured`, `approval_status`, `approved_on`, `approved_by`, `admin_remark`, `meta_title`, `meta_description`, `meta_keyword`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Ramesh', 'ramesh@gmail.com', 'Khar', '400049', 1, '1245785412', 0, NULL, 1, '1', 'accepted', '2022-03-25 14:24:39', 1, NULL, NULL, NULL, NULL, '0', 1, 0, NULL, '2022-03-25 08:54:39', '2022-03-25 09:08:41'),
(2, 'Adi', 'adi@gmail.com', 'Santacruz', '400049', 1, '1245785472', 0, NULL, 1, '0', 'accepted', '2022-04-04 10:10:33', 1, '', NULL, NULL, NULL, '1', 1, 0, NULL, '2022-03-25 10:51:59', '2022-04-04 04:40:33'),
(3, 'Pradyumn', 'pradyumn@gmail.com', 'juhu', '125487', 1, '8785875481', 0, NULL, 1, '0', 'rejected', '2022-04-11 11:47:05', 1, '', NULL, NULL, NULL, '0', 1, 0, NULL, '2022-03-25 10:54:07', '2022-04-11 06:17:05'),
(4, 'Manish', 'manish@gmail.com', 'Juhu', '400049', 1, '8785875481', 1, '8785875481', 1, '1', 'pending', '2022-04-11 11:37:03', 1, '', NULL, NULL, NULL, '1', 1, 1, NULL, '2022-04-01 05:21:48', '2022-04-11 06:07:03'),
(5, 'Shani', 'shani@gmail.com', 'Khar', '125487', 1, '1245785412', 0, NULL, 1, '0', 'pending', '2022-04-05 13:39:05', 1, NULL, NULL, NULL, NULL, '0', 1, 0, NULL, '2022-04-05 08:09:05', '2022-04-05 08:09:05'),
(6, 'Manish Shukla', 'mnshsk@gmail.com', 'Bandra', '215487', 1, '8789563221', 0, NULL, 1, '0', 'accepted', '2022-04-05 13:48:40', 1, '', NULL, NULL, NULL, '0', 1, 1, NULL, '2022-04-05 08:10:15', '2022-04-05 08:19:02'),
(7, 'Ramesh Mishra', 'ramesh1@gmail.com', 'Dadar', '543562', 1, '1245785412', 0, NULL, 1, '0', 'accepted', '2022-04-05 13:47:53', 1, NULL, NULL, NULL, NULL, '0', 1, 0, NULL, '2022-04-05 08:17:53', '2022-04-05 08:17:53'),
(8, 'Kaushal', 'kl@gmail.com', 'khar', '215487', 1, '8785875481', 0, NULL, 1, '0', 'accepted', '2022-04-05 13:50:42', 1, NULL, NULL, NULL, NULL, '0', 1, 1, NULL, '2022-04-05 08:20:42', '2022-04-05 08:21:13'),
(9, 'Ramesh2', 'ramesh2@gmail.com', 'khar', '125487', 1, '8785875481', 0, NULL, 1, '0', 'accepted', '2022-04-05 13:56:03', 1, NULL, NULL, NULL, NULL, '0', 1, 0, NULL, '2022-04-05 08:26:03', '2022-04-05 08:26:03'),
(10, 'Ramesh3', 'ramesh3@gmail.com', 'khar', '543562', 1, '8785875481', 0, NULL, 1, '0', 'accepted', '2022-04-05 14:00:48', 1, NULL, NULL, NULL, NULL, '0', 1, 1, NULL, '2022-04-05 08:30:48', '2022-04-05 08:31:23');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_material_mappings`
--

CREATE TABLE `vendor_material_mappings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `packaging_material_id` int(11) NOT NULL,
  `recommendation_engine_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Mapping with ID',
  `product_id` int(11) NOT NULL DEFAULT 0,
  `min_amt_profit` decimal(8,3) NOT NULL COMMENT 'Per Kg',
  `min_stock_qty` decimal(8,3) NOT NULL,
  `vendor_price` decimal(8,3) NOT NULL COMMENT 'Per Kg',
  `meta_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_material_mappings`
--

INSERT INTO `vendor_material_mappings` (`id`, `vendor_id`, `packaging_material_id`, `recommendation_engine_id`, `product_id`, `min_amt_profit`, `min_stock_qty`, `vendor_price`, `meta_title`, `meta_description`, `meta_keyword`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 1, 1, '254.000', '399.000', '255.000', NULL, NULL, NULL, '1', 0, 1, NULL, '2022-04-01 08:35:10', '2022-04-01 09:40:36'),
(2, 4, 1, 1, 1, '254.000', '399.000', '0.000', NULL, NULL, NULL, '0', 0, 1, NULL, '2022-04-01 09:16:18', '2022-04-01 10:43:28'),
(3, 4, 2, 1, 1, '254.000', '399.000', '0.000', NULL, NULL, NULL, '1', 1, 0, NULL, '2022-04-01 09:48:34', '2022-04-01 09:48:40'),
(4, 2, 1, 1, 1, '254.000', '399.000', '399.000', NULL, NULL, NULL, '1', 1, 0, NULL, '2022-04-01 13:52:25', '2022-04-01 13:52:40'),
(5, 10, 2, 1, 1, '254.000', '399.000', '0.000', NULL, NULL, NULL, '1', 1, 1, NULL, '2022-04-05 08:47:53', '2022-04-05 08:48:14');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payments`
--

CREATE TABLE `vendor_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT 0,
  `order_id` int(11) NOT NULL DEFAULT 0,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'semi_paid' COMMENT 'semi_paid|fully_paid',
  `payment_mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash' COMMENT 'cash|bank_transfer|cheque|demand_draft',
  `amount` decimal(8,3) NOT NULL COMMENT 'chunk payment',
  `remark` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `payment_details` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_payments`
--

INSERT INTO `vendor_payments` (`id`, `vendor_id`, `order_id`, `payment_status`, `payment_mode`, `amount`, `remark`, `transaction_date`, `payment_details`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'semi_paid', 'cash', '1000.000', 'Trnsaction will be on 9 april', '2022-04-09', NULL, 1, 0, NULL, '2022-04-07 08:58:06', '2022-04-07 08:58:06'),
(2, 1, 1, 'semi_paid', 'bank_transfer', '5000.000', NULL, '2022-04-22', NULL, 1, 0, NULL, '2022-04-08 06:34:59', '2022-04-08 06:34:59'),
(3, 1, 1, 'semi_paid', 'cash', '6000.000', 'Semi paid', '2022-04-08', NULL, 1, 0, NULL, '2022-04-08 06:35:48', '2022-04-08 06:35:48'),
(4, 1, 1, 'semi_paid', 'bank_transfer', '5000.000', 'semi', '2022-04-10', NULL, 1, 0, NULL, '2022-04-08 06:41:25', '2022-04-08 06:41:25'),
(5, 2, 2, 'semi_paid', 'bank_transfer', '5000.000', NULL, '2022-04-09', NULL, 1, 0, NULL, '2022-04-08 10:40:51', '2022-04-08 10:40:51'),
(6, 1, 2, 'semi_paid', 'cash', '5000.000', 'semi test', '2022-04-09', NULL, 1, 0, NULL, '2022-04-08 12:12:05', '2022-04-08 12:12:05'),
(7, 1, 2, 'semi_paid', 'cash', '1000.000', NULL, '2022-04-09', NULL, 1, 0, NULL, '2022-04-08 12:12:31', '2022-04-08 12:12:31'),
(8, 1, 2, 'semi_paid', 'cash', '1000.000', 'test', '2022-04-23', NULL, 1, 0, NULL, '2022-04-08 12:13:23', '2022-04-08 12:13:23'),
(9, 1, 2, 'semi_paid', 'bank_transfer', '1000.000', NULL, '2022-04-30', NULL, 1, 0, NULL, '2022-04-08 12:17:55', '2022-04-08 12:17:55'),
(10, 1, 1, 'semi_paid', 'cash', '1000.000', NULL, '2022-04-10', NULL, 1, 0, NULL, '2022-04-08 12:18:45', '2022-04-08 12:18:45'),
(11, 1, 1, 'semi_paid', 'bank_transfer', '1000.000', '', '2022-04-16', NULL, 1, 0, NULL, '2022-04-08 12:21:02', '2022-04-08 12:21:02'),
(12, 1, 1, 'semi_paid', 'bank_transfer', '1000.000', 'success', '2022-05-07', NULL, 1, 0, NULL, '2022-04-08 12:21:25', '2022-04-08 12:21:25'),
(13, 1, 1, 'semi_paid', 'cash', '1000.000', 'testing default date', '2022-04-11', NULL, 1, 0, NULL, '2022-04-11 06:55:16', '2022-04-11 06:55:16'),
(14, 1, 1, 'semi_paid', 'cash', '1000.000', '', '2022-04-11', NULL, 1, 0, NULL, '2022-04-11 14:35:06', '2022-04-11 14:35:06');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_quotations`
--

CREATE TABLE `vendor_quotations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `customer_enquiry_id` int(11) NOT NULL DEFAULT 0,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `vendor_id` int(11) NOT NULL DEFAULT 0,
  `vendor_warehouse_id` int(11) NOT NULL DEFAULT 0,
  `vendor_price` decimal(8,3) NOT NULL COMMENT 'Per Kg',
  `commission_amt` decimal(8,3) NOT NULL COMMENT 'Per Kg',
  `enquiry_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mapped' COMMENT 'mapped|quoted|viewed|accept|reject|requote',
  `quotation_expiry_datetime` datetime DEFAULT NULL,
  `etd` date DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_quotations`
--

INSERT INTO `vendor_quotations` (`id`, `user_id`, `customer_enquiry_id`, `product_id`, `vendor_id`, `vendor_warehouse_id`, `vendor_price`, `commission_amt`, `enquiry_status`, `quotation_expiry_datetime`, `etd`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 1, 1, '125.000', '5645.000', 'mapped', '2022-04-13 21:40:05', '2022-04-21', '0', 1, 0, '2022-04-13 11:10:05', '2022-04-13 11:10:05');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_warehouses`
--

CREATE TABLE `vendor_warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT 0,
  `city_id` int(11) NOT NULL DEFAULT 0,
  `state_id` int(11) NOT NULL DEFAULT 0,
  `country_id` int(11) NOT NULL DEFAULT 0,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pincode` int(11) NOT NULL DEFAULT 0,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_warehouses`
--

INSERT INTO `vendor_warehouses` (`id`, `warehouse_name`, `vendor_id`, `city_id`, `state_id`, `country_id`, `address`, `pincode`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Super Fast', 1, 1, 1, 1, 'Juhu', 400049, '0', 1, 1, NULL, '2022-04-01 14:46:58', '2022-04-01 14:55:42'),
(2, 'Express Warehouse', 1, 1, 1, 1, 'Juhu', 125487, '0', 1, 1, NULL, '2022-04-21 04:43:23', '2022-04-21 04:43:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`),
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_enquiries`
--
ALTER TABLE `contact_enquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_enquiries`
--
ALTER TABLE `customer_enquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `measurement_units`
--
ALTER TABLE `measurement_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_emails`
--
ALTER TABLE `message_emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_notifications`
--
ALTER TABLE `message_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_sms`
--
ALTER TABLE `message_sms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_whatsapps`
--
ALTER TABLE `message_whatsapps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_payments`
--
ALTER TABLE `order_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otps`
--
ALTER TABLE `otps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packaging_machines`
--
ALTER TABLE `packaging_machines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packaging_materials`
--
ALTER TABLE `packaging_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packaging_treatments`
--
ALTER TABLE `packaging_treatments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packing_types`
--
ALTER TABLE `packing_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_forms`
--
ALTER TABLE `product_forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recommendation_engines`
--
ALTER TABLE `recommendation_engines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `storage_conditions`
--
ALTER TABLE `storage_conditions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_subscription_payments`
--
ALTER TABLE `user_subscription_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_material_mappings`
--
ALTER TABLE `vendor_material_mappings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_quotations`
--
ALTER TABLE `vendor_quotations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_warehouses`
--
ALTER TABLE `vendor_warehouses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_enquiries`
--
ALTER TABLE `contact_enquiries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer_enquiries`
--
ALTER TABLE `customer_enquiries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `measurement_units`
--
ALTER TABLE `measurement_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `message_emails`
--
ALTER TABLE `message_emails`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `message_notifications`
--
ALTER TABLE `message_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `message_sms`
--
ALTER TABLE `message_sms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `message_whatsapps`
--
ALTER TABLE `message_whatsapps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_payments`
--
ALTER TABLE `order_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `otps`
--
ALTER TABLE `otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packaging_machines`
--
ALTER TABLE `packaging_machines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `packaging_materials`
--
ALTER TABLE `packaging_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `packaging_treatments`
--
ALTER TABLE `packaging_treatments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `packing_types`
--
ALTER TABLE `packing_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_forms`
--
ALTER TABLE `product_forms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `recommendation_engines`
--
ALTER TABLE `recommendation_engines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `storage_conditions`
--
ALTER TABLE `storage_conditions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_subscription_payments`
--
ALTER TABLE `user_subscription_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `vendor_material_mappings`
--
ALTER TABLE `vendor_material_mappings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `vendor_quotations`
--
ALTER TABLE `vendor_quotations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendor_warehouses`
--
ALTER TABLE `vendor_warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
