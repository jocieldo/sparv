<?php 
foreach ($_REQUEST as $___opt => $___val) {
  $$___opt = $___val;
}
if(empty($spv)) {    
include("nav/modelos.php");
}
elseif(substr($spv, 0, 4)=='http' or substr($spv, 
0, 1)=="/" or substr($spv, 0, 1)==".") 
{
echo '<br><font face=arial size=11px><br><b>A página não existe.</b><br>Por favor selecione uma página a partir do Menu Principal.</font>'; 
}
else {
include("$spv.php");
}

?>