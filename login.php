<?php //
session_start();

$is_invalid = false;
$lockout_duration = 5;
$disable_inputs = false;

if(!isset($_SESSION['login_attempts'])){
    $_SESSION['login_attempts'] = 0;
}

if(!isset($_SESSION['last_failed_attempt'])){
    $_SESSION['last_failed_attempt'] = 0;
}

if ($_SESSION['login_attempts'] >= 3){
    $time_remaining = time() - $_SESSION['last_failed_attempt'];
    
    if($time_remaining < $lockout_duration){
        $disable_inputs = true;
        $lockout_time = $lockout_duration - $time_remaining;
    }
    
    else{
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_failed_attempt'] = 0;
        $disable_inputs = false;
    }
}
    
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if($_SESSION['login_attempts'] < 3 || $time_remaining >= $lockout_duration){
        $mysqli = require __DIR__ . "/database.php";

        $sql = sprintf("SELECT * FROM user
                WHERE name = '%s'",
                $mysqli->real_escape_string($_POST["name"]));

        $result = $mysqli->query($sql);

        $user = $result->fetch_assoc();

        if ($user) {

            if (password_verify($_POST["password"], $user["password_hash"])) {

                $_SESSION['login_attempts'] = 0;

                session_regenerate_id();

                $_SESSION["user_id"] = $user["id"];

                header("Location: index.php");
                exit;
            }
        }
    
        $_SESSION['login_attempts'] += 1;
        $_SESSION['last_failed_attempt'] = time();
        $is_invalid = true;
    }
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
                <h1>Welcome to osu!</h1>

                <?php if ($is_invalid): ?>
                    <p class="err_message"><?= $lockout_message ?? "Username or Password is incorrect. You have " . (4 - $_SESSION['login_attempts']) . " attempt(s) left."?></p>
                <?php endif; ?>
                    
                <?php if ($disable_inputs): ?>
                    <!--<p class="err_message"><?= $lockout_message ?? "Too many failed login attempts. Please try again in " . ceil($lockout_time) . " seconds"?></p>-->
                    <p class="err_message"><?= $lockout_message ?? "Too many failed login attempts. Please try again in 5 seconds"?></p>
                <?php endif; ?>
                    
                <form method="post">
                    <input type="name" name="name" id="name" placeholder="Username"
                           value="<?= htmlspecialchars($_POST["name"] ?? "") ?>"
                           <?= $disable_inputs ? 'disabled' : '' ?>>
                    
                    <input type="password" name="password" id="password" placeholder="Password"
                           <?= $disable_inputs ? 'disabled' : '' ?>>
                    
                    <button class="sign_in" <?= $disable_inputs ? 'disabled' : '' ?>>Sign in</button>
                    
                    <p>Don't have an account? Click here to <a href="newsignup.html">Sign Up.</a></p>
                </form>
            </div>
        </div>
        
        <?php if ($disable_inputs): ?>
            <script>

                let lockoutExpiration = <?= time() + $lockout_time ?> * 1000;
                let now = Date.now();
                let timeout = lockoutExpiration - now;

                if (timeout > 0) {
                    setTimeout(function() {
                        window.location.reload();
                    }, timeout);
                }
            </script>
        <?php endif; ?>
    </body>
</html>