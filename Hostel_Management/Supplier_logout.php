<?php
session_start();
session_destroy();
header("Location: supplier_login.php");
exit;
?>
