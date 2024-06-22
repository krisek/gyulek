<?
$filtertext = '';
if($order == 'address'){
    $orderby = 'ir_szam, varos, cim, nev';
}
else{
    $orderby = 'nev';
}

$mycatalog = $_SESSION['catalog'];

$tag_query = '';
$filtertext_add = '';

if($tag_pattern != ''){
    if(preg_match('/^c:\s*(.*)/',$tag_pattern,$result)){
        $tags = array();
        //$tags = explode(' ',$result[1]);
        //$tag_query = " and (  m.cimke LIKE '%". implode("%' and m.cimke like '%",$tags) ."%') ";
        $tags = $freetags['cimke']->_parse_tags($result[1]);
        $tag_query = " and m.id IN (SELECT object_id FROM ${global_table_prefix}member_freetagged_objects WHERE tag_id IN (SELECT id FROM ${global_table_prefix}member_freetags WHERE tag LIKE '%". implode("%' and " . getQ("cimke","m",0) ." like '%",$tags) ."%')) ";    
        
        $filtertext_add = " és címkék közül mindegyik - $result[1]";
    }
    else if(preg_match('/^cv:\s*(.*)/',$tag_pattern,$result)){
        $tags = array();
        
		//$tags = explode(' ',$result[1]);
        //$tag_query = " and (m.cimke LIKE '%". implode("%' or m.cimke like '%",$tags) ."%') ";
        $tags = $freetags['cimke']->_parse_tags($result[1]);
        $tag_query = " and m.id IN (SELECT object_id FROM ${global_table_prefix}member_freetagged_objects WHERE tag_id IN (SELECT id FROM ${global_table_prefix}member_freetags WHERE tag LIKE '%". implode("%' or " . getQ("cimke","m",0) ." like '%",$tags) ."%')) ";    
		
		$filtertext_add = " és címkék közül egyik - $result[1]";
    }
    else if(preg_match('/^cn:\s*(.*)/',$tag_pattern,$result)){
        $tags = array();
        
		//$tags = explode(' ',$result[1]);
        //$tag_query = "  and (m.cimke NOT LIKE '%". implode("%' AND m.cimke NOT LIKE '%",$tags) ."%') ";
        $tags = $freetags['cimke']->_parse_tags($result[1]);
		$tag_query = " AND m.id NOT IN (SELECT object_id FROM ${global_table_prefix}member_freetagged_objects WHERE tag_id IN (SELECT id FROM ${global_table_prefix}member_freetags WHERE tag LIKE '%". implode("%' OR " . getQ("cimke","m",0) ." LIKE '%",$tags) ."%'))  ";
        $filtertext_add = " és címkék közül egyik sem - $result[1]";
    }
}

$additional_filter = $additional_filter ?? '';

