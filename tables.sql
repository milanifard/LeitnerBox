CREATE TABLE IF NOT EXISTS `box`(
    id int AUTO_INCREMENT,
    title char(255),
    description_text TEXT,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS `section`(
    id int AUTO_INCREMENT,

    PRIMARY KEY(id),
    FOREIGN KEY(`box_id`) REFERENCES `box`(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `card`(
    id int AUTO_INCREMENT,
    front_text TEXT,
    back_text TEXT,
    audio_name CHAR(255),
    PRIMARY KEY(id),
    FOREIGN KEY(`section_id`) REFERENCES `section`(id)) ON DELETE CASCADE
);
