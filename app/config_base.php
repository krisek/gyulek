<?php
ob_start();
session_start();

include("config_db.php");
$aes_enabled = FALSE;

$connect_retries = 0;

while($connect_retries < 200){

    try 
    {
        $dbh = mysqli_connect($db_options['db_host'], $db_options['db_user'], $db_options['db_pass']) or die('Nem tudok csatlakozni');
        break;
    } 
    catch (Exception  $e) {
                // write into logs maybe?
        $connect_retries++;
    }

}
error_log($connect_retries);
if($connect_retries >= 200){
    die('Nem tudok csatlakozni.');
}

mysqli_select_db($dbh, $db_options['db_name']) or die("Nem sikerült kiválasztanom az adatbázist");

mysqli_query($dbh, "set character_set_client = 'utf8'");
mysqli_query($dbh, "set character_set_connection = 'utf8'");
mysqli_query($dbh, "set character_set_results = 'utf8'");
$DEFAULTWEEKFORMAT = 3;
mysqli_query($dbh, "SET NAMES utf8");
if($aes_enabled){
    mysqli_query($dbh, "set  @key_me = \"$key\"");
}


?>
