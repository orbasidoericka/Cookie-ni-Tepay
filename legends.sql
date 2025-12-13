-- Buttercloud Bakery Database Schema
-- Updated: December 13, 2025
-- 
-- This is the current database schema with unnecessary tables removed.
-- Tables removed: cache, cache_locks, failed_jobs, jobs, job_batches, sessions
-- Columns removed: users.email_verified_at
-- 
-- Database: `legends`
--

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_10_122903_create_products_table', 1),
(5, '2025_12_10_124707_create_orders_table', 1),
(6, '2025_12_10_124800_create_order_items_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `customer_name`, `contact_number`, `total_amount`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'ORD-693985A618A95-20251210', 1, 'Mayumi Muchachi', '09277675124', 65.00, 'completed', NULL, '2025-12-10 06:37:26', '2025-12-10 06:37:26'),
(2, 'ORD-69398675E1185-20251210', 1, 'Mayumi Muchachi', '09277675124', 160.00, 'completed', NULL, '2025-12-10 06:40:53', '2025-12-10 06:40:53'),
(3, 'ORD-6939868987668-20251210', 1, 'Mayumi Muchachi', '09277675124', 65.00, 'completed', NULL, '2025-12-10 06:41:13', '2025-12-10 06:41:13'),
(4, 'ORD-693986AE6BE4B-20251210', 1, 'Mayumi Muchachi', '09277675124', 1820.00, 'completed', NULL, '2025-12-10 06:41:50', '2025-12-10 06:41:50');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Butter Croissant', 65.00, 1, 65.00, '2025-12-10 06:37:26', '2025-12-10 06:37:26'),
(2, 2, 5, 'Almond Croissant', 85.00, 1, 85.00, '2025-12-10 06:40:53', '2025-12-10 06:40:53'),
(3, 2, 2, 'Chocolate Danish', 75.00, 1, 75.00, '2025-12-10 06:40:53', '2025-12-10 06:40:53'),
(4, 3, 1, 'Butter Croissant', 65.00, 1, 65.00, '2025-12-10 06:41:13', '2025-12-10 06:41:13'),
(5, 4, 1, 'Butter Croissant', 65.00, 28, 1820.00, '2025-12-10 06:41:50', '2025-12-10 06:41:50');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `stock`, `created_at`, `updated_at`) VALUES
(1, 'Butter Croissant', 'Flaky, buttery croissant with golden layers. Baked fresh daily with premium French butter.', 65.00, NULL, 0, '2025-12-10 06:34:54', '2025-12-10 06:41:50'),
(2, 'Chocolate Danish', 'Rich chocolate filling wrapped in delicate pastry, topped with chocolate drizzle.', 75.00, NULL, 24, '2025-12-10 06:34:54', '2025-12-10 06:40:53'),
(3, 'Blueberry Muffin', 'Moist vanilla muffin loaded with fresh blueberries and a sweet crumb topping.', 55.00, NULL, 40, '2025-12-10 06:34:54', '2025-12-10 06:34:54'),
(4, 'Cinnamon Roll', 'Soft, gooey cinnamon roll with cream cheese frosting. A sweet breakfast favorite.', 80.00, NULL, 20, '2025-12-10 06:34:54', '2025-12-10 06:34:54'),
(5, 'Almond Croissant', 'Butter croissant filled with sweet almond cream and topped with sliced almonds.', 85.00, NULL, 17, '2025-12-10 06:34:54', '2025-12-10 06:40:53'),
(6, 'Apple Turnover', 'Flaky puff pastry filled with spiced apple filling and dusted with cinnamon sugar.', 60.00, NULL, 28, '2025-12-10 06:34:54', '2025-12-10 06:34:54'),
(7, 'Lemon Tart', 'Tangy lemon curd in a buttery shortbread crust, topped with toasted meringue.', 95.00, NULL, 15, '2025-12-10 06:34:54', '2025-12-10 06:34:54'),
(8, 'Chocolate Chip Scone', 'Tender scone studded with chocolate chips. Perfect with your morning coffee.', 58.00, NULL, 35, '2025-12-10 06:34:54', '2025-12-10 06:34:54'),
(9, 'Malunggay Pandesal', 'Traditional Filipino bread roll infused with nutritious malunggay leaves. Soft and healthy.', 50.00, NULL, 50, '2025-12-10 06:34:54', '2025-12-10 06:34:54');

-- --------------------------------------------------------
--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`),
  ADD KEY `order_items_order_id_product_id_index` (`order_id`,`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
