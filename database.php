<?php
$conn_Wikipedia = mysql_connect($database_host, $database_user, $database_pass);
mysql_select_db($db_Wikipedia, $conn_Wikipedia);

$conn_TimeWiki = mysql_connect($database_host, $database_user, $database_pass, true);
mysql_select_db($db_TimeWiki, $conn_TimeWiki);


$sql_create_persons = "CREATE TABLE IF NOT EXISTS `persons` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
`wiki_id` INT UNSIGNED NOT NULL,	
`name` VARCHAR(255) NOT NULL,
`importance` DOUBLE UNSIGNED NOT NULL,
`linking` INT UNSIGNED NOT NULL,
`qual_linking` DOUBLE UNSIGNED NOT NULL,
`linked` INT UNSIGNED NOT NULL,
`qual_linked` DOUBLE UNSIGNED NOT NULL,
`depth` INT UNSIGNED NOT NULL,
`added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
mysql_query($sql_create_persons, $conn_TimeWiki);

$sql_create_links = "CREATE TABLE IF NOT EXISTS `links` (
`link_from` INT UNSIGNED NOT NULL,
`link_to` INT UNSIGNED NOT NULL,	
`quality` DOUBLE UNSIGNED NOT NULL,
`over` TEXT DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
mysql_query($sql_create_links, $conn_TimeWiki);
?>