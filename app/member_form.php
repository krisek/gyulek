<form accept-charset="utf-8" action="member_save.php" method="post" id="memberedit<?=$line['id']?>" name="memberedit<?=$line['id']?>">
<tr nowrap>
<td>
<input type="hidden" name="id" value="<?=$line['id']?>">
<?
$undelete = 0;
if(preg_match('/^s\s*:\s*torolt/',$pattern ?? '')){
?>
<input type="hidden" name="undelete" value="<?=$line['id']?>">
<?
        $undelete=1;
}
?>
<?=$line['id']?>
</td>
<td id="nev_header_<?=$line['id']?>"><?=$line['nev'] ?? null?></td>
<!--
<td>
<? if ($line['szul_datum']  ?? null != '0000-00-00'): ?> <?=$line['szul_datum']  ?? null ?> (<?=$line['kor']  ?? null ?>)<?endif;?>
</td>
//-->

<td id="telefon_header_<?=$line['id']?>">
<?=FormatMSISDN($line['telefon'] ?? '')?>
</td>
<td id="telefon_mobil_header_<?=$line['id']?>">
<?=FormatMSISDN($line['telefon_mobil'] ?? '')?>
</td>

<td nowrap><span id="ir_szam_header_<?=$line['id']?>"><?=$line['ir_szam'] ?? ''?></span><?if($line['ir_szam'] ?? '' != ''):?>.<?endif;?> <span id="varos_header_<?=$line['id']?>"><?=$line['varos'] ?? '' ?></span><?if($line['cim'] ?? '' != ''):?>,<?endif;?> <span id="cim_header_<?=$line['id']?>"><?=$line['cim'] ?? '' ?></span>
</td>

<?if($line['id'] != ''):?>
<td nowrap><a href="javascript:addForm('<?=$line['id']?>')"><var style="font-style: normal;" id="innerlink<?=$line['id']?>"><?=$lang['details_show']?></var></a></td>
<td nowrap><a href="?clearsession=true&pattern=spec-csalad-<?=$line['member_id'] ?? ''?>">családja</a></td>
<td nowrap><a href="javascript:deleteForm('<?=$line['id']?>')">x</a> <a href="list_print.php?pattern=spec-id-<?=$line['id']?>">b</a></td>
 <td><? if ($line['email'] ?? '' != ''): ?><a href="mailto:<?=$email?>" onMouseOver="window.status='<?=$line['nev']?>';return true;" onMouseOut="window.status='';" >@</a><?endif;?></td>
<?endif;?>
</tr>
<tr id="member2row<?=$line['id']?>" style="display: none;">
<td colspan="9" id="memberdiv<?=$line['id']?>">
<div id="innermember<?=$line['id']?>" style="display: none;">
<table border="0">
<?$row = 0;?>
<tr>
<?$i = 0;?>
<?//go trough fields?>

<?for($field_c = 0; $field_c < sizeof($field_order); $field_c++):?>
<?$field_name = $field_order[$field_c];?>

<td nowrap <?if($field_name != 'cimke' && $field_name != 'megjegyzes' ):?>class="theStyle"<?endif;?>>
<?if( $fields[$field_name]['descr'] ?? '' != '' ):?>
<?=$fields[$field_name]['descr']?>: <br>
<?DisplayInputField($field_name,$line[$field_name] ?? '',$fields[$field_name]['type'],$fields[$field_name]['show'] ?? '',$fields[$field_name]['length'] ?? '',$line['id']);?>
<?else:?>

<?=$fields['group_descr'][$field_name]?>:<br> 

<?for($group_el_c = 0; $group_el_c < sizeof($fields['group'][$field_name]); $group_el_c++):?>
<?$sub_field_name= $fields['group'][$field_name][$group_el_c];?>
<?DisplayInputField($sub_field_name,$line[$sub_field_name] ?? '',$fields[$sub_field_name]['type'],$fields[$sub_field_name]['show'] ?? '',$fields[$sub_field_name]['length'] ?? '',$line['id']);?>
<?if($group_el_c < sizeof($fields['group'][$field_name])-1):?>/<?endif;?>
<?endfor;?> 

<?endif;?>




<?$i++;?>


<? if ($i == 3): ?>
<?$i=0;?>
<? if ($row == 0): ?>
<td rowspan="9" valign="top"  class="theStyle">
<a id='save<?=$line['id']?>' href="#" onclick="javascript:submitMemberEditForm('<?=$line['id']?>')"><?if($undelete):?><?else:?>ment<?endif;?></a><br><br>    
<div id="money_box_<?=$line['id']?>">
Befizetések:<br>
<table>
<tr><td><input type="text" name="amount_year" value="<?=strftime("%Y-%m-%d")?>" class="<?if($amount_year_show ?? 0 != 1):?>lightborder<?else:?>redborder<?endif;?>" size="8"><td><input type="text" name="amount" value="" class="lightborder" size="5" id="input_amount_<?=$line['id']?>"><td><select name="type">
        <?foreach($amount_ref as $key => $value){
            ?><option value="<?=$key?>"><?=$value?></option><?
        }?>
</select>
</table>
<div id="money_<?=$line['id']?>">
<table>
</table>
</div>
</div>
<div>
Címkék:<br>
<textarea name="cimke" class="<?if($show ?? 0 != 1):?>lightborder<?else:?>redborder<?endif;?>" id="cimke_<?=$line['id']?>"><?=$line['cimke'] ?? ''?></textarea><br/>
        <span id="cimke_<?=$line['id']?>_list">
        
        </span>
        <script>
        showMemberTags('addTag','cimke_<?=$line['id']?>_list','cimke_<?=$line['id']?>');
        </script>
</div>
</td>

<?endif;?>
<?$row++;?>
<?if($row < 12):?><tr><?endif;?>
<?endif;?>

&nbsp;&nbsp;


<?endfor;?> <?//end go trough fields?>


<td nowrap valign="top">Adatbázisok:<br>
<select name="catalogs[]" id="catalogs" multiple size="7" onChange="saveCheck(<?=$line['id']?>)"> 
</select>
</td>
<tr><td></td></tr>
</table>

</div>
</td>
</tr>
</form>

