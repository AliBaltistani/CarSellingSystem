-- Complete Database Schema for Car Selling Web App
-- MySQL 8.0+ Compatible

-- Users table (extends Laravel default)
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) NULL,
  `whatsapp` varchar(20) NULL,
  `avatar` varchar(255) NULL,
  `latitude` decimal(10,7) NULL,
  `longitude` decimal(10,7) NULL,
  `city` varchar(100) NULL,
  `state` varchar(100) NULL,
  `country` varchar(100) NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `remember_token` varchar(100) NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories table
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `description` text NULL,
  `icon` varchar(255) NULL,
  `image` varchar(255) NULL,
  `parent_id` bigint unsigned NULL,
  `order` int DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_slug_index` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) 
    REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cars table (main table)
CREATE TABLE `cars` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  
  -- Basic Info
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `description` text NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `negotiable` tinyint(1) DEFAULT 0,
  
  -- Car Details
  `year` year NOT NULL,
  `make` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `trim` varchar(100) NULL,
  `vin` varchar(17) NULL UNIQUE,
  `condition` enum('new', 'used', 'certified') DEFAULT 'used',
  `mileage` int unsigned NULL COMMENT 'in kilometers',
  `registration_number` varchar(50) NULL,
  
  -- Specifications
  `transmission` enum('automatic', 'manual', 'cvt', 'semi-automatic') NOT NULL,
  `fuel_type` enum('petrol', 'diesel', 'electric', 'hybrid', 'cng', 'lpg') NOT NULL,
  `engine_capacity` varchar(20) NULL COMMENT 'e.g., 2.0L, 1500cc',
  `horsepower` int unsigned NULL,
  `cylinders` tinyint unsigned NULL,
  `body_type` varchar(50) NULL COMMENT 'sedan, suv, coupe, etc',
  `exterior_color` varchar(50) NULL,
  `interior_color` varchar(50) NULL,
  `doors` tinyint unsigned NULL,
  `seats` tinyint unsigned NULL,
  `
` enum('front', 'rear', 'all', '4x4') NULL,
  
  -- Features (JSON)
  `features` json NULL COMMENT 'safety, comfort, tech features',
  
  -- Location
  `latitude` decimal(10,7) NULL,
  `longitude` decimal(10,7) NULL,
  `address` varchar(255) NULL,
  `city` varchar(100) NULL,
  `state` varchar(100) NULL,
  `country` varchar(100) NOT NULL DEFAULT 'UAE',
  `postal_code` varchar(20) NULL,
  
  -- Contact
  `whatsapp_number` varchar(20) NOT NULL,
  `phone_number` varchar(20) NULL,
  `email` varchar(255) NULL,
  
  -- Status & Visibility
  `status` enum('available', 'sold', 'pending', 'reserved') DEFAULT 'available',
  `is_published` tinyint(1) DEFAULT 1,
  `is_featured` tinyint(1) DEFAULT 0,
  `featured_until` timestamp NULL,
  
  -- SEO
  `meta_title` varchar(255) NULL,
  `meta_description` text NULL,
  `meta_keywords` varchar(255) NULL,
  
  -- Tracking
  `views_count` int unsigned DEFAULT 0,
  `favorites_count` int unsigned DEFAULT 0,
  `inquiries_count` int unsigned DEFAULT 0,
  
  -- Timestamps
  `published_at` timestamp NULL,
  `sold_at` timestamp NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  
  PRIMARY KEY (`id`),
  KEY `cars_user_id_foreign` (`user_id`),
  KEY `cars_category_id_foreign` (`category_id`),
  KEY `cars_slug_index` (`slug`),
  KEY `cars_status_index` (`status`),
  KEY `cars_is_published_index` (`is_published`),
  KEY `cars_location_index` (`latitude`, `longitude`),
  KEY `cars_price_index` (`price`),
  KEY `cars_year_index` (`year`),
  FULLTEXT KEY `cars_search_index` (`title`, `description`),
  
  CONSTRAINT `cars_user_id_foreign` FOREIGN KEY (`user_id`) 
    REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cars_category_id_foreign` FOREIGN KEY (`category_id`) 
    REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Car Images table (if not using Media Library)
