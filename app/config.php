<?

require_once("freetag.class.php");

$list_user_id = 1;

$freetag_options = $db_options;

$freetag_member_options = array(
	'table_prefix'  => $global_table_prefix.'member_',
	'debug' => FALSE
);

$freetags = array();
$freetags['cimke'] = new freetag(array_merge($freetag_options,$freetag_member_options));

$tag_resolve = array(
'cimke'=>'Címke',
);



$field_order = array(
    'nev','telefon','telefon_mobil','cim_g','email',
    'anyja_neve', 'szuletes', 'leany_neve', 
    'kereszteles', 'religion_id', 'szarm_hely',
    'konfirmacio', 'foglalkozas', 'adoszam',
    'egyh_esk',  'polg_esk' , 
    'hazastars', 'halal_datum', 'misc1','misc2',  'megjegyzes'
);

$fields['group_descr']['misc1'] = 'Családfő az. / Családi áll.';
$fields['group_descr']['misc2'] = 'Képviselő / Presbiter';
$fields['group_descr']['cim_g'] = 'Ir.szám, város, utca';

$fields['group']['misc1'][0] = 'member_id';
$fields['group']['misc1'][1] = 'csal_all_id';
$fields['group']['misc2'][0] = 'kepviselo';
$fields['group']['misc2'][1] = 'presbiter';

$fields['group']['cim_g'][0] = 'ir_szam';
$fields['group']['cim_g'][1] = 'varos';
$fields['group']['cim_g'][2] = 'cim';


$fields['nev']['descr'] = "Név";
$fields['nev']['type'] = "text";

$fields['telefon']['descr'] = "Telefon";
$fields['telefon']['type'] = "phone";

$fields['telefon_mobil']['descr'] = "Mobil";
$fields['telefon_mobil']['type'] = "phone";

$fields['email']['descr'] = "E-mail";
$fields['email']['type'] = "text";

$fields['ir_szam']['descr'] = "Irányítószám";
$fields['ir_szam']['type'] = "text";
$fields['ir_szam']['length'] = "40px";

$fields['varos']['descr'] = "Város";
$fields['varos']['type'] = "text";

$fields['cim']['descr'] = "Utca";
$fields['cim']['type'] = "text";


$fields['leany_neve']['descr'] = "Leánykori neve";
$fields['leany_neve']['type'] = "text";

$fields['adoszam']['descr'] = "Adószám";
$fields['adoszam']['type'] = "text";


$fields['anyja_neve']['descr'] = "Anyja neve";
$fields['anyja_neve']['type'] = "text";

$fields['foglalkozas']['descr'] = "Foglalkozás";
$fields['foglalkozas']['type'] = "text";

$fields['religion_id']['descr'] = "Vallás";
$fields['religion_id']['type'] = "reference";

$fields['csal_all_id']['descr'] = "Családi állapot";
$fields['csal_all_id']['type'] = "reference";

$fields['member_id']['descr'] = "Családfő azonosí­tója";
$fields['member_id']['type'] = "text";


$fields['group_descr']['szuletes'] = 'Születési hely/dátum'; 
$fields['szul_hely']['type'] = "text"; 
$fields['group']['szuletes'][0] = 'szul_hely'; 
 
$fields['szul_datum']['type'] = "date"; 
$fields['group']['szuletes'][1] = 'szul_datum'; 

$fields['group_descr']['kereszteles'] = 'Keresztelés helye/dátuma/ige'; 
$fields['ker_hely']['type'] = "text"; 
$fields['group']['kereszteles'][0] = 'ker_hely'; 
 
$fields['ker_datum']['type'] = "date"; 
$fields['group']['kereszteles'][1] = 'ker_datum'; 

$fields['ker_ige']['type'] = "text"; 
$fields['group']['kereszteles'][2] = 'ker_ige'; 

$fields['group_descr']['konfirmacio'] = 'Konfirmáció éve/ige'; 
$fields['konf_ev']['type'] = "text"; 
$fields['konf_ev']['length'] = "40px"; 
$fields['group']['konfirmacio'][0] = 'konf_ev';
  
$fields['konf_ige']['type'] = "text"; 
$fields['group']['konfirmacio'][1] = 'konf_ige'; 
  

$fields['group_descr']['polg_esk'] = 'Polg.esk. helye/dátuma'; 

