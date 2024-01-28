<?
if(isset($_REQUEST['clearsession']) && $_REQUEST['clearsession'] == 'true'){
    unset($_SESSION['offset']);
    unset($_SESSION['pattern']);
    unset($_SESSION['tag_pattern']);
    unset($_SESSION['bday']);
    unset($_SESSION['id']);
    unset($_SESSION['order']);
}


if(isset($_REQUEST['catalog']) && isset($_SESSION['catalogs'][$_REQUEST['catalog']])){
    $_SESSION['catalog'] = $_REQUEST['catalog'];
}
else if(!isset($_REQUEST['catalog']) && !isset($_SESSION['catalog'])){
    $_SESSION['catalog'] = $default_catalog;
    /*foreach ($_SESSION['catalogs'] as $key => $value){
        $_SESSION['catalog'] = $key;
        break;
    }    
*/}

$pattern = $_REQUEST['pattern'] ?? '';
$tag_pattern = $_REQUEST['tag_pattern'] ?? '';
$offset = $_REQUEST['offset'] ?? 0;
$bday = $_REQUEST['bday'] ?? '';
$order = $_REQUEST['order'] ?? '';

if($offset == ''){
   $offset = $_SESSION['offset'] ?? 0;
}


if($offset == '' || $offset < 0){
    $offset = 0;
}

if($pattern == ''){
   $pattern = $_SESSION['pattern'] ?? '';
}

if($tag_pattern == ''){
   $tag_pattern = $_SESSION['tag_pattern'] ?? '';
}

if($order == ''){
   $order = $_SESSION['order'] ?? '';
}

if($bday == ''){
   $bday = $_SESSION['bday'] ?? '';
}


if(isset($_REQUEST['pattern'])){
    $_SESSION['pattern'] = $_REQUEST['pattern'];
}

if(isset($_REQUEST['tag_pattern'])){
    $_SESSION['tag_pattern'] = $_REQUEST['tag_pattern'];
}

if(isset($_REQUEST['order'])){
    $_SESSION['order'] = $_REQUEST['order'];
}


if(isset($_REQUEST['offset'])){
    $_SESSION['offset'] = $_REQUEST['offset'];
}

if(isset($_REQUEST['bday'])){
    $_SESSION['bday'] = $_REQUEST['bday'];
}

//print_r($_SESSION);


?>