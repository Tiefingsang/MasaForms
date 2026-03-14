-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : sam. 14 mars 2026 à 17:25
-- Version du serveur : 8.0.45-0ubuntu0.24.04.1
-- Version de PHP : 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `masaforms`
--

-- --------------------------------------------------------

--
-- Structure de la table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `permissions` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `forms`
--

CREATE TABLE `forms` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `thank_you_message` text COLLATE utf8mb4_unicode_ci,
  `logo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3B82F6',
  `background_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#FFFFFF',
  `is_public` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `accepts_responses` tinyint(1) NOT NULL DEFAULT '1',
  `show_progress_bar` tinyint(1) NOT NULL DEFAULT '1',
  `captcha_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `max_responses` int DEFAULT NULL,
  `current_responses` int NOT NULL DEFAULT '0',
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `forms`
--

INSERT INTO `forms` (`id`, `user_id`, `title`, `slug`, `description`, `thank_you_message`, `logo_path`, `cover_image`, `primary_color`, `background_color`, `is_public`, `is_active`, `accepts_responses`, `show_progress_bar`, `captcha_enabled`, `max_responses`, `current_responses`, `start_date`, `end_date`, `password`, `settings`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'formulaire1', 'Dl2pa5JPpq', 'formulaire de test', NULL, NULL, NULL, '#8ff0a4', '#9a9996', 1, 1, 1, 1, 0, NULL, 0, NULL, NULL, NULL, '[]', '2026-03-12 21:22:46', '2026-03-12 21:22:46', NULL),
(2, 2, 'formulaire1', 'bXkfpfV68P', 'formulaire de test', NULL, NULL, NULL, '#8ff0a4', '#9a9996', 1, 1, 1, 1, 0, NULL, 0, NULL, NULL, NULL, '[]', '2026-03-12 21:23:40', '2026-03-12 21:23:40', NULL),
(3, 2, 'Formulaire1', 'e2nK7Ntx8K', 'Formulaire de test', NULL, 'form-logos/n2EY7bLmA0RrgWcZCX2IWPCTZ5fs6FyRYNBHkkEi.png', 'form-covers/tBfVUStn4jGPiLpvihrNPlwA6jt9NCcZszyhYE7S.png', '#F59E0B', '#c0bfbc', 1, 1, 1, 1, 0, NULL, 2, NULL, NULL, NULL, '[]', '2026-03-12 22:11:53', '2026-03-13 18:36:49', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `form_fields`
--

CREATE TABLE `form_fields` (
  `id` bigint UNSIGNED NOT NULL,
  `form_id` bigint UNSIGNED NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `placeholder` text COLLATE utf8mb4_unicode_ci,
  `help_text` text COLLATE utf8mb4_unicode_ci,
  `options` json DEFAULT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `default_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `validation_rules` json DEFAULT NULL,
  `conditional_logic` json DEFAULT NULL,
  `file_types` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_file_size` int DEFAULT NULL,
  `min_length` int DEFAULT NULL,
  `max_length` int DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `form_fields`
--

INSERT INTO `form_fields` (`id`, `form_id`, `label`, `name`, `type`, `placeholder`, `help_text`, `options`, `is_required`, `order`, `default_value`, `validation_rules`, `conditional_logic`, `file_types`, `max_file_size`, `min_length`, `max_length`, `is_active`, `created_at`, `updated_at`) VALUES
(10, 3, 'Nom et prenom', 'nom-et-prenom_Hsd5K', 'text', 'Sangare tiefing', NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-03-12 23:06:19', '2026-03-12 23:06:19'),
(11, 3, 'Email', 'email_qNX8c', 'email', 'Entrer votre email', NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-03-12 23:06:19', '2026-03-12 23:06:19'),
(12, 3, 'Tél', 'tel_W9FIM', 'tel', '66894475', NULL, NULL, 0, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-03-12 23:06:19', '2026-03-12 23:06:19');

-- --------------------------------------------------------

--
-- Structure de la table `form_responses`
--

CREATE TABLE `form_responses` (
  `id` bigint UNSIGNED NOT NULL,
  `form_id` bigint UNSIGNED NOT NULL,
  `respondent_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `respondent_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `location_data` json DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '1',
  `completion_time` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `form_responses`
--

INSERT INTO `form_responses` (`id`, `form_id`, `respondent_name`, `respondent_email`, `ip_address`, `user_agent`, `location_data`, `is_completed`, `completion_time`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:148.0) Gecko/20100101 Firefox/148.0', NULL, 1, NULL, '2026-03-13 18:35:56', '2026-03-13 18:35:56'),
(2, 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, 1, NULL, '2026-03-13 18:36:49', '2026-03-13 18:36:49');

-- --------------------------------------------------------

--
-- Structure de la table `integrations`
--

CREATE TABLE `integrations` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `credentials` json DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_03_10_130050_create_plans_table', 1),
(5, '2026_03_10_130050_create_subscriptions_table', 1),
(6, '2026_03_10_175434_create_payments_table', 1),
(7, '2026_03_10_175521_create_forms_table', 1),
(8, '2026_03_10_175609_create_form_fields', 1),
(9, '2026_03_10_175643_create_form_responses', 1),
(10, '2026_03_10_175729_create_response_values', 1),
(11, '2026_03_10_175814_create_templates_table', 1),
(12, '2026_03_10_175849_create_integrations', 1),
(13, '2026_03_10_192125_create_permission_tables', 1),
(14, '2026_03_11_230317_add_soft_deletes_to_users_table', 2),
(15, '2026_03_12_090026_create_notifications_table', 3),
(16, '2026_03_12_194406_create_webhook_logs_table', 4),
(17, '2026_03_12_194903_create_team_invitations_table', 5),
(18, '2026_03_12_202320_create_api_keys_table', 6),
(19, '2026_03_14_113944_add_payment_fields_to_plans_table', 7),
(20, '2026_03_14_140519_fix_plan_price_types', 8),
(21, '2026_03_14_160720_add_plan_id_to_payments_table', 9),
(22, '2026_03_14_163342_add_plan_id_to_users_table', 10);

-- --------------------------------------------------------

--
-- Structure de la table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('047c675e-23ff-4110-94be-a1c3f1c18632', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:47:09.359915Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:47:09', '2026-03-12 10:48:08'),
('09756e8a-70e2-428e-847a-a0b8630eb6d6', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:41.895383Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:41', '2026-03-12 10:48:09'),
('0a7aa396-bc59-474b-935b-335576d34a50', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:40.654658Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:40', '2026-03-12 10:48:09'),
('14cdcc01-6bcc-440c-a070-4714a1136160', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:47:27.397583Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:47:27', '2026-03-12 10:48:08'),
('183dd244-6d0c-439d-8c48-8f4797eecc49', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:41.701678Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:41', '2026-03-12 10:48:09'),
('1b799077-ff9c-4735-b439-71a1d371951b', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:42.600794Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:42', '2026-03-12 10:48:09'),
('1d526ba6-9ae0-4f7c-a14b-16deb0d6f62b', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:42.428224Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:42', '2026-03-12 10:48:09'),
('1d902cc5-0bf6-4d6a-8936-75ae88f7af2a', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:41.021077Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:41', '2026-03-12 10:48:09'),
('1f893e56-f654-41eb-b235-5d7e3d367c2d', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:41.182237Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:41', '2026-03-12 10:48:09'),
('2083a816-a86d-4ccc-8fc9-b105cf3490ce', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:41.016006Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:41', '2026-03-12 10:48:09'),
('216ab484-1758-42e4-a104-d13d18f6a864', 'App\\Notifications\\SubscriptionActivatedNotification', 'App\\Models\\User', 2, '{\"title\":\"\\ud83c\\udf89 Abonnement activ\\u00e9\",\"message\":\"Votre abonnement Business a \\u00e9t\\u00e9 activ\\u00e9 avec succ\\u00e8s. Profitez de toutes les fonctionnalit\\u00e9s !\",\"subscription_id\":5,\"plan\":\"Business\",\"type\":\"subscription_activated\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/plans\",\"action_text\":\"G\\u00e9rer mon abonnement\",\"created_at\":\"2026-03-13 20:24:11\"}', '2026-03-14 15:57:21', '2026-03-13 20:24:11', '2026-03-14 15:57:21'),
('21ac0484-fd7b-4026-8716-88b54446c4ad', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:40.474862Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:40', '2026-03-12 10:48:09'),
('260c4bcc-1d95-444f-8e4b-4bc3476405ab', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:43.636022Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:43', '2026-03-12 10:48:09'),
('2759880a-0489-4664-9e6f-24bafbea8fa2', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:43.477785Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:44:43', '2026-03-12 10:48:08'),
('2a5cfd91-dd84-4867-b7f1-6394cde649d2', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:43.460116Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:44:43', '2026-03-12 10:48:08'),
('2decae14-20cf-44fb-87e4-734cf4f2d215', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:43.469008Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:44:43', '2026-03-12 10:48:08'),
('334cc7fe-6bc2-454e-820f-1673afbdacfb', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:41.539498Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:41', '2026-03-12 10:48:09'),
('33a5c9ab-518c-443b-a9d2-2bb004b444f0', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:48:27.427573Z\"}', '2026-03-12 10:57:46', '2026-03-12 10:48:27', '2026-03-12 10:57:46'),
('3576744d-26cb-4fef-925c-b0b4fc505da5', 'App\\Notifications\\SubscriptionActivatedNotification', 'App\\Models\\User', 2, '{\"title\":\"\\ud83c\\udf89 Abonnement activ\\u00e9\",\"message\":\"Votre abonnement Pro a \\u00e9t\\u00e9 activ\\u00e9 avec succ\\u00e8s. Profitez de toutes les fonctionnalit\\u00e9s !\",\"subscription_id\":2,\"plan\":\"Pro\",\"type\":\"subscription_activated\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/plans\",\"action_text\":\"G\\u00e9rer mon abonnement\",\"created_at\":\"2026-03-12 12:38:35\"}', '2026-03-14 15:57:21', '2026-03-12 12:38:35', '2026-03-14 15:57:21'),
('35e6d0de-2abd-4076-bdd4-f976fb85911a', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:42.774706Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:42', '2026-03-12 10:48:09'),
('3d8baa3b-2755-44db-9eac-e3aa8bab31c9', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:47:09.347068Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:47:09', '2026-03-12 10:48:08'),
('44b6a9dd-5260-4821-9524-5aac7a2b494f', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:43.113709Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:44:43', '2026-03-12 10:48:08'),
('47402366-ac47-4776-a915-69381d4ff843', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:48:27.436885Z\"}', '2026-03-12 10:57:46', '2026-03-12 10:48:27', '2026-03-12 10:57:46'),
('50467bf1-b39b-467c-b453-cb7249a665aa', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"\\ud83d\\udccb Test de notification\",\"message\":\"Ceci est une notification de test cr\\u00e9\\u00e9e depuis Tinker\",\"created_at\":\"2026-03-12T11:19:53.056376Z\"}', '2026-03-14 15:57:21', '2026-03-12 11:19:53', '2026-03-14 15:57:21'),
('5470dd7e-7f1b-4708-a792-d4699d52ffe0', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:43.648969Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:44:43', '2026-03-12 10:48:08'),
('57cb9f84-55a6-41d1-901c-b648a6638d25', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Test de notification\",\"message\":\"Ceci est une notification de test\",\"created_at\":\"2026-03-12T10:28:39.320020Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:28:39', '2026-03-12 10:48:09'),
('582614d6-469d-4a07-a095-2e25dbe0ac9d', 'App\\Notifications\\SubscriptionActivatedNotification', 'App\\Models\\User', 2, '{\"title\":\"\\ud83c\\udf89 Abonnement activ\\u00e9\",\"message\":\"Votre abonnement Gratuit a \\u00e9t\\u00e9 activ\\u00e9 avec succ\\u00e8s. Profitez de toutes les fonctionnalit\\u00e9s !\",\"subscription_id\":6,\"plan\":\"Gratuit\",\"type\":\"subscription_activated\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/plans\",\"action_text\":\"G\\u00e9rer mon abonnement\",\"created_at\":\"2026-03-14 12:11:36\"}', '2026-03-14 15:57:21', '2026-03-14 12:11:36', '2026-03-14 15:57:21'),
('5cd4069e-56d8-4bc6-ad3a-a364e8adf1b3', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:41.714818Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:41', '2026-03-12 10:48:09'),
('5e16e5fa-313a-4fba-ba44-a6aa3675982b', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:40.667425Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:40', '2026-03-12 10:48:09'),
('6029d57a-e123-446b-9d76-8c07f7790693', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:43.299549Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:44:43', '2026-03-12 10:48:08'),
('60de0fb8-4e95-47cd-b5bc-6f701d227dd3', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:47:27.383536Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:47:27', '2026-03-12 10:48:08'),
('60f38310-6112-4060-a6c0-50edb28a028d', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:43.286999Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:44:43', '2026-03-12 10:48:08'),
('6417e857-4bc1-420e-a1d7-3d91ad9306b8', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:42.954515Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:42', '2026-03-12 10:48:09'),
('6590d350-f1f3-424d-ae1d-89e75fd71020', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:42.593485Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:42', '2026-03-12 10:48:09'),
('692423fe-5a0c-4189-bc83-4e56c6887910', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:42.949452Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:42', '2026-03-12 10:48:09'),
('6dd8e879-b54d-4f28-92f0-74539fbadc84', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:41.708862Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:41', '2026-03-12 10:48:09'),
('72b5474d-5ec6-409c-9614-5ce6d3090f7b', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Test de notification\",\"message\":\"Ceci est une notification de test\",\"created_at\":\"2026-03-12T10:30:21.960013Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:30:21', '2026-03-12 10:48:09'),
('75d768e0-1781-4f18-89c2-c80cc13f55f1', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:43.828178Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:44:43', '2026-03-12 10:48:08'),
('76672a1a-8b2b-468c-b239-3885f8a5f835', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:43.643409Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:44:43', '2026-03-12 10:48:08'),
('78d13d57-052f-4a66-a237-c096a2d2f99d', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:42.434577Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:42', '2026-03-12 10:48:09'),
('793a26da-90fd-47f8-8bc5-5093857c87f9', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:41.364578Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:41', '2026-03-12 10:48:09'),
('7fe38b17-4716-4f0d-bcb3-96f473ad9db9', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:41.532165Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:41', '2026-03-12 10:48:09'),
('8526020c-ca16-4165-b4f2-ec13fc9c6a0f', 'App\\Notifications\\SubscriptionActivatedNotification', 'App\\Models\\User', 2, '{\"title\":\"\\ud83c\\udf89 Abonnement activ\\u00e9\",\"message\":\"Votre abonnement Gratuit a \\u00e9t\\u00e9 activ\\u00e9 avec succ\\u00e8s. Profitez de toutes les fonctionnalit\\u00e9s !\",\"subscription_id\":7,\"plan\":\"Gratuit\",\"type\":\"subscription_activated\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/plans\",\"action_text\":\"G\\u00e9rer mon abonnement\",\"created_at\":\"2026-03-14 12:19:18\"}', '2026-03-14 15:57:21', '2026-03-14 12:19:18', '2026-03-14 15:57:21'),
('86557fb7-3bd8-4247-9b4b-b7b032f127f9', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:43.808114Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:44:43', '2026-03-12 10:48:08'),
('8871aea8-0bdd-4936-9d42-cf3304287a8d', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:43.121307Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:44:43', '2026-03-12 10:48:08'),
('89f73d96-c1af-4fe9-a610-c29aca773f13', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:42.941177Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:42', '2026-03-12 10:48:09'),
('8fdc9c0f-0f5c-4dbd-ae37-0555a766f549', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:43.821173Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:44:43', '2026-03-12 10:48:08'),
('963f745f-f600-4aca-8fb3-a7109d8db7ff', 'App\\Notifications\\SubscriptionActivatedNotification', 'App\\Models\\User', 2, '{\"title\":\"\\ud83c\\udf89 Abonnement activ\\u00e9\",\"message\":\"Votre abonnement Gratuit a \\u00e9t\\u00e9 activ\\u00e9 avec succ\\u00e8s. Profitez de toutes les fonctionnalit\\u00e9s !\",\"subscription_id\":3,\"plan\":\"Gratuit\",\"type\":\"subscription_activated\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/plans\",\"action_text\":\"G\\u00e9rer mon abonnement\",\"created_at\":\"2026-03-13 20:19:15\"}', '2026-03-14 15:57:21', '2026-03-13 20:19:15', '2026-03-14 15:57:21'),
('9a623c2c-e206-4198-9d46-046e2348489b', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:42.073913Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:42', '2026-03-12 10:48:09'),
('9b43dc83-ade2-4dfc-ba79-8d0362a691f1', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:42.421122Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:42', '2026-03-12 10:48:09'),
('9c41b878-57d0-410c-a2e5-cac693bd43c1', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:41.195152Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:41', '2026-03-12 10:48:09'),
('9dbe8dc7-8e1e-4128-a80a-8c1000f6c639', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:41.544913Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:41', '2026-03-12 10:48:09'),
('9eb00e29-5e0f-4424-987a-c2783a64cee3', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:42.606060Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:42', '2026-03-12 10:48:09'),
('a029b979-d486-4326-a158-854b7008211c', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:48:27.442013Z\"}', '2026-03-12 10:57:46', '2026-03-12 10:48:27', '2026-03-12 10:57:46'),
('a0f17b7f-c311-4158-bbcf-787b8fd4f226', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:42.061409Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:42', '2026-03-12 10:48:09'),
('adef6e75-257f-4696-803b-be9ae8461523', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:40.662365Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:40', '2026-03-12 10:48:09'),
('ae01d39c-069e-4131-a4ba-a2d3c8bd69ea', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:44.045457Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:44:44', '2026-03-12 10:48:08'),
('af40e487-e984-48c2-9341-4fe1a81e682b', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:44.032920Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:44:44', '2026-03-12 10:48:08'),
('b0430a62-e4fa-403a-a578-737ce325eb18', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:40.850467Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:40', '2026-03-12 10:48:09'),
('b2a0853a-cb44-42bf-9449-04b7e7381ce5', 'App\\Notifications\\SubscriptionActivatedNotification', 'App\\Models\\User', 2, '{\"title\":\"\\ud83c\\udf89 Abonnement activ\\u00e9\",\"message\":\"Votre abonnement Pro a \\u00e9t\\u00e9 activ\\u00e9 avec succ\\u00e8s. Profitez de toutes les fonctionnalit\\u00e9s !\",\"subscription_id\":4,\"plan\":\"Pro\",\"type\":\"subscription_activated\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/plans\",\"action_text\":\"G\\u00e9rer mon abonnement\",\"created_at\":\"2026-03-13 20:23:16\"}', '2026-03-14 15:57:21', '2026-03-13 20:23:16', '2026-03-14 15:57:21'),
('b5175a53-ac74-44f5-922e-c343777874d7', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Votre compte a \\u00e9t\\u00e9 cr\\u00e9\\u00e9 avec succ\\u00e8s !\",\"created_at\":\"2026-03-12T10:41:04.854179Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:41:04', '2026-03-12 10:48:09'),
('b8c4baea-a712-4d4d-93b8-c5679a823dbe', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:40.835657Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:40', '2026-03-12 10:48:09'),
('b8f33abc-2832-4fe8-9a8c-6614c8d56594', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:42.769732Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:42', '2026-03-12 10:48:09'),
('b8f4dedb-f674-4e52-aa88-2fb4a35a8d05', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:41.190059Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:41', '2026-03-12 10:48:09'),
('bc671af5-dd03-4986-9dfa-66ba3039387f', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:42.069088Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:42', '2026-03-12 10:48:09'),
('c0c5315b-148c-47a5-b752-734776bd16bb', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:44.040347Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:44:44', '2026-03-12 10:48:08'),
('c1b8bf07-66ad-4ccb-9ac5-f11d09b1d7bb', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:43.126705Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:44:43', '2026-03-12 10:48:08'),
('c4445c8f-b13a-4c65-bcd7-7b8b9e0cf122', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:42.238931Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:42', '2026-03-12 10:48:09'),
('c58fc8cf-2802-4c12-801a-24b1d549c790', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:41.370764Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:41', '2026-03-12 10:48:09'),
('c71ad592-2b90-4bde-9c90-58a647673e39', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:41.356679Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:41', '2026-03-12 10:48:09'),
('c8bf7a80-cfce-46da-b960-e22dc029e7ac', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:42.251746Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:42', '2026-03-12 10:48:09'),
('cfe43802-19e6-4a2d-9bc7-ca5e003120a0', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:42.761848Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:42', '2026-03-12 10:48:09'),
('d11b4160-c7e1-445d-b2df-2590bee080d1', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:47:09.354870Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:47:09', '2026-03-12 10:48:08'),
('d12b995e-7bb8-4756-840a-8f3a9f8a06ff', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Rappel\",\"message\":\"Vous avez 3 formulaires en attente de publication.\",\"created_at\":\"2026-03-12T10:44:40.479816Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:40', '2026-03-12 10:48:09'),
('d2276720-e995-42eb-8e7f-bd94746fcf4f', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:41.007946Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:41', '2026-03-12 10:48:09'),
('d725a56e-4bdb-4be8-8373-1f70c3db7f41', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:40.464549Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:40', '2026-03-12 10:48:09'),
('da25df1f-e95d-4ac4-b841-5147ca10cf22', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:42.246370Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:42', '2026-03-12 10:48:09'),
('daa0c563-3fd1-4a52-8820-5167d527e1c8', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:40.844875Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:40', '2026-03-12 10:48:09'),
('dbf8bf66-e81a-4ed3-b61f-34b4044c8f03', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:41.889651Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:41', '2026-03-12 10:48:09'),
('e9e366e0-b733-4340-90d7-1e912f09cc05', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:47:27.392253Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:47:27', '2026-03-12 10:48:08'),
('f656b77d-6cd4-4057-93b9-eaa03ef0b543', 'App\\Notifications\\SubscriptionActivatedNotification', 'App\\Models\\User', 2, '{\"title\":\"\\ud83c\\udf89 Abonnement activ\\u00e9\",\"message\":\"Votre abonnement Pro a \\u00e9t\\u00e9 activ\\u00e9 avec succ\\u00e8s. Profitez de toutes les fonctionnalit\\u00e9s !\",\"subscription_id\":16,\"plan\":\"Pro\",\"type\":\"subscription_activated\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/plans\",\"action_text\":\"G\\u00e9rer mon abonnement\",\"created_at\":\"2026-03-14 16:53:56\"}', NULL, '2026-03-14 16:53:56', '2026-03-14 16:53:56'),
('fa5c21b0-250e-4c0c-adb5-01a09cb7683f', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Nouvelle fonctionnalit\\u00e9\",\"message\":\"Le glisser-d\\u00e9poser est maintenant disponible pour tous vos formulaires.\",\"created_at\":\"2026-03-12T10:44:43.294336Z\"}', '2026-03-12 10:48:08', '2026-03-12 10:44:43', '2026-03-12 10:48:08'),
('fc733c0b-13bd-438f-b506-425d263d860d', 'App\\Notifications\\TestNotification', 'App\\Models\\User', 2, '{\"title\":\"Bienvenue sur Masadigitale Forms\",\"message\":\"Nous sommes ravis de vous compter parmi nos utilisateurs !\",\"created_at\":\"2026-03-12T10:44:41.882469Z\"}', '2026-03-12 10:48:09', '2026-03-12 10:44:41', '2026-03-12 10:48:09');

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `plan_id` bigint UNSIGNED DEFAULT NULL,
  `subscription_id` bigint UNSIGNED DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'XOF',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','completed','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `provider_response` json DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `plan_id`, `subscription_id`, `transaction_id`, `amount`, `currency`, `payment_method`, `payment_provider`, `status`, `provider_response`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 3, 'txn_69b471663db7a', 0.00, 'XOF', 'card', 'card', 'completed', NULL, '2026-03-13 20:19:50', '2026-03-13 20:19:50', '2026-03-13 20:19:50'),
(2, 2, NULL, 4, 'txn_69b47252a7bd5', 19.99, 'XOF', 'card', 'orange_money', 'completed', NULL, '2026-03-13 20:23:46', '2026-03-13 20:23:46', '2026-03-13 20:23:46'),
(3, 2, NULL, 5, 'txn_69b4728ac498a', 49.99, 'XOF', 'card', 'orange_money', 'completed', NULL, '2026-03-13 20:24:42', '2026-03-13 20:24:42', '2026-03-13 20:24:42'),
(4, 2, NULL, NULL, 'txn_69b5546f98355', 19990.00, 'XOF', 'moneyfusion', 'moneyfusion', 'pending', NULL, NULL, '2026-03-14 12:28:31', '2026-03-14 12:28:31'),
(5, 2, NULL, NULL, 'txn_69b5567a84327', 19990.00, 'XOF', 'moneyfusion', 'moneyfusion', 'pending', NULL, NULL, '2026-03-14 12:37:14', '2026-03-14 12:37:14'),
(6, 2, NULL, NULL, 'txn_69b55724a3d58', 33.32, 'USD', 'stripe', 'stripe', 'pending', NULL, NULL, '2026-03-14 12:40:04', '2026-03-14 12:40:04'),
(7, 2, NULL, NULL, 'txn_69b55ee76ffe9', 33.32, 'USD', 'stripe', 'stripe', 'pending', NULL, NULL, '2026-03-14 13:13:11', '2026-03-14 13:13:11'),
(8, 2, NULL, NULL, 'txn_69b56517302c3', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 13:39:35', '2026-03-14 13:39:35'),
(9, 2, NULL, NULL, 'txn_69b56912a82a0', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 13:56:34', '2026-03-14 13:56:34'),
(10, 2, NULL, NULL, 'txn_69b569af6b31c', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 13:59:11', '2026-03-14 13:59:11'),
(11, 2, NULL, NULL, 'txn_69b56a8702c26', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 14:02:47', '2026-03-14 14:02:47'),
(12, 2, NULL, NULL, 'txn_69b56a9f8a941', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 14:03:11', '2026-03-14 14:03:11'),
(13, 2, NULL, NULL, 'txn_69b56b54b1243', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 14:06:12', '2026-03-14 14:06:12'),
(14, 2, NULL, NULL, 'txn_69b56b79e3bb3', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 14:06:49', '2026-03-14 14:06:49'),
(15, 2, NULL, NULL, 'txn_69b56ca3cd9c4', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 14:11:47', '2026-03-14 14:11:47'),
(16, 2, NULL, NULL, 'txn_69b56d11a2092', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 14:13:37', '2026-03-14 14:13:37'),
(17, 2, NULL, NULL, 'txn_69b56db85dbbd', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 14:16:24', '2026-03-14 14:16:24'),
(18, 2, NULL, NULL, 'txn_69b56e3b922c3', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 14:18:35', '2026-03-14 14:18:35'),
(19, 2, NULL, NULL, 'txn_69b56e6453b43', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 14:19:16', '2026-03-14 14:19:16'),
(20, 2, NULL, NULL, 'txn_69b56f7a8f2d0', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 14:23:54', '2026-03-14 14:23:54'),
(21, 2, NULL, NULL, 'txn_69b56ffbdd45d', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 14:26:03', '2026-03-14 14:26:03'),
(22, 2, NULL, NULL, 'txn_69b570e3c4e99', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 14:29:55', '2026-03-14 14:29:55'),
(23, 2, NULL, NULL, 'txn_69b570f20c85d', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 14:30:10', '2026-03-14 14:30:10'),
(24, 2, NULL, NULL, 'txn_69b57167ed122', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 14:32:07', '2026-03-14 14:32:07'),
(25, 2, NULL, NULL, 'txn_69b57fd14a66e', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 15:33:37', '2026-03-14 15:33:37'),
(26, 2, NULL, NULL, 'txn_69b58045b495e', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 15:35:33', '2026-03-14 15:35:33'),
(27, 2, NULL, NULL, 'txn_69b58158d86d1', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 15:40:08', '2026-03-14 15:40:08'),
(28, 2, NULL, NULL, 'txn_69b582e444e79', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 15:46:44', '2026-03-14 15:46:44'),
(29, 2, NULL, NULL, 'txn_69b58345f16e8', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 15:48:21', '2026-03-14 15:48:21'),
(30, 2, NULL, NULL, 'txn_69b585b7d8e53', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 15:58:47', '2026-03-14 15:58:47'),
(31, 2, NULL, NULL, 'txn_69b5863907f3e', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 16:00:57', '2026-03-14 16:00:57'),
(32, 2, NULL, NULL, 'txn_69b586d5506c4', 49990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 16:03:33', '2026-03-14 16:03:33'),
(33, 2, NULL, NULL, 'txn_69b588f6d43e0', 19990.00, 'XOF', 'paydunya', 'paydunya', 'completed', NULL, '2026-03-14 16:13:03', '2026-03-14 16:12:38', '2026-03-14 16:13:03'),
(34, 2, NULL, NULL, 'txn_69b5895d9165a', 19990.00, 'XOF', 'paydunya', 'paydunya', 'completed', NULL, '2026-03-14 16:15:24', '2026-03-14 16:14:21', '2026-03-14 16:15:24'),
(35, 2, NULL, NULL, 'txn_69b58b8ed1dd8', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 16:23:42', '2026-03-14 16:23:42'),
(36, 2, NULL, NULL, 'txn_69b58b98adc98', 19990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 16:23:52', '2026-03-14 16:23:52'),
(37, 2, NULL, NULL, 'txn_69b58c5cba8f9', 49990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 16:27:08', '2026-03-14 16:27:08'),
(38, 2, NULL, NULL, 'txn_69b58c9c77986', 49990.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 16:28:12', '2026-03-14 16:28:12'),
(39, 2, NULL, 11, 'txn_69b58eefea20a', 49990.00, 'XOF', 'paydunya', 'paydunya', 'completed', NULL, '2026-03-14 16:38:34', '2026-03-14 16:38:07', '2026-03-14 16:38:34'),
(40, 2, NULL, 12, 'txn_69b58f22f2e79', 19990.00, 'XOF', 'paydunya', 'paydunya', 'completed', NULL, '2026-03-14 16:39:23', '2026-03-14 16:38:58', '2026-03-14 16:39:23'),
(41, 2, NULL, NULL, 'txn_69b58f48b5c90', 0.00, 'XOF', 'paydunya', 'paydunya', 'pending', NULL, NULL, '2026-03-14 16:39:36', '2026-03-14 16:39:36'),
(42, 2, NULL, 13, 'free_69b590929dc71', 0.00, 'XOF', 'free', 'free', 'completed', NULL, '2026-03-14 16:45:06', '2026-03-14 16:45:06', '2026-03-14 16:45:06'),
(43, 2, NULL, 14, 'txn_69b5909e1d20e', 19990.00, 'XOF', 'paydunya', 'paydunya', 'completed', NULL, '2026-03-14 16:45:41', '2026-03-14 16:45:18', '2026-03-14 16:45:41'),
(44, 2, NULL, 15, 'free_69b590ca101e4', 0.00, 'XOF', 'free', 'free', 'completed', NULL, '2026-03-14 16:46:02', '2026-03-14 16:46:02', '2026-03-14 16:46:02'),
(45, 2, NULL, 16, 'txn_69b59289519d1', 19990.00, 'XOF', 'paydunya', 'paydunya', 'completed', NULL, '2026-03-14 16:53:56', '2026-03-14 16:53:29', '2026-03-14 16:53:56');

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view forms', 'web', '2026-03-11 05:27:38', '2026-03-11 05:27:38'),
(2, 'create forms', 'web', '2026-03-11 05:27:38', '2026-03-11 05:27:38'),
(3, 'edit forms', 'web', '2026-03-11 05:27:38', '2026-03-11 05:27:38'),
(4, 'delete forms', 'web', '2026-03-11 05:27:38', '2026-03-11 05:27:38'),
(5, 'duplicate forms', 'web', '2026-03-11 05:27:38', '2026-03-11 05:27:38'),
(6, 'view responses', 'web', '2026-03-11 05:27:38', '2026-03-11 05:27:38'),
(7, 'export responses', 'web', '2026-03-11 05:27:38', '2026-03-11 05:27:38'),
(8, 'delete responses', 'web', '2026-03-11 05:27:38', '2026-03-11 05:27:38'),
(9, 'view plans', 'web', '2026-03-11 05:27:38', '2026-03-11 05:27:38'),
(10, 'subscribe to plans', 'web', '2026-03-11 05:27:38', '2026-03-11 05:27:38'),
(11, 'view templates', 'web', '2026-03-11 05:27:38', '2026-03-11 05:27:38'),
(12, 'use templates', 'web', '2026-03-11 05:27:38', '2026-03-11 05:27:38'),
(13, 'create templates', 'web', '2026-03-11 05:27:38', '2026-03-11 05:27:38'),
(14, 'view integrations', 'web', '2026-03-11 05:27:38', '2026-03-11 05:27:38'),
(15, 'manage integrations', 'web', '2026-03-11 05:27:38', '2026-03-11 05:27:38'),
(16, 'manage users', 'web', '2026-03-11 05:27:38', '2026-03-11 05:27:38'),
(17, 'manage all forms', 'web', '2026-03-11 05:27:39', '2026-03-11 05:27:39'),
(18, 'view analytics', 'web', '2026-03-11 05:27:39', '2026-03-11 05:27:39'),
(19, 'manage settings', 'web', '2026-03-11 05:27:39', '2026-03-11 05:27:39');

-- --------------------------------------------------------

--
-- Structure de la table `plans`
--

CREATE TABLE `plans` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `max_forms` int DEFAULT NULL,
  `max_responses_per_form` int DEFAULT NULL,
  `has_advanced_stats` tinyint(1) NOT NULL DEFAULT '0',
  `has_export_csv` tinyint(1) NOT NULL DEFAULT '1',
  `has_export_excel` tinyint(1) NOT NULL DEFAULT '0',
  `has_export_pdf` tinyint(1) NOT NULL DEFAULT '0',
  `has_multi_users` tinyint(1) NOT NULL DEFAULT '0',
  `has_automations` tinyint(1) NOT NULL DEFAULT '0',
  `has_custom_logo` tinyint(1) NOT NULL DEFAULT '0',
  `has_remove_masadigitale_logo` tinyint(1) NOT NULL DEFAULT '0',
  `has_whatsapp_integration` tinyint(1) NOT NULL DEFAULT '0',
  `has_email_notifications` tinyint(1) NOT NULL DEFAULT '0',
  `has_api_access` tinyint(1) NOT NULL DEFAULT '0',
  `has_templates` tinyint(1) NOT NULL DEFAULT '0',
  `price_monthly` decimal(10,2) NOT NULL,
  `price_yearly` decimal(10,2) NOT NULL,
  `price_monthly_usd` decimal(10,2) DEFAULT NULL,
  `price_yearly_usd` decimal(10,2) DEFAULT NULL,
  `price_monthly_eur` decimal(10,2) DEFAULT NULL,
  `price_yearly_eur` decimal(10,2) DEFAULT NULL,
  `stripe_price_id_monthly` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_price_id_yearly` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paypal_plan_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'XOF',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_popular` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `plans`
--

INSERT INTO `plans` (`id`, `name`, `slug`, `description`, `max_forms`, `max_responses_per_form`, `has_advanced_stats`, `has_export_csv`, `has_export_excel`, `has_export_pdf`, `has_multi_users`, `has_automations`, `has_custom_logo`, `has_remove_masadigitale_logo`, `has_whatsapp_integration`, `has_email_notifications`, `has_api_access`, `has_templates`, `price_monthly`, `price_yearly`, `price_monthly_usd`, `price_yearly_usd`, `price_monthly_eur`, `price_yearly_eur`, `stripe_price_id_monthly`, `stripe_price_id_yearly`, `paypal_plan_id`, `currency`, `sort_order`, `is_popular`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Gratuit', 'free', 'Pour démarrer avec des fonctionnalités de base', 3, 100, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, 'XOF', 0, 0, 1, '2026-03-10 19:22:49', '2026-03-14 11:42:04'),
(2, 'Pro', 'pro', 'Pour les professionnels ayant besoin de plus de fonctionnalités', 50, 5000, 1, 1, 1, 0, 0, 0, 1, 0, 0, 0, 0, 0, 19990.00, 199900.00, 33.32, 333.17, 30.75, 307.54, NULL, NULL, NULL, 'XOF', 0, 0, 1, '2026-03-10 19:22:49', '2026-03-14 11:45:07'),
(3, 'Business', 'business', 'Solution complète pour les entreprises', NULL, NULL, 1, 1, 1, 0, 1, 1, 1, 0, 0, 0, 0, 0, 49990.00, 499900.00, 83.32, 833.17, 76.91, 769.08, NULL, NULL, NULL, 'XOF', 0, 0, 1, '2026-03-10 19:22:49', '2026-03-14 11:45:07');

-- --------------------------------------------------------

--
-- Structure de la table `response_values`
--

CREATE TABLE `response_values` (
  `id` bigint UNSIGNED NOT NULL,
  `form_response_id` bigint UNSIGNED NOT NULL,
  `form_field_id` bigint UNSIGNED NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `file_paths` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `response_values`
--

INSERT INTO `response_values` (`id`, `form_response_id`, `form_field_id`, `value`, `file_paths`, `created_at`, `updated_at`) VALUES
(1, 1, 10, 'tiefing', NULL, '2026-03-13 18:35:56', '2026-03-13 18:35:56'),
(2, 1, 11, 'tiefing@gmail.com', NULL, '2026-03-13 18:35:56', '2026-03-13 18:35:56'),
(3, 1, 12, '66894475', NULL, '2026-03-13 18:35:56', '2026-03-13 18:35:56'),
(4, 2, 10, 'tiefing', NULL, '2026-03-13 18:36:49', '2026-03-13 18:36:49'),
(5, 2, 11, 'tiefing@example.com', NULL, '2026-03-13 18:36:49', '2026-03-13 18:36:49'),
(6, 2, 12, '66894475', NULL, '2026-03-13 18:36:49', '2026-03-13 18:36:49');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'user', 'web', '2026-03-11 05:27:39', '2026-03-11 05:27:39'),
(2, 'admin', 'web', '2026-03-11 05:27:39', '2026-03-11 05:27:39');

-- --------------------------------------------------------

--
-- Structure de la table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(14, 1),
(15, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(8, 2),
(9, 2),
(10, 2),
(11, 2),
(12, 2),
(13, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2);

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('35I07k3fhiU1SKnOpZ67PRPbhmirtVgIdpcelSgq', NULL, '127.0.0.1', 'GuzzleHttp/6.2.1 curl/7.58.0 PHP/5.6.40-8+ubuntu18.04.1+deb.sury.org+1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoidEtYOXJyNWVMTzIxelpkajdSQTY4Z1U2cXdpaEF6TW1ISHRFWW56dCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773505776),
('8bMWqgf4GW3DBnH18Phd0iRqz6CWGzPmNBpNom7N', NULL, '127.0.0.1', 'GuzzleHttp/6.2.1 curl/7.58.0 PHP/5.6.40-8+ubuntu18.04.1+deb.sury.org+1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiYVhKejc0aFVvSzFkWVVsbnc4Uml1YjcwaWNJVjJrYWZad05VeW1mTyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773505454),
('a8FdhXfQwQlmwfNBpSM47oQGTyemICoyTDvHvE5A', 2, '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:148.0) Gecko/20100101 Firefox/148.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiektSdWNsRjhMbE9laFdJb0k4R2hDTVNpSkh0enhVcHloUTdIZ2xkOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wYXltZW50L3BsYW5zLzIvcGF5bWVudC9tb250aGx5IjtzOjU6InJvdXRlIjtzOjE0OiJwYXltZW50LnNlbGVjdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1773502537),
('eKQybjQ2PlKHTIC8MS8DnhIq0JTBO0P7eE6JfmwE', NULL, '127.0.0.1', 'GuzzleHttp/6.2.1 curl/7.58.0 PHP/5.6.40-8+ubuntu18.04.1+deb.sury.org+1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoid1JvOUVkanFKOVM5Rk9vM2Nud2IyWmpxRjk2Wm9NS0hNbGdsZ2cwWiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773504077),
('fYHQecUU9NfIfTOkJGIvLmLGYJkVyJebEgyzBJtk', NULL, '127.0.0.1', 'GuzzleHttp/6.2.1 curl/7.58.0 PHP/5.6.40-8+ubuntu18.04.1+deb.sury.org+1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiWTNJTVR2NUVIeEhZV1pIT01GYzNKM29CR0Yxdksxa2o3OVFRVUtZYSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773504234),
('FZpQSyK49aWNh40RoYlB9KLewni0ug0GteCImoNA', NULL, '127.0.0.1', 'GuzzleHttp/6.2.1 curl/7.58.0 PHP/5.6.40-8+ubuntu18.04.1+deb.sury.org+1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiOTdsUmpBRTZFTjlFUGI4YnNJM0JiRGtsaHpqaGpxMkRpZVY0TmVadSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773504924),
('hi8UeLa8BscJugUDNPDaHxnzyPrOHBGH0jwgsNpD', NULL, '127.0.0.1', 'GuzzleHttp/6.2.1 curl/7.58.0 PHP/5.6.40-8+ubuntu18.04.1+deb.sury.org+1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiSlZLb2s3N0drWXE5eWtOOHNkekMzczFZRVhLeXNpMEk5djhINFVZdiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773504002),
('iLNXtWLAAHomSAkvbOlMWRCHNuGjZBiQdHG81vhV', NULL, '127.0.0.1', 'GuzzleHttp/6.2.1 curl/7.58.0 PHP/5.6.40-8+ubuntu18.04.1+deb.sury.org+1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiSW44Y3l4cnFsd0dqQW9pZEdVT3lBaXU2a0VENFZIQ1Nzb0NQdWE0OSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773504784),
('kyUz8orPV9b5LfiZtFi7IbjdKbyEtooNlVVBT1zh', NULL, '127.0.0.1', 'GuzzleHttp/6.2.1 curl/7.58.0 PHP/5.6.40-8+ubuntu18.04.1+deb.sury.org+1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiTEZmWmxGblNTNTZ1VHY0a2llNXRtYWNEVEt1UDl4R3B6U0cwZWpIciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773506364),
('nJbUD2Bxd6UxgudjPk8Y41iHZs6SqasQsOttBpul', NULL, '127.0.0.1', 'GuzzleHttp/6.2.1 curl/7.58.0 PHP/5.6.40-8+ubuntu18.04.1+deb.sury.org+1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiTDRic1h5UnJ0QzM0czdRU0tFTUxpdE10VktuT3VJdkJnYWE3bEp3RiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773503381),
('NvlJP6YMTlwGFo4QswZ8hR8i3N00KkpXa7i8dX9c', NULL, '127.0.0.1', 'GuzzleHttp/6.2.1 curl/7.58.0 PHP/5.6.40-8+ubuntu18.04.1+deb.sury.org+1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoibmF0MHNyV0tTQjU1aWJvTUoxUHRoSWxMaGhtdFc4TXp0cWVtcENsTCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773506313),
('qAxC4Jw7aXjg9GUroIX0aVBw3TQ94nyptGjtVp5n', NULL, '127.0.0.1', 'GuzzleHttp/6.2.1 curl/7.58.0 PHP/5.6.40-8+ubuntu18.04.1+deb.sury.org+1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiTmtkdmJGTmJseDJLc1V3TGZ2ZGY2NmpsaERGYlFKOUNtYjdSYmZGciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773507235),
('rqqce4PAFSQsqqaBbB94NiA1pSzE6XGR4S5Vym9K', NULL, '127.0.0.1', 'GuzzleHttp/6.2.1 curl/7.58.0 PHP/5.6.40-8+ubuntu18.04.1+deb.sury.org+1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoieWxaanl4NXM3a004aGpTcWpoY29yWDhnZ0o0VXpkOGQxNzM0OEtyeSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773505660),
('ShhEFJwBUdPsx7VQ1jRAdQbYXTCS2acBikb1BIo5', 2, '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:148.0) Gecko/20100101 Firefox/148.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUE9MWjkwUmQ5ZlVrN1FNcWY2OEVhbVdmMG5IZkxoSDd6Mk4yZUhENyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1773507240),
('zwyhIvmVHds2Mw0mLEoEVjYU44vgbilgBdcbmN4P', NULL, '127.0.0.1', 'GuzzleHttp/6.2.1 curl/7.58.0 PHP/5.6.40-8+ubuntu18.04.1+deb.sury.org+1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiNlZmZWtPTjdldzhCRWpvQXNRbEhNSGpnRjV5T2ZuRU1MRlZBR0pLcyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773506741);

-- --------------------------------------------------------

--
-- Structure de la table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `plan_id` bigint UNSIGNED NOT NULL,
  `subscription_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly',
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','cancelled','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `meta_data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `plan_id`, `subscription_type`, `starts_at`, `ends_at`, `trial_ends_at`, `cancelled_at`, `status`, `meta_data`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'monthly', '2026-03-11 22:53:33', NULL, NULL, NULL, 'cancelled', NULL, '2026-03-11 22:53:33', '2026-03-12 12:38:35'),
(2, 2, 2, 'monthly', '2026-03-12 12:38:35', NULL, NULL, NULL, 'cancelled', NULL, '2026-03-12 12:38:35', '2026-03-13 20:19:15'),
(3, 2, 1, 'monthly', '2026-03-13 20:19:50', '2026-04-13 20:19:50', NULL, NULL, 'cancelled', NULL, '2026-03-13 20:19:15', '2026-03-13 20:23:16'),
(4, 2, 2, 'monthly', '2026-03-13 20:23:46', '2026-04-13 20:23:46', NULL, NULL, 'cancelled', NULL, '2026-03-13 20:23:16', '2026-03-13 20:24:11'),
(5, 2, 3, 'monthly', '2026-03-13 20:24:42', '2026-04-13 20:24:42', NULL, NULL, 'cancelled', NULL, '2026-03-13 20:24:11', '2026-03-14 12:11:36'),
(6, 2, 1, 'monthly', '2026-03-14 12:11:36', NULL, NULL, NULL, 'cancelled', NULL, '2026-03-14 12:11:36', '2026-03-14 12:19:18'),
(7, 2, 2, 'monthly', '2026-03-14 12:19:18', NULL, NULL, NULL, 'cancelled', NULL, '2026-03-14 12:19:18', '2026-03-14 16:38:34'),
(11, 2, 3, 'monthly', '2026-03-14 16:38:34', '2026-04-14 16:38:34', NULL, NULL, 'cancelled', NULL, '2026-03-14 16:38:34', '2026-03-14 16:39:23'),
(12, 2, 2, 'monthly', '2026-03-14 16:39:23', '2026-04-14 16:39:23', NULL, NULL, 'cancelled', NULL, '2026-03-14 16:39:23', '2026-03-14 16:45:06'),
(13, 2, 1, 'monthly', '2026-03-14 16:45:06', '2026-04-14 16:45:06', NULL, NULL, 'cancelled', NULL, '2026-03-14 16:45:06', '2026-03-14 16:45:41'),
(14, 2, 2, 'monthly', '2026-03-14 16:45:41', '2026-04-14 16:45:41', NULL, NULL, 'cancelled', NULL, '2026-03-14 16:45:41', '2026-03-14 16:46:02'),
(15, 2, 1, 'monthly', '2026-03-14 16:46:02', '2026-04-14 16:46:02', NULL, NULL, 'cancelled', NULL, '2026-03-14 16:46:02', '2026-03-14 16:53:56'),
(16, 2, 2, 'monthly', '2026-03-14 16:53:56', '2026-04-14 16:53:56', NULL, NULL, 'active', NULL, '2026-03-14 16:53:56', '2026-03-14 16:53:56');

-- --------------------------------------------------------

--
-- Structure de la table `team_invitations`
--

CREATE TABLE `team_invitations` (
  `id` bigint UNSIGNED NOT NULL,
  `inviter_id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'viewer',
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `accepted_at` timestamp NULL DEFAULT NULL,
  `declined_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','accepted','declined','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `templates`
--

CREATE TABLE `templates` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `structure` json NOT NULL,
  `is_premium` tinyint(1) NOT NULL DEFAULT '0',
  `usage_count` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `plan_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('user','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `plan_id`, `name`, `email`, `email_verified_at`, `password`, `company_name`, `phone`, `avatar`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'Test User', 'test@example.com', '2026-03-10 19:22:32', '$2y$12$ZynLw0CMSvw99fKXg5MgM.UbNzRtxdIUJZtAneaSG43T/guSOt/au', NULL, NULL, NULL, 'user', 1, 'qhP0QcrRfo', '2026-03-10 19:22:32', '2026-03-10 19:22:32', NULL),
(2, 2, 'Tiefing SANGARE', 'masadigitale@gmail.com', NULL, '$2y$12$ITLWCQymJuEhRsOeOag5L.nWxL2Je9FNozPqH.wGgq0YJoi5Y8BXy', 'masa', '66777899', NULL, 'user', 1, 'OoGtTddAxW7r2VoKNmDzglkkgAggB0asGAHygAAcATBzRhzgN25SiTOrezjV', '2026-03-11 22:53:33', '2026-03-14 16:53:56', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `webhook_logs`
--

CREATE TABLE `webhook_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` json NOT NULL,
  `response` json DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'received',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `api_keys_key_unique` (`key`),
  ADD KEY `api_keys_user_id_foreign` (`user_id`),
  ADD KEY `api_keys_key_index` (`key`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `forms_slug_unique` (`slug`),
  ADD KEY `forms_user_id_foreign` (`user_id`);

--
-- Index pour la table `form_fields`
--
ALTER TABLE `form_fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `form_fields_name_unique` (`name`),
  ADD KEY `form_fields_form_id_foreign` (`form_id`);

--
-- Index pour la table `form_responses`
--
ALTER TABLE `form_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_responses_form_id_foreign` (`form_id`);

--
-- Index pour la table `integrations`
--
ALTER TABLE `integrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `integrations_user_id_foreign` (`user_id`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  ADD KEY `payments_user_id_foreign` (`user_id`),
  ADD KEY `payments_subscription_id_foreign` (`subscription_id`),
  ADD KEY `payments_plan_id_foreign` (`plan_id`);

--
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plans_slug_unique` (`slug`);

--
-- Index pour la table `response_values`
--
ALTER TABLE `response_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `response_values_form_response_id_foreign` (`form_response_id`),
  ADD KEY `response_values_form_field_id_foreign` (`form_field_id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_user_id_foreign` (`user_id`),
  ADD KEY `subscriptions_plan_id_foreign` (`plan_id`);

--
-- Index pour la table `team_invitations`
--
ALTER TABLE `team_invitations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `team_invitations_token_unique` (`token`),
  ADD KEY `team_invitations_inviter_id_foreign` (`inviter_id`),
  ADD KEY `team_invitations_email_status_index` (`email`,`status`);

--
-- Index pour la table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `templates_slug_unique` (`slug`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_plan_id_foreign` (`plan_id`);

--
-- Index pour la table `webhook_logs`
--
ALTER TABLE `webhook_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `form_fields`
--
ALTER TABLE `form_fields`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `form_responses`
--
ALTER TABLE `form_responses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `integrations`
--
ALTER TABLE `integrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `response_values`
--
ALTER TABLE `response_values`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `team_invitations`
--
ALTER TABLE `team_invitations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `webhook_logs`
--
ALTER TABLE `webhook_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `api_keys`
--
ALTER TABLE `api_keys`
  ADD CONSTRAINT `api_keys_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `forms`
--
ALTER TABLE `forms`
  ADD CONSTRAINT `forms_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `form_fields`
--
ALTER TABLE `form_fields`
  ADD CONSTRAINT `form_fields_form_id_foreign` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `form_responses`
--
ALTER TABLE `form_responses`
  ADD CONSTRAINT `form_responses_form_id_foreign` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `integrations`
--
ALTER TABLE `integrations`
  ADD CONSTRAINT `integrations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`),
  ADD CONSTRAINT `payments_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `response_values`
--
ALTER TABLE `response_values`
  ADD CONSTRAINT `response_values_form_field_id_foreign` FOREIGN KEY (`form_field_id`) REFERENCES `form_fields` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `response_values_form_response_id_foreign` FOREIGN KEY (`form_response_id`) REFERENCES `form_responses` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `team_invitations`
--
ALTER TABLE `team_invitations`
  ADD CONSTRAINT `team_invitations_inviter_id_foreign` FOREIGN KEY (`inviter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
