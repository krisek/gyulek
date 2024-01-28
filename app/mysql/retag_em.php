<?php

include('../config_base.php');

require_once("../freetag.class.php");                                              

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
                                                                                
$freetag_options = $db_options;                                                 
                                                                                
$freetag_member_options = array(                                                
'table_prefix'  => $global_table_prefix.'member_'                               
);                                                                              
                                                                                
$freetags = array();                                                            
$freetags['cimke'] = new freetag(array_merge($freetag_options,$freetag_member_options));                                                                        
                                                                                
$tag_resolve = array(                                                           
'cimke'=>'Címke',                                                               
);                                                                              
                                 

$statement = "SELECT members.id, catalog_name FROM members, catalog WHERE members.id=catalog.member_id and (catalog_name = 'nyuglelk')";

$result = mysqli_query($dbh, $statement);




while ($line = mysqli_fetch_assoc($result)) {                                    
            $freetags['cimke']->tag_object($list_user_id,$line['id'], "EM".$line['catalog_name']);
$statement2 = "REPLACE INTO catalog (member_id, catalog_name) VALUES (".$line['id'].", 'egyhazmegye')";
        mysqli_query($dbh, $statement2);
        ?><?=$line['id']?>: <?=$line['catalog_name']?><?
        print "\n";
}


?>
