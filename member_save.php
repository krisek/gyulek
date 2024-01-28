<?

include("config_base.php");
include("config.php");

$check_failed = 0;

$ajax = FALSE;

if($_REQUEST['ajax'] == 'yes'){
    $ajax = TRUE;
    header("Content-type: text/plain; charset=utf-8");
    }
else{
    header("Content-Type: text/html; charset=utf-8");
    }


/*work on tags*/
if($_REQUEST['cimke'] != ''){
    $tag_s = preg_replace("/[\,\s]+/"," ",$_REQUEST['cimke']);
    $tags = array();
    $tags = array_unique(explode(' ',$tag_s));
    $_REQUEST['cimke'] = implode(' ',$tags);
    $statement = "INSERT INTO ${global_table_prefix}tags VALUES ('". implode('\'),(\'',$tags). "')";
    $result = mysqli_query($dbh, $statement);
    
    
    if($mysql_error = mysqli_error($dbh)){
            $_SESSION['message']=$mysql_error;
    }
}    



if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
//update
    $statement = "UPDATE ";
    }
else{
    $statement = "INSERT INTO ";
    }
$id = $_REQUEST['id'];


$statement .= "${global_table_prefix}members SET ";
/*
$statement .= "nev = ".setQ($_REQUEST['nev']).", ";
$statement .= "email = ".setQ($_REQUEST['email']).", ";
$statement .= "telefon = ".setQ($_REQUEST['telefon']).", ";
$statement .= "telefon_mobil = ".setQ($_REQUEST['telefon_mobil']).", ";
$statement .= "ir_szam = ".setQ($_REQUEST['ir_szam']).", ";
$statement .= "varos = ".setQ($_REQUEST['varos']).", ";
$statement .= "cim = ".setQ($_REQUEST['cim'])." ";
*/

$first = true;
//go through fields
foreach($fields as $field => $field_params){
    //check date formats
    if($field == 'group' || $field == 'group_descr'){
        continue;
    }
    if($field_params['type'] == 'date' && (CheckDateFormat($_REQUEST[$field]) && $_REQUEST[$field] != '' )){
        $check_failed = 1;
        $fields[$field]['show'] = 1; 
        }
    if($field_params['type'] == 'date' && $_REQUEST[$field] == '' ){
        
        continue;
    }
    if(($field == 'member_id' || $field == 'hazastars') && $_REQUEST[$field] == '' ){
        
        continue;
    }
    if(! $first){
        $statement .= ", ";
        }
    else{
        $first = false;
        }

    if($field == 'konf_ev'){
        $statement .= "$field = ". setQ("str_to_date('".$_REQUEST[$field]."','%Y') ",null, 0)  ;
        }
    else{
        $statement .= "$field = ".setQ($_REQUEST[$field])." ";
        }
    
    }

if($_REQUEST['id'] == ''){
    $statement .= ", entry = '".strftime("%Y-%m-%d")."' ";
    }

if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
    $statement .= " WHERE id = ".$_REQUEST['id'].";";
    $_SESSION['id'] = $_REQUEST['id'];
    }

$_SESSION['message'] = $statement;

