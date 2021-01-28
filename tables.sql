CREATE DATABASE IF NOT EXISTS `LeitnerBox`;

use `LeitnerBox`;

CREATE TABLE IF NOT EXISTS `box`(
    id int AUTO_INCREMENT,
    title char(255),
    description_text TEXT,
    ownerId int,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS `section`(
    id int AUTO_INCREMENT,
    box_id int,
    prev_section int,
    next_section int,
    PRIMARY KEY(id),
    FOREIGN KEY(`box_id`) REFERENCES `box`(id) ON DELETE CASCADE,
    FOREIGN KEY(`prev_section`) REFERENCES `section`(id),
    FOREIGN KEY(`prev_section`) REFERENCES `section`(id)
);

CREATE TABLE IF NOT EXISTS `card`(
    id int AUTO_INCREMENT,
    front_text TEXT,
    back_text TEXT,
    audio_name CHAR(255),
    section_id int,
    PRIMARY KEY(id),
    FOREIGN KEY(`section_id`) REFERENCES `section`(id) ON DELETE CASCADE
);
