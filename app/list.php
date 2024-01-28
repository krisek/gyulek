<?
include("config_base.php");
include("lang.php");
include("config.php");
include("include.php");
include("member_form.js.php");

$length = 20;

include("list_init.php");
?>
<span id="session_message">
<? if($_SESSION['message'] ?? '' != '') { ?>
<p><b><?=$_SESSION['message']?></b></p>
<? 
    $_SESSION['message'] = '';
} ?>
</span>
<script>
function fillForm(c_id, c_name, c_email, c_phone){
    myForm = document.contactform;
    myForm.c_id.value = c_id;
    myForm.c_name.value = c_name;
    myForm.c_email.value = c_email;
    myForm.c_phone.value = c_phone;
}

function selectValue(field){
    myForm = document.contactform;
    if(field == 'c_name' && myForm.c_name.value == 'név'){
        myForm.c_name.select();
    }
    if(field == 'c_email' && myForm.c_email.value == 'email'){
        myForm.c_email.select();   
    }

}


function setFocus(){
 document.searchform.pattern.focus();
}
var tagpattern='<?=$tag_pattern?>';
var tagpattern_saved = '';
var tagtoggled = 0;
function showTagPattern(){
    tagPatternSpan=document.getElementById("tag_pattern_span");
    tagPatternLink=document.getElementById("tag_pattern_link");
    if(tagtoggled == 0){
        if(tagpattern == '' && tagpattern_saved != ''){
            tagpattern = tagpattern_saved;
        }
        tagPatternSpan.innerHTML='<input id="tag_pattern" type="text" name="tag_pattern" value="'+tagpattern+'" class="lightborder">';
        tagPatternLink.innerHTML='<?=$lang['filter_text_toggled']?>';
//          document.searchform.pattern.blur();
//          document.searchform.tag_pattern.focus();
        tagtoggled=1;
        
    }
    else{
        tagpattern_saved = document.searchform.tag_pattern.value;
        tagPatternSpan.innerHTML='';
        tagpattern='';
        tagPatternLink.innerHTML='<?=$lang['filter_text']?>';
        if(document.searchform.tag_pattern != null){
            document.searchform.tag_pattern.value='';
        }
        document.searchform.pattern.focus();
        tagtoggled=0;  
    }   
}


</script>
<body onLoad="setFocus()<? if ($_SESSION['id'] ?? '' != ''): ?>;addForm(<?=$_SESSION['id']?>);<?endif;?><?if($tag_pattern != ''):?>;showTagPattern();<?endif;?>">
<p>
<form accept-charset="utf-8" id="searchform" name="searchform" method="get" action="list.php">
<table>
<tr>
<td>
<input type="hidden" name="clearsession" value="true">
<input id="pattern" type="text" name="pattern" value="<? if (!preg_match('/^spec-/',$pattern)): ?><?=$pattern?><?endif;?>" class="lightborder">
<?if($pattern != ''):?>
<span id="tag_pattern_span">

</span>
<?endif;?>
<input type="submit" value="Keres">
</form>
<script>
function addPattern(prefix,tag,clear){
    var tagarea = document.getElementById('pattern');
    if(tagtoggled == 1 && prefix == 'c'){
        tagarea = document.getElementById('tag_pattern');
        prefix = 'cn';
    }
    if(clear == 1){
        tagarea.value='';
    }   
    
    tagarea.value=tagarea.value.replace(/^\w+:/,prefix+':');
	if(tagarea.value.length != 0){
			tagarea.value = tagarea.value + ' ';
	}
	else{
			tagarea.value = prefix + ':';
	}
	tagarea.value = tagarea.value + tag;
	
    //some search pattern related hacks
    if(tag == 'csaladfok' || tag == 'presbiterek' || tag == 'kepviselok' ){
        var mycheckbox = document.getElementById('checkbox_order');
        mycheckbox.checked = true;
    }
    
}
function emptyPattern(){
	document.getElementById('pattern').value='';
}