if(! $check_failed){
    $result = mysqli_query($dbh, $statement);


    if($mysql_error = mysqli_error($dbh)){
        $_SESSION['message']=$mysql_error . " (" . $statement . ")";
    }
    else{
        $_SESSION['message']="sikeres mentés";
        //get request id and add to pattern
        $_SESSION['pattern'] = "spec-id-".$_SESSION['id'];       

        if($_REQUEST['id'] == ''){
        
        //
        //$statement = "SELECT id FROM ${global_table_prefix}members WHERE ";
        //$statement .= "nev = '".$_REQUEST['nev']."' AND ";
        //$statement .= "email = '".$_REQUEST['email']."' AND ";
        //$statement .= "telefon = '".$_REQUEST['telefon']."' AND ";
        //$statement .= "ir_szam = '".$_REQUEST['ir_szam']."' AND ";
        //$statement .= "varos = '".$_REQUEST['varos']."' AND ";
        //$statement .= "cim = '".$_REQUEST['cim']."';";
        //
        //$result = mysqli_query($dbh, $statement);
        //$line = mysqli_fetch_assoc($result);
                $_SESSION['id'] = mysqli_insert_id($dbh);
                $id = $_SESSION['id'];
                $_SESSION['pattern'] = "spec-id-".$_SESSION['id'];
                $_SESSION['message']="sikeres hozzáadás";
     
        }
    }

//update tags
foreach ($freetags as $tagname => $freetag){
       //print("calling tag_object " . $tagname . " " . $_POST[$tagname]. "<br>\n");    
	$freetag->tag_object($list_user_id, $_SESSION['id'], $_POST[$tagname],0);
        }


//set default member_id (családfő azonosító)

if($_REQUEST['member_id'] == '' || $_REQUEST['member_id'] == 0){
    $statement_mi = "UPDATE members SET member_id = ".$_SESSION['id']." WHERE id = ".$_SESSION['id'];
    $result_mi = mysqli_query($dbh, $statement_mi);
    } 


if($_REQUEST['amount'] != ''){
    if(CheckDateFormat($_REQUEST['amount_year']) && $_REQUEST['amount_year'] != ''){
            $check_failed = 1;
            $amount_year_show = 1;
        }       
    else{
        $statement = "INSERT INTO ${global_table_prefix}amounts SET ";
        $statement .=  "member_id = ".$_REQUEST['id'].", ";
        $statement .=  "amount = '".$_REQUEST['amount']."', ";
        if($_REQUEST['amount_year'] != ''){
            $statement .=  "dt = '".$_REQUEST['amount_year']."', ";
        }    
        $statement .=  "type = '".$_REQUEST['type']."'; ";

        $result = mysqli_query($dbh, $statement);

        if($mysql_error = mysqli_error($dbh)){
            $_SESSION['message']=$mysql_error;
            }
        }

}


if(isset($_REQUEST['catalogs'])){
    $statement = "DELETE FROM ${global_table_prefix}catalog WHERE member_id =  ".$id ;
    $result = mysqli_query($dbh, $statement);
    if($mysql_error = mysqli_error($dbh)){
        $_SESSION['message']=$_SESSION['message'] . " " . $mysql_error . " (" . $statement . ")";;;
    }
    $statement = "REPLACE INTO ${global_table_prefix}catalog VALUES ('";   
    $statement .= implode("',$id),('",$_REQUEST['catalogs']);
    $statement .= "',$id)";
    $result = mysqli_query($dbh, $statement);
    if($mysql_error = mysqli_error($dbh)){
        $_SESSION['message']= $_SESSION['message'] . " " . $mysql_error . " (" . $statement . ")";;
    }
}
if(isset($_REQUEST['undelete'])){
    $statement = "UPDATE `${global_table_prefix}members` SET ";
    $statement .= "`leave`='0000-00-00' ";
    $statement .= " WHERE `id`=".$_REQUEST['id'].";";
   

        $result = mysqli_query($dbh, $statement);

        $statement = "UPDATE `${global_table_prefix}member_freetagged_objects` SET ";
        $statement .= "tag_id=tag_id-100000 ";
        $statement .= " WHERE `object_id`=".$_REQUEST['id'].";";


        $result = mysqli_query($dbh, $statement);
        if($mysql_error = mysqli_error($dbh)){
            $_SESSION['message']=$mysql_error;
        }
        else{
            $_SESSION['message']="sikeres visszavétel";
        }


}


}
if(! $check_failed){
    if($ajax){
?><?=$_SESSION['id']?>|<?=$_SESSION['message']?><?
       $_SESSION['message'] = ''; 
       exit;
       }

    
    header('Location: list.php');
}
else{
    $line = $_REQUEST;
?><body><p>Hibás paraméter!</p>

<table border="0" width="100%">
<tr><th>#</th><th>Név</th><th>E-mail</th><th>Telefon</th><th>Cím</th><th></th></tr>
<?include('member_form.php')?>

</table>
</body></html><?
}

?>
