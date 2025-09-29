-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 27, 2024 at 11:47 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dt_tube_clean`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ads`
--

CREATE TABLE `tbl_ads` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1- Banner Ads, 2- Interstital Ads, 3- Reward Ads',
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL COMMENT '\r\n',
  `video` varchar(255) NOT NULL,
  `redirect_uri` text NOT NULL,
  `budget` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0- Inactive, 1- Active',
  `is_hide` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ads_package`
--

CREATE TABLE `tbl_ads_package` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `coin` int(11) NOT NULL,
  `android_product_package` varchar(255) NOT NULL,
  `ios_product_package` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ads_transaction`
--

CREATE TABLE `tbl_ads_transaction` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `coin` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ads_view_click_count`
--

CREATE TABLE `tbl_ads_view_click_count` (
  `id` int(11) NOT NULL,
  `ads_type` int(11) NOT NULL COMMENT '1- Banner Ads, 2- Interstital Ads, 3- Reward Ads',
  `ads_id` int(11) NOT NULL,
  `device_type` int(11) NOT NULL DEFAULT 0 COMMENT '1- Android, 2- IOS, 3- Web',
  `device_token` varchar(255) NOT NULL,
  `content_id` int(11) NOT NULL DEFAULT 0,
  `type` int(11) NOT NULL DEFAULT 0 COMMENT '1- CPV, 2- CPC',
  `total_coin` int(11) NOT NULL DEFAULT 0,
  `admin_commission` int(11) NOT NULL DEFAULT 0,
  `user_wallet_earning` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_artist`
--

CREATE TABLE `tbl_artist` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `bio` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_block_channel`
--

CREATE TABLE `tbl_block_channel` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `block_user_id` int(11) NOT NULL,
  `block_channel_id` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1- Video, 2- Music',
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_comment`
--

CREATE TABLE `tbl_comment` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL,
  `content_type` int(11) NOT NULL COMMENT '	1- Video, 2- Music, 3- Reels, 4- Podcasts, 5- Playlist, 6- Radio	',
  `content_id` int(11) NOT NULL,
  `episode_id` int(11) NOT NULL DEFAULT 0,
  `comment` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_comment_report`
--

CREATE TABLE `tbl_comment_report` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `report_user_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_content`
--

CREATE TABLE `tbl_content` (
  `id` int(11) NOT NULL,
  `content_type` int(11) NOT NULL COMMENT '1- Video, 2- Music, 3- Reels, 4- Podcasts, 5- Playlist, 6- Radio\r\n',
  `channel_id` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `artist_id` int(11) NOT NULL,
  `hashtag_id` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `portrait_img` varchar(255) NOT NULL,
  `landscape_img` varchar(255) NOT NULL,
  `content_upload_type` varchar(255) NOT NULL COMMENT 'server_video, external_url, youtube',
  `content` varchar(255) NOT NULL,
  `content_size` varchar(255) NOT NULL COMMENT 'KB',
  `content_duration` int(11) NOT NULL DEFAULT 0,
  `is_rent` int(11) NOT NULL DEFAULT 0,
  `rent_price` int(11) NOT NULL DEFAULT 0,
  `is_comment` int(11) NOT NULL DEFAULT 0,
  `is_download` int(11) NOT NULL DEFAULT 0,
  `is_like` int(11) NOT NULL DEFAULT 0,
  `total_view` int(11) NOT NULL DEFAULT 0,
  `total_like` int(11) NOT NULL DEFAULT 0,
  `total_dislike` int(11) NOT NULL DEFAULT 0,
  `playlist_type` int(11) NOT NULL DEFAULT 0 COMMENT '1- Public, 2- Private',
  `is_admin_added` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_content_report`
--

