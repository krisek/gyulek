<?

function pop3_login($host,$user,$pass){
    return (imap_open("{"."$host:995/pop3/ssl/novalidate-cert"."}",$user,$pass));
}


if( 
    ( !isset($_SESSION['key']) && ! isset($_POST['key']) ) || 
        ( !isset($_SESSION['user']) && !isset($_POST['user']) && !isset($_COOKIE['client_id']) ) || 
            $_SESSION['loginfail'] == 1){
?>
<form id="login" action="" method="post">
<input type="hidden" name="uri" value="<?=$_SERVER['REQUEST_URI']?>" >
<div id="login_div">
<center><span style="font-size: 44px;">gyulek.</span><br/>

<?if( ($_POST['user'] == "") && (!isset($_COOKIE['client_id']) && !isset($_SESSION['user']) ) || $_SESSION['loginfail'] == 1 ){?>
<?if($_SESSION['loginfail'] == 1):?>Sikertelen belépés<br/><?php $_SESSION['loginfail'] = 0?><?endif;?>
felhasználónév:<br/>
<input type="text" name="user" value="" size="12" style="width: 250px" class="lightborder <?if($_SESSION['loginfail'] == 1):?>redborder<?endif;?> lightborder_w2" ><br/><br/>
jelszó:<br/>
<input type="password" name="password"  style="width: 150px" value="" class="lightborder lightborder_w2" ><br/>
<br>
<span style="font-size: 16px;"><input type="checkbox" name="remember" value="remember" checked>emlékezz rám<span><br>
<?}?>

<!-- kulcs:<br/> //-->
<input type="hidden" name="key"  style="width: 150px" value="" class="lightborder lightborder_w2" ><br/>
<br>
<input type="submit" name="login" value="Belépés" class="lightborder_w2" ><br/>
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

    if(isset($_SESSION['loginfail'])){
        $_SESSION['loginfail'] = 0;
    }

    if(isset($_POST['key'])){
        $_SESSION['key'] = $_POST['key'];
    }
    
    if(isset($_POST['user']) && ! in_array($_POST['user'], array_merge($offshore_users, [$admin_user])  )   ){
        $_SESSION['loginfail'] = 1;
        error_log('user not on list: ' . $_POST['user'] . "   " . join(', ', $offshore_users));
        header("Location: " . (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        die();
    }

    if(isset($_COOKIE['client_id'])){
        //put username into session
        $client_id = mysqli_real_escape_string($dbh, $_COOKIE['client_id']);
        $result = mysqli_query($dbh, "SELECT * FROM ${global_table_prefix}permanent_users WHERE client_id = '$client_id'"); 
        if ($line = mysqli_fetch_assoc($result)) {
            $_SESSION['user'] = $line['user'];
        }
        mysqli_free_result($result);
    }
    elseif(isset($_POST['user']) && isset($_POST['password'])){
        
        $login_success = 0;

        if($_POST['user'] == $admin_user){
            if(password_verify($_POST['password'], $admin_pass)){
                $login_success = 1;
            }
        }
        else{
            if(!(pop3_login("mail.lutheran.hu",$_POST['user'],$_POST['password']) === FALSE)){
                $login_success = 1;
            }
        }
        
        if($login_success == 1){
            $_SESSION['user'] = preg_replace('/@lutheran.hu/','',$_POST['user']);
            
            if($_POST['remember'] == 'remember'){
                //generate id
                $user = mysqli_real_escape_string($dbh, strtolower($_SESSION['user']));
                $client_id = md5(rand());
                $statement = "INSERT INTO ${global_table_prefix}permanent_users (user, client_id, ts) values ('$user','$client_id', NOW())";
                $result = mysqli_query($dbh, $statement) or die("Error in statement -- " . mysqli_error($dbh));
                //setcookie("client_id", $client_id, time()+60*60*24*28,null,null,TRUE);
                setcookie("client_id", $client_id, time()+60*60*24*28);
            }

        }
        else{
            error_log('login failed ' . $_POST['user']);
            $_SESSION['loginfail'] = 1;
            header("Location: " . (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
            die();
        }
    }

}


?>

