<?php

function DBP_tb_create() {
	global $wpdb;
	//$DBP_tb_name = $wpdb->prefix."cardetails";
	$DBP_query1 = "CREATE TABLE IF NOT EXISTS `wp_cardetails` (`id` INT NOT NULL AUTO_INCREMENT, `regno` VARCHAR(100) DEFAULT '', `mobilenumber` VARCHAR(100) DEFAULT '', `name` VARCHAR(100) DEFAULT '', `zip` VARCHAR(100) DEFAULT '', `carbrand` VARCHAR(100) DEFAULT '', `model` VARCHAR(100) DEFAULT '', PRIMARY KEY (`id`)) ENGINE = InnoDB;";
	$DBP_query2 = "CREATE TABLE IF NOT EXISTS `wp_carphotos` (`id` INT NOT NULL AUTO_INCREMENT, `cardetailsid` INT, `filename` VARCHAR(100) DEFAULT '', `fileurl` VARCHAR(500) DEFAULT '', PRIMARY KEY (`id`)) ENGINE = InnoDB;";
	require_once(ABSPATH."wp-admin/includes/upgrade.php");
	dbDelta($DBP_query1);
	dbDelta($DBP_query2);
}

function DBP_tb_delete() {
	global $wpdb;
	//$DBP_tb_name = $wpdb->prefix."cardetails";
	$wpdb->query("DROP table IF EXISTS `wp_cardetails`;");
	$wpdb->query("DROP TABLE IF EXISTS `wp_carphotos`;");
}