if($pattern == '' || $pattern == '*'){
    $statement = "SELECT $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ." = '0000-00-00' $tag_query $additional_filter ORDER BY $orderby $limit";
    $filtertext = '*';
}
else if($pattern != ''){
    $pattern = strtolower($pattern);
    if(preg_match('/^s\s*:\s*presbiter/',$pattern)){
        $statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ."  = '0000-00-00' and " . getQ("presbiter","m",0) ." = 1 $tag_query $additional_filter ORDER BY $orderby $limit";
        $filtertext = 'Presbiterek';    
    }
    else if(preg_match('/^s\s*:\s*kepvi/',$pattern)){
        $statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ."  = '0000-00-00' and " . getQ("kepviselo","m",0) ." = 1 $tag_query $additional_filter ORDER BY $orderby $limit";
        $filtertext = 'Képviselők';    
    }
    else if(preg_match('/^s\s*:\s*csaladfo/',$pattern)){
        $statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ."  = '0000-00-00'  and id = " . getQ("member_id","m",0) ." $tag_query $additional_filter ORDER BY $orderby $limit";
        $filtertext = 'Családfők';
    }
    else if(preg_match('/^s\s*:\s*torolt/',$pattern)){
        $statement = "select $selectfields FROM ${global_table_prefix}members m WHERE " . getQ("leave","m",0) ."  != '0000-00-00'  and id = " . getQ("member_id","m",0) ." $tag_query $additional_filter ORDER BY $orderby $limit";
        $filtertext = 'Összes törölt tag';
    }
    else if($pattern == 'spec-fenntartok'){
        $statement = "select $selectfields from ${global_table_prefix}members m, ${global_table_prefix}amounts a where " . getQ("leave","m",0) ." and m.id = a.member_id and amount != 0 and year(a.dt) = year(now()) $tag_query $additional_filter GROUP BY m.id ORDER BY $orderby $limit";
        $filtertext = 'Fenntartók';
    }
    else if($pattern == 'spec-szulikeres'){
        $statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ."  = '0000-00-00' AND DATE_FORMAT(" . getQ("szul_datum","m",0) .", '%m-%d') < DATE_FORMAT(now() + INTERVAL $bday DAY, '%m-%d') AND DATE_FORMAT(" . getQ("szul_datum","m",0) .", '%m-%d') >= DATE_FORMAT(now(), '%m-%d') $tag_query $additional_filter ORDER BY $orderby $limit";
        $filtertext = $bday . ' napon belül';
        /*
        if($bday == -2){
            $filtertext = 'Tegnapelőtt';            
        }
        if($bday == -1){
            $filtertext = 'Tegnap';            
        }
        */
        if($bday == 0){
            $filtertext = 'Ma';            
        }
        if($bday == 1){
            $filtertext = 'Ma és holnap';            
        }
        
        $filtertext .= ' szülinaposok';
        
    }
    else if(preg_match('/^spec-csalad-(\d+)/',$pattern,$result)){
        $statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ."  = '0000-00-00'  AND " . getQ("member_id","m",0) ." = $result[1] $tag_query $additional_filter ORDER BY $orderby $limit";
        $filtertext = "Család (#$result[1])";
    }
    else if(preg_match('/^spec-id-(\d+)/',$pattern,$result)){
        $statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ." = '0000-00-00' AND id = $result[1] $tag_query $additional_filter ORDER BY $orderby $limit";
        $filtertext = "Gyülekezeti tag (#$result[1])";
    }
    else if(preg_match('/^k:\s*(\d{4})/',$pattern,$result)){
        $statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ." = '0000-00-00' AND year(" . getQ("konf_ev","m",0) .") = '$result[1]' $tag_query $additional_filter ORDER BY $orderby $limit";
        $filtertext = "Konfirmáció éve - $result[1]";
    }
    else if(preg_match('/^b:\s*(\d{4})\D(\d{2})/',$pattern,$result)){
        $statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ." = '0000-00-00' AND year(" . getQ("szul_datum","m",0) .") = '$result[1]' AND MONTH(" . getQ("szul_datum","m",0) .") = '$result[2]'  $tag_query $additional_filter ORDER BY $orderby $limit";
        $filtertext = "Születésnaposok - $result[1]-$result[2]";
    }
    else if(preg_match('/^sz:\D*(\d{2})\D+(\d{2})\D+(\d{2})\D+(\d{2})/',$pattern,$result)){
        $statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ." = '0000-00-00' AND DATE_FORMAT(" . getQ("szul_datum","m",0) .", '%m-%d') >=  '$result[1]-$result[2]' AND DATE_FORMAT(" . getQ("szul_datum","m",0) .", '%m-%d') <=  '$result[3]-$result[4]' $tag_query $additional_filter ORDER BY $orderby $limit";
        $filtertext = "Születésnap: $result[1].$result[2]-$result[3].$result[4]";
    }
    else if(preg_match('/^sz:\D*(\d+)\s*$/',$pattern,$result)){
        $bday = $result[1];
        $statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ."  = '0000-00-00' AND DATE_FORMAT(" . getQ("szul_datum","m",0) .", '%m-%d') < DATE_FORMAT(now() + INTERVAL $bday DAY, '%m-%d') AND DATE_FORMAT(" . getQ("szul_datum","m",0) .", '%m-%d') >= DATE_FORMAT(now(), '%m-%d') $tag_query $additional_filter ORDER BY $orderby $limit";
        $filtertext = $bday . ' napon belül';
        /*
        if($bday == -2){
            $filtertext = 'Tegnapelőtt';            
        }
        if($bday == -1){
            $filtertext = 'Tegnap';            
        }
        */
        if($bday == 0){
            $filtertext = 'Ma';            
        }
        if($bday == 1){
            $filtertext = 'Ma és holnap';            
        }
        
        $filtertext .= ' szülinaposok';

    }
    else if(preg_match('/^sz:\D*(\d{4})\D+(\d{2})\D+(\d{4})\D+(\d{2})\D*/',$pattern,$result)){
        $statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ." = '0000-00-00' AND DATE_FORMAT(" . getQ("szul_datum","m",0) .", '%Y-%m') >=  '$result[1]-$result[2]' AND DATE_FORMAT(" . getQ("szul_datum","m",0) .", '%Y-%m') <=  '$result[3]-$result[4]' $tag_query $additional_filter ORDER BY $orderby $limit";
        $filtertext = "Születésnap: $result[1].$result[2]-$result[3].$result[4]";
    }
    else if(preg_match('/^u:\s*(.*)/',$pattern,$result)){
        $statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ." = '0000-00-00' AND lower(" . getQ("cim","m",0) .") like '%$result[1]%' $tag_query $additional_filter ORDER BY $orderby $limit";
        $filtertext = "Utca - $result[1]";
    }
    else if(preg_match('/^i:\s*(.*)/',$pattern,$result)){
        $statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ." = '0000-00-00' AND " . getQ("ir_szam ","m",0) ." like '$result[1]%' $tag_query $additional_filter ORDER BY $orderby $limit";
        $filtertext = "Irányítószám - $result[1]";
    }
    else if(preg_match('/^c:\s*(.*)/',$pattern,$result)){
        $tags = array();
        //$tags = explode(' ',$result[1]);
        
        //$statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ." = '0000-00-00' and (  m.cimke LIKE '%". implode("%' and m.cimke like '%",$tags) ."%') $tag_query $additional_filter ORDER BY $orderby $limit";
		$tags = $freetags['cimke']->_parse_tags($result[1]);
        $statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ." = '0000-00-00' and m.id IN (SELECT object_id FROM ${global_table_prefix}member_freetagged_objects WHERE tag_id IN (SELECT id FROM ${global_table_prefix}member_freetags WHERE tag LIKE '%". implode("%' and m.cimke like '%",$tags) ."%')) $tag_query $additional_filter ORDER BY $orderby $limit";
        	
        
        $filtertext = "Címkék közül mindegyik - $result[1]";
    }
    else if(preg_match('/^cv:\s*(.*)/',$pattern,$result)){
        $tags = array();
        //$tags = explode(' ',$result[1]);
        //$statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ." = '0000-00-00' and (m.cimke LIKE '%". implode("%' or m.cimke like '%",$tags) ."%') $tag_query $additional_filter ORDER BY $orderby $limit";
        $tags = $freetags['cimke']->_parse_tags($result[1]);
        $statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ." = '0000-00-00' and m.id IN (SELECT object_id FROM ${global_table_prefix}member_freetagged_objects WHERE tag_id IN (SELECT id FROM ${global_table_prefix}member_freetags WHERE tag LIKE '%". implode("%' or m.cimke LIKE '%",$tags) ."%')) $tag_query $additional_filter ORDER BY $orderby $limit";
        	
        $filtertext = "Címkék közül egyik - $result[1]";
    }
    else if(preg_match('/^cn:\s*(.*)/',$pattern,$result)){
        $tags = array();
        //$tags = explode(' ',$result[1]);
        //$statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ." = '0000-00-00' and (m.cimke NOT LIKE '%". implode("%' AND m.cimke NOT LIKE '%",$tags) ."%') $tag_query $additional_filter ORDER BY $orderby $limit";
        $tags = $freetags['cimke']->_parse_tags($result[1]);
        $statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' AND " . getQ("leave","m",0) ." = '0000-00-00' AND m.id NOT IN (SELECT object_id FROM ${global_table_prefix}member_freetagged_objects WHERE tag_id IN (SELECT id FROM ${global_table_prefix}member_freetags WHERE tag LIKE '%". implode("%' OR m.cimke LIKE '%",$tags) ."%')) $tag_query $additional_filter ORDER BY $orderby $limit";
		
		
        $filtertext = "Címkék közül egyik sem - $result[1]";
    }
    else if(preg_match('/^f:\s*(\d{4})/',$pattern,$result)){
        $statement = "select $selectfields from ${global_table_prefix}members m, ${global_table_prefix}amounts a where " . getQ("leave","m",0) ."  = '0000-00-00' and m.id = a.member_id and amount != 0 and year(a.dt) = $result[1] $tag_query $additional_filter GROUP BY m.id ORDER BY $orderby $limit";
        $filtertext = 'Fenntartók - ' . $result[1];
    }
    else if(preg_match('/^l:\s*(.*)/',$pattern,$result)){
        $statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ." = '0000-00-00' AND lower(" . getQ("leany_neve","m",0) .") like '%$result[1]%' $tag_query $additional_filter ORDER BY $orderby $limit";
        $filtertext = 'Leánykori név - ' . $result[1];
    }
else if(preg_match("/^[0-9,]+$/",$pattern)){
        $statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and id IN ($pattern) $tag_query $additional_filter ORDER BY $orderby $limit";
        $filtertext = $pattern;

}
    else{
        $statement = "select $selectfields FROM ${global_table_prefix}catalog c, ${global_table_prefix}members m WHERE c.member_id = m.id and c.catalog_name = '$mycatalog' and " . getQ("leave","m",0) ."  = '0000-00-00' and (".getQ("nev","m",0)." like '%$pattern%' or ".getQ("telefon","m",0)." like '%$pattern%' or ".getQ("telefon_mobil","m",0)." like '%$pattern%' or ".getQ("email","m",0)." like '%$pattern%' or ".getQ("megjegyzes","m",0)." like '%$pattern%' or id='$pattern') $tag_query $additional_filter ORDER BY $orderby $limit";
        $filtertext = $pattern;
        #lower(concat(" . getQ("nev","m",0) .",' '," . getQ("telefon","m",0) .",' ' , " . getQ("cim","m",0) .", ' ', " . getQ("megjegyzes","m",0) .")
    }
}

//if tag_pattern is on
$filtertext .= $filtertext_add;

?>
