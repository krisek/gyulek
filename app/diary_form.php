<script>

tags_used['<?=$line['id']?>'] = new Object();
<?foreach(array_keys($tag_resolve) as $i):?>
    <?$line['a_'.$i] = array();?>
    tags_used['<?=$line['id']?>']['<?=$i?>'] = new Object();
    <?if(isset($tags[$i])){?>
        <?foreach($tags[$i] as $tag){?>
            tags_used['<?=$line['id']?>']['<?=$i?>']['<?=htmlspecialchars($tag['raw_tag'])?>'] = 1;
            <?array_push($line['a_'.$i],htmlspecialchars($tag['raw_tag']))?>
        <?}?>
    <?}?>
<?endforeach;?>
<?
//something nasty
$line[hely] = join(',', $line['a_hely']);
$line[tipus] = join(',', $line['a_tipus']);
$line[lelkesz] = join(',', $line['a_lelkesz']);
$line[szolgalat] = join(',', $line['a_szolgalat']);
?>

</script>

<form accept-charset="utf-8" action="diary_save.php" method="post" id="diary_form_<?=$line['id']?>" onsubmit="submitForm('<?=$line['id']?>')">
<tr id="edit0<?=$line['id']?>"  style="<?=$editstyle?>">
<td class="show13">Időpont</td>
<td class="show13">Ige/téma</td>
<td class="show13">Résztvevők</td>
<td class="show13">Úrvacsora</td>
<td class="show13">Megjegyzés</td>
<td class="show13">&nbsp;</td>
</tr>
<tr id="edit<?=$line['id']?>"  style="<?=$editstyle?>" >

<input type="HIDDEN" name="id" value="<?=$line['id']?>" >

<?foreach(array_keys($tag_resolve) as $i):?>
<input type="HIDDEN" id="added_<?=$line['id']?><?=$i?>" name="<?=$i?>" value="" >    
<?endforeach;?>


<td class="edit1"><input type="TEXT" id="diary_form_<?=$line['id']?>_tm" name="tm" value="<?if(isset($line['tm'])):?><?=$line['tm']?><?else:?><?=strftime("%Y-%m-%d %H:%M")?><?endif;?>" class="lightborder2"></td>



<td class="edit1"><input type="TEXT" name="ige" value="<?=$line['ige']?>" class="lightborder2" width="15"></td>
<td class="edit1"><input type="TEXT" name="resztvevok" value="<?=$line['resztvevok']?>" size="5" class="lightborder2" width="4"></td>
<td class="edit1"><input type="TEXT" name="urvacsora" value="<?=$line['urvacsora']?>" size="5" class="lightborder2" width="4"></td>
<td rowspan="2" class="edit13"><textarea name="megjegyzes" class="lightborder2"><?=$line['megjegyzes']?></textarea><br><br><br><?if($line['id']=='new'):?><center><span class="large">Új bejegyzés</span></center><?endif;?></td>
<td class="edit1"><a href="javascript:submitForm('<?=$line['id']?>');" id="save<?=$line['id']?>">ment</a><br/>
<a href="javascript:closeForm('<?=$line['id']?>')">mégsem</a><br/></td>
</tr>
<tr id="edit2<?=$line['id']?>" style="<?=$editstyle?>" >
<td colspan="4" class="edit1">


<?foreach(array_keys($tag_resolve) as $i):?>
    <p><b><?=$tag_resolve[$i]?></b>: <span  id="edit_added_<?=$line['id']?><?=$i?>">&nbsp;</span><br/><span class="edit2" id="edit_avail_<?=$line['id']?><?=$i?>"></span></p>
    <p>
    <span class="edit3" nowrap>
        <input id="newtag_<?=$line['id']?><?=$i?>" name="" value="" class="lightborder" onkeydown="javascript:checkEnter(event,'<?=$line['id']?>','<?=$i?>')"><a href="javascript:addTag('edit_added_','<?=$line['id']?>','<?=$i?>',document.getElementById('newtag_<?=$line['id']?><?=$i?>').value,1);" style="text-decoration: none;" title="hozzáad" >^</a>
    </span></p>
  
    
<?endforeach;?>
    
</td>
    </tr>
<tr id="edit3<?=$line['id']?>" style="<?=$editstyle?>" >
   <td class="edit3">&nbsp;</td>


<?if($new):?>
<script>
var id = 'new';
tag_names_misc = [];
tag_names_misc_state = [];


