<?php
$default_catalog = 'budavár';
$global_table_prefix = '';

$db_options = array(
'db_user' => 'gyulek',
'db_pass' => 'gy.lek12',
'db_host' => 'localhost',
'db_name' => 'gyulek'
);


$aes_enabled = false;
$offshore_enabled = false;

$aes_enabled = FALSE;

$dbh = mysqli_connect($db_options['db_host'], $db_options['db_user'], $db_options['db_pass'])
    or die("Nem tudok csatlakozni");
mysqli_select_db($dbh, $db_options['db_name']) or die("Nem sikerült kiválasztanom az adatbázist");

mysqli_query($dbh, "set character_set_client = 'utf8'");
mysqli_query($dbh, "set character_set_connection = 'utf8'");
mysqli_query($dbh, "set character_set_results = 'utf8'");
$DEFAULTWEEKFORMAT = 3;
mysqli_query($dbh, "SET NAMES utf8");
if($aes_enabled){
    mysqli_query($dbh, "set  @key_me = \"$key\"");
}



$reports = [
    "hazastarsak_in" => [
        "tag_raw" => "megkeres2",
        "tag" => "megkeres2",
        "tag_id" => 92,
        "query" => "select 
        ar.id as arid,
        ar.nev,
        ar.y2023,
        ar.y2024,
        mh.id as id,
        mh.nev,
        mh.email,
        mh.telefon_mobil,
        mh.telefon,
        (select ar2.y2023 from amounts_report ar2 where ar2.id = mh.id) as mh_y2023,
        (select ar2.y2024 from amounts_report ar2 where ar2.id = mh.id) as mh_y2024
     from
        amounts_report as ar,
        members as m,
        members as mh
     where
        m.id = ar.id and
        mh.id = m.hazastars and
        (ar.y2023 = 1 or ar.y2024 = 1) and
        mh.id not in (select id from amounts_report where y2023 + y2024 > 0) and
        m.csal_all_id IN (3,4)
    ORDER BY ar.nev"
    ],
    "hazastarsak_out" => [
        "tag_raw" => "megkeres3",
        "tag" => "megkeres3",
        "tag_id" => 93,
        "query" => "select 
        ar.id as id,
        ar.nev,
        ar.y2023,
        ar.y2024,
        m.hazastars_neve,
        m.email,
        m.telefon,
        m.telefon_mobil,
        (select COUNT(*) from members mh where ar.id = mh.member_id and mh.id != mh.member_id) as csaladtagok,
        (select CONCAT(mh.nev,' ',mh.id) from members mh where mh.nev REGEXP m.hazastars_neve LIMIT 1)
     from
        amounts_report as ar,
        members as m
     where
        m.id = ar.id and
        m.hazastars = 0 and
        m.hazastars_neve != '' and
        (ar.y2023 = 1 or ar.y2024 = 1) and
        m.csal_all_id IN (3,4)
     order by ar.nev"
    ],
    "gyerekek" => [
        "tag_raw" => "megkeres4",
        "tag" => "megkeres4",
        "tag_id" => 94,
        "query" => "select 
        ar.id,
        ar.nev,
        ar.y2023,
        ar.y2024,
        mf.id,
        mf.nev,
        mf.email,
        mf.telefon_mobil,
        mf.telefon,
        (select ar2.y2023 from amounts_report ar2 where ar2.id = mf.id) as mf_y2023,
        (select ar2.y2024 from amounts_report ar2 where ar2.id = mf.id) as mf_y2024,
        mf.szul_datum,
        TIMESTAMPDIFF(year,mf.szul_datum, now() ) as kor
     from
        amounts_report as ar,
        members as m,
        members as mf,
        catalog as c
     where
        m.id = ar.id and
        mf.id != m.hazastars and
        mf.hazastars != m.id and
        mf.member_id = m.id and
        mf.id = c.member_id and
        c.catalog_name = 'budavár' and
        mf.halal_datum = '0000-00-00' and
        mf.leave = '0000-00-00' and
        (ar.y2023 = 1 or ar.y2024 = 1) and
        mf.id not in (select id from amounts_report where y2023 + y2024 > 0)
     order by ar.nev, mf.nev"
    ],
    "elmaradok" => [
        "tag_raw" => "megkeres1",
        "tag" => "megkeres1",
        "tag_id" => 91,
        "query" => "select 
        ar.*, m.email, m.telefon_mobil, m.telefon  
     from 
       amounts_report as ar,
       members as m 
     where 
       m.id = ar.id and 
       (ar.y2015+ar.y2016+ar.y2017+ar.y2018+ar.y2019+ar.y2020+ar.y2021+ar.y2022 + ar.y2024 > 3) and 
       (ar.y2023 = 0 or ar.y2023+ar.y2022 = 0) and
       ar.y2024 = 0 and
       m.cimke NOT LIKE '%megkeres2%' AND m.cimke NOT LIKE '%megkeres4%'
     ORDER BY ar.nev"
    ]
    
];