function calupdate(cal){
    var spel = document.getElementById("from");
    spel.innerHTML = "tól";
    var spel = document.getElementById("until");
    spel.innerHTML = "ig";

}



</script>



<td id="cimkek">
Címkék: 
<script>

showMemberTags('addPattern','cimkek');
</script>

</table>
<p>
<input type="checkbox" id="checkbox_order" name="order" value="address" <?if($order == 'address'):?>CHECKED<?endif;?>  >Lista lakcím szerinti sorrendben
|
<a href="?clearsession=true">Új keresés</a>
<!-- <a href="javascript:emptyPattern();">Mégse</a> //-->
<?if($pattern != ''):?>
| <a href="javascript:showTagPattern();" id="tag_pattern_link"><?=$lang['filter_text']?></a>
<?endif;?>
<!--
<form name="szulikeres" action="list.php">
<input type="hidden" name="clearsession" value="true">
<input type="hidden" name="pattern" value="spec-szulikeres">
<input type="text" name="bday" value="<? if (isset($bday)): ?><?=$bday?><?else:?>0<?endif;?>" size="1" class="lightborder"> <a href="javascript:document.szulikeres.submit()">napon belül szülinaposok</a>
</form>
-->
</p>
<p>
<a href="javascript:addPattern('s','presbiterek',1);">Presbiterek</a> | 
<a href="javascript:addPattern('s','kepviselok',1);">Képviselők</a> | 
<a href="javascript:addPattern('s','csaladfok',1);">Családfők</a> | 
<a href="javascript:addPattern('f','<?=strftime("%Y",time()-365*24*3600)?>',1);">Fenntartók</a> |
<a href="javascript:addPattern('sz','<?=strftime("%m.%d")?>-<?=strftime("%m.%d",time()+168*3600)?>',1);">Szülinaposok</a> |
<a href="javascript:addPattern('u','',1);">Utca szerint</a> |
<a href="javascript:addPattern('k','',1);">Konfirmáció éve szerint</a>
</p>
<p><a href="member_new.php">új tag</a> 

</p>
<hr>

<?




// SQL kérés végrehajtása
$selectfields = "SQL_CALC_FOUND_ROWS m.id id, " . getQ("nev","m") ." , ".getQ("leany_neve","m")." , ".getQ("anyja_neve","m")." , ".getQ("foglalkozas","m")." , ".getQ("ir_szam","m")." , ".getQ("varos","m")." , ".getQ("cim","m")." , ".getQ("telefon_mobil","m") .",". getQ("telefon","m")." , ".getQ("email","m")." , ".getQ("religion_id","m")." , ".getQ("member_id","m")." , ".getQ("csal_all_id","m")." , ".getQ("szarm_hely","m")." , ".getQ("szul_hely","m")." , ".getQ("szul_datum","m")." , ".getQ("ker_hely","m")." , ".getQ("ker_datum","m")." , ".getQ("ker_ige","m") ." ,date_format(".getQ("konf_ev","m",0).",'%Y') konf_ev , ". getQ("konf_ige","m") ." , ".getQ("polg_esk_h","m")." , ". getQ("polg_esk_datum","m")." , ".getQ("egyh_esk_h","m")." , ".getQ("egyh_esk_datum","m")." , ".getQ("egyh_esk_ige","m")." , ".getQ("hazastars_neve","m")." , ".getQ("hazastars","m")." , ".getQ("halal_datum","m")." , ".getQ("presbiter","m")." , ".getQ("kepviselo","m").", year(now())-year(".getQ("szul_datum","m",0).")  kor, ".getQ("cimke","m"). " , " . getQ("megjegyzes","m")." , ".getQ("adoszam","m");
$rowcount = $length + 1;
$limit = "LIMIT $offset, $rowcount";
include('query.php');

