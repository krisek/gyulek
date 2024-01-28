<?php
#chdir('/home/kris/public_html/gyulek/maillist');

$list=$_GET["list"];
$key=$_GET[key];

include('config_base.php');

include('config.php');


$statement = "select id, ".getQ("nev","m").", ".getQ("email","m")." FROM ${global_table_prefix}members m WHERE ". getQ("leave","m",0) ." = '0000-00-00' and m.id IN (SELECT object_id FROM ${global_table_prefix}member_freetagged_objects WHERE tag_id IN (SELECT id FROM ${global_table_prefix}member_freetags WHERE replace(replace(replace(replace(replace(replace(replace(replace(replace(raw_tag,'ó','o'),'ú','u'),'í','i'),'ő','o'),'ü','u'),'ű','u'),'é','e'),'ö','o'),'á','a') = '$list')) and m.email != '';";
$result = mysqli_query($dbh, $statement) or die("Hiba a kérésben $statement\n");


while ($line = mysqli_fetch_assoc($result)) {
    echo("$line[id]:$line[nev]:$line[email]\n");
}

