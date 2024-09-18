CREATE DATABASE personalfinance;

USE personalfinance;

CREATE TABLE user (
    u_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    address VARCHAR(255)
);
