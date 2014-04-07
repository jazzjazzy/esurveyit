# ---------------------------------------------------------------------- #
# Script generated with: DeZign for Databases v6.0.0                     #
# Target DBMS:           MySQL 5                                         #
# Project file:          esurveyit.dez                                   #
# Project name:                                                          #
# Author:                                                                #
# Script type:           Database creation script                        #
# Created on:            2011-07-13 16:28                                #
# ---------------------------------------------------------------------- #


# ---------------------------------------------------------------------- #
# Tables                                                                 #
# ---------------------------------------------------------------------- #

# ---------------------------------------------------------------------- #
# Add table "survey"                                                     #
# ---------------------------------------------------------------------- #

CREATE TABLE `survey` (
    `survey_id` INTEGER NOT NULL AUTO_INCREMENT,
    `account_id` INTEGER NOT NULL,
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
    CONSTRAINT `PK_survey` PRIMARY KEY (`survey_id`, `account_id`),
    CHECK (start_date>=end_date)
);

# ---------------------------------------------------------------------- #
# Add table "question"                                                   #
# ---------------------------------------------------------------------- #

CREATE TABLE `question` (
    `question_id` INTEGER NOT NULL,
    `survey_id` INTEGER NOT NULL,
    `question_label` TEXT,
    `question_type` VARCHAR(40),
    `question_options` TEXT,
    `sort` INTEGER,
    `delete_check` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`question_id`, `survey_id`)
);

# ---------------------------------------------------------------------- #
# Add table "response_multi"                                             #
# ---------------------------------------------------------------------- #

CREATE TABLE `response_multi` (
    `response_id` INTEGER NOT NULL,
    `question_id` INTEGER NOT NULL,
    `survey_id` INTEGER NOT NULL,
    `question_row` VARCHAR(40) NOT NULL,
    `multi_value` TEXT,
    CONSTRAINT `PK_question_multi` PRIMARY KEY (`response_id`, `question_id`, `survey_id`, `question_row`)
);

# ---------------------------------------------------------------------- #
# Add table "response_single"                                            #
# ---------------------------------------------------------------------- #

CREATE TABLE `response_single` (
    `response_id` INTEGER NOT NULL,
    `question_id` INTEGER NOT NULL,
    `survey_id` INTEGER NOT NULL,
    `single_value` TEXT,
    CONSTRAINT `PK_response_single` PRIMARY KEY (`response_id`, `question_id`, `survey_id`)
);

# ---------------------------------------------------------------------- #
# Add table "response_array"                                             #
# ---------------------------------------------------------------------- #

CREATE TABLE `response_array` (
    `response_id` INTEGER NOT NULL,
    `question_id` INTEGER NOT NULL,
    `survey_id` INTEGER NOT NULL,
    `question_row` INTEGER NOT NULL,
    `question_column` INTEGER NOT NULL,
    `array_value` TEXT,
    CONSTRAINT `PK_response_array` PRIMARY KEY (`response_id`, `question_id`, `survey_id`, `question_row`, `question_column`)
);

# ---------------------------------------------------------------------- #
# Add table "question_multi"                                             #
# ---------------------------------------------------------------------- #

CREATE TABLE `question_multi` (
    `question_id` INTEGER NOT NULL,
    `survey_id` INTEGER NOT NULL,
    `question_row` INTEGER NOT NULL,
    `row_label` VARCHAR(40),
    `delete_check` TINYINT(1) NOT NULL DEFAULT 0,
    CONSTRAINT `PK_question_multi` PRIMARY KEY (`question_id`, `survey_id`, `question_row`)
);

# ---------------------------------------------------------------------- #
# Add table "question_array"                                             #
# ---------------------------------------------------------------------- #

CREATE TABLE `question_array` (
    `question_id` INTEGER NOT NULL,
    `survey_id` INTEGER NOT NULL,
    `question_row` INTEGER NOT NULL,
    `question_column` INTEGER NOT NULL,
    `row_label` VARCHAR(255),
    `column_label` VARCHAR(255),
    `delete_check` TINYINT(1) NOT NULL DEFAULT 0,
    CONSTRAINT `PK_question_array` PRIMARY KEY (`question_id`, `survey_id`, `question_row`, `question_column`)
);

# ---------------------------------------------------------------------- #
# Add table "survey_response"                                            #
# ---------------------------------------------------------------------- #

