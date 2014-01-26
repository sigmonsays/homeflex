# default admin user with password admin

DELETE FROM `users`;
INSERT INTO `users` VALUES (NULL, NULL, 'admin', PASSWORD('admin'), '', '', '', 3, 1, '');
