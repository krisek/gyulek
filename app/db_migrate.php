<?
include("config_base.php");
include("include.php");

$sql = "SHOW TABLES FROM $db_options[db_name]";
$result = mysqli_query($dbh, $sql);

if (!$result) {
    echo "DB Error, could not list tables\n";
    echo 'MySQL Error: ' . mysqli_error($dbh);
    exit;
}

while ($row = mysql_fetch_row($result)) {
    //echo "Table: {$row[0]}<br>\n";
    $sql = "SHOW CREATE TABLE $row[0]";
    $result_create = mysqli_query($dbh, $sql);
    $line = mysqli_fetch_assoc($result_create);
    //echo "<pre>" . $line['Create Table'] . "</pre><br>\n";
    mysql_free_result($result_create);

    if($row[0] == 'members_u'){
        $new_create = preg_replace("/` varchar\(100/","` varbinary(200",$line['Create Table']);
        $new_create = preg_replace("/` varchar\(30/","` varbinary(70",$new_create);
        
        $new_create = preg_replace("/` int\(11/","` varbinary(70",$new_create);
        $new_create = preg_replace("/`id` varbinary\(70/","`id` int(11",$new_create);
        $new_create = preg_replace("/` blob/","` blob",$new_create);
        $new_create = preg_replace("/` date/","` varbinary(70)",$new_create);
        $new_create = preg_replace("/` tinyint\(1\)/","` varbinary(20)",$new_create);
        $new_create = preg_replace("/`members_u`/","`members`",$new_create);
        $new_create = preg_replace("/AUTO_INCREMENT=\d+/","",$new_create);
        
        echo "<pre>" . $new_create . "</pre><br>\n";
        $result_create_new = mysqli_query($dbh, "DROP TABLE IF EXISTS `members` ");

        $result_create_new = mysqli_query($dbh, $new_create);

        if (!$result_create_new) {
            echo "DB Error, could not list tables\n";
            echo 'MySQL Error: ' . mysqli_error($dbh);
            }

        $sql_select_s_cols = "show columns from members";
       
        $result_select_s_cols = mysqli_query($dbh, $sql_select_s_cols);
        $fields_t = array();
        while ($line = mysqli_fetch_assoc($result_select_s_cols)) {
            if(preg_match("/varbinary|blob/",$line[Type])){
                $fields_t[] = "AES_ENCRYPT(`$line[Field]`,CONCAT('$key',`id`)) as `$line[Field]`";
                }
            else{
                $fields_t[] = "`$line[Field]` as `$line[Field]`";
                }
            }
        //sort($fields_t);
        $sql_copy = "INSERT INTO members SELECT " . join(", ", $fields_t). " FROM members_u";
        echo "<pre>$sql_copy</pre><br>\n";
        }

}

mysql_free_result($result);

?>
