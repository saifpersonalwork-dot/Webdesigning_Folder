CREATE DATABASE IF NOT EXISTS mca108p_app
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Replace the password with a long, unique password.
CREATE USER IF NOT EXISTS 'mca108p_user'@'localhost'
  IDENTIFIED BY 'CHANGE_THIS_STRONG_PASSWORD';

GRANT SELECT, INSERT, UPDATE, DELETE
  ON mca108p_app.* TO 'mca108p_user'@'localhost';

USE mca108p_app;

CREATE TABLE IF NOT EXISTS products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  price DECIMAL(10,2) NOT NULL CHECK (price >= 0),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  failed_attempts INT NOT NULL DEFAULT 0,
  locked_until DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS password_resets (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  token_hash CHAR(64) NOT NULL,
  expires_at DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_password_reset_user FOREIGN KEY (user_id)
    REFERENCES users(id) ON DELETE CASCADE
);

-- Optional sample data:
INSERT INTO products (name, price) VALUES
('Keyboard', 799.00), ('Mouse', 499.00);
