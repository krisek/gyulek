<script>
//member form common functions



//some ajax stuff
var http_request = false;

function makePOSTRequest(url, parameters) {
  http_request = false;
  if (window.XMLHttpRequest) { // Mozilla, Safari,...
     http_request = new XMLHttpRequest();
  } else if (window.ActiveXObject) { // IE
     try {
        http_request = new ActiveXObject("Msxml2.XMLHTTP");
     } catch (e) {
        try {
           http_request = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {}
     }
  }
  if (!http_request) {
     alert('Cannot create XMLHTTP instance');
     return false;
  }
  
  //http_request.onreadystatechange = member_saved;
  http_request.onload = member_saved;
  
  http_request.open('POST', url, true);
  http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  http_request.setRequestHeader("Content-length", parameters.length);
  http_request.setRequestHeader("Connection", "close");
  http_request.send(parameters);
}

function member_saved() {
  //console.log("http request state: " + http_request.readyState);
  if (http_request.readyState == 4) {
     
    result = http_request.responseText;
    result = result.replace(/^[\n\s\t]+/,"").replace(/[\n\s\t]+$/,"");
    
    var update = new Array();
    if(result.indexOf('|' != -1)) {
        update = result.split('|');
        //console.log("-" + encodeURI(update[0]) + "-" + update[1] );
        //alert("-" + encodeURI(update[0]) + "-" + update[1] );
        id = update[0];
        
        document.getElementById('session_message').innerHTML = "<b>" + update[1] + " (" + update[0] + ")</b>";
        

        if(update[1] == "sikeres mentés" || update[1] == "sikeres hozzáadás" ){
            //alert(id);
            //update header row TODO
            if(update[1] == "sikeres hozzáadás"){
                id = '';
                }

            document.getElementById("save"+id).innerHTML = "ment";
            
            myform = document.getElementById('memberedit' + id);
            var header_text = new Array('nev', 'telefon','telefon_mobil','ir_szam', 'varos','cim');
            var header_select = new Array();

            for(var i in header_text){
                if(document.getElementById(header_text[i]+'_header_' + id) != undefined && myform.elements[header_text[i]] != undefined){
                    document.getElementById(header_text[i]+'_header_' + id).innerHTML = myform.elements[header_text[i]].value;
                }
            }
             for(var i in header_select){
                if(document.getElementById('select_' + header_select[i]+'_' + id) != undefined && myform.elements[header_select[i]] != undefined){
                    selectedOption = document.getElementById('select_'+header_select[i]+'_' + id).options[document.getElementById('select_'+header_select[i]+'_' + id).selectedIndex];
                    i_text = selectedOption.text;
                    if(References[header_select[i] + '_short'] != undefined && References[header_select[i] + '_short'][selectedOption.value] != undefined){
                            i_text = References[header_select[i] + '_short'][selectedOption.value];
                        }

                    document.getElementById(header_select[i]+'_header_' + id).innerHTML = i_text;
                }
            }
        sndReq(id); /* update payments*/
        myelem = document.getElementById('input_amount_'+id);
        myelem.value = "";
        }
    }
  }
    //console.log("member save exits");
}

function checkMemberForm(id){
	var myform;
    
    if(id == undefined){
        myform = document.getElementById('memberedit');
    }
    else{
        myform = document.getElementById('memberedit' + id);
		}
	if(myform.nev.value == "" || myform.nev.value.match(/^\s+$/)){
		alert("Nincs név megadva.");
		return false;
		}
	
		
	return true;
	}

   
function submitMemberEditForm(id) {
    var myform;
    if(checkMemberForm(id) == false){
		return false;
		}
	else{
		if(id == undefined){
			myform = document.getElementById('memberedit');
			myform.submit();
		}
		else{
			myform = document.getElementById('memberedit' + id);
		
			var poststr = "";

			for(i=0; i<myform.length; i++){
				if(myform.elements[i].multiple == true && typeof(myform.elements[i].options) != "undef"){
					//console.log("multiple item found: " + myform.elements[i].name);
					for(var n = 0; n < myform.elements[i].options.length; n++){
						//console.log("option: " + n);
						if(myform.elements[i].options[n].selected == true){
							//console.log("selected option: " + myform.elements[i].options[n].value);
							poststr +=  encodeURI(myform.elements[i].name) + "=" + encodeURI(myform.elements[i].options[n].value) + "&";
							}
						}
				}
				else{
					poststr +=  encodeURI(myform.elements[i].name) + "=" + encodeURI(myform.elements[i].value) + "&";
				}
			}

			poststr += "ajax=yes";
			document.getElementById("save"+id).innerHTML = "mentés";
			makePOSTRequest('member_save.php', poststr);
		}
	}
}






/*

function submitMemberEditForm(id){
    var myform;
    if(id == undefined){
        myform = document.getElementById('memberedit');
    }
    else{
        myform = document.getElementById('memberedit' + id);
    }


    myform.submit();
}
*/
function showMemberTags(action,id,idto){
        for(var i = 0; i < tag_names.length; i++){
                //alert(i + " " + tag_names[i]);
                var mytd = document.getElementById(id);
                var put = new String();
                for(var n = 0; n < tags[tag_names[i]].length; n++){
                    var type="normal";
                    if(tags_quant[tag_names[i]][n]/tags_treshold[tag_names[i]] > 2){
                        type="large";
                    
                    }
                    else if(tags_quant[tag_names[i]][n]/tags_treshold[tag_names[i]] > 1){
                        type="medium";

                    }
                    //alert(tags[tag_names[i]][n] + " " + fontsize);
                    var tag = tags[tag_names[i]][n];
                    if(action == 'addPattern'){
                        put += '<span class="'+type+'_member"><a href="javascript:addPattern(\'c\',\''+tag+'\')">'+tag+'</a></span> '; 
                        if(n%8 == 7){
                                put += '<br>';
                        }
                    }
                    else{
                        put += '<span class="'+type+'_member"><a href="javascript:addTag(\''+idto+'\',\''+tag+'\')">'+tag+'</a></span> '; 
                        if(n%3 == 2){
                                put += '<br>';
                        }
                    }
                }
                
                mytd.innerHTML = put;
                
        }        
}

//load tag related data
var tag_names = new Array();
var tags = new Array();
var tags_quant = new Array();
var tags_treshold = new Array();
var tags_used = new Array();

<?
    //print($tagname . " " . $_POST[$tagname]. "<br>\n");    
    $raw_tags = array();
    $raw_tags = $freetags['cimke']->get_used_raw_tags();
 ?>
tag_names.push('cimke');
tags['cimke'] = new Array("<?=join('","',array_keys($raw_tags))?>");
tags_quant['cimke'] = new Array("<?=join('","',array_values($raw_tags))?>");
<?if(count(array_values($raw_tags)) > 0){?>
tags_treshold['cimke'] = <?=max(array_values($raw_tags))/3?>;
<?}?>

function addTag(id,tag){
    var tagarea = document.getElementById(id);
    if(tagarea.value.length != 0){
            tagarea.value = tagarea.value + ' ';
    }   
    tagarea.value = tagarea.value + tag;
    
}

function saveCheck(id){
    var myform;
    var save;
    
    if(id == undefined){
        myform = document.getElementById('memberedit');
        save = document.getElementById('save');
    }
    else{
        myform = document.getElementById('memberedit' + id);
        save = document.getElementById('save' + id);
    }
    
    var selected_nr = 0;
    for(var i=0; i<myform.catalogs.length; i++){
        if(myform.catalogs.options[i].selected == true){
            selected_nr++;
        }
    }
    
    if(selected_nr == 0){
        save.innerHTML='';
    }
    else{
        save.innerHTML='<?if(preg_match('/^s\s*:\s*torolt/',$_REQUEST['pattern'] ?? '')):?><?=$lang['undelete']?><?else:?><?=$lang['save']?><?endif;?>';
    }

}


</script>

