CREATE DATABASE IF NOT EXISTS `LeitnerBox` char set utf8 collate utf8_persian_ci;

use `LeitnerBox`;

CREATE TABLE IF NOT EXISTS `box`(
    id int AUTO_INCREMENT,
    title char(255),
    description_text TEXT,
    ownerId int,
    default_section int,
    created_at DATETIME,
    PRIMARY KEY(id),
    FOREIGN KEY(`default_section`) REFERENCES `section`(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `section`(
    id int AUTO_INCREMENT,
    box_id int,
    prev_section int,
    next_section int,
    created_at DATETIME,
    PRIMARY KEY(id),
    FOREIGN KEY(`box_id`) REFERENCES `box`(id) ON DELETE CASCADE,
    FOREIGN KEY(`prev_section`) REFERENCES `section`(id) ON DELETE SET NULL,
    FOREIGN KEY(`next_section`) REFERENCES `section`(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS `card`(
    id int AUTO_INCREMENT,
    front_text TEXT NOT NULL,
    back_text TEXT  NOT NULL,
    front_image_name CHAR(255),
    front_audio_name CHAR(255),
    back_image_name CHAR(255),
    back_audio_name CHAR(255),
    section_id int NOT NULL,
    created_at DATETIME,
    PRIMARY KEY(id),
    FOREIGN KEY(`section_id`) REFERENCES `section`(id) ON DELETE CASCADE
);
