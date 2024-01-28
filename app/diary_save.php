<?

include("config_base.php");
include("config_diary.php");
include("include.php");


$check_failed = 0;

$ajax = FALSE;

if($_REQUEST['ajax'] == 'yes'){
    $ajax = TRUE;
    header("Content-type: text/plain; charset=utf-8");
    }
else{
    header("Content-Type: text/html; charset=utf-8");
    }


if($_REQUEST['id'] == 'new'){
    $_REQUEST['id'] = '';
    }

foreach (array('resztvevok','urvacsora') as $i){
    if(!isset($_REQUEST[$i]) || $_REQUEST[$i] == ''){
        $_REQUEST[$i] = 0;
        }
    }

$id = $_REQUEST['id'];

if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
//update
    $statement = "UPDATE ";
    }
else{
    $statement = "INSERT INTO ";
    }
    
$statement .= "${global_table_prefix}diary SET ";
$statement .= "tm = '".$_REQUEST['tm']."', ";
$statement .= "ige = '".$_REQUEST['ige']."', ";
$statement .= "resztvevok = ".$_REQUEST['resztvevok'].", ";
$statement .= "urvacsora = ".$_REQUEST['urvacsora'].", ";
$statement .= "megjegyzes = '".$_REQUEST['megjegyzes']."'";


if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
    $statement .= " WHERE id = ".$_REQUEST['id'].";";
    $_SESSION['id'] = $_REQUEST['id'];
    }
    
if(! $check_failed){
    $result = mysqli_query($dbh, $statement);
}
else{
    //input error        
}
if($mysql_error = mysqli_error($dbh)){
    $_SESSION['message']=$mysql_error;
    }
else{
    $_SESSION['message']="sikeres mentés";
    //get request id and add to pattern
    if($_REQUEST['id'] == ''){
        $statement = "SELECT id FROM ${global_table_prefix}diary WHERE ";
        $statement .= "tm = '".$_REQUEST['tm']."' AND ";
        $statement .= "ige = '".$_REQUEST['ige']."' AND ";
        $statement .= "resztvevok = ".$_REQUEST['resztvevok']." AND ";
        $statement .= "urvacsora = ".$_REQUEST['urvacsora']." AND ";
        $statement .= "megjegyzes = '".$_REQUEST['megjegyzes']."' ; ";
        

        $result = mysqli_query($dbh, $statement);
        $line = mysqli_fetch_assoc($result);
        $_SESSION['id'] = $line['id'];
        $id = $line['id'];
        
        }
    
    //print("Going t. tags<br>\n");    
    if($_REQUEST['urvacsora'] > 0){
        //$freetags['tipus']->tag_object($diary_user_id, $id, 'úrvacsorai',0);
        $_POST['tipus'] .= ' úrvacsorai';
        }   
    foreach ($freetags as $tagname => $freetag){
        //print($tagname . " " . $_REQUEST[$tagname]. "<br>\n");    
        
        $freetag->tag_object($diary_user_id, $id, $_REQUEST[$tagname],0);
        }
     
}




if(! $check_failed){
    if($ajax){
?><?=$_SESSION['id']?>|<?=$_SESSION['message']?><?
       $_SESSION['message'] = ''; 
       exit;
       }

    header('Location: diary.php');
}
else{
    $line = $_REQUEST;
?>
<body><p>Hibás paraméter!</p>

<table border="0" width="100%">

<?include('diary_form.php')?>

</table>
</body></html>
<?
}


?>
