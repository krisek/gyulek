<?

$line = array();
$line['id'] = '';

include("lang.php");
include("config_base.php");
include("config.php");
include("include.php");
include("member_form.js.php");
unset($_SESSION['id']);
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Datum in der Vergangenheit
?>

<script>
function setFocus(){
 document.memberedit.nev.focus();
 document.getElementById('member2row').style.display = "";
 document.getElementById('innermember').style.display = "";
 document.getElementById('money_box_').style.display = "none";
 var catalogs = new Array();
 <?
  $i=0;
  foreach ($_SESSION['catalogs'] as $key => $value){
  ?>catalogs[<?=$i?>]='<?=$key?>';<?
    $i++;
  }
 ?>  
 mycatalog = document.getElementById('catalogs');
 for(var i=0; i< catalogs.length; i++){
 var opt=document.createElement('option');
 opt.text=catalogs[i];
                opt.value=catalogs[i];
                if(i==0){
                    opt.selected=true;
                }
                
                mycatalog.add(opt,null);
                

 }
}


</script>
<body onLoad="setFocus()">
<div STYLE="position:absolute; top:10px; left:90%; border-width:1px; border-color:white;"><a href="index.php">Gyulek</a>&nbsp;<a href="list.php">tagok</a></div>
<span id="session_message"></span>
<table border="0" width="100%">
<tr><td>#</td><td>Név</td><td>Telefon</td><td>Mobil</td><td>Cím</td></tr>
<?include('member_form.php')?>

</table>

</body>
</html>
