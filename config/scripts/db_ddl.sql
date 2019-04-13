CREATE DATABASE bookflix0;

CREATE TABLE `books` (
    `id` CHAR(36) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `author` VARCHAR(255),
    `publisher` VARCHAR(255),
    `description` TEXT,
    `year` VARCHAR(255),
    `no_of_pages` INT(9) DEFAULT '0',
    `cover` VARCHAR(255),
    `category` VARCHAR(255),
    `date_added` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
)  ENGINE=INNODB;

CREATE TABLE `users` (
    `id` CHAR(36) NOT NULL,
    `name` VARCHAR(255),
    `email` VARCHAR(255),
    `phone1` VARCHAR(255),
    `phone2` VARCHAR(255),
    `last_login` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `date_registered` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`email`)
)  ENGINE=INNODB;

CREATE TABLE `user_readings` (
    `id` CHAR(36) NOT NULL,
    `user_email` VARCHAR(255),
    `book_id` CHAR(36),
    `page_no` INT(9),
    `date_added` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
)  ENGINE=INNODB;

CREATE TABLE `user_books` (
    `id` CHAR(36) NOT NULL,
    `user_email` VARCHAR(255),
    `book_id` CHAR(36),
    `date_added` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
)  ENGINE=INNODB;