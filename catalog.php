<?php
include("config_base.php");
include("include.php");

//$statement = "SELECT distinct(catalog_name) catalog_name FROM ${global_table_prefix}catalog;";

?>
<? if($_SESSION['message'] ?? '' != '') { ?>
<p><b><?=$_SESSION['message']?></b></p>
<? 
    $_SESSION['message'] = '';
} ?>
<h1>Válassz katalógust</h1>
<p>

<?
//get non-empty catalogs
$statement = "select c.catalog_name catalog_name, count(*) count, max(c.member_id) max from ${global_table_prefix}catalog c, ${global_table_prefix}members m where c.member_id = m.id  and m.leave  = '0000-00-00' group by c.catalog_name;";
$result = mysqli_query($dbh, $statement) or die("Hiba a kérésben $statement ");
$catalogs = array();
while ($line = mysqli_fetch_assoc($result)) {
?>
<a href="list.php?catalog=<?=$line['catalog_name']?>"><?=$line['catalog_name']?></a> (<?if($line['count']==1 && $line['max']==0):?>0<?else:?><?=$line['count']?><?endif;?>) <?if($line['count']==1 && $line['max']==0):?><a href="catalog_manage.php?action=delete&catalog_name=<?=$line['catalog_name']?>" title="töröl">x</a><?endif;?><br/>

<?
$catalogs[$line['catalog_name']] = 1;
}

//get empty catalogs
$statement = "SELECT catalog_name, count(*) count, max(member_id) max  FROM ${global_table_prefix}catalog  group by catalog_name;";
$result = mysqli_query($dbh, $statement) or die("Hiba a kérésben $statement ");

while ($line = mysqli_fetch_assoc($result)) {
if(isset($catalogs[$line['catalog_name']])){
        continue;
}
?>
<a href="list.php?catalog=<?=$line['catalog_name']?>"><?=$line['catalog_name']?></a> (0) <a href="catalog_manage.php?action=delete&catalog_name=<?=$line['catalog_name']?>" title="töröl">x</a><br/>

<?
}
?>

</p>
<p>
<form action="catalog_manage.php" method="post">
<input type="text" id="catalog_name" name="catalog_name" value="Új katalógus" onFocus="document.getElementById('catalog_name').select()">
<input type="button" id="ok" name="ok" value="ok">
</form>
</p>
<p>
<a href="list.php?pattern=s:torolt">Törölt tagok</a> (minden adatbázisból)
</p>
</body>
</html>