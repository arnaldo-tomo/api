-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 11-Mar-2025 às 19:38
-- Versão do servidor: 10.4.28-MariaDB
-- versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `api_mtaco`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `budgets`
--

CREATE TABLE `budgets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `budgets`
--

INSERT INTO `budgets` (`id`, `user_id`, `category_id`, `amount`, `created_at`, `updated_at`) VALUES
(2, 1, 4, 800.00, '2025-03-11 17:41:49', '2025-03-11 17:41:49');

-- --------------------------------------------------------

--
-- Estrutura da tabela `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Alimentação', '2024-08-31 17:30:15', '2024-08-31 17:30:15'),
(2, 'Transporte', '2024-08-31 17:30:15', '2024-08-31 17:30:15'),
(3, 'Saúde', '2024-08-31 17:30:15', '2024-08-31 17:30:15'),
(4, 'Educação', '2024-08-31 17:30:15', '2024-08-31 17:30:15'),
(5, 'Entretenimento', '2024-08-31 17:30:15', '2024-08-31 17:30:15'),
(6, 'Outros', '2024-08-31 17:30:15', '2024-08-31 17:30:15'),
(7, 'Alimentação', '2024-08-31 17:30:26', '2024-08-31 17:30:26'),
(8, 'Transporte', '2024-08-31 17:30:26', '2024-08-31 17:30:26'),
(9, 'Saúde', '2024-08-31 17:30:26', '2024-08-31 17:30:26'),
(10, 'Educação', '2024-08-31 17:30:26', '2024-08-31 17:30:26'),
(11, 'Entretenimento', '2024-08-31 17:30:26', '2024-08-31 17:30:26'),
(12, 'Outros', '2024-08-31 17:30:26', '2024-08-31 17:30:26');

-- --------------------------------------------------------

--
-- Estrutura da tabela `entries`
--

CREATE TABLE `entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `origim` varchar(255) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `entries`
--

INSERT INTO `entries` (`id`, `user_id`, `amount`, `origim`, `entry_date`, `created_at`, `updated_at`) VALUES
(33, 1, 69088.00, 'buscar', '2024-10-03', '2024-10-03 14:54:21', '2024-10-03 14:54:21'),
(34, 1, 10000.00, 'Bolada de gajas', '2022-10-04', '2024-10-04 07:15:40', '2024-10-04 07:15:40'),
(35, 1, 69800.00, 'Salário', '2024-10-04', '2024-10-04 13:50:00', '2024-10-04 13:50:00'),
(36, 1, 80.00, 'gg', '2025-03-11', '2025-03-11 14:32:34', '2025-03-11 14:32:34');

-- --------------------------------------------------------

--
-- Estrutura da tabela `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `expense_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `expenses`
--

INSERT INTO `expenses` (`id`, `user_id`, `category_id`, `amount`, `expense_date`, `created_at`, `updated_at`) VALUES
(37, 1, 9, 200.00, '2023-10-03', '2024-10-03 13:53:11', '2024-10-03 13:53:11'),
(38, 1, 9, 100.00, '2024-10-03', '2024-10-03 13:53:51', '2024-10-03 13:53:51'),
(39, 1, 9, 1000.00, '2023-10-03', '2024-10-03 13:55:20', '2024-10-03 13:55:20'),
(40, 1, 9, 599.00, '2024-10-03', '2024-10-03 13:55:57', '2024-10-03 13:55:57'),
(41, 1, 9, 500.00, '2024-10-03', '2024-10-03 13:56:10', '2024-10-03 13:56:10'),
(42, 1, 2, 1999.00, '2024-10-04', '2024-10-03 23:05:40', '2024-10-03 23:05:40'),
(43, 1, 5, 20000.00, '2024-10-04', '2024-10-04 07:16:08', '2024-10-04 07:16:08'),
(44, 1, 2, 800.00, '2024-10-04', '2024-10-04 09:16:32', '2024-10-04 09:16:32'),
(45, 1, 4, 50.00, '2024-10-04', '2024-10-04 09:28:13', '2024-10-04 09:28:13'),
(46, 1, 4, 500.00, '2025-03-11', '2025-03-11 17:45:19', '2025-03-11 17:45:19');

-- --------------------------------------------------------

--
-- Estrutura da tabela `failed_jobs`
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
-- Estrutura da tabela `financial_goals`
--

CREATE TABLE `financial_goals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `target_amount` decimal(10,2) NOT NULL,
  `initial_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `current_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `target_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `financial_goals`
--

INSERT INTO `financial_goals` (`id`, `user_id`, `title`, `description`, `target_amount`, `initial_amount`, `current_amount`, `target_date`, `created_at`, `updated_at`) VALUES
(1, 1, 'Toyota Hillux GD6', 'Carro', 2000000.00, 10000.00, 12000.00, '2025-12-12', '2025-03-11 17:56:58', '2025-03-11 18:19:17');

-- --------------------------------------------------------

--
-- Estrutura da tabela `goal_contributions`
--

CREATE TABLE `goal_contributions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `goal_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `contribution_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `goal_contributions`
--

