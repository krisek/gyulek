<?php

include("config_base.php");

if(isset($_REQUEST['year'])){
    $year= $_REQUEST['year'];
}else{
    $year = strftime("%Y",time()-365*24*3600);    
}

$statements = array();
//--fenntartók
$statements[0]['title'] = "Fenntartók";
$statements[0]['format'] = "text/csv";
$statements[0]['query'] = "select m.nev Név,m.ir_szam Irányítószám, m.varos Város, m.cim Cím, m.adoszam Adószám,sum(a.fenntart) Fenntartás, sum(a.alapitvany) Alapívátny, sum(a.adomany) Adomány, sum(a.amount) Összesen from amounts_aggr a, members m where a.member_id = m.id and year(dt) = $year  group by a.member_id order by nev;";



if(!isset($_REQUEST['statement_id'])){

include("include.php");

?>
<script>
function submitReport(id) {
    year = document.getElementById('year');
    window.location = "?statement_id="+id+"&year="+year.value;

}


</script>
<h1>Jelentések, statisztikák, közvetlen adatbázislekérések</h1>
<p>
<form accept-charset="utf-8" name="reportyear" method="get" action="report.php">
év: <input id="year" type="text" name="year" value="<?=$year?>" class="lightborder">
</form>
</p>
<hr>
<?


foreach ($statements as $i => $statement){
    ?>
    <a href="javascript:submitReport('<?=$i?>')"><?=$statement['title']?> (<?=$statement['format']?>)
    <?

}
}
else{

header("Cache-control: no-cache");
header("Content-Type: ".$statements[$_REQUEST['statement_id']]['format']."; charset=utf-8");
header("Content-disposition: attachment; filename=".$statements[$_REQUEST['statement_id']]['title']."_".$year.";");

$result = mysqli_query($dbh, $statements[$_REQUEST['statement_id']]['query']);
$delimiter = "\t";
for ($i = 0; $i < mysqli_num_fields($result); ++$i) {
    ?><?=mysqli_field_name($result,$i)?><?=$delimiter?><?
}
echo "\n";
while ($line = mysqli_fetch_row($result)) {
    ?><?=implode($delimiter,$line)?><?echo "\n";
    }

}
?>
