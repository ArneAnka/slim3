CREATE TABLE IF NOT EXISTS `subscriptions` (
 `subscription_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `user_id` text NOT NULL,
 `due_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
 `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
 `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
 PRIMARY KEY (`subscription_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User subscriptions';

CREATE TABLE `subscriptions` (
	`subscription_id`	INTEGER PRIMARY KEY AUTOINCREMENT,
	`user_id`	INTEGER,
	`due_date`	TEXT,
	`created_at`	TEXT,
	`updated_at`	TEXT
);