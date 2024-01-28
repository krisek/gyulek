<?

include("config_base.php");
include("config.php");
include("include.php");


$check_failed = 0;

$result = mysqli_query($dbh, "DELETE FROM amounts WHERE id =".$_REQUEST['id']);
    if($mysql_error = mysqli_error($dbh)){
        $_SESSION['message']=$mysql_error;
    }    
    
$_SESSION['id'] = $_REQUEST['member_id'];

if(! $check_failed){
    header('Location: list.php');
}
else{
    $line = $_REQUEST;
?><body><p>Hibás paraméter!</p>

</body></html><?
}


?>