for(var i = 0; i < tag_names.length; i++){
                //alert(i + " " + tag_names[i]);
                var mytd = document.getElementById("edit_avail_"+id+tag_names[i]);
                var put = new String();
                var type = new String();
                var normal = new Array;
                var large = new Array;
                var medium = new Array;
                var sizep_normal = new Array;
                var sizep_large = new Array;
                var sizep_medium = new Array;
                for(var n = 0; n < tags[tag_names[i]].length; n++){
                    
                    if(tags_quant[tag_names[i]][n]/tags_treshold[tag_names[i]] > 2){
                        type="large";
                        large.push(tags[tag_names[i]][n]);
                        sizep_large.push("large");
                    }
                    else if(tags_quant[tag_names[i]][n]/tags_treshold[tag_names[i]] > 1){
                        type="medium";
                        medium.push(tags[tag_names[i]][n]);
                        sizep_medium.push("medium");
                    }
                    else{
                        type="normal";
                        normal.push(tags[tag_names[i]][n]);
                        sizep_normal.push("normal");
                        }
                }
                var sorted = large.concat(medium);
                var sizep_sorted = sizep_large.concat(sizep_medium);
                for(var item in sorted){
                    put += '<span class="'+sizep_sorted[item]+'"><a href="javascript:addTag(\'edit_added_\',\''+id+'\',\''+tag_names[i]+'\',\''+sorted[item]+'\',1)">'+sorted[item]+'</a></span> ';
                    }
                put += '<span id="misc_'+id+'_'+tag_names[i]+'""><a href="javascript:toggle_tag_names_misc(\''+id+'\',\''+tag_names[i]+'\')">>></a>';
                tag_names_misc[tag_names[i]] = '';
                tag_names_misc_state[tag_names[i]] = 0;
                for(var item in normal){
                    tag_names_misc[tag_names[i]] += '<span class="normal"><a href="javascript:addTag(\'edit_added_\',\''+id+'\',\''+tag_names[i]+'\',\''+normal[item]+'\',1)">'+normal[item]+'</a></span> ';
                    }
                put += '</span>';
                
                
                mytd.innerHTML = put;
                
                
                
        }
        myinput = document.getElementById("diary_form_new_tm");
        myinput.select();
        myinput.focus();
</script>
<?endif;?>

<td class="edit3">&nbsp;</td>
    <td class="edit3">&nbsp;</td>
    <td class="edit3">&nbsp;</td>
    <td class="edit3">&nbsp;</td>
    <td class="edit3">&nbsp;</td>
   
</tr>    
</form>


<tr id="show<?=$line['id']?>" style="<?=$showstyle?>">
    
    <td class="show12<?=$flipflop?>"><span><?=preg_replace('/00:00:00$|:00$/','',$line['tm'])?></span></td>
   <td class="show12<?=$flipflop?>"><span><?=$line['tipus']?> <?if($line['lelkesz'] != ''):?>(<?endif;?><?=$line['lelkesz']?><?if($line['lelkesz'] != ''):?>)<?endif;?> <?if($line['szolgalat'] != ''):?>(<?endif;?><?=$line['szolgalat']?><?if($line['szolgalat'] != ''):?>)<?endif;?> <?=$line['hely']?></span></td>
    <td class="show12<?=$flipflop?>"><?if($line['ige'] != ''):?><span class="descr">Ige/téma</span>:<?=$line['ige']?><?endif;?></td>
    <td class="show12<?=$flipflop?>"><?if($line['resztvevok'] != ''):?><span class="descr"></span><?=$line['resztvevok']?><?if($line['urvacsora'] != 0):?>/<?=$line['urvacsora']?><?endif;?><?endif;?></td>
    <td class="show12<?=$flipflop?>" ><?if($line['megjegyzes'] != ''):?><span class="descr"></span><?=$line['megjegyzes']?><?endif;?></td>
    <td class="show12<?=$flipflop?>" >
    <?if(!preg_match('/^spec/',$pattern)):?>
    <a href="javascript:addForm('<?=$line['id']?>')"><?if(!$new):?>szerkeszt<?else:?>új<?endif;?></a>&nbsp;
    <a href="javascript:deleteItem('<?=$line['id']?>')">x</a><br/>
    <?endif;?>

    </td>

</tr>
<tr id="show2<?=$line['id']?>" style="<?=$showstyle?>">

</tr>



