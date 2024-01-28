<?

include("config_base.php");
include("config.php");
$length = $_REQUEST['length'] ?? 100;
include("list_init.php");

?><? if($_SESSION['message'] ?? '' != '') { ?>
<p><b><?=$_SESSION['message']?></b></p>
<? 
    $_SESSION['message'] = '';
} ?><?
// SQL k?? v?rehajt?a
if(isset($_REQUEST['add']) && $_REQUEST['add'] == 'woemail'){
    $additional_filter = "and ((email = '') or (email is NULL))";
}

$rowcount = $length;
#$selectfields = 'm.id id, m.nev nev, m.leany_neve leany_neve ,m.anyja_neve anyja_neve ,m.foglalkozas foglalkozas ,m.ir_szam ir_szam ,m.varos varos ,m.cim cim ,m.telefon_mobil telefon_mobil,m.telefon telefon ,m.email email ,m.religion_id religion_id ,m.member_id member_id ,m.csal_all_id csal_all_id ,m.szarm_hely szarm_hely ,m.szul_hely szul_hely ,m.szul_datum szul_datum ,m.ker_hely ker_hely ,m.ker_datum ker_datum ,m.ker_ige ker_ige ,date_format(m.konf_ev,\'%Y\') konf_ev ,m.konf_ige konf_ige ,m.polg_esk_h polg_esk_h ,m.polg_esk_datum polg_esk_datum ,m.egyh_esk_h egyh_esk_h ,m.egyh_esk_datum egyh_esk_datum ,m.egyh_esk_ige egyh_esk_ige ,m.hazastars_neve hazastars_neve ,m.hazastars hazastars ,m.halal_datum halal_datum ,m.presbiter presbiter,m.kepviselo kepviselo, year(now())-year(m.szul_datum) kor';
$selectfields = "SQL_CALC_FOUND_ROWS m.id id, " . getQ("nev","m") ." , ".getQ("leany_neve","m")." , ".getQ("anyja_neve","m")." , ".getQ("foglalkozas","m")." , ".getQ("ir_szam","m")." , ".getQ("varos","m")." , ".getQ("cim","m")." , ".getQ("telefon_mobil","m") .",". getQ("telefon","m")." , ".getQ("email","m")." , ".getQ("religion_id","m")." , ".getQ("member_id","m")." , ".getQ("csal_all_id","m")." , ".getQ("szarm_hely","m")." , ".getQ("szul_hely","m")." , ".getQ("szul_datum","m")." , ".getQ("ker_hely","m")." , ".getQ("ker_datum","m")." , ".getQ("ker_ige","m") ." ,date_format(".getQ("konf_ev","m",0).",'%Y') konf_ev , ". getQ("konf_ige","m") ." , ".getQ("polg_esk_h","m")." , ". getQ("polg_esk_datum","m")." , ".getQ("egyh_esk_h","m")." , ".getQ("egyh_esk_datum","m")." , ".getQ("egyh_esk_ige","m")." , ".getQ("hazastars_neve","m")." , ".getQ("hazastars","m")." , ".getQ("halal_datum","m")." , ".getQ("presbiter","m")." , ".getQ("kepviselo","m").", year(now())-year(".getQ("szul_datum","m",0).")  kor, ".getQ("cimke","m"). " , " . getQ("megjegyzes","m")." , ".getQ("adoszam","m");

$limit = "LIMIT $offset, $rowcount";

if(strpos($_REQUEST['envtpl'] ?? '',"list") !== false){
        $limit = "";
}
include('query.php');

$result = mysqli_query($dbh, $statement) or die("Hiba kérés közben  $statement ");

$rowcount = 0;

$tmpfname = tempnam($tmpdir, "boritek-");
if(strpos($_REQUEST['envtpl'] ?? '',"list") !== false){
        $tmpfname = tempnam($tmpdir, "list-");
}
$tmpfname = preg_replace('/\.tmp$/','',$tmpfname);
$texhandle = fopen($tmpfname.".tex", "w");
 
$felado = "Budavári Evangélikus Egyházközség\nBudapest\nTáncsics M. u. 28.\n1014\n";
if(isset($_REQUEST['felado']) && $_REQUEST['felado'] != ""){
    $felado = $_REQUEST['felado'];
}
if(!preg_match('/\n$/',$felado)){
    $felado .= "\n";
}
$felado = preg_replace('/\n/','\\\\\\\\',$felado);
$temphandle = fopen($tmpfname.".msg", "w");
fwrite($temphandle, $felado);

fclose($temphandle);
// do here something

//unlink($tmpfname);
$handle = fopen("./boritek/" . $_REQUEST['envtpl'] ?? '',"r");
$postprocess = 0;
while (!feof($handle)) {
       $buffer = fgets($handle, 4096);
       if(preg_match("/postprocess/",$buffer)){
        $postprocess = 1;
        break;
        }

       fwrite($texhandle,"$buffer");
		
	   if(preg_match("/startlabels/",$buffer)){
		while ($line = mysqli_fetch_assoc($result)) {
			fwrite($texhandle,"\\mlabel{%\n");
            fwrite($texhandle,$felado."}{%\n");
			fwrite($texhandle,"\Large{" . $line['nev'] . "}\\\\\underline{" . $line['varos'] . "}\\\\" . $line['cim'] . "\\\\" . $line['ir_szam'] . "}\n");
			}
		break;
		}	

   	
   	
	}
	if($postprocess == 0){
    fwrite($texhandle,"\\end{document}\n");
    }
	fclose($handle);

if($postprocess == 1){
    $fa = array();
    $fa = explode("\n",$_REQUEST['felado']);
    #$texhandle-be átirányít
    ob_start();
    include("./boritek/" . $_REQUEST['envtpl']);
    $s_data = ob_get_contents();
    ob_end_clean(); 
    #vissza
    $s_data = preg_replace("/\%postprocess\n/","",$s_data);
    fwrite($texhandle,$s_data);
}

fclose($texhandle);

$mydir = getcwd();

if(preg_match("/\.tex$/",$_REQUEST['envtpl'])){
    putenv("TEXMFVAR=/var/tmp/.texmf-var");
    exec('cp boritek/*.eps '.$tmpdir);
    exec('copy boritek\\*.eps '.$tmpdir);

    chdir($tmpdir);
    $command = "cat ".$tmpfname.".tex";
    $tmpfname_h = str_replace("/|\\","_",$tmpfname);
    header("Content-type: text");
    header("Content-disposition: attachment; filename=$tmpfname_h.tex;");
    if(strpos($_REQUEST['envtpl'] ?? '',"list") !== false){
    //$command = 'dvips -t landscape -o - '.$tmpfname.'';
    }

    passthru($command);
}

if(preg_match("/\.html$/",$_REQUEST['envtpl'])){
    header("Content-type: text/html");
    
    print($s_data);


}



/* Eredm?yhalmaz felszabad??a */
mysqli_free_result($result);

// Kapcsolat lez??a
mysqli_close($dbh);
/*unlink($tmpfname.".tex");
unlink($tmpfname.".dvi");
unlink($tmpfname.".aux");
unlink($tmpfname.".log");
*/
chdir($mydir);
?>