CREATE TABLE `survey_response` (
    `response_id` INTEGER NOT NULL AUTO_INCREMENT,
    `survey_id` INTEGER NOT NULL,
    `question_id` INTEGER NOT NULL,
    CONSTRAINT `PK_survey_response` PRIMARY KEY (`response_id`, `survey_id`, `question_id`)
);

# ---------------------------------------------------------------------- #
# Add table "account"                                                    #
# ---------------------------------------------------------------------- #

CREATE TABLE `account` (
    `account_id` BIGINT NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(80),
    `password` VARCHAR(40),
    `name` VARCHAR(40),
    `surname` VARCHAR(40),
    `company` VARCHAR(255),
    `phone` VARCHAR(40),
    `address` VARCHAR(255),
    `address2` VARCHAR(255),
    `suburb` VARCHAR(40),
    `postcode` VARCHAR(10),
    `state` VARCHAR(40),
    `create_date` DATETIME,
    `modifiy_date` DATETIME,
    `delete_date` DATETIME,
    CONSTRAINT `PK_account` PRIMARY KEY (`account_id`)
);

# ---------------------------------------------------------------------- #
# Add table "country"                                                    #
# ---------------------------------------------------------------------- #

CREATE TABLE `country` (
    `code` CHAR(4),
    `label` VARCHAR(255)
);

# ---------------------------------------------------------------------- #
# Add table "invoice"                                                    #
# ---------------------------------------------------------------------- #

CREATE TABLE `invoice` (
    `invoice_id` BIGINT NOT NULL AUTO_INCREMENT,
    `account_id` BIGINT,
    `invoice_type` ENUM('Single','account'),
    `invoice_card` VARCHAR(40),
    `invoice_card_name` VARCHAR(40),
    `invoice_card_date` VARCHAR(40),
    `days` INTEGER,
    `create_date` DATE,
    CONSTRAINT `PK_invoice` PRIMARY KEY (`invoice_id`)
);

# ---------------------------------------------------------------------- #
# Foreign key constraints                                                #
# ---------------------------------------------------------------------- #

ALTER TABLE `question` ADD CONSTRAINT `survey_question` 
    FOREIGN KEY (`survey_id`) REFERENCES `survey` (`survey_id`);

ALTER TABLE `response_multi` ADD CONSTRAINT `question_response_multi` 
    FOREIGN KEY (`question_id`, `survey_id`) REFERENCES `question` (`question_id`,`survey_id`);

ALTER TABLE `response_multi` ADD CONSTRAINT `survey_response_response_multi` 
    FOREIGN KEY (`response_id`, `survey_id`, `question_id`) REFERENCES `survey_response` (`response_id`,`survey_id`,`question_id`);

ALTER TABLE `response_single` ADD CONSTRAINT `question_response_single` 
    FOREIGN KEY (`question_id`, `survey_id`) REFERENCES `question` (`question_id`,`survey_id`);

ALTER TABLE `response_single` ADD CONSTRAINT `survey_response_response_single` 
    FOREIGN KEY (`response_id`, `survey_id`, `question_id`) REFERENCES `survey_response` (`response_id`,`survey_id`,`question_id`);

ALTER TABLE `response_array` ADD CONSTRAINT `question_response_array` 
    FOREIGN KEY (`question_id`, `survey_id`) REFERENCES `question` (`question_id`,`survey_id`);

ALTER TABLE `response_array` ADD CONSTRAINT `survey_response_response_array` 
    FOREIGN KEY (`response_id`, `survey_id`, `question_id`) REFERENCES `survey_response` (`response_id`,`survey_id`,`question_id`);

ALTER TABLE `question_multi` ADD CONSTRAINT `question_question_multi` 
    FOREIGN KEY (`question_id`, `survey_id`) REFERENCES `question` (`question_id`,`survey_id`);

ALTER TABLE `question_array` ADD CONSTRAINT `question_question_array` 
    FOREIGN KEY (`question_id`, `survey_id`) REFERENCES `question` (`question_id`,`survey_id`);

ALTER TABLE `survey_response` ADD CONSTRAINT `survey_survey_response` 
    FOREIGN KEY (`survey_id`) REFERENCES `survey` (`survey_id`);

ALTER TABLE `survey_response` ADD CONSTRAINT `question_survey_response` 
    FOREIGN KEY (`question_id`, `survey_id`) REFERENCES `question` (`question_id`,`survey_id`);

ALTER TABLE `account` ADD CONSTRAINT `survey_account` 
    FOREIGN KEY (`account_id`) REFERENCES `survey` (`account_id`);

ALTER TABLE `invoice` ADD CONSTRAINT `account_invoice` 
    FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`);
