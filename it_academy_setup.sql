-- ============================
-- it_academy_setup.sql
-- phpMyAdmin-де осыны орындаңыз
-- ============================

-- 1. Дерекқор жасау
CREATE DATABASE IF NOT EXISTS it_academy
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE it_academy;

-- 2. users кестесі
CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    first_name  VARCHAR(100)  NOT NULL,
    last_name   VARCHAR(100)  NOT NULL,
    email       VARCHAR(191)  NOT NULL UNIQUE,
    phone       VARCHAR(20),
    role        ENUM('student','teacher') DEFAULT 'student',
    password    VARCHAR(255)  NOT NULL,   -- bcrypt хэш
    avatar      VARCHAR(10)   DEFAULT '🧑‍💻',
    created_at  DATETIME      DEFAULT CURRENT_TIMESTAMP
);

-- 3. Тест деректері (қажет болса)
-- INSERT INTO users (first_name, last_name, email, phone, role, password, avatar)
-- VALUES ('Алі', 'Тестов', 'ali@test.kz', '+77001234567', 'student',
--         '$2y$10$examplehashedpassword', '🎓');