CREATE TABLE `tbl_content_report` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `report_user_id` int(11) NOT NULL,
  `content_type` int(11) NOT NULL COMMENT '	1- Video, 2- Music, 3- Reels, 4- Podcasts, 5- Playlist, 6- Radio	',
  `content_id` int(11) NOT NULL,
  `episode_id` int(11) NOT NULL DEFAULT 0,
  `message` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_episode`
--

CREATE TABLE `tbl_episode` (
  `id` int(11) NOT NULL,
  `podcasts_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `portrait_img` varchar(255) NOT NULL,
  `landscape_img` varchar(255) NOT NULL,
  `episode_upload_type` varchar(255) NOT NULL COMMENT 'server_video, external_url, youtube',
  `episode_audio` varchar(255) NOT NULL,
  `episode_size` varchar(255) NOT NULL,
  `is_comment` int(11) NOT NULL DEFAULT 0,
  `is_download` int(11) NOT NULL DEFAULT 0,
  `is_like` int(11) NOT NULL DEFAULT 0,
  `total_view` int(11) NOT NULL DEFAULT 0,
  `total_like` int(11) NOT NULL DEFAULT 0,
  `total_dislike` int(11) NOT NULL DEFAULT 0,
  `sortable` int(11) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_general_setting`
--

CREATE TABLE `tbl_general_setting` (
  `id` int(11) NOT NULL,
  `key` text NOT NULL,
  `value` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_general_setting`
--

