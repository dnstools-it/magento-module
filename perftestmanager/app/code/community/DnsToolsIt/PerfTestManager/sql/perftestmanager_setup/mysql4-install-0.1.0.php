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
 * 
+-------------+------------------+------+-----+---------+----------------+
| Field       | Type             | Null | Key | Default | Extra          |
+-------------+------------------+------+-----+---------+----------------+
| id          | int(11) unsigned | NO   | PRI | NULL    | auto_increment |
| email       | varchar(255)     | NO   | UNI |         |                |
| login_time  | varchar(255)     | NO   |     |         |                |
| logout_time | varchar(255)     | NO   |     |         |                |
+-------------+------------------+------+-----+---------+----------------+
 * 
 */


$installer = $this;

$installer->startSetup();

$installer->run("-- DROP TABLE IF EXISTS {$this->getTable('perftestmanager')};
			  CREATE TABLE IF NOT EXISTS {$this->getTable('perftestmanager')}( `pt_id` int(11) unsigned NOT NULL auto_increment,
			  `name` VARCHAR(255) NOT NULL default '',
			  `desc` VARCHAR(255) NOT NULL default '',
			  `type` VARCHAR(255) NOT NULL default '',
			  `qty` INT(11) NOT NULL default 0,
			  `emaildomain` VARCHAR(255) NOT NULL default '',
			  `password` VARCHAR(100) NOT NULL default '',
			  `website_id` INT(11) NOT NULL default 0,
			  `store_id` INT(11) NOT NULL default 0,
			  `group_id` INT(11) NOT NULL default 0,
			  `group_id` INT(11) NOT NULL default 0,
			  `categories` VARCHAR(255) NULL default '',
			  PRIMARY KEY (`pt_id`),
			  UNIQUE INDEX name(`name`)
			) ENGINE=InnoDB;");


$installer->run("CREATE TABLE IF NOT EXISTS {$this->getTable('statsinfo')}( `id` int(11) unsigned NOT NULL auto_increment,
			  `email` VARCHAR(255) NOT NULL default '',
			  `login_time` VARCHAR(255) NOT NULL default '',
			  `logout_time` VARCHAR(255) NOT NULL default '',
			  PRIMARY KEY (`id`),
			  UNIQUE KEY email(email)
			) ENGINE=InnoDB;");

$installer->endSetup();