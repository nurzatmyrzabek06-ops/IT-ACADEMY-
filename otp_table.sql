-- phpMyAdmin-де осыны орындаңыз (it_academy базасында)

USE it_academy;

CREATE TABLE IF NOT EXISTS otp_codes (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    email      VARCHAR(191) NOT NULL,
    otp        VARCHAR(6)   NOT NULL,
    expires_at DATETIME     NOT NULL,
    created_at DATETIME     DEFAULT CURRENT_TIMESTAMP
);
