# ---------------------------------------------------------------------- #
# Script generated with: DeZign for Databases v6.0.0                     #
# Target DBMS:           MySQL 5                                         #
# Project file:          esurveyit.dez                                   #
# Project name:                                                          #
# Author:                                                                #
# Script type:           Database creation script                        #
# Created on:            2011-06-03 11:23                                #
# ---------------------------------------------------------------------- #


# ---------------------------------------------------------------------- #
# Tables                                                                 #
# ---------------------------------------------------------------------- #

# ---------------------------------------------------------------------- #
# Add table "survey"                                                     #
# ---------------------------------------------------------------------- #

CREATE TABLE `survey` (
    `survey_id` INTEGER NOT NULL AUTO_INCREMENT,
    `account_id` INTEGER,
    `survey_title` VARCHAR(255),
    `survey_description` TEXT,
    `mainContent` VARCHAR(40),
    `title_color` VARCHAR(40),
    `details_color` VARCHAR(40),
    `question_block` VARCHAR(40),
    `question_label_bg` VARCHAR(40),
    `question_label_color` VARCHAR(40),
    `title_font` VARCHAR(40),
    `details_font` VARCHAR(40),
    `question_label_font` VARCHAR(40),
    `question_block_font` VARCHAR(40),
    `survey_type` ENUM('free','single','account'),
    `start_date` DATETIME DEFAULT null,
    `end_date` DATETIME DEFAULT null,
    `network_type` VARCHAR(40),
    `network_value` VARCHAR(40),
    `create_date` DATETIME DEFAULT NULL,
    `modify_date` DATETIME DEFAULT NULL,
    `delete_date` DATETIME DEFAULT NULL,
    CONSTRAINT `PK_survey` PRIMARY KEY (`survey_id`),
    CHECK (start_date>=end_date)
);

# ---------------------------------------------------------------------- #
# Add table "question"                                                   #
# ---------------------------------------------------------------------- #

CREATE TABLE `question` (
    `question_id` INTEGER NOT NULL AUTO_INCREMENT,
    `question_label` TEXT,
    `question_type` VARCHAR(40),
    `question_options` TEXT,
    PRIMARY KEY (`question_id`)
);

# ---------------------------------------------------------------------- #
# Add table "survey_question"                                            #
# ---------------------------------------------------------------------- #

CREATE TABLE `survey_question` (
    `survey_id` INTEGER NOT NULL,
    `question_id` INTEGER NOT NULL,
    `sort` INTEGER,
    CONSTRAINT `PK_survey_question` PRIMARY KEY (`survey_id`, `question_id`)
);

# ---------------------------------------------------------------------- #
# Add table "responce_multi"                                             #
# ---------------------------------------------------------------------- #

CREATE TABLE `responce_multi` (
    `multi_id` INTEGER NOT NULL AUTO_INCREMENT,
    `question_id` INTEGER NOT NULL,
    `multi_label` VARCHAR(40),
    `multi_value` TEXT,
    CONSTRAINT `PK_question_multi` PRIMARY KEY (`multi_id`, `question_id`)
);

# ---------------------------------------------------------------------- #
# Add table "responce_single"                                            #
# ---------------------------------------------------------------------- #

CREATE TABLE `responce_single` (
    `single_id` INTEGER NOT NULL AUTO_INCREMENT,
    `question_id` INTEGER NOT NULL,
    `single_value` TEXT,
    CONSTRAINT `PK_responce_single` PRIMARY KEY (`single_id`, `question_id`)
);

# ---------------------------------------------------------------------- #
# Add table "responce_array"                                             #
# ---------------------------------------------------------------------- #

CREATE TABLE `responce_array` (
    `array_id` INTEGER NOT NULL AUTO_INCREMENT,
    `question_id` INTEGER NOT NULL,
    `question_row` INTEGER,
    `question_column` VARCHAR(40),
    `array_value` TEXT,
    `array_label` VARCHAR(40) COMMENT 'This table is used to store information returned from a question that is array type',
    CONSTRAINT `PK_responce_array` PRIMARY KEY (`array_id`, `question_id`)
);

# ---------------------------------------------------------------------- #
# Add table "question_multi"                                             #
# ---------------------------------------------------------------------- #

CREATE TABLE `question_multi` (
    `question_id` INTEGER NOT NULL,
    `question_row` INTEGER NOT NULL,
    `row_label` VARCHAR(40),
    CONSTRAINT `PK_question_multi` PRIMARY KEY (`question_id`, `question_row`)
);

# ---------------------------------------------------------------------- #
# Add table "question_array"                                             #
# ---------------------------------------------------------------------- #

CREATE TABLE `question_array` (
    `question_id` INTEGER NOT NULL,
    `question_row` INTEGER NOT NULL,
    `question_column` INTEGER NOT NULL,
    `row_label` VARCHAR(255),
    `column_label` VARCHAR(255),
    CONSTRAINT `PK_question_array` PRIMARY KEY (`question_id`, `question_row`, `question_column`)
);

# ---------------------------------------------------------------------- #
# Add table "survey_reponse"                                             #
# ---------------------------------------------------------------------- #

CREATE TABLE `survey_reponse` (
    `responce_id` INTEGER NOT NULL,
    `survey_id` INTEGER NOT NULL,
    `question_id` INTEGER NOT NULL,
    CONSTRAINT `PK_survey_reponse` PRIMARY KEY (`responce_id`, `survey_id`, `question_id`)
);

# ---------------------------------------------------------------------- #
# Foreign key constraints                                                #
# ---------------------------------------------------------------------- #

ALTER TABLE `survey_question` ADD CONSTRAINT `survey_survey_question` 
    FOREIGN KEY (`survey_id`) REFERENCES `survey` (`survey_id`);

ALTER TABLE `survey_question` ADD CONSTRAINT `question_survey_question` 
    FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`);

ALTER TABLE `responce_multi` ADD CONSTRAINT `question_question_multi` 
    FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`);

ALTER TABLE `responce_single` ADD CONSTRAINT `question_responce_single` 
    FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`);

ALTER TABLE `responce_array` ADD CONSTRAINT `question_responce_array` 
    FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`);

ALTER TABLE `question_multi` ADD CONSTRAINT `question_question_multi` 
    FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`);

ALTER TABLE `question_array` ADD CONSTRAINT `question_question_array` 
    FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`);

ALTER TABLE `survey_reponse` ADD CONSTRAINT `survey_survey_reponse` 
    FOREIGN KEY (`survey_id`) REFERENCES `survey` (`survey_id`);

ALTER TABLE `survey_reponse` ADD CONSTRAINT `question_survey_reponse` 
    FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`);
