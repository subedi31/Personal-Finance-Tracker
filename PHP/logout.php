<?php
session_start();
session_destroy(); // Destroy the session

// Redirect to login page after logging out
header("Location: ../login.html");
exit();
?>