$result = mysqli_query($dbh, $statement) or die("Hiba a kérésben $statement ");
$result_numrows = mysqli_query($dbh, "SELECT FOUND_ROWS()");
$total_a = mysqli_fetch_row($result_numrows);
$total= $total_a[0];
?>

<script>

function changeRule(theNumber, display) {
	var theRules = new Array();
	if (document.styleSheets[0].cssRules) {
		theRules = document.styleSheets[0].cssRules;
	} else if (document.styleSheets[0].rules) {
		theRules = document.styleSheets[0].rules;
	}
	//theRules[theNumber].style.backgroundColor = '#FF0000';
        //theRules[theNumber].style.visibility = 'visible';
        theRules[theNumber].style.display = display;
}


function createRequestObject() {
    var ro;
    var browser = navigator.appName;
    if(browser == "Microsoft Internet Explorer"){
        ro = new ActiveXObject("Microsoft.XMLHTTP");
    }else{
        ro = new XMLHttpRequest();
    }
    return ro;
}

var http = createRequestObject();

function sndReq(id) {
    http.open('get', 'member_data.php?id='+id);
    http.onreadystatechange = handleResponse;
    http.send(null);
}

function handleResponse() {
   //alert('got glint 2 ' + http.readyState + '  ' + http.responseText);

    if(http.readyState == 4){
        var response = http.responseText;
        var update = new Array();

        if(response.indexOf('|' != -1)) {
            update = response.split('|');
            //alert(update[0] + '  ' + update[1] + '  ' + update[2]);
            id=update[0];
            catalogs=update[2];
            money=update[1];
			tags=update[3];
            document.getElementById('cimke_'+id).value = tags;
            
            document.getElementById('money_'+id).innerHTML =  money;
            myform = document.getElementById('memberedit'+id);
            
            mycatalog = myform.catalogs;
            mycatalog.options.length=0;
            catalogs_array = catalogs.split(';'); 
            for(var i=0; i<catalogs_array.length; i++){
                catalog = catalogs_array[i].split(',');
                if(catalog[0]==''){
                    continue;
                }
                if(catalog[0]=='budavár' && catalog[1] == 'selected'){
                    
                    changeRule(0,'');
                    //handle gyulek_member
                }
                var opt=document.createElement('option');
                opt.text=catalog[0];
                opt.value=catalog[0];
                
                if(catalog[1] == 'selected'){
                    opt.selected = true;
                }

                mycatalog.add(opt,null);
                
            }
            
            saveCheck(id);
            
 
        }
    }
}


var atta = new Array();

var opened = 0;
function addForm(id){
    if(opened !=   0 && opened != id){
        var myodiv = document.getElementById('innermember' + opened);
    	var myodivlink = document.getElementById('innerlink' + opened);
        var myoform = document.getElementById('memberedit' + opened);
        var myomdiv = document.getElementById('memberdiv' + opened);
        var myorow = document.getElementById('member2row' + opened);
            
        myodivlink.innerHTML = '<?=$lang['details_show']?>';
    	myodiv.style.display = "none";
    	myorow.style.display = "none";
        changeRule(0,'none');
    	atta[id] = 0;
    	myomdiv.style['border'] = "";
        
        //restore save link on member form        
        saveCheck(id);
    }
	var mydiv = document.getElementById('innermember' + id);
	var mydivlink = document.getElementById('innerlink' + id);
    var myform = document.getElementById('memberedit' + id);
    var mymdiv = document.getElementById('memberdiv' + id);
	var myrow = document.getElementById('member2row' + id);
	
	if(atta[id] == 0 || atta[id] == null ){
        mydivlink.innerHTML = '<?=$lang['details_hide']?>';
		mydiv.style.display = "";
		myrow.style.display = "";
		myform.amount.focus();
		atta[id] = 1;
		mymdiv.style['border'] = "1px solid green";
        myrow.style['width'] = "70%";
		opened = id;
		sndReq(id);
	}
	else{
        mydivlink.innerHTML = '<?=$lang['details_show']?>';
		mydiv.style.display = "none";
		myrow.style.display = "none";
                changeRule(0,'none');
		atta[id] = 0;
		mymdiv.style['border'] = "";
        
        //restore save link on member form
        saveCheck(id);
              
		opened = 0;
	}

}



