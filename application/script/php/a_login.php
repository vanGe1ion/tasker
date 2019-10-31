<?php
session_start();
$_SESSION["isAdmin"] = true;
$back = isset($_SESSION["backTrace"])?$_SESSION["backTrace"]:"http://".$_SERVER["SERVER_NAME"];
?>
<script type="text/javascript">
  document.location.replace("<?=$back?>");
</script>