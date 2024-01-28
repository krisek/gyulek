<?php

include("config_base.php");
include("config_diary.php");
include("include.php");



if(isset($_REQUEST['from'])){
    $from = $_REQUEST['from'];
}else{
    $from = strftime("%Y-01-01",time());    
}

if(isset($_REQUEST['to'])){
    $to = $_REQUEST['to'];
}else{
    $to = strftime("%Y-01-01",time()+(365*24*3600));      
}

?>
<h1>Statisztika</h1>
<p>
<form accept-charset="utf-8" name="reportform" method="get" action="diary_report.php">
innentől: <input id="pattern" type="text" name="from" value="<?=$from?>" class="lightborder">
idáig: <input id="pattern" type="text" name="to" value="<?=$to?>" class="lightborder">
<input type="submit" value="Statisztika">
</form>
</p>
<hr>
<?

$statements = array();
//--vendégszolgálat - név, ki hányszor volt:        
$statements[0]['title'] = "Lelkészek";
$statements[0]['query'] = "SELECT lf.tag `lelkész`, COUNT(*) as `alkalmak száma`  FROM ${global_table_prefix}lelkesz_freetags lf INNER JOIN ${global_table_prefix}lelkesz_freetagged_objects lfo ON (lf.id = lfo.tag_id) INNER JOIN diary d ON (lfo.object_id = d.id ) WHERE d.tm > '$from' and d.tm < '$to'   GROUP BY lf.tag ORDER BY `alkalmak száma` DESC, tag ASC";
//
//--istentisztelet - vasárnapi, ünnepi alkalmak száma, résztvevők száma
$statements[1]['title'] = "Alkalmak";
$statements[1]['query'] = "SELECT tf.tag `típus`, COUNT(*) as darab, sum(resztvevok) `résztvevők`, sum(urvacsora) `úrvacsorát vett` FROM ${global_table_prefix}tipus_freetags tf INNER JOIN ${global_table_prefix}tipus_freetagged_objects tfo ON (tf.id = tfo.tag_id) INNER JOIN ${global_table_prefix}diary d ON (tfo.object_id = d.id ) WHERE d.tm > '$from' and d.tm < '$to'  GROUP BY tf.tag ORDER BY darab DESC, tf.tag ASC;";

//$statements[2]['title'] = "Helyek vs. úrvacsora";
//$statements[2]['query'] = "SELECT hf.tag `hely`, COUNT(*) as darab, sum(resztvevok) `résztvevők`, sum(urvacsora) `úrvacsorát vett` FROM hely_freetags hf INNER JOIN hely_freetagged_objects hfo ON (hf.id = hfo.tag_id) INNER JOIN diary d ON (hfo.object_id = d.id ) WHERE d.tm > '$from' and d.tm < '$to'  GROUP BY hf.tag ORDER BY darab DESC, hf.tag ASC;";


foreach ($statements as $statement){
   $result = mysqli_query($dbh, $statement['query']) or die("Hiba a kérésben " . $statement['query'] );
   $i = 0;
?>    
  <h2><?=$statement['title']?></h2>
  <table border="0" class="lightborder">
<?while ($line = mysqli_fetch_assoc($result)) {
    if($i == 0){
        ?><tr class="lightborder"><?
        foreach ($line as $key => $value){
            ?><td class="lightborder"><?=$key?></td><?                   
        }
        ?></tr><?
    }

  ?>
  <tr><?
        foreach ($line as $key => $value){
            ?><td><?=$value?></td><?                   
        }
        ?></tr>
  <?
  $i++;
  }?>
  
  </table>
  <br><br>
  <hr>    
<?    
    }
?>
