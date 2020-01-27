<?php
// Sessie starten
session_start();
 
// Controleren of de gebruiker al ingelogd is. Als hij al ingelogd is dan sturen we hem door.
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: huizen.php");
    exit;
}
 
// Config aanroepen
require_once "config.php";
 
// Variabelen aanroepen en lege waarden geven
$username = $password = "";
$username_err = $password_err = "";
 
// Data van de form processen
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Controle voor lege username veld
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Controle voor lege wachtwoord veld
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Valideer gegevens
    if(empty($username_err) && empty($password_err)){
        // Variabel aangemaakt om te selecteren
        $sql = "SELECT id, username, password FROM gebruikers WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Variabelen binden aan parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Definieer parameters die eerder gezet zijn
            $param_username = $username;
            
            // De voorbereidde statement uitvoeren
            if(mysqli_stmt_execute($stmt)){
                // Resultaat opslaan
                mysqli_stmt_store_result($stmt);
                
                // Controleren of gebruiker bestaat
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Variabelen binden aan resultaat
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Wachtwoord is correct ook, dus begin nieuwe sessie
                            session_start();
                            
                            // Sla data op in variabelen
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Gebruiker doorsturen naar onderstaande pagina
                            header("location: huizen.php");
                        } else{
                            // Error tonen als wachtwoord niet klopt
                            $password_err = "Het ingevulde wachtwoord is niet correct.";
                        }
                    }
                } else{
                    // Error voor als de account niet bestaat of de naam klopt niet.
                    $username_err = "Controleer of het ingevulde accountnaam klopt.";
                }
            } else{
                echo "Oeps! Iets klopt er niet, probeer het later opnieuw.";
            }
        }
        
        // Sluit statement
        mysqli_stmt_close($stmt);
    }
    
    // Sluit verbinding met mysql
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inloggen</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Log in</h2>
        <p>Voer uw gegevens in om in te loggen.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Gebruikersnaam</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Wachtwoord</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Nog geen account? <a href="registreer.php">Maak er een aan</a>.</p>
        </form>
    </div>    
</body>
</html>