$fields['polg_esk_h']['type'] = "text"; 
$fields['group']['polg_esk'][0] = 'polg_esk_h'; 
 
$fields['polg_esk_datum']['type'] = "date"; 
 $fields['group']['polg_esk'][1] = 'polg_esk_datum'; 
 

$fields['group_descr']['egyh_esk'] = 'Egyh.esk. helye/dátuma/ige'; 
$fields['egyh_esk_h']['type'] = "text"; 
$fields['group']['egyh_esk'][0] = 'egyh_esk_h'; 
 
$fields['egyh_esk_datum']['type'] = "date"; 
$fields['group']['egyh_esk'][1] = 'egyh_esk_datum'; 

$fields['egyh_esk_ige']['type'] = "text"; 
$fields['group']['egyh_esk'][2] = 'egyh_esk_ige'; 


$fields['group_descr']['hazastars'] = 'Házastárs neve/azonosítója';   
$fields['hazastars_neve']['type'] = "text";
$fields['group']['hazastars'][0] = 'hazastars_neve'; 
  
$fields['hazastars']['type'] = "text"; 
$fields['hazastars']['length'] = "40px";  
$fields['group']['hazastars'][1] = 'hazastars';

$fields['szarm_hely']['descr'] = "Származási hely";
$fields['szarm_hely']['type'] = "text"; 

  
$fields['halal_datum']['descr'] = "Halál dátuma";
$fields['halal_datum']['type'] = "date"; 
 
 
$fields['presbiter']['descr'] = "Presbiter";
$fields['presbiter']['type'] = "reference";

$fields['kepviselo']['descr'] = "Képviselő";
$fields['kepviselo']['type'] = "reference";


$fields['megjegyzes']['descr'] = "Megjegyzés";
$fields['megjegyzes']['type'] = "textarea";

$fields['cimke']['descr'] = "Cimkék";
$fields['cimke']['type'] = "textarea";

$reference['presbiter'][0] = "nem";
$reference['presbiter'][1] = "igen";
$reference['kepviselo'][0] = "nem";
$reference['kepviselo'][1] = "igen";
$reference['religion_id'][1] = "evangélikus";
$reference['religion_id'][2] = "református";
$reference['religion_id'][3] = "katolikus";
$reference['religion_id'][4] = "ortodox";
$reference['religion_id'][5] = "unitárius";
$reference['religion_id'][6] = "egyéb";
$reference['religion_id'][7] = "felekezeten kívüli";

$reference['csal_all_id'][1] = "nőtlen";
$reference['csal_all_id'][2] = "hajadon";
$reference['csal_all_id'][3] = "nős";
$reference['csal_all_id'][4] = "férjezett";
$reference['csal_all_id'][5] = "elvált";
$reference['csal_all_id'][6] = "özvegy";
$reference['csal_all_id'][7] = "egyéb";

//set-up tags
/*$reference['tags'] = array();
$statement_tags = "SELECT tag FROM ${global_table_prefix}tags";
$result = mysqli_query($dbh, $statement_tags) or die("Hiba a kérésben $statement_tags ");
while ($line = mysqli_fetch_assoc($result)) {
    array_push($reference['tags'], $line['tag']);   
}
*/
//set-up catalogs
if( !isset($_SESSION['catalogs']) || isset($_REQUEST['catalog'])){
    LoadCatalogs();
}


$amount_ref['fenntart'] = 'fenntart';
$amount_ref['adomany'] = 'adomány';
$amount_ref['alapitvany'] = 'alapítvány';        

function FormatMSISDN($msisdn){
    $msisdn = preg_replace("/\-|\//","",$msisdn);
    $msisdn = preg_replace("/^06/","",$msisdn);
    
    $n = strlen($msisdn);
    if($n != 9 && $n != 7 && $n != 8){
        return $msisdn;
    }

    
    if($n == 9 || $n == 7){
        $parts=array(4,3,2);
        $separators=array('-','/');
    }
    if($n == 8){
        $parts=array(3,3,2);
        $separators=array('-','/');
    }
    $i=0;
    $begin = 0;
    $new_msisdn = '';
    while($begin < $n){
        if($i > 0){
            $new_msisdn = $separators[$i-1].$new_msisdn;
        }
        $begin += $parts[$i];    
        $new_msisdn = substr($msisdn, -1*$begin, $parts[$i]).$new_msisdn;
        $i++;
    }
    return $new_msisdn;
}

