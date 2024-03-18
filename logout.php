<?php
session_start();
session_destroy();
setcookie('tks_npp', "", time()-3600);
setcookie('tks_pass', "", time()-3600);
header("Location: index.php");
exit;
?>
