<?php
/**
+-------------+------------------+------+-----+---------+----------------+
| Field       | Type             | Null | Key | Default | Extra          |
+-------------+------------------+------+-----+---------+----------------+
| pt_id       | int(11) unsigned | NO   | PRI | NULL    | auto_increment |
| name        | varchar(255)     | NO   |     |         |                |
| desc        | varchar(255)     | NO   |     |         |                |
| type        | varchar(255)     | NO   |     |         |                |
| usernumber  | int(11)          | YES  |     | NULL    |                |
| emaildomain | varchar(255)     | NO   |     | NULL    |                |
| password    | varchar(100)     | NO   |     | NULL    |                |
| website_id  | int(11)          | NO   |     | NULL    |                |
| store_id    | int(11)          | NO   |     | NULL    |                |
| group_id    | int(11)          | NO   |     | NULL    |                |
+-------------+------------------+------+-----+---------+----------------+
 */


$installer = $this;

$installer->startSetup();

$installer->run("-- DROP TABLE IF EXISTS {$this->getTable('perftestmanager')};
			  CREATE TABLE IF NOT EXISTS {$this->getTable('perftestmanager')}( `pt_id` int(11) unsigned NOT NULL auto_increment,
			  `name` VARCHAR(255) NOT NULL default '',
			  `desc` VARCHAR(255) NOT NULL default '',
			  `type` VARCHAR(255) NOT NULL default '',
			  `usernumber` INT(11) NOT NULL default 0,
			  `emaildomain` VARCHAR(255) NOT NULL default '',
			  `password` VARCHAR(100) NOT NULL default '',
			  `website_id` INT(11) NOT NULL default 0,
			  `store_id` INT(11) NOT NULL default 0,
			  `group_id` INT(11) NOT NULL default 0,
			  PRIMARY KEY (`pt_id`),
			  UNIQUE INDEX name(`name`)
			) ENGINE=InnoDB;");

$installer->endSetup();