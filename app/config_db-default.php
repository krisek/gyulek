<?php

$default_catalog = 'gyülekezet';
$global_table_prefix = '';

$db_options = array(
'db_user' => 'gyulek',
'db_pass' => 'gyulek123',
'db_host' => 'localhost',
'db_name' => 'gyulek'
);

$default_address = "A gyülekezet neve\nVáros\nCím\nIrányítószám"; //alapértelmezett boríték cím

$tmpdir = '/tmp';

if(isset($_SERVER['WINDIR'])){
    $tmpdir = $_SERVER['WINDIR'].'\\Temp';
}


?>