</script>



<p>Szűrőfeltétel: <b><?=$filtertext?></b> (<?=$total?>) <?if($total>0):?><a href="list_print.php?pattern=<?=$pattern?>&bday=<?=$bday?>&id=<?=$id ?? ''?><?if($order != ''):?>&order=<?=$order?><?endif;?><?if($tag_pattern != ''):?>&tag_pattern=<?=$tag_pattern?><?endif;?>">Boríték nekik</a> &nbsp; <a href="list_print.php?pattern=<?=$pattern?>&bday=<?=$bday?>&id=<?=$id ?? ''?>&add=woemail<?if($order != ''):?>&order=<?=$order?><?endif;?><?if($tag_pattern != ''):?>&tag_pattern=<?=$tag_pattern?><?endif;?>">Boríték az e-mail nélkülieknek</a><?endif;?></p>
<table border="0" width="100%">
<tr><td>#</td><td>Név</td><td>Telefon</td><td>Mobil</td><td>Cím</td></tr>
<script>

// function submitForm(id){
//     var myform = document.getElementById('memberedit' + id);
//     myform.submit();
// }
function deleteForm(id){
    var answer = confirm("Biztos törlöd?")
    if (answer) window.location = 'member_leave.php?id='+id;

}
function deletePayment(id,member_id){
    var answer = confirm("Biztos törlöd?")
    if (answer) window.location = 'member_delete_payment.php?id='+id+'&member_id='+member_id;
    
}


</script>
<?
$rowcount = 0;

while ($line = mysqli_fetch_assoc($result)) {
    $email = "";
    $email = $line['nev'] . " &lt;" . $line['email'] . "&gt;";

?>

<?include('member_form.php')?>

<?
$rowcount++;
if($rowcount == $length){
    break;
}

}
?>
</table>
<br/>
<?
if($offset > 0){
?>
        <a href="list.php?offset=<?=$offset-$length?>&pattern=<?=$pattern?><?if($order != ''):?>&order=<?=$order?><?endif;?><?if($tag_pattern != ''):?>&tag_pattern=<?=$tag_pattern?><?endif;?>">Előző oldal</a>
<?
}

$pages=floor($total/$length);


for($i=0; $i<=$pages; $i++){
    if($i*$length != $offset){
    ?>
    <a href="list.php?offset=<?=$i*$length?>&pattern=<?=$pattern?><?if($order != ''):?>&order=<?=$order?><?endif;?><?if($tag_pattern != ''):?>&tag_pattern=<?=$tag_pattern?><?endif;?>"><?=$i+1?></a>
    <?
    }
    else{
    ?>
    <b><?=$i+1?></b>
    <?
    }
}

if($rowcount == $length){
?>
   <a href="list.php?offset=<?=$offset+$length?>&pattern=<?=$pattern?><?if($order != ''):?>&order=<?=$order?><?endif;?><?if($tag_pattern != ''):?>&tag_pattern=<?=$tag_pattern?><?endif;?>">Következő oldal</a>
<?
}
/* Eredményhalmaz felszabadítása */
mysqli_free_result($result);

// Kapcsolat lezárása
mysqli_close($dbh);
?>
<script>
if (document.getElementsByTagName) {
var inputElements = document.getElementsByTagName("input");
for (i=0; inputElements[i]; i++) {
if (inputElements[i].className && (inputElements[i].className.indexOf("disableAutoComplete") != -1)) {
inputElements[i].setAttribute("autocomplete","off");
}//if current input element has the disableAutoComplete class set.
}//loop thru input elements
}//basic DOM-happiness-check
</script>
<?include('footer.php');?>
</body>
</html>
