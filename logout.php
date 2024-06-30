<?php
session_start(); 
session_destroy();  // destroy the session and redirect to the search page as any vistors 
header("Location: Search_For_Car.php");
exit;
?>