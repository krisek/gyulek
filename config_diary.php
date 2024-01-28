<?php
require_once("freetag.class.php");

$diary_user_id = 1;

include("config_db.php");


$freetag_options = $db_options;

$freetag_hely_options = array(
'table_prefix'  => $global_table_prefix.'hely_'
);
$freetag_lelkesz_options = array(
'table_prefix'  => $global_table_prefix.'lelkesz_'
);
$freetag_szolgalat_options = array(
'table_prefix'  => $global_table_prefix.'szolgalat_'
);

$freetag_tipus_options = array(
'table_prefix'  => $global_table_prefix.'tipus_'
);

$freetags = array();
$freetags['hely'] = new freetag(array_merge($freetag_options,$freetag_hely_options));
$freetags['lelkesz'] = new freetag(array_merge($freetag_options,$freetag_lelkesz_options));
$freetags['szolgalat'] = new freetag(array_merge($freetag_options,$freetag_szolgalat_options));
$freetags['tipus'] = new freetag(array_merge($freetag_options,$freetag_tipus_options));

$tag_resolve = array(
'tipus'=>'Típus',
'lelkesz' => 'Lelkész',
'szolgalat' => 'Szolgálattevő',
'hely' => 'Hely'
);

function LoadDiaryPeriod(){
    global $global_table_prefix;
    
    $statement_diary_period = "SELECT COALESCE(YEARWEEK(min(tm)),YEARWEEK(now())) min, COALESCE(YEARWEEK(max(tm)),YEARWEEK(now())) max FROM ${global_table_prefix}diary;";
    $result = mysqli_query($dbh, $statement_diary_period) or die("Hiba a kérésben $statement_catalogs ");
    if ($line = mysqli_fetch_assoc($result)) {
        $_SESSION['diary']['min'] = $line['min'];
        $_SESSION['diary']['max'] = $line['max'];
    }
}
    
if(!isset($_SESSION['diary']['min'])){
    LoadDiaryPeriod();
}


?>
