CREATE TABLE IF NOT EXISTS `notes` (
 `note_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `note_text` text NOT NULL,
 `user_id` int(11) unsigned NOT NULL,
 `note_deleted` tinyint(1) unsigned DEFAULT NULL,
 `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
 `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
 PRIMARY KEY (`note_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User notes, CRUD';

INSERT INTO notes (note_text, user_id, created_at) 
VALUES('This is a test for user 1', '1', '2016-06-11 09:20:14');

INSERT INTO notes (note_text, user_id, updated_at, created_at) 
VALUES('This is another test for user 1', '1', '2016-06-21 09:20:14', '2016-06-11 09:20:14');