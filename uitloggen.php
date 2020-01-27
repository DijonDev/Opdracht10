<?php
// Sessie starten
session_start();
 
// Alle sessie variabelen uitschakelen
$_SESSION = array();
 
// De sessie zelf sluiten
session_destroy();
 
// Terug naar de homepage
header("location: inloggen.php");
exit;
?>