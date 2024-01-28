<?

include("config_base.php");
include("config.php");
include("include.php");
$length = 100;

include("list_init.php");
?>

<? if($_SESSION['message'] != '') { ?>
<p><b><?=$_SESSION['message']?></b></p>
<? 
    $_SESSION['message'] = '';
} ?>

<?
// SQL kérés végrehajtása
if(isset($_REQUEST['add']) && $_REQUEST['add'] == 'woemail'){
    $additional_filter = "and ((email = '') or (email is NULL))";
}

$rowcount = $length + 1;
/* $selectfields = 'count(*) co'; */
$selectfields = "SQL_CALC_FOUND_ROWS 1";
$limit = '';

include('query.php');


$result = mysqli_query($dbh, $statement) or die("Hiba a kérésben $statement ");


?>
<script>
function Submit(offset){
    var offsetElem = document.getElementById("offset");
    offsetElem.value = offset;
    document.forms.boritek.submit();
}
</script>
<h1>Nyomtatás</h1>
<p>Szűrőfeltétel: <b><?=$filtertext?></b></p>

<form accept-charset="utf-8" name="boritek" id="boritek" method="POST" action="list_printer.php"  target="_blank">
<table>
<tr>
<td valign="top">
<p>Boríték minta: 
<td>
<select name="envtpl" size="6">

<?
if ($handle = opendir('./boritek')) {
   while (false !== ($file = readdir($handle))) {
       if ($file != "." && $file != ".." && preg_match("/\.tex$|\.html$/",$file)) {
    	?><option <?if($file == 'boritek.html'){?>selected<?}?>><?=$file?></option><?       
       }
   }
   closedir($handle);
}
?> 

	

</select>
<td valign="top">
<p>Feladó: 
<td><textarea cols=40 rows=5 id="felado" name="felado">
<?=$default_address?>
</textarea>
</tr>
</table>

</p>
<input type="hidden" name="length" value="<?=$length?>">
<input type="hidden" name="pattern" value="<?=$pattern?>">
<input type="hidden" name="tag_pattern" value="<?=$tag_pattern?>">
<input type="hidden" name="bday" value="<?=$bday?>">
<input type="hidden" name="id" value="<?=$id ?? ''?>">
<input type="hidden" name="offset" value="" id="offset">
<?if(isset($_REQUEST['add']) && $_REQUEST['add'] == 'woemail'):?>
<input type="hidden" name="add" value="woemail">
<?endif;?>
<?
$rowcount = 0;
$result_numrows = mysqli_query($dbh, "SELECT FOUND_ROWS()");
$total_a = mysqli_fetch_row($result_numrows);
$rowcount = $total_a[0];

if ($rowcount > 0) {

?>
<?=$rowcount?> találat <br>

<?
$i=1;
for($offset = 0; $offset < $rowcount; $offset+=$length){
	
	?>
	
	<a href="javascript:Submit('<?=$offset?>')"><?=$i?>. adag</a><br>
	
	<?	
	$i++;
}

}

/* Eredményhalmaz felszabadítása */
mysqli_free_result($result);


// Kapcsolat lezárása
mysqli_close($dbh);
?>
</form>
<br><br><hr>
<a href="list.php?offset=<?=$_SESSION['offset'] ?? 0?>&pattern=<?=$pattern?>">Vissza az adatokhoz</a><br>

</body>
</html>
