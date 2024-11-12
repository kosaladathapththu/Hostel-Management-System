<?php
session_start();
session_unset();
session_destroy();
header("Location: employee_login.php");
exit();
?>
