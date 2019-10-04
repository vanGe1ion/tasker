<?php
$f_config = "private/config.json";
$configs = json_decode(file_get_contents($f_config));
$domain = $configs->domain_name;
?>
<meta http-equiv="refresh" content="0; url=http://<?=$domain?>/application/tasks.php">