<?php 
    session_start();

    if(isset($_SESSION['passwordiscorrect']))
    {
        $validpass = $_SESSION['passwordiscorrect'];
    }
    else
    {
        $validpass = TRUE;
    }
    if(isset($_SESSION['userfound']))
    {
        $ufound = $_SESSION['userfound'];
    }
    else
    {
        $ufound = TRUE;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Sign In | Budgetin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/style-global.css">
        <link rel="stylesheet" type="text/css" href="css/style-logreg.css">
        <script type="text/javascript" src="js/panel-collapsed.js"></script>
        <script defer type="text/javascript" src="js/validation-login.js"></script>
    </head>
    <body>
        <div class="wrapper">
            <div id="collapsed-panel">
                <button class="btn-close" onclick="hideCollapsed()">
                    <img src="img/icons/btn-close.png" id="img-btn-close">
            </div>
<!-- ----------------------------------------------HEADER-------------------------------------------------- -->            
            <header>
                <a href="landing.html" class="logo-header">KELOMPOK 7 MIC</a>
                <button href="#" class="btn-collapse" onclick="showCollapsed()">
                    <img src="img/icons/btn-collapse.png">
                </button>
                <div class="nav-and-reg">
                    <nav class="nav-header">
                    <button class="btn-signin" onclick="location.href='login.php'">Sign In</button>
                </div>
            </header>
<!-- ----------------------------------------------CONTENT------------------------------------------------- -->
            <div class="log-reg log">
                <div class="tab-menu">
                    <a href="login.php" class="tab-menu-active">Log in</a>
                    <a href="registration.html">Join</a>
                </div>                
                <form id="form_login" action="phpscripts/users_validation.php" method="POST" autocomplete="off">
                    <input type="text" id="username" name="username" placeholder="Username" class="input-text">
                    <input type="password" id="password" name="password" placeholder="Password" class="input-text">
                    <input type="submit" value="Log In" name="submit" class="btn-submit">
                    <div id="error"><?php if(!$validpass || !$ufound){echo "The username or password you entered is incorrect!";}?></div>
                </form>
            </div>
        </div>
    </body>
</html>