function CheckDateFormat($date){
    if($date == ''){
        return 0;
    }
    if(preg_match("/\d{4}\-\d{2}\-\d{2}/", $date)){
        return 0;
    }
    if(preg_match("/^\d{4}$/", $date)){
        return 0;
    }
    return 1;
}


$field_cast = array();

$field_cast["member_id"] = "unsigned";
$field_cast["szul_datum"] = "date";
$field_cast["ker_datum"] = "date";
$field_cast["konf_ev"] = "date";
$field_cast["polg_esk_datum"] = "date";
$field_cast["egyh_esk_datum"] = "date";
$field_cast["halal_datum"] = "date";
$field_cast["entry"] = "date";
$field_cast["leave"] = "date";
$field_cast["ts"] = "datetime";
$field_cast["hazastars"] = "unsigned";
$field_cast["kepviselo"] = "unsigned";
$field_cast["presbiter"] = "unsigned";
#$field_cast[""] = "date";


function getQ( $field, $table = "", $w_as="1" ){
    global $key;
    global $field_cast;
    global $aes_enabled;

    $cast = "char";
    if(isset($field_cast[$field])){
        $cast = $field_cast[$field];
        }
    if($table != ""){
        $table .= "."; 
        }
    $as = " $field";
    if($w_as == 0){
        $as = "";
        }

    if(! $aes_enabled){
        return("$table$field$as");
        }

    return("cast(aes_decrypt($table$field, concat(@key_me,${table}id)) as $cast)$as");

    }

function setQ($field, $table = "", $str = 1) {
    global $key, $aes_enabled;
    if(! $aes_enabled){
        if($str == 1){
            return "'$field'";
            }
        else{
            return "$field";
        }
    }   
    if($str == 1){
        return "aes_encrypt('$field', concat(@key_me,${table}id))";
        }
    else{
        return "aes_encrypt($field, concat(@key_me,${table}id))";
        }
    
    }


function LoadCatalogs(){
    global $global_table_prefix, $dbh;
    $catalogs = array();
    $statement_catalogs = "SELECT distinct(catalog_name) catalog_name FROM ${global_table_prefix}catalog;";
    $result = mysqli_query($dbh, $statement_catalogs) or die("Hiba a kérésben $statement_catalogs ");
    while ($line = mysqli_fetch_assoc($result)) {
        $catalogs[$line['catalog_name']] = 1;   
    }
    $_SESSION['catalogs'] = $catalogs;
}

function DisplayInputField($field,$value = '',$type='text',$show=0,$length="80px",$id=''){
    global $reference;
    if ($type == 'phone'){
        $value = FormatMSISDN($value);
        $type = 'text';
        }  
    if ($type == 'text'){
    ?>
        <input type="text" name="<?=$field?>" value="<?=$value?>" class="<?if($show != 1):?>lightborder<?else:?>redborder<?endif;?>" style="width: <? if ($length != ''): ?><?=$length?><?else:?><? if ($value != ''): ?><?=min(180,round(strlen($value)*11))?>px<?else:?>80px<?endif;?><?endif;?>">
    <?
    }
    if ($type == 'textarea'){
    ?>
                
        <textarea name="<?=$field?>" class="<?if($show != 1):?>lightborder<?else:?>redborder<?endif;?>" id="<?=$field?>_<?=$id?>"><?=$value?></textarea>
        <? if ($field == 'cimke') {?>
        
        
        
        <?}?>
    <?
    }
    if ($type == 'reference'){
    ?>
        <select name="<?=$field?>">
        <?
            $n=1;
            if($field=='presbiter' || $field=='kepviselo'){
                $n=0;
            }
            foreach($reference[$field] as $val){
                ?><option value="<?=$n?>"   <? if ($n==$value): ?>selected<?endif;?>><?=$val?></option><?
                $n++;
            }
        ?>
        </select>

    <?
    }
    if ($type == 'date'){
    ?>
        <input type="text" name="<?=$field?>" value="<?if($value != '0000-00-00'):?><?=$value?><?endif;?>" class="<?if($show != 1):?>lightborder<?else:?>redborder<?endif;?>" style="width: <? if ($length != ''): ?><?=$length?><?else:?><? if ($value != ''): ?><?=round(strlen($value)*11)?><?else:?>80px<?endif;?><?endif;?>">
    <?
    }
}



?>
