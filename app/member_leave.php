<?

include("config_base.php");
include("config.php");
include("include.php");

$check_failed = 0;

if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
//update
    $statement = "UPDATE `${global_table_prefix}members` SET ";
    $statement .= "`leave`='".strftime("%Y-%m-%d")."' ";
    $statement .= " WHERE `id`=".$_REQUEST['id'].";";
   

        $result = mysqli_query($dbh, $statement);

        $statement = "UPDATE `${global_table_prefix}member_freetagged_objects` SET ";
        $statement .= "tag_id=tag_id+100000 ";
        $statement .= " WHERE `object_id`=".$_REQUEST['id'].";";


        $result = mysqli_query($dbh, $statement);
}
if($mysql_error = mysqli_error($dbh)){
    $_SESSION['message']=$mysql_error;
}
else{
    $_SESSION['message']="sikeres törlés";
}


header('Location: list.php');

?>
