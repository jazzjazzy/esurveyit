# ---------------------------------------------------------------------- #
# Script generated with: DeZign for Databases v6.0.0                     #
# Target DBMS:           MySQL 5                                         #
# Project file:          esurveyit.dez                                   #
# Project name:                                                          #
# Author:                                                                #
# Script type:           Database creation script                        #
# Created on:            2011-06-30 15:40                                #
# ---------------------------------------------------------------------- #


# ---------------------------------------------------------------------- #
# Tables                                                                 #
# ---------------------------------------------------------------------- #

# ---------------------------------------------------------------------- #
# Add table "invoice"                                                    #
# ---------------------------------------------------------------------- #

CREATE TABLE `invoice` (
    `invoice_id` BIGINT NOT NULL,
    `account_id` BIGINT,
    `invoice_type` ENUM(&quot;Single&quot;,&quot;account&quot;),
    `invoice_card` VARCHAR(40),
    `invoice_card_name` VARCHAR(40),
    `invoice_card_date` VARCHAR(40),
    `days` INTEGER,
    `create_date` DATE
);
