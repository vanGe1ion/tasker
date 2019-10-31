<?php
session_start();
$back = isset($_SESSION["backTrace"])?$_SESSION["backTrace"]:"http://".$_SERVER["SERVER_NAME"];;
if(isset($_SESSION["isAdmin"]))
    unset($_SESSION["isAdmin"]);
setcookie(session_name(), '', time() - 60*60*24*32, '/');
session_unset();
session_destroy();
?>
<script type="text/javascript">
    document.location.replace("<?=$back?>");
</script>