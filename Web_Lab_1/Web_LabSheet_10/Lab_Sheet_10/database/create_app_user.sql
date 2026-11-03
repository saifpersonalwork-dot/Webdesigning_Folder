-- Run as a MySQL administrator. Replace the password before running.
CREATE USER IF NOT EXISTS 'webapp_user'@'localhost' IDENTIFIED BY 'ChangeThis_StrongPassword!';
GRANT SELECT, INSERT, UPDATE, DELETE ON webapp_db.* TO 'webapp_user'@'localhost';
FLUSH PRIVILEGES;
