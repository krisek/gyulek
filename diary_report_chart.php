<?php
include("config_base.php");
include("config_diary.php");
include("include.php");



/**
 * linear regression function
 * @param $x array x-coords
 * @param $y array y-coords
 * @returns array() m=>slope, b=>intercept
 */
function linear_regression($x, $y) {

  // calculate number points
  $n = count($x);

  // ensure both arrays of points are the same size
  if ($n != count($y)) {

    trigger_error("linear_regression(): Number of elements in coordinate arrays do not match.", E_USER_ERROR);

  }

  // calculate sums
  $x_sum = array_sum($x);
  $y_sum = array_sum($y);

  $xx_sum = 0;
  $xy_sum = 0;

  for($i = 0; $i < $n; $i++) {

    $xy_sum+=($x[$i]*$y[$i]);
    $xx_sum+=($x[$i]*$x[$i]);

  }

  // calculate slope
  $m = (($n * $xy_sum) - ($x_sum * $y_sum)) / (($n * $xx_sum) - ($x_sum * $x_sum));

  // calculate intercept
  $b = ($y_sum - ($m * $x_sum)) / $n;

  // return result
  return array("m"=>$m, "b"=>$b);

}


if(isset($_REQUEST['from'])){
    $from = $_REQUEST['from'];
}else{
    $from = strftime("2007-01-01",time());    
}

if(isset($_REQUEST['tipus'])){
    $tipus = $_REQUEST['tipus'];
}else{
    $tipus = "istentisztelet";    
}


if(isset($_REQUEST['to'])){
    $to = $_REQUEST['to'];
}else{
    $to = strftime("%Y-01-01",time()+(365*24*3600));      
}

?>
<h1>Statisztika</h1>
<p>
<form accept-charset="utf-8" name="reportform" method="get" action="diary_report_chart.php">
innentől: <input id="pattern" type="text" name="from" value="<?=$from?>" class="lightborder">
idáig: <input id="pattern" type="text" name="to" value="<?=$to?>" class="lightborder">
<select name="tipus">
<?
$statement_t = "SELECT DISTINCT(raw_tag) tipus FROM ${global_table_prefix}tipus_freetags where raw_tag IN ('istentisztelet','urvacsorai','bibliaora','hittanora','lelkipásztori beszélgetés','jegyes oktatás','közegyházi munka', 'Gyülekezet-kormányzás', 'egyéb alkalom')";
$result_t = mysqli_query($dbh, $statement_t) or die("Hiba a kérésben " ); // $statement['query']
while($line = mysqli_fetch_assoc($result_t)) {
    ?><option value="<?=$line['tipus']?>" <?if($line['tipus'] == $tipus):?>selected<?endif;?>><?=$line['tipus']?></option><?

}


?>
</select>
<input type="submit" value="Statisztika">
</form>
</p>
<hr>
<?

$statement['query'] = "SELECT tm, date(tm) day, hour(tm) hour,  tf.tag `típus`, resztvevok, urvacsora FROM tipus_freetags tf INNER JOIN ${global_table_prefix}tipus_freetagged_objects tfo ON (tf.id = tfo.tag_id) INNER JOIN ${global_table_prefix}diary d ON (tfo.object_id = d.id ) WHERE  d.tm > '$from' and d.tm < '$to' and tf.raw_tag = '$tipus' ORDER BY tm";
$statement['title'] = ucfirst($tipus) . " látogatottság";




$result = mysqli_query($dbh, $statement['query']) or die("Hiba a kérésben " ); // $statement['query']
$i = 0;
$events = array();
$hours = array();
/*
    date
    time
    count
    urvacsora
    
*/

$i = -1;
$prev_day = '';
while ($line = mysqli_fetch_assoc($result)) {

    if($prev_day != $line['day']){
        $i++;
    }
    $events[$i][$line['hour']]['c'] = $line['resztvevok'];
    $events[$i][$line['hour']]['u'] = $line['urvacsora'];
    $events[$i]['day'] = $line['day'];
    
    $hours[$line['hour']] = 1;
    $prev_day = $line['day'];
  }

$my_hours = array_keys($hours);
sort($my_hours, SORT_NUMERIC);
$p_x = array();
$p_y = array();

$to_count = 'c';
if($tipus == 'urvacsorai'){
    $to_count = 'u';
}

foreach($events as $index => $event){
    $events[$index]['nums'] = array();
    foreach($my_hours as $hour){
        array_push($events[$index]['nums'],$events[$index]['nums'][count($events[$index]['nums'])-1] + $events[$index][$hour][$to_count]);
    }
    array_push($p_x, $index);
    array_push($p_y, $events[$index]['nums'][7]);
}

$regression_params = linear_regression($p_x,$p_y);
//print_r($regression_params);
?>


<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Nap');
        data.addColumn('number', 'résztvevők');
        data.addColumn('number', 'trend');

        <?/*foreach($my_hours as $hour){?>
        data.addColumn('number', '<?=$hour?>');
        <?}*/ //end foreach
        
        ?>

        data.addRows([

        <?$i = 0; foreach($events as $index => $event){
            if($i != 0){
            ?>,
            <?
            }
            else{
                $i = 1;
            }?>
        ['<?=$events[$index]['day']?>', <?=$events[$index]['nums'][7]?>, <?=($regression_params['m']*$index) + $regression_params['b']?>]<?} //end foreach join(',', $events[$day]['nums'] )?>

        ]);

        

                var options = {
          title : '<?=$statement['title']?>',
          vAxis: {title: "fő"},
          hAxis: {title: "dátum"},
          seriesType: "bars",
          series: {1: {type: "line"}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));


        chart.draw(data, options);
      }
    </script>

  </head>
  <body>
    <div id="chart_div" style="width: 900px; height: 500px;"></div>
  </body>
</html>
