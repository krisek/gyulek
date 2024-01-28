<?

function pop3_login($host,$port,$user,$pass,$folder="INBOX",$ssl=false){
    $ssl=($ssl==false)?"/novalidate-cert":"";
    return (imap_open("{"."$host:$port/pop3$ssl"."}$folder",$user,$pass));
}


if( ( !isset($_SESSION[key]) && !isset($_POST[key]) ) || (!isset($_SESSION[user]) && !isset($_POST[user]) && !isset($_COOKIE[client_id])) ){
?>
<form id="login" action="" method="post">
<input type="hidden" name="uri" value="<?=$_SERVER[REQUEST_URI]?>" >
<div id="login_div">
<center><span style="font-size: 44px;">gyulek.</span><br/>

<?if( (($_POST[user] == "") && !isset($_COOKIE[client_id]) && !isset($_SESSION[user]) )  ){?>
felhasználónév<?if($_SESSION[loginfail] == 1):?><?endif;?>:<br/>
<input type="text" name="user" value="" size="12" style="width: 250px" class="lightborder <?if($_SESSION[loginfail] == 1):?>redborder<?endif;?> lightborder_w2" ><br/><br/>
jelszó:<br/>
<input type="password" name="password"  style="width: 150px" value="" class="lightborder lightborder_w2" ><br/>
<br>
<span style="font-size: 16px;"><input type="checkbox" name="remember" value="remember" checked>remember me<span><br>
<?}?>

kulcs:<br/>
<input type="password" name="key"  style="width: 150px" value="" class="lightborder lightborder_w2" ><br/>
<br>
<input type="submit" name="login" value="login" class="lightborder_w2" ><br/>
</form>
</center>
</div>
</body>
</html>
<?
    exit;
    }
else{
//do some logic
    if(isset($_POST[key])){
        $_SESSION[key] = $_POST[key];
        }
    if(isset($_COOKIE[client_id])){
        //put username into session
        $client_id = mysql_real_escape_string($_COOKIE[client_id]);
        $result = mysqli_query($dbh, "SELECT * FROM ${global_table_prefix}permanent_users WHERE client_id = '$client_id'") or die("Error in statement -- this was not very elegant I know");
        if ($line = mysqli_fetch_assoc($result)) {
            $_SESSION[user] = $line[user];
            }
        mysql_free_result($result);
        }
    elseif((isset($_POST[user]) && isset($_POST[password]))){
        if(isset($_SESSION[loginfail])){
            unset($_SESSION[loginfail]);
            }
            //check password
        if(pop3_login("mail.lutheran.hu",110,$_POST[user],$_POST[password]) === FALSE){
            $_SESSION[loginfail] = 1;
            }
        else{
            $_SESSION[user] = preg_replace('/@lutheran.hu/','',$_POST[user]);
            
            if($_POST[remember] == 'remember'){
                //generate id
                $user = mysql_real_escape_string(strtolower($_SESSION[user]));
                $client_id = md5(rand());
                $statement = "INSERT INTO ${global_table_prefix}permanent_users (user, client_id) values ('$user','$client_id')";
                $result = mysqli_query($dbh, $statement) or die("Error in statement -- this was not very elegant I know");
                //setcookie("client_id", $client_id, time()+60*60*24*28,null,null,TRUE);
                setcookie("client_id", $client_id, time()+60*60*24*28);
                }
            }
        }

    }


?>

