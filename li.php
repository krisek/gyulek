<?php
include("config_base.php");
include("lang.php");
echo('require freetag<br/>');
require_once("freetag.class.php");

$global_table_prefix = "";


$freetag_options = $db_options;

$freetag_member_options = array(
	'table_prefix'  => $global_table_prefix.'member_',
	'debug' => TRUE
);

$freetags = array();
echo('init freetag cimke<br/>');
$freetags['cimke'] = new freetag(array_merge($freetag_options,$freetag_member_options));
echo('a<br/>');
$properties = get_object_vars($freetags['cimke']);
foreach ($properties as $property => $value) {
  echo $property . ': ' . $value . PHP_EOL;
}


#$raw_tags = $freetags['cimke']->get_used_raw_tags();

?>