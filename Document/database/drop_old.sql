# ---------------------------------------------------------------------- #
# Script generated with: DeZign for Databases v6.0.0                     #
# Target DBMS:           MySQL 5                                         #
# Project file:          esurveyit.dez                                   #
# Project name:                                                          #
# Author:                                                                #
# Script type:           Database drop script                            #
# Created on:            2011-05-18 15:33                                #
# ---------------------------------------------------------------------- #


# ---------------------------------------------------------------------- #
# Drop foreign key constraints                                           #
# ---------------------------------------------------------------------- #

ALTER TABLE `survey_question` DROP FOREIGN KEY `survey_survey_question`;

ALTER TABLE `survey_question` DROP FOREIGN KEY `question_survey_question`;

ALTER TABLE `responce_multi` DROP FOREIGN KEY `question_question_multi`;

ALTER TABLE `responce_single` DROP FOREIGN KEY `question_responce_single`;

ALTER TABLE `responce_array` DROP FOREIGN KEY `question_responce_array`;

ALTER TABLE `question_single` DROP FOREIGN KEY `question_question_single`;

ALTER TABLE `question_multi` DROP FOREIGN KEY `question_question_multi`;

ALTER TABLE `question_array` DROP FOREIGN KEY `question_question_array`;

# ---------------------------------------------------------------------- #
# Drop table "question_array"                                            #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `question_array` MODIFY `array_id` INTEGER NOT NULL;

# Drop constraints #

ALTER TABLE `question_array` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `question_array`;

# ---------------------------------------------------------------------- #
# Drop table "question_multi"                                            #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `question_multi` MODIFY `multi_id` INTEGER NOT NULL;

# Drop constraints #

ALTER TABLE `question_multi` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `question_multi`;

# ---------------------------------------------------------------------- #
# Drop table "question_single"                                           #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `question_single` MODIFY `single_id` INTEGER NOT NULL;

# Drop constraints #

ALTER TABLE `question_single` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `question_single`;

# ---------------------------------------------------------------------- #
# Drop table "responce_array"                                            #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `responce_array` MODIFY `array_id` INTEGER NOT NULL;

# Drop constraints #

ALTER TABLE `responce_array` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `responce_array`;

# ---------------------------------------------------------------------- #
# Drop table "responce_single"                                           #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `responce_single` MODIFY `single_id` INTEGER NOT NULL;

# Drop constraints #

ALTER TABLE `responce_single` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `responce_single`;

# ---------------------------------------------------------------------- #
# Drop table "responce_multi"                                            #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `responce_multi` MODIFY `multi_id` INTEGER NOT NULL;

# Drop constraints #

ALTER TABLE `responce_multi` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `responce_multi`;

# ---------------------------------------------------------------------- #
# Drop table "survey_question"                                           #
# ---------------------------------------------------------------------- #

# Drop constraints #

ALTER TABLE `survey_question` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `survey_question`;

# ---------------------------------------------------------------------- #
# Drop table "question"                                                  #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `question` MODIFY `question_id` INTEGER NOT NULL;

# Drop constraints #

ALTER TABLE `question` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `question`;

# ---------------------------------------------------------------------- #
# Drop table "survey"                                                    #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `survey` MODIFY `survey_id` INTEGER NOT NULL;

# Drop constraints #

ALTER TABLE `survey` ALTER COLUMN `create_date` DROP DEFAULT;

ALTER TABLE `survey` ALTER COLUMN `modify_date` DROP DEFAULT;

ALTER TABLE `survey` ALTER COLUMN `delete_date` DROP DEFAULT;

ALTER TABLE `survey` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `survey`;
