<?php
// Sessie starten
session_start();
 
// Controleren of de bezoeker is ingelogd, echter staat deze HIER uit omdat dit de huizen pagina is.

/* if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}*/
?>
 
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Huizen</title>
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div>
        <h1>Hoi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welkom op onze site!</h1>
    </div>
    <p>
        <a href="uitloggen.php" class="btn btn-danger">Uitloggen</a>
    </p>
</body>
</html>