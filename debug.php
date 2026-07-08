<?php
echo "Keys:\n";
foreach($_GET as $k=>$v){echo "[" . $k . "] hex=" . bin2hex($k) . " val=" . $v . "\n";}
?>
