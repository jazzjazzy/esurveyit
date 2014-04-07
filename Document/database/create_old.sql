# ---------------------------------------------------------------------- #
# Script generated with: DeZign for Databases v6.0.0                     #
# Target DBMS:           MySQL 5                                         #
# Project file:          esurveyit.dez                                   #
# Project name:                                                          #
# Author:                                                                #
# Script type:           Database creation script                        #
# Created on:            2011-05-18 15:33                                #
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
    `survey_type` ENUM('free','silver','gold'),
    `start_date` DATETIME,
    `end_date` DATETIME,
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
# Add table "question_single"                                            #
# ---------------------------------------------------------------------- #

CREATE TABLE `question_single` (
    `single_id` INTEGER NOT NULL AUTO_INCREMENT,
    `question_id` VARCHAR(40) NOT NULL,
    CONSTRAINT `PK_question_single` PRIMARY KEY (`single_id`, `question_id`)
);

# ---------------------------------------------------------------------- #
# Add table "question_multi"                                             #
# ---------------------------------------------------------------------- #

CREATE TABLE `question_multi` (
    `multi_id` INTEGER NOT NULL AUTO_INCREMENT,
    `question_id` INTEGER NOT NULL,
    `question_row` INTEGER,
    `row_label` VARCHAR(40),
    CONSTRAINT `PK_question_multi` PRIMARY KEY (`multi_id`, `question_id`)
);

# ---------------------------------------------------------------------- #
# Add table "question_array"                                             #
# ---------------------------------------------------------------------- #

CREATE TABLE `question_array` (
    `array_id` INTEGER NOT NULL AUTO_INCREMENT,
    `question_id` INTEGER NOT NULL,
    `question_row` INTEGER,
    `row_label` VARCHAR(255),
    `question_column` INTEGER,
    `column_label` VARCHAR(255),
    CONSTRAINT `PK_question_array` PRIMARY KEY (`array_id`, `question_id`)
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

ALTER TABLE `question_single` ADD CONSTRAINT `question_question_single` 
    FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`);

ALTER TABLE `question_multi` ADD CONSTRAINT `question_question_multi` 
    FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`);

ALTER TABLE `question_array` ADD CONSTRAINT `question_question_array` 
    FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`);
