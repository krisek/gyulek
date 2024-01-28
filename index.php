<?php
include('config_base.php');
include('include.php');

?>
<body>
<div id="header">
    <table>

    <tr align="left">
    <td><img src="luther.jpg" width="92">
    <td align="left"><h1 style="color:#333333;font-family:Verdana,Arial,Sans-Serif;">gyülek</h1>
    <p id="blog_description" style="color:#333333;font-family:Verdana,Arial,Sans-Serif;">gyülekezeti nyilvántartó</p>
    </table>
</div>

<br><br><br><br><br>
<hr>

<div id="body">
<?if(is_readable('config_db.php')):?>
<a href="list.php?clearsession=true">Gyülekezeti tagok</a><br/>
<a href="diary.php?clearsession=true">Gyülekezeti napló</a><br/>
<a href="catalog.php?clearsession=true">Katalógusok</a><br/>
<a href="diary_report.php?clearsession=true">Gyülekezeti napló statisztika</a><br/>
<a href="report.php?clearsession=true">Statisztikák, jelentések, közvetlen adatbázislekérések</a><br/>
<?else:?>
<b>A config_db.php fájl nem olvasható. Ellenőrizd a telepítést.</b>
<?endif;?>
</div>

<?include('footer.php');?>
</body>
</html>