foreach(array_keys($reports) as $report){

# cleanup existing tags

$result = mysqli_query($dbh, "DELETE FROM member_freetagged_objects WHERE tag_id = " . $reports[$report]['tag_id']) or die("Hiba a kérésben $statement ");


$statement = "UPDATE members SET cimke = REPLACE(cimke, ' ".$reports[$report]['tag_raw']."', '') WHERE cimke LIKE '%" . $reports[$report]['tag_raw'] . "%'";
$result = mysqli_query($dbh, $statement) or die("Hiba a kérésben $statement ");



$result = mysqli_query($dbh, $reports[$report]['query']) or die("Hiba a kérésben  " . $reports[$report]['query']);
$result_numrows = mysqli_query($dbh, "SELECT FOUND_ROWS()");
$total_a = mysqli_fetch_row($result_numrows);
$total= $total_a[0];

$statement_notag = "SELECT id FROM members WHERE cimke LIKE '%megkereskikapcs%'";
$result_notag = mysqli_query($dbh, $statement_notag) or die("Hiba a kérésben  " . $statement_notag);

$members_notag = [];
while ($member = mysqli_fetch_assoc($result_notag)){
    array_push($members_notag, $member['id']);
}

while ($line = mysqli_fetch_assoc($result)) {

    
    print($line['nev'] . " - ". $line['id'] . "<br/>\n");
    

    if(in_array($line['id'], $members_notag)){
        print("Skipped<br/>\n");
        continue;
    }

    $statement = "UPDATE members SET cimke = CONCAT(cimke, ' ', '" . $reports[$report]['tag_raw'] . "') WHERE id = " . $line['id'];
    $result_update = mysqli_query($dbh, $statement) or die("Hiba a kérésben $statement ");
    $statement = "INSERT INTO member_freetagged_objects (tag_id, tagger_id, object_id, tagged_on) VALUES (" . $reports[$report]['tag_id'] . ", 1, "  . $line['id'] . ",NOW())";
    $result_insert_tag = mysqli_query($dbh, $statement) or die("Hiba a kérésben $statement ");



    }

    //tag gábor
    $statement = "UPDATE members SET cimke = CONCAT(cimke, ' ', '" . $reports[$report]['tag_raw'] . "') WHERE id = 1834";
    $result_update_g = mysqli_query($dbh, $statement) or die("Hiba a kérésben $statement ");
    $statement = "INSERT INTO member_freetagged_objects (tag_id, tagger_id, object_id, tagged_on) VALUES (" . $reports[$report]['tag_id'] . ", 1, 1834, NOW())";
    $result_insert_tag_g = mysqli_query($dbh, $statement) or die("Hiba a kérésben $statement ");

}
/*
$statement = "SELECT * FROM members WHERE id = 2213;";
$result = mysqli_query($dbh, $statement) or die("Hiba a kérésben $statement ");



while ($line = mysqli_fetch_assoc($result)) {
    $email = $line['nev'] . " &lt;" . $line['email'] . "&gt;";
    print(json_encode($line));
    }

*/


    

?>
