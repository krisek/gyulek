<script>
var toggled = 0;

function aboutToggle(){
	aboutDiv=document.getElementById("about");
	if(toggled == 0){
		aboutDiv.innerHTML='<p style="color: grey; text-align: right;"><a href="javascript:aboutToggle();" style="text-decoration: none;"><small><small>gyulek.php by krisek (2006-2021) based on gyulek.exe by czky (1996) (bezár)</small></small></a></p>';
		toggled=1;
		
	}
	else{
		aboutDiv.innerHTML='<p style="color: grey; text-align: right;"><a href="javascript:aboutToggle();" style="text-decoration: none;"><small><small>(névjegy)</small></small></a></p>';
		toggled=0;	
	}	
}
</script>
<div id="about"><a href="javascript:aboutToggle();" style="text-decoration: none;"><p style="color: grey; text-align: right;"><small><small>(névjegy)</small></small></p></a></div>
  