INSERT INTO `tbl_general_setting` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'DTTube', '2023-04-21 05:09:12', '2024-08-22 14:22:17'),
(2, 'host_email', 'admin@admin.com', '2023-04-21 05:09:12', '2023-08-26 06:11:33'),
(3, 'app_version', '1.1', '2023-04-21 05:09:12', '2023-08-26 06:11:33'),
(4, 'author', 'Divintechs', '2023-04-21 05:09:12', '2023-08-26 06:11:33'),
(5, 'email', 'admin@admin.com', '2023-04-21 05:09:12', '2023-08-26 06:11:38'),
(6, 'contact', '1234567890', '2023-04-21 05:09:12', '2023-08-26 06:11:33'),
(7, 'app_desripation', 'DivineTechs, a top web & mobile app development company offering innovative solutions for diverse industry verticals. \r\nWe have creative and dedicated group of developers who are mastered in Apps Developments and Web Development with a nice in delivering quality solutions to customers across the globe.', '2023-04-21 05:09:12', '2023-09-22 06:52:02'),
(10, 'app_logo', '', '2023-04-21 05:09:12', '2024-11-27 10:42:27'),
(11, 'website', 'www.admin.com', '2023-04-21 05:09:12', '2023-08-26 06:11:33'),
(12, 'currency', 'USD', '2023-04-21 05:09:12', '2023-08-26 06:12:03'),
(13, 'currency_code', '$', '2023-04-21 05:09:12', '2023-09-16 06:44:53'),
(14, 'banner_ad', '0', '2023-04-21 05:09:12', '2024-11-27 10:42:30'),
(15, 'banner_adid', '', '2023-04-21 05:09:12', '2024-11-27 10:42:34'),
(16, 'interstital_ad', '0', '2023-04-21 05:09:12', '2024-11-27 10:42:36'),
(17, 'interstital_adid', '', '2023-04-21 05:09:12', '2024-11-27 10:42:37'),
(18, 'interstital_adclick', '', '2023-04-21 05:09:12', '2024-11-27 10:42:38'),
(19, 'reward_ad', '0', '2023-04-21 05:09:12', '2024-11-27 10:42:40'),
(20, 'reward_adid', '', '2023-04-21 05:09:12', '2024-11-27 10:42:41'),
(21, 'reward_adclick', '', '2023-04-21 05:09:12', '2024-11-27 10:42:42'),
(22, 'ios_banner_ad', '0', '2023-04-21 05:09:12', '2024-11-27 10:42:44'),
(23, 'ios_banner_adid', '', '2023-04-21 05:09:12', '2024-11-27 10:42:45'),
(24, 'ios_interstital_ad', '0', '2023-04-21 05:09:12', '2024-11-27 10:42:47'),
(25, 'ios_interstital_adid', '', '2023-04-21 05:09:12', '2024-11-27 10:42:48'),
(26, 'ios_interstital_adclick', '', '2023-04-21 05:09:12', '2024-11-27 10:42:50'),
(27, 'ios_reward_ad', '0', '2023-04-21 05:09:12', '2024-11-27 10:42:52'),
(28, 'ios_reward_adid', '', '2023-04-21 05:09:12', '2024-11-27 10:42:53'),
(29, 'ios_reward_adclick', '', '2023-04-21 05:09:12', '2024-11-27 10:42:56'),
(30, 'fb_native_status', '0', '2023-04-21 05:09:12', '2023-08-26 06:15:38'),
(31, 'fb_native_id', '', '2023-04-21 05:09:12', '2023-08-26 06:15:38'),
(32, 'fb_banner_status', '0', '2023-04-21 05:09:12', '2023-08-26 06:15:38'),
(33, 'fb_banner_id', '', '2023-04-21 05:09:12', '2023-08-26 06:15:38'),
(34, 'fb_interstiatial_status', '0', '2023-04-21 05:09:12', '2023-08-26 06:15:38'),
(35, 'fb_interstiatial_id', '', '2023-04-21 05:09:12', '2023-08-26 06:15:38'),
(36, 'fb_rewardvideo_status', '0', '2023-04-21 05:09:12', '2023-08-26 06:15:38'),
(37, 'fb_rewardvideo_id', '', '2023-04-21 05:09:12', '2023-08-26 06:15:38'),
(38, 'fb_native_full_status', '0', '2023-04-21 05:09:12', '2023-08-26 06:15:38'),
(39, 'fb_native_full_id', '', '2023-04-21 05:09:12', '2023-08-26 06:15:38'),
(40, 'fb_ios_native_status', '0', '2023-04-21 05:09:12', '2023-08-26 06:16:07'),
(41, 'fb_ios_native_id', '', '2023-04-21 05:09:12', '2023-08-26 06:16:07'),
(42, 'fb_ios_banner_status', '0', '2023-04-21 05:09:12', '2023-08-26 06:16:07'),
(43, 'fb_ios_banner_id', '', '2023-04-21 05:09:12', '2023-08-26 06:16:07'),
(44, 'fb_ios_interstiatial_status', '0', '2023-04-21 05:09:12', '2023-08-26 06:16:07'),
(45, 'fb_ios_interstiatial_id', '', '2023-04-21 05:09:12', '2023-08-26 06:16:07'),
(46, 'fb_ios_rewardvideo_status', '0', '2023-04-21 05:09:12', '2023-08-26 06:16:07'),
(47, 'fb_ios_rewardvideo_id', '', '2023-04-21 05:09:12', '2023-08-26 06:16:07'),
(48, 'fb_ios_native_full_status', '0', '2023-04-21 05:09:12', '2023-08-26 06:16:07'),
(49, 'fb_ios_native_full_id', '', '2023-04-21 05:09:12', '2023-08-26 06:16:07'),
(50, 'onesignal_apid', '', '2023-04-21 05:09:12', '2024-10-22 10:09:22'),
(51, 'onesignal_rest_key', '', '2023-04-21 05:09:12', '2024-10-22 10:09:22'),
(52, 'live_appid', '', '2023-10-17 10:35:28', '2024-11-27 10:43:02'),
(53, 'live_appsign', '', '2023-10-17 10:35:28', '2024-11-27 10:43:04'),
(54, 'live_serversecret', '', '2023-10-17 10:35:32', '2024-11-27 10:43:05'),
(55, 'rent_commission', '5', '2023-12-11 13:37:36', '2024-11-27 10:44:36'),
(56, 'banner_ads_status', '0', '2024-01-31 11:57:47', '2024-11-27 10:43:12'),
(57, 'banner_ads_cpv', '', '2024-01-31 11:57:47', '2024-11-27 10:43:13'),
(58, 'banner_ads_cpc', '', '2024-01-31 11:57:47', '2024-11-27 10:43:14'),
(59, 'interstital_ads_status', '0', '2024-01-31 11:57:47', '2024-11-27 10:43:16'),
(60, 'interstital_ads_cpv', '', '2024-01-31 11:57:47', '2024-11-27 10:43:26'),
(61, 'interstital_ads_cpc', '', '2024-01-31 11:57:47', '2024-11-27 10:43:28'),
(62, 'reward_ads_status', '0', '2024-01-31 11:57:47', '2024-11-27 10:43:21'),
(63, 'reward_ads_cpv', '', '2024-01-31 11:57:47', '2024-11-27 10:43:22'),
(64, 'reward_ads_cpc', '', '2024-01-31 11:57:47', '2024-11-27 10:43:24'),
(65, 'ads_commission', '5', '2024-01-31 11:57:47', '2024-11-27 10:44:39'),
(66, 'min_withdrawal_amount', '100', '2024-01-31 11:57:52', '2024-01-31 17:50:50'),
(67, 'vap_id_key', '', '2024-07-09 10:11:01', '2024-11-27 10:43:36'),
(68, 'after_day_delete_reels', '0', '2024-07-15 06:58:03', '2024-07-15 06:58:03'),
(69, 'sight_engine_status', '0', '2024-10-21 11:15:55', '2024-10-23 06:56:39'),
(70, 'sight_engine_user_key', '', '2024-10-21 11:15:55', '2024-11-27 10:43:42'),
(71, 'sight_engine_secret_key', '', '2024-10-21 11:16:10', '2024-11-27 10:43:44'),
(72, 'sight_engine_concepts', '', '2024-10-21 11:16:10', '2024-11-27 10:43:46'),
(73, 'deepar_android_key', '', '2024-10-30 05:27:10', '2024-11-27 10:43:48'),
(74, 'deepar_ios_key', '', '2024-10-30 05:27:10', '2024-11-27 10:43:50'),
(75, 'is_live_streaming_fake', '0', '2024-11-26 10:44:43', '2024-11-27 10:44:01');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_gift`
--

CREATE TABLE `tbl_gift` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_gift_transaction`
--

