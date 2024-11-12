<?php
session_start(); // Start the session

// Destroy all session data to log out
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session

// Redirect to the login page
header("Location: matron_auth.php");
exit();
?>
