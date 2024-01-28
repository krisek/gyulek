<?php

include('../config_base.php');
include('../config.php');


function utf8_compliant($str) {
    if ( strlen($str) == 0 ) {
        return TRUE;
    }
    // If even just the first character can be matched, when the /u
    // modifier is used, then it's valid UTF-8. If the UTF-8 is somehow
    // invalid, nothing at all will match, even if the string contains
    // some valid sequences
    return (preg_match('/^.{1}/us',$str,$ar) == 1);
}



$list_user_id = 1;                                                              
                                                                                

//load file
$lines = file('./data/gimn.csv');

$fields = array();
$tags;
$lasthw = '';
//process lines
foreach ($lines as $line_num => $line) {
    //split
    //list($dns, $hostserver, $service, $area1, $os, $hw, $comment1, comment2, $area2) 
    #echo "$line<br>\n";
    $fields = array();
    $tags = '';
    list($fields[nev], $fields[ir_szam], $fields[varos], $fields[cim]) = explode("\t", $line);
    //correct data
    
    foreach($fields as $i => $field){
        $fields[$i] = trim($fields[$i], " \t.\"\n\r");
    }
   

    $tags .= "gimn import";
    
    $fields[megjegyzes] = 'listából importált';
    
    $statement = "INSERT INTO members (nev,ir_szam,varos,cim,megjegyzes,cimke) VALUES ('$fields[nev]', '$fields[ir_szam]', '$fields[varos]', '$fields[cim]','$fields[megjegyzes]', '$tags')";

    echo "$statement<br>\n";
    echo "$tags<br>\n";
    echo "=====================<br>\n";
    
   
    $result = mysqli_query($dbh, $statement);
    

    
    $id = mysql_insert_id();
    echo "id: $id<br>\n";
    
    $freetags['cimke']->tag_object(1,$id,$tags);
    
    $statement_c = "INSERT INTO catalog ( catalog_name, member_id ) VALUES ( 'sztehlo', '$id' )";
    $result = mysqli_query($dbh, $statement_c);
    
	
}




?>
