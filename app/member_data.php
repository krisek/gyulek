<?

include("config_base.php");
include("config.php");
header("Content-type: text/plain; charset=utf-8");
$id = $_REQUEST['id'];
?><?=$id?>|<table><?
if($id != ''){
	 $member_id = $id;
/*
	$member_tags = array();
   
    $statement_tag = "SELECT * FROM ${global_table_prefix}member_tags  where member_id = $member_id and tag not like '%-akombakom'";
    $result_tag = mysqli_query($dbh,$statement_tag) or die("Hiba a kĂŠrĂŠsben $statement_tag ");
    while ($line_tag = mysqli_fetch_assoc($result_tag)) {
		array_push($member_tags,$line_tag['tag']);
    }
    mysqli_free_result($result_tag);
*/
    $statement_money = "SELECT id id,member_id,year(dt) year,date(dt) dt1,dt ,type, amount FROM ${global_table_prefix}amounts WHERE member_id = $member_id ORDER BY dt DESC, type";
    $result_money = mysqli_query($dbh,$statement_money) or die("Hiba a kérésben $statement_money ");
    $curr_year = 0;
    $curr_sum = 0; 
    $curr_data = '';
    $printed = 0;
    while ($line_money = mysqli_fetch_assoc($result_money)) {
    if($curr_year == 0){
        $curr_year = $line_money['year'];
    }
    
    if($curr_year != $line_money['year']){
        if($printed < 3){
        ?><tr><td><b><?=$curr_year?></b><td colspan="3"><?=$curr_sum?><?=$curr_data?><?
        }
        $printed++;
        
        $curr_sum = 0;
        $curr_data = '';
        $curr_year = $line_money['year'];        
    
    }
    
    $curr_sum += $line_money['amount'];
    $curr_data .= "<tr><td>".$line_money['dt1']."<td>".$line_money['amount']."<td>".$amount_ref[$line_money['type']]."<td><a href=\"javascript:deletePayment(".$line_money['id'].",".$line_money['member_id'].")\">x</a>";
    
    $curr_year = $line_money['year'];
//do printout    
    }
    if($curr_year != 0 && $printed < 3){
    ?><tr><td><b><?=$curr_year?></b><td colspan="2"><?=$curr_sum?><?=$curr_data?><?
    }
    mysqli_free_result($result_money);
}
?></table>|<?
if($id != ''){
     $member_id = $id;
/*
    $member_tags = array();
   
    $statement_tag = "SELECT * FROM ${global_table_prefix}member_tags  where member_id = $member_id and tag not like '%-akombakom'";
    $result_tag = mysqli_query($dbh,$statement_tag) or die("Hiba a kĂŠrĂŠsben $statement_tag ");
    while ($line_tag = mysqli_fetch_assoc($result_tag)) {
        array_push($member_tags,$line_tag['tag']);
    }
    mysqli_free_result($result_tag);
*/
    $statement_catalog = "SELECT catalog_name FROM ${global_table_prefix}catalog  WHERE member_id = $member_id ORDER BY catalog_name";
    $result_catalog = mysqli_query($dbh,$statement_catalog) or die("Hiba a kérésben $statement_catalog ");
    $member_catalog = array();
    while ($line_catalog = mysqli_fetch_assoc($result_catalog)) {
        $member_catalog[$line_catalog['catalog_name']] = 1;
        
    }
    mysqli_free_result($result_catalog);
    foreach ($_SESSION['catalogs'] as $key => $value){
        ?><?=$key?>,<?if(isset($member_catalog["$key"])):?>selected<?else:?>whattamatta<?endif;?>;<?
    }    
    
}
?>|<?
if($id != ''){
     $member_id = $id;
	 $tags_r = array();
	 $tags = array();
	 $tags_r = $freetags['cimke']->get_tags_on_object($member_id, 0, 0, $list_user_id);
     //print(join(' ', $tags));
	 //print_r($freetags['cimke']->get_tags_on_object($member_id, 0, 0, $list_user_id));	
    //print($tagname . ": " . join(' ', $tags[$tagname]) . "<br> \n");
      foreach($tags_r as $tag_r){
		if(strpos($tag_r['tag'],' ')){
				$tag_r['tag'] = '"'.$tag_r['tag'].'"';
		}
		array_push($tags, $tag_r['tag']);
	  } 
	 print(join(' ', $tags));
	 }
?>