<?php

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $mysqli = require __DIR__ . "/database.php";
    
    $sql = sprintf("SELECT * FROM user
                    WHERE name = '%s'",
                   $mysqli->real_escape_string($_POST["name"]));
    
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
    
    if ($user) {
        
        if (password_verify($_POST["password"], $user["password_hash"])) {
            
            session_start();
            
            session_regenerate_id();
            
            $_SESSION["user_id"] = $user["id"];
            
            header("Location: index.php");
            exit;
        }
    }
    
    $is_invalid = true;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="login.css">
    </head>
    
    <body>
        <div class="container">
            <div class="login-box">
                <h1>Sign In</h1>

                <?php if ($is_invalid): ?>
                    <em>Invalid sign in.</em>
                <?php endif; ?>

                <form method="post">
                    <input type="name" name="name" id="name" placeholder="Username"
                           value="<?= htmlspecialchars($_POST["name"] ?? "") ?>">
                    <input type="password" name="password" id="password" placeholder="Password">
                    
                    <button class="sign_in">Sign in</button>
                    
                    <p>Don't have an account? Click here to <a href="newsignup.html">Sign Up.</a></p>
                </form>
            </div>
        </div>
    </body>
</html>