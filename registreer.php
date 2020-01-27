<?php
// Config file aanroepen
require_once "config.php";
 
// Variabelen aanmaken met (nu nog) lege waarden
$email = $username = $password = $confirm_password = "";
$email_err = $username_err = $password_err = $confirm_password_err = "";
 
// Data van de form processen
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    if(empty(trim($_POST["email"]))){
        $email_err = "Voer a.u.b. een gebruikersnaam in.";
    } else{
        // Variabel aangemaakt om te selecteren
        $sql = "SELECT id FROM gebruikers WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Variabelen binden aan parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Definieer parameters die eerder gezet zijn
            $param_email = trim($_POST["email"]);
            
            // De voorbereidde statement uitvoeren
            if(mysqli_stmt_execute($stmt)){
                /* opslag resultaat */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "Deze email is al in gebruik.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oeps! Er is iets fout gegaan bij de Email. Probeer het opnieuw.";
            }
        }
         
        // Eindig statement
        mysqli_stmt_close($stmt);
    }

    // Username valideren
    if(empty(trim($_POST["username"]))){
        $username_err = "Voer a.u.b. een gebruikersnaam in.";
    } else{
        // Variabel aangemaakt om te selecteren
        $sql = "SELECT id FROM gebruikers WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Variabelen binden aan parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Definieer parameters die eerder gezet zijn
            $param_username = trim($_POST["username"]);
            
            // De voorbereidde statement uitvoeren
            if(mysqli_stmt_execute($stmt)){
                /* opslag resultaat */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Deze gebruikersnaam is al in gebruik.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oeps! Er is iets fout gegaan bij de username. Probeer het opnieuw.";
            }
        }
         
        // Eindig statement
        mysqli_stmt_close($stmt);
    }
    
    // Valideer wachtwoord
    if(empty(trim($_POST["password"]))){
        $password_err = "Voer a.u.b. een wachtwoord in.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Wachtwoord moet minimaal uit 6 karakters bestaan.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Valideer confirmatie wachtwoord
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Valideer uw wachtwoord.";     
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Het ingevulde wachtwoord komt niet overeen met het eerder ingevulde wachtwoord.";
        }
    }
    
    // Leegte controle voor we de data toevoegen aan de database
    if(empty($email_err) && empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Variabel aangemaakt om in te voegen
        $sql = "INSERT INTO gebruikers (email, username, password) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Variabelen binden aan parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_username, $param_password);
            
            // Definieer parameters die eerder gezet zijn
            $param_email = $email;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Password wordt gehashed.
            
            // Probeer de statement uit te voeren
            if(mysqli_stmt_execute($stmt)){
                // Registratie succesvol, stuur door naar onderstaande pagina.
                header("location: inloggen.php");
            } else {
                echo "Er is iets fout gegaan bij de leegte check. Probeer het later opnieuw.";
            }
        }
         
        // Sluit statement
        mysqli_stmt_close($stmt);
    }
    
    // Sluit mysql verbinding
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registratie Formulier</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Registreren</h2>
        <p>Vul deze form in om een account aan te maken.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>E-mail Adres</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Gebruikersnaam</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Wachtwoord</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Valideer Wachtwoord</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Registreer">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Al een account? <a href="inloggen.php">Log hier in.</a>.</p>
        </form>
    </div>    
</body>
</html>