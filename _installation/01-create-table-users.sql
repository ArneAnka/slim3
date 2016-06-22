CREATE TABLE IF NOT EXISTS `users` (
 `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',
 `session_id` varchar(48) DEFAULT NULL COMMENT 'stores session cookie id to prevent session concurrency',
 `user_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'user''s name, unique',
 `user_slug` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'user''s name sluggified, unique',
 `user_password_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'user''s password in salted and hashed format',
 `user_email` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'user''s email, unique',
 `user_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'user''s activation status',
 `user_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'user''s deletion status',
 `user_account_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'user''s account type (basic, premium, etc)',
 `user_has_avatar` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 if user has a local avatar, 0 if not',
 `user_remember_me_token` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'user''s remember-me cookie token',
 `user_suspension_timestamp` bigint(20) DEFAULT NULL COMMENT 'Timestamp till the end of a user suspension',
 `user_last_login_timestamp` bigint(20) DEFAULT NULL COMMENT 'timestamp of user''s last login',
 `user_failed_logins` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'user''s failed login attempts',
 `user_last_failed_login` int(10) DEFAULT NULL COMMENT 'unix timestamp of last failed login attempt',
 `user_activation_hash` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'user''s email verification hash string',
 `user_profile` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Is the profile public, 1 is yes.',
 `user_password_reset_hash` char(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'user''s password reset code',
 `user_password_reset_timestamp` bigint(20) DEFAULT NULL COMMENT 'timestamp of the password reset request',
 `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
 `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
 PRIMARY KEY (`user_id`),
 UNIQUE KEY `user_name` (`user_name`),
 UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='user data';

INSERT INTO users (user_name, user_password_hash, user_email, user_account_type) 
VALUES('demo', '$2y$10$w92ibsqyGjlGBL8vQipbJeS9BCUcbC8j0vxIgfC5ShxAdcsXC/s3W', 'john@example.com', '1');

INSERT INTO users (user_name, user_password_hash, user_email) 
VALUES('demo2', '$2y$10$w92ibsqyGjlGBL8vQipbJeS9BCUcbC8j0vxIgfC5ShxAdcsXC/s3W', 'demo@example.com');

INSERT INTO users (user_name, user_password_hash, user_email, user_deleted) 
VALUES('mrSpam', '$2y$10$w92ibsqyGjlGBL8vQipbJeS9BCUcbC8j0vxIgfC5ShxAdcsXC/s3W', 'spam.alot@example.com', '1');