CREATE TABLE `car_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `car_id` bigint unsigned NOT NULL,
  `path` varchar(255) NOT NULL,
  `thumbnail_path` varchar(255) NULL,
  `title` varchar(255) NULL,
  `alt_text` varchar(255) NULL,
  `order` int DEFAULT 0,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `car_images_car_id_foreign` (`car_id`),
  CONSTRAINT `car_images_car_id_foreign` FOREIGN KEY (`car_id`) 
    REFERENCES `cars` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Favorites table
CREATE TABLE `favorites` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `car_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `favorites_user_car_unique` (`user_id`, `car_id`),
  KEY `favorites_user_id_foreign` (`user_id`),
  KEY `favorites_car_id_foreign` (`car_id`),
  CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) 
    REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favorites_car_id_foreign` FOREIGN KEY (`car_id`) 
    REFERENCES `cars` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inquiries table
CREATE TABLE `inquiries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `car_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NULL,
  `message` text NOT NULL,
  `status` enum('new', 'contacted', 'closed') DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inquiries_car_id_foreign` (`car_id`),
  KEY `inquiries_user_id_foreign` (`user_id`),
  CONSTRAINT `inquiries_car_id_foreign` FOREIGN KEY (`car_id`) 
    REFERENCES `cars` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inquiries_user_id_foreign` FOREIGN KEY (`user_id`) 
    REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Settings table
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL UNIQUE,
  `value` text NULL,
  `type` varchar(50) DEFAULT 'string',
  `group` varchar(100) DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `settings_key_index` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Car views tracking
CREATE TABLE `car_views` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `car_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `car_views_car_id_foreign` (`car_id`),
  KEY `car_views_user_id_foreign` (`user_id`),
  KEY `car_views_created_at_index` (`created_at`),
  CONSTRAINT `car_views_car_id_foreign` FOREIGN KEY (`car_id`) 
    REFERENCES `cars` (`id`) ON DELETE CASCADE,
  CONSTRAINT `car_views_user_id_foreign` FOREIGN KEY (`user_id`) 
    REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Saved searches
CREATE TABLE `saved_searches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `filters` json NOT NULL COMMENT 'search criteria',
  `notify_new_results` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `saved_searches_user_id_foreign` (`user_id`),
  CONSTRAINT `saved_searches_user_id_foreign` FOREIGN KEY (`user_id`) 
    REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample Settings Data
INSERT INTO `settings` (`key`, `value`, `type`, `group`) VALUES
('site_name', 'Xenon Motors', 'string', 'general'),
('site_tagline', 'Buy and Sell Quality Cars', 'string', 'general'),
('site_email', 'info@xenonmotors.com', 'string', 'general'),
('site_phone', '+971 50 123 4567', 'string', 'general'),
('whatsapp_number', '+971501234567', 'string', 'contact'),
('default_currency', 'AED', 'string', 'general'),
('distance_unit', 'km', 'string', 'general'),
('default_radius', '50', 'integer', 'location'),
('cars_per_page', '20', 'integer', 'display'),
('google_analytics_id', '', 'string', 'seo'),
('meta_description', 'Find your perfect car at Xenon Motors', 'text', 'seo'),
('meta_keywords', 'cars, buy car, sell car, used cars, Dubai cars', 'text', 'seo');

-- Sample Categories
INSERT INTO `categories` (`name`, `slug`, `description`, `order`, `is_active`) VALUES
('Sedan', 'sedan', 'Comfortable 4-door passenger cars', 1, 1),
('SUV', 'suv', 'Sport Utility Vehicles', 2, 1),
('Coupe', 'coupe', 'Sporty 2-door cars', 3, 1),
('Hatchback', 'hatchback', 'Compact cars with rear door', 4, 1),
('Pickup Truck', 'pickup-truck', 'Trucks with open cargo area', 5, 1),
('Van', 'van', 'Multi-passenger vehicles', 6, 1),
('Convertible', 'convertible', 'Cars with retractable roof', 7, 1),
('Sports Car', 'sports-car', 'High-performance vehicles', 8, 1),
('Luxury', 'luxury', 'Premium luxury vehicles', 9, 1),
('Electric', 'electric', 'Electric and hybrid vehicles', 10, 1);

-- Indexes for performance
CREATE INDEX idx_cars_location_status ON cars(latitude, longitude, status, is_published);
CREATE INDEX idx_cars_price_year ON cars(price, year);
CREATE INDEX idx_cars_make_model ON cars(make, model);

-- View for car statistics
CREATE VIEW car_statistics AS
SELECT 
    c.id,
    c.title,
    c.views_count,
    c.favorites_count,
    c.inquiries_count,
    COUNT(DISTINCT cv.id) as unique_views,
    COUNT(DISTINCT f.id) as favorites,
    COUNT(DISTINCT i.id) as inquiries
FROM cars c
LEFT JOIN car_views cv ON c.id = cv.car_id
LEFT JOIN favorites f ON c.id = f.car_id
LEFT JOIN inquiries i ON c.id = i.car_id
GROUP BY c.id;
