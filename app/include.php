<?
header("Cache-control: no-cache");
header("Content-Type: text/html; charset=utf-8");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="style.css">

<link rel="shortcut icon" href="<?=preg_replace('/gyulek.*$/','gyulek/favicon.ico',$_SERVER['REQUEST_URI'])?>">

<title>gyulek</title>

</head>
<?if(strstr($_SERVER['REQUEST_URI'],'list.php') || strstr($_SERVER['REQUEST_URI'],'diary.php') || strstr($_SERVER['REQUEST_URI'],'diary_report.php')):?>
<div STYLE="position:absolute; top:10px; left:95%; border-width:1px; border-color:white;"><a href="index.php">Gyulek</a></div>
<?endif;?>

<?
if($offshore_enabled){
    include('login.inc.php');
}
?>