INSERT INTO `goal_contributions` (`id`, `goal_id`, `user_id`, `amount`, `contribution_date`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2000.00, '2025-03-11', '2025-03-11 18:19:17', '2025-03-11 18:19:17');

-- --------------------------------------------------------

--
-- Estrutura da tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(11, '2025_03_11_190006_create_financial_goals_table', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `personal_access_tokens`
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

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `saldo` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name`, `saldo`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Arnaldo Tomo', 128119, 'arnaldotomo@gmail.com', NULL, '$2y$12$IczEbjik6ftmlYSTQ.NFs.z8YThLL/099.1jcFuemuSDWkygv/DTG', NULL, '2024-08-31 19:07:54', '2025-03-11 18:04:33'),
(6, 'Arnaldo Tomo', NULL, 'arnaldo@example.com', NULL, '$2y$12$InH7xHbSvmLV2We3Yq6phOR9qRov.zXDZzoc6SmwOl6T2g9StrsMq', NULL, '2024-08-31 19:37:51', '2024-08-31 19:37:51'),
(7, 'Maria Silva', NULL, 'maria@example.com', NULL, '$2y$12$oojaGTi.X5e0z7nkYOX7oeB3oCtZeH2j.x/6.3JhrsitGcRnnxl3O', NULL, '2024-08-31 19:37:51', '2024-08-31 19:37:51'),
(8, 'ggsgdh', NULL, 'arnaldotovmo@gmail.com', NULL, '$2y$12$m7xjJlaQjE0KDvv1WW3eRua/Kg4SM4pGY7q3sKYCmL1vQaQCmgt4a', NULL, '2025-03-11 14:43:59', '2025-03-11 14:43:59');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `budgets_user_id_category_id_unique` (`user_id`,`category_id`),
  ADD KEY `budgets_category_id_foreign` (`category_id`);

--
-- Índices para tabela `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `entries`
--
ALTER TABLE `entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entries_user_id_foreign` (`user_id`);

--
-- Índices para tabela `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_user_id_foreign` (`user_id`),
  ADD KEY `expenses_category_id_foreign` (`category_id`);

--
-- Índices para tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Índices para tabela `financial_goals`
--
ALTER TABLE `financial_goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `financial_goals_user_id_foreign` (`user_id`);

--
-- Índices para tabela `goal_contributions`
--
ALTER TABLE `goal_contributions`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Índices para tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `entries`
--
ALTER TABLE `entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de tabela `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `financial_goals`
--
ALTER TABLE `financial_goals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `goal_contributions`
--
ALTER TABLE `goal_contributions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `budgets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `entries`
--
ALTER TABLE `entries`
  ADD CONSTRAINT `entries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `financial_goals`
--
ALTER TABLE `financial_goals`
  ADD CONSTRAINT `financial_goals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
