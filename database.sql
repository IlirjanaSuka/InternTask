CREATE DATABASE IF NOT EXISTS Task;
USE Task;

CREATE TABLE short_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    original_url VARCHAR(255) NOT NULL,
    short_link VARCHAR(6) NOT NULL UNIQUE,
    expires_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE short_links ADD COLUMN expiration_time DATETIME NULL;

select* from short_links;

drop table short_links;
