<?php
session_start( );
// Logout of the site
session_destroy( );
unset($_SESSION['usr_id']);

header('Location: ./login.php');
?>
