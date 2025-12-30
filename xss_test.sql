CREATE DATABASE xss_demo;
USE xss_demo;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_comment TEXT NOT NULL
);

INSERT INTO users (username, password) VALUES ('admin', '123');