<?php

$vs = $_REQUEST["VS"];
$res = $_REQUEST["RES"];
$ac = $_REQUEST["AC"];
$sign = $_REQUEST["SIGN"];
//echo 'http://'.$_SERVER["SERVER_NAME"]."bystra.localhost/index.php?option=com_cotres&controller=payments&task=cardpay_return&RES=OK&VS=30&AC=&SIGN=4BA0CC6DBBB02C23";
header('Location:http://'.$_SERVER["SERVER_NAME"]."/index.php?option=com_cotres&controller=payments&task=cardpay_return&RES=$res&VS=$vs&AC=$ac&SIGN=$sign");

?>