CREATE TABLE `tbl_gift_transaction` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gift_id` int(11) NOT NULL DEFAULT 0,
  `coin` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hashtag`
--

CREATE TABLE `tbl_hashtag` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_used` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_history`
--

CREATE TABLE `tbl_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_type` int(11) NOT NULL COMMENT '1- Video, 2- Music, 3- Reels, 4- Podcasts, 5- Playlist, 6- Radio	',
  `content_id` int(11) NOT NULL,
  `episode_id` int(11) NOT NULL,
  `stop_time` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_interests_category`
--

CREATE TABLE `tbl_interests_category` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_interests_hashtag`
--

CREATE TABLE `tbl_interests_hashtag` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hashtag_id` int(11) NOT NULL,
  `count` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_language`
--

CREATE TABLE `tbl_language` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_like`
--

CREATE TABLE `tbl_like` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_type` int(11) NOT NULL COMMENT '	1- Video, 2- Music, 3- Reels, 4- Podcasts, 5- Playlist, 6- Radio	',
  `content_id` int(11) NOT NULL,
  `episode_id` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL COMMENT '0- Remove, 1- Like, 2- Dislike',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_live_history`
--

CREATE TABLE `tbl_live_history` (
  `id` int(11) UNSIGNED NOT NULL,
  `room_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_gift` int(11) NOT NULL DEFAULT 0,
  `total_join_user` int(11) NOT NULL DEFAULT 0,
  `total_live_chat` int(11) NOT NULL DEFAULT 0,
  `start_time` varchar(255) NOT NULL,
  `end_time` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_live_user`
--

