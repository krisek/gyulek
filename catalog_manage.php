<?

include("config_base.php");
include("config.php");
include("include.php");

$check_failed = 0;

if(isset($_REQUEST['catalog_name']) && $_REQUEST['catalog_name'] != '' && $_REQUEST['catalog_name'] != 'Új katalógus' && $_REQUEST['action'] == ""){
//update
    $statement = "INSERT INTO `${global_table_prefix}catalog` (catalog_name, member_id) VALUES ('".$_REQUEST['catalog_name']."',0);";
    $success_message = "katalógus létrehozva";
    }
if($_REQUEST['action'] == "delete"){
    $statement = "DELETE FROM `${global_table_prefix}catalog` WHERE catalog_name = '".$_REQUEST['catalog_name']."'";
    $success_message = "katalógus törölve";
}

$result = mysqli_query($dbh, $statement);

if($mysql_error = mysqli_error($dbh)){
    $_SESSION['message']=$mysql_error;
}
else{
    $_SESSION['message']= $success_message;
}


header('Location: catalog.php');

?>
