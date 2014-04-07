# ---------------------------------------------------------------------- #
# Script generated with: DeZign for Databases v6.0.0                     #
# Target DBMS:           MySQL 5                                         #
# Project file:          esurveyit.dez                                   #
# Project name:                                                          #
# Author:                                                                #
# Script type:           Database drop script                            #
# Created on:            2011-07-13 16:28                                #
# ---------------------------------------------------------------------- #


# ---------------------------------------------------------------------- #
# Drop foreign key constraints                                           #
# ---------------------------------------------------------------------- #

ALTER TABLE `question` DROP FOREIGN KEY `survey_question`;

ALTER TABLE `response_multi` DROP FOREIGN KEY `question_response_multi`;

ALTER TABLE `response_multi` DROP FOREIGN KEY `survey_response_response_multi`;

ALTER TABLE `response_single` DROP FOREIGN KEY `question_response_single`;

ALTER TABLE `response_single` DROP FOREIGN KEY `survey_response_response_single`;

ALTER TABLE `response_array` DROP FOREIGN KEY `question_response_array`;

ALTER TABLE `response_array` DROP FOREIGN KEY `survey_response_response_array`;

ALTER TABLE `question_multi` DROP FOREIGN KEY `question_question_multi`;

ALTER TABLE `question_array` DROP FOREIGN KEY `question_question_array`;

ALTER TABLE `survey_response` DROP FOREIGN KEY `survey_survey_response`;

ALTER TABLE `survey_response` DROP FOREIGN KEY `question_survey_response`;

ALTER TABLE `account` DROP FOREIGN KEY `survey_account`;

ALTER TABLE `invoice` DROP FOREIGN KEY `account_invoice`;

# ---------------------------------------------------------------------- #
# Drop table "response_array"                                            #
# ---------------------------------------------------------------------- #

# Drop constraints #

ALTER TABLE `response_array` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `response_array`;

# ---------------------------------------------------------------------- #
# Drop table "response_single"                                           #
# ---------------------------------------------------------------------- #

# Drop constraints #

ALTER TABLE `response_single` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `response_single`;

# ---------------------------------------------------------------------- #
# Drop table "response_multi"                                            #
# ---------------------------------------------------------------------- #

# Drop constraints #

ALTER TABLE `response_multi` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `response_multi`;

# ---------------------------------------------------------------------- #
# Drop table "invoice"                                                   #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `invoice` MODIFY `invoice_id` BIGINT NOT NULL;

# Drop constraints #

ALTER TABLE `invoice` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `invoice`;

# ---------------------------------------------------------------------- #
# Drop table "country"                                                   #
# ---------------------------------------------------------------------- #

# Drop table #

DROP TABLE `country`;

# ---------------------------------------------------------------------- #
# Drop table "account"                                                   #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `account` MODIFY `account_id` BIGINT NOT NULL;

# Drop constraints #

ALTER TABLE `account` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `account`;

# ---------------------------------------------------------------------- #
# Drop table "survey_response"                                           #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `survey_response` MODIFY `response_id` INTEGER NOT NULL;

# Drop constraints #

ALTER TABLE `survey_response` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `survey_response`;

# ---------------------------------------------------------------------- #
# Drop table "question_array"                                            #
# ---------------------------------------------------------------------- #

# Drop constraints #

ALTER TABLE `question_array` ALTER COLUMN `delete_check` DROP DEFAULT;

ALTER TABLE `question_array` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `question_array`;

# ---------------------------------------------------------------------- #
# Drop table "question_multi"                                            #
# ---------------------------------------------------------------------- #

# Drop constraints #

ALTER TABLE `question_multi` ALTER COLUMN `delete_check` DROP DEFAULT;

ALTER TABLE `question_multi` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `question_multi`;

# ---------------------------------------------------------------------- #
# Drop table "question"                                                  #
# ---------------------------------------------------------------------- #

# Drop constraints #

ALTER TABLE `question` ALTER COLUMN `delete_check` DROP DEFAULT;

ALTER TABLE `question` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `question`;

# ---------------------------------------------------------------------- #
# Drop table "survey"                                                    #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `survey` MODIFY `survey_id` INTEGER NOT NULL;

# Drop constraints #

ALTER TABLE `survey` ALTER COLUMN `start_date` DROP DEFAULT;

ALTER TABLE `survey` ALTER COLUMN `end_date` DROP DEFAULT;

ALTER TABLE `survey` ALTER COLUMN `create_date` DROP DEFAULT;

ALTER TABLE `survey` ALTER COLUMN `modify_date` DROP DEFAULT;

ALTER TABLE `survey` ALTER COLUMN `delete_date` DROP DEFAULT;

ALTER TABLE `survey` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `survey`;
