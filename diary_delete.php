<?

include("config_base.php");
include("config_diary.php");
include("include.php");

$id = $_REQUEST['id'];


foreach ($freetags as $tagname => $freetag){
        //print($tagname . " " . $_POST[$tagname]. "<br>\n");    
        
        $freetag->delete_all_object_tags($id);
        }
     
$statement = "DELETE FROM ${global_table_prefix}diary WHERE id = $id";
$result = mysqli_query($dbh, $statement);

header('Location: diary.php');

?>