CREATE TABLE `tbl_live_user` (
  `id` int(11) UNSIGNED NOT NULL,
  `room_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_view` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0- not live, 1- live	',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notification`
--

CREATE TABLE `tbl_notification` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1- Admin, 2- Like, 3- Comment, 4- Subscribe, 5- Hide Content',
  `title` text NOT NULL,
  `message` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_onboarding_screen`
--

CREATE TABLE `tbl_onboarding_screen` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_package`
--

CREATE TABLE `tbl_package` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `no_of_device` int(11) NOT NULL,
  `size_of_data_upload` varchar(255) NOT NULL COMMENT 'MB',
  `ads_free` int(11) NOT NULL,
  `download` int(11) NOT NULL,
  `background_play` int(11) NOT NULL,
  `verifly_artist` int(11) NOT NULL,
  `verifly_account` int(11) NOT NULL,
  `android_product_package` varchar(255) NOT NULL,
  `ios_product_package` varchar(255) NOT NULL,
  `web_product_package` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_package_detail`
--

CREATE TABLE `tbl_package_detail` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `package_key` text NOT NULL,
  `package_value` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_page`
--

CREATE TABLE `tbl_page` (
  `id` int(11) NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_page`
--

INSERT INTO `tbl_page` (`id`, `page_name`, `title`, `description`, `icon`, `status`, `created_at`, `updated_at`) VALUES
(1, 'about-us', 'About Us', '', '', 1, '2023-04-21 05:09:12', '2024-11-27 10:45:06'),
(2, 'privacy-policy', 'Privacy Policy', '', '', 1, '2023-04-21 05:09:12', '2024-11-27 10:45:05'),
(3, 'terms-and-conditions', 'Terms & Conditions', '', '', 1, '2023-04-21 05:09:12', '2024-11-27 10:45:03'),
(4, 'refund-policy', 'Refund Policy', '', '', 1, '2023-04-21 05:09:12', '2024-11-27 10:45:02');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment_option`
--

CREATE TABLE `tbl_payment_option` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `visibility` varchar(255) NOT NULL,
  `is_live` varchar(255) NOT NULL,
  `key_1` varchar(255) NOT NULL,
  `key_2` varchar(255) NOT NULL,
  `key_3` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_payment_option`
--

INSERT INTO `tbl_payment_option` (`id`, `name`, `visibility`, `is_live`, `key_1`, `key_2`, `key_3`, `created_at`, `updated_at`) VALUES
(1, 'inapppurchage', '0', '0', '', '', '', '2023-04-21 05:09:13', '2024-11-27 10:45:34'),
(2, 'paypal', '0', '0', '', '', '', '2023-04-21 05:09:13', '2024-11-27 10:45:35'),
(3, 'razorpay', '0', '0', '', '', '', '2023-04-21 05:09:13', '2024-11-27 10:45:35'),
(4, 'flutterwave', '0', '0', '', '', '', '2023-04-21 05:09:13', '2024-11-27 10:45:38'),
(5, 'payumoney', '0', '0', '', '', '', '2023-04-21 05:09:13', '2024-11-27 10:45:37'),
(6, 'paytm', '0', '0', '', '', '', '2023-04-21 05:09:13', '2024-11-27 10:45:39'),
(7, 'stripe', '0', '0', '', '', '', '2023-07-14 13:04:49', '2024-11-27 10:45:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_playlist_content`
--

CREATE TABLE `tbl_playlist_content` (
  `id` int(11) NOT NULL,
  `channel_id` varchar(255) NOT NULL,
  `playlist_id` int(11) NOT NULL COMMENT 'FK tbl_content-id',
  `content_type` int(11) NOT NULL COMMENT '	1- Video, 2- Music, 3- Reels, 4- Podcasts, 5- Playlist, 6- Radio	',
  `content_id` int(11) NOT NULL,
  `episode_id` int(11) NOT NULL DEFAULT 0,
  `sortable` int(11) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_post`
--

CREATE TABLE `tbl_post` (
  `id` int(11) UNSIGNED NOT NULL,
  `channel_id` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `hashtag_id` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `descripation` text NOT NULL,
  `is_comment` int(11) NOT NULL DEFAULT 0 COMMENT '	0- No, 1- Yes',
  `view` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_post_comment`
--

CREATE TABLE `tbl_post_comment` (
  `id` int(11) UNSIGNED NOT NULL,
  `comment_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_post_content`
--

CREATE TABLE `tbl_post_content` (
  `id` int(11) UNSIGNED NOT NULL,
  `post_id` int(11) NOT NULL,
  `content_type` int(11) NOT NULL COMMENT '1-Image, 2-Video',
  `content_url` varchar(255) NOT NULL,
  `thumbnail_image` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_post_like`
--

CREATE TABLE `tbl_post_like` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_post_report`
--

CREATE TABLE `tbl_post_report` (
  `id` int(11) UNSIGNED NOT NULL,
  `report_user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_post_view`
--

CREATE TABLE `tbl_post_view` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_radio_content`
--

CREATE TABLE `tbl_radio_content` (
  `id` int(11) NOT NULL,
  `radio_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `sortable` int(11) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_read_notification`
--

CREATE TABLE `tbl_read_notification` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rent_section`
--

CREATE TABLE `tbl_rent_section` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `no_of_content` int(11) NOT NULL DEFAULT 1,
  `view_all` int(11) NOT NULL DEFAULT 0,
  `sortable` int(11) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rent_transaction`
--

CREATE TABLE `tbl_rent_transaction` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `admin_commission` varchar(255) NOT NULL DEFAULT '0',
  `user_wallet_amount` int(11) NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_report_reason`
--

CREATE TABLE `tbl_report_reason` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1- Comment, 2- Content',
  `reason` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_section`
--

CREATE TABLE `tbl_section` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `short_title` varchar(255) NOT NULL,
  `is_home_screen` int(11) NOT NULL DEFAULT 1 COMMENT '1- home screen, 2- other screen',
  `content_type` int(11) NOT NULL COMMENT '1- Music, 2- Podcasts, 3- Radio, 4- Playlist',
  `category_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `artist_id` int(11) NOT NULL,
  `order_by_view` int(11) NOT NULL DEFAULT 0 COMMENT '1- ASC, 2- DESC',
  `order_by_like` int(11) NOT NULL DEFAULT 0 COMMENT '1- ASC, 2- DESC',
  `order_by_upload` int(11) NOT NULL DEFAULT 0 COMMENT '1- ASC, 2- DESC',
  `screen_layout` varchar(255) NOT NULL,
  `is_admin_added` int(11) NOT NULL DEFAULT 0 COMMENT '0- All, 1- Admin, 2- User',
  `no_of_content` int(11) NOT NULL DEFAULT 0 COMMENT '0- All',
  `view_all` int(11) NOT NULL DEFAULT 0,
  `sortable` int(11) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_smtp_setting`
--

CREATE TABLE `tbl_smtp_setting` (
  `id` int(11) NOT NULL,
  `protocol` varchar(255) NOT NULL,
  `host` varchar(255) NOT NULL,
  `port` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `from_name` varchar(255) NOT NULL,
  `from_email` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_smtp_setting`
--

INSERT INTO `tbl_smtp_setting` (`id`, `protocol`, `host`, `port`, `user`, `pass`, `from_name`, `from_email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'smtp123', 'smtp.gmail.com', '587', 'admin@admin.com', 'admin', 'DTTube-Divinetechs', 'admin@admin.com', 0, '2023-08-26 06:19:33', '2024-11-27 10:46:22');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_social_link`
--

CREATE TABLE `tbl_social_link` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subscriber`
--

CREATE TABLE `tbl_subscriber` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1- Channel, 2- Artist',
  `user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transaction`
--

CREATE TABLE `tbl_transaction` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `expiry_date` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `channel_id` varchar(255) NOT NULL,
  `channel_name` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `country_code` varchar(255) NOT NULL,
  `mobile_number` varchar(255) NOT NULL,
  `country_name` varchar(255) NOT NULL,
  `type` int(11) NOT NULL DEFAULT 0 COMMENT '1- OTP, 2- Google, 3- Apple, 4- Normal',
  `image` varchar(255) NOT NULL,
  `cover_img` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `device_type` int(11) NOT NULL DEFAULT 0 COMMENT '1- Android, 2- IOS, 3- Web',
  `device_token` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `facebook_url` varchar(255) NOT NULL,
  `instagram_url` varchar(255) NOT NULL,
  `twitter_url` varchar(255) NOT NULL,
  `wallet_balance` int(11) NOT NULL DEFAULT 0,
  `wallet_earning` int(11) NOT NULL DEFAULT 0,
  `bank_name` varchar(255) NOT NULL,
  `bank_code` varchar(255) NOT NULL,
  `bank_address` varchar(255) NOT NULL,
  `ifsc_no` varchar(255) NOT NULL,
  `account_no` varchar(255) NOT NULL,
  `id_proof` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `pincode` int(11) NOT NULL,
  `user_penal_status` int(11) NOT NULL DEFAULT 0 COMMENT '0- No, 1- Yes',
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_view`
--

CREATE TABLE `tbl_view` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_type` int(11) NOT NULL COMMENT '1- Video, 2- Music, 3- Reels, 4- Podcasts, 5- Playlist, 6- Radio',
  `content_id` int(11) NOT NULL,
  `episode_id` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_watch_later`
--

CREATE TABLE `tbl_watch_later` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_type` int(11) NOT NULL COMMENT '	1- Video, 2- Music, 3- Reels, 4- Podcasts, 5- Playlist, 6- Radio	',
  `content_id` int(11) NOT NULL,
  `episode_id` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_withdrawal_request`
--

CREATE TABLE `tbl_withdrawal_request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `payment_type` varchar(255) NOT NULL,
  `payment_detail` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 - Pending, 1- Completed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_ads`
--
ALTER TABLE `tbl_ads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_ads_package`
--
ALTER TABLE `tbl_ads_package`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_ads_transaction`
--
ALTER TABLE `tbl_ads_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_ads_view_click_count`
--
ALTER TABLE `tbl_ads_view_click_count`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_artist`
--
ALTER TABLE `tbl_artist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_block_channel`
--
ALTER TABLE `tbl_block_channel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_comment`
--
ALTER TABLE `tbl_comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_comment_report`
--
ALTER TABLE `tbl_comment_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_content`
--
ALTER TABLE `tbl_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_content_report`
--
ALTER TABLE `tbl_content_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_episode`
--
ALTER TABLE `tbl_episode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_general_setting`
--
ALTER TABLE `tbl_general_setting`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `tbl_gift`
--
ALTER TABLE `tbl_gift`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_gift_transaction`
--
ALTER TABLE `tbl_gift_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_hashtag`
--
ALTER TABLE `tbl_hashtag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_history`
--
ALTER TABLE `tbl_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_interests_category`
--
ALTER TABLE `tbl_interests_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_interests_hashtag`
--
ALTER TABLE `tbl_interests_hashtag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_language`
--
ALTER TABLE `tbl_language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_like`
--
ALTER TABLE `tbl_like`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_live_history`
--
ALTER TABLE `tbl_live_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_live_user`
--
ALTER TABLE `tbl_live_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_notification`
--
ALTER TABLE `tbl_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_onboarding_screen`
--
ALTER TABLE `tbl_onboarding_screen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_package`
--
ALTER TABLE `tbl_package`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_package_detail`
--
ALTER TABLE `tbl_package_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_page`
--
ALTER TABLE `tbl_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_payment_option`
--
ALTER TABLE `tbl_payment_option`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_playlist_content`
--
ALTER TABLE `tbl_playlist_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_post`
--
ALTER TABLE `tbl_post`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_post_comment`
--
ALTER TABLE `tbl_post_comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_post_content`
--
ALTER TABLE `tbl_post_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_post_like`
--
ALTER TABLE `tbl_post_like`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_post_report`
--
ALTER TABLE `tbl_post_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_post_view`
--
ALTER TABLE `tbl_post_view`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_radio_content`
--
ALTER TABLE `tbl_radio_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_read_notification`
--
ALTER TABLE `tbl_read_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_rent_section`
--
ALTER TABLE `tbl_rent_section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_rent_transaction`
--
ALTER TABLE `tbl_rent_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_report_reason`
--
ALTER TABLE `tbl_report_reason`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_section`
--
ALTER TABLE `tbl_section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_smtp_setting`
--
ALTER TABLE `tbl_smtp_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_social_link`
--
ALTER TABLE `tbl_social_link`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_subscriber`
--
ALTER TABLE `tbl_subscriber`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_view`
--
ALTER TABLE `tbl_view`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_watch_later`
--
ALTER TABLE `tbl_watch_later`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_withdrawal_request`
--
ALTER TABLE `tbl_withdrawal_request`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_ads`
--
ALTER TABLE `tbl_ads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_ads_package`
--
ALTER TABLE `tbl_ads_package`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_ads_transaction`
--
ALTER TABLE `tbl_ads_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_ads_view_click_count`
--
ALTER TABLE `tbl_ads_view_click_count`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_artist`
--
ALTER TABLE `tbl_artist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_block_channel`
--
ALTER TABLE `tbl_block_channel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_comment`
--
ALTER TABLE `tbl_comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_comment_report`
--
ALTER TABLE `tbl_comment_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_content`
--
ALTER TABLE `tbl_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_content_report`
--
ALTER TABLE `tbl_content_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_episode`
--
ALTER TABLE `tbl_episode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_general_setting`
--
ALTER TABLE `tbl_general_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `tbl_gift`
--
ALTER TABLE `tbl_gift`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_gift_transaction`
--
ALTER TABLE `tbl_gift_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_hashtag`
--
ALTER TABLE `tbl_hashtag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_history`
--
ALTER TABLE `tbl_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_interests_category`
--
ALTER TABLE `tbl_interests_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_interests_hashtag`
--
ALTER TABLE `tbl_interests_hashtag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_language`
--
ALTER TABLE `tbl_language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_like`
--
ALTER TABLE `tbl_like`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_live_history`
--
ALTER TABLE `tbl_live_history`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_live_user`
--
ALTER TABLE `tbl_live_user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_notification`
--
ALTER TABLE `tbl_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_onboarding_screen`
--
ALTER TABLE `tbl_onboarding_screen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_package`
--
ALTER TABLE `tbl_package`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_package_detail`
--
ALTER TABLE `tbl_package_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_page`
--
ALTER TABLE `tbl_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_payment_option`
--
ALTER TABLE `tbl_payment_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_playlist_content`
--
ALTER TABLE `tbl_playlist_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_post`
--
ALTER TABLE `tbl_post`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_post_comment`
--
ALTER TABLE `tbl_post_comment`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_post_content`
--
ALTER TABLE `tbl_post_content`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_post_like`
--
ALTER TABLE `tbl_post_like`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_post_report`
--
ALTER TABLE `tbl_post_report`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_post_view`
--
ALTER TABLE `tbl_post_view`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_radio_content`
--
ALTER TABLE `tbl_radio_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_read_notification`
--
ALTER TABLE `tbl_read_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_rent_section`
--
ALTER TABLE `tbl_rent_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_rent_transaction`
--
ALTER TABLE `tbl_rent_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_report_reason`
--
ALTER TABLE `tbl_report_reason`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_section`
--
ALTER TABLE `tbl_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_smtp_setting`
--
ALTER TABLE `tbl_smtp_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_social_link`
--
ALTER TABLE `tbl_social_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_subscriber`
--
ALTER TABLE `tbl_subscriber`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_view`
--
ALTER TABLE `tbl_view`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_watch_later`
--
ALTER TABLE `tbl_watch_later`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_withdrawal_request`
--
ALTER TABLE `tbl_withdrawal_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
