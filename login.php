<?php
session_start();
require_once('db/config.php');

// Check if there is a message in the session
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';

// Clear the message from the session
unset($_SESSION['message']);

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stay_logged_in = isset($_POST['stay_logged_in']);

    $check_user = $con->prepare("SELECT id, username, password FROM users WHERE username = ?");
    
    if (!$check_user) {
        die('Error in prepare statement: ' . $con->error);
    }

    $check_user->bind_param("s", $username);
    $check_user->execute();
    $check_user->store_result();

    if ($check_user->num_rows > 0) {
        $check_user->bind_result($user_id, $username, $hashed_password);
        $check_user->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;  
            if ($stay_logged_in) {
                $expiry_time = time() + (86400 * 30); // 30 days
                setcookie('remember_me', $user_id, $expiry_time, "/", "", false, true);
            }
            header('Location: index.php');  
            exit();
        } else {
            $error_message = "Invalid password";
        }
    } else {
        $error_message = "Username not found";
    }

    $check_user->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login | CuddleBug</title>
    <link rel="shortcut icon" href="images/icon.ico" type="image/x-icon">
    <?php include 'inc/links.php'; ?>
    <link rel="stylesheet" href="login.css">

</head>
<body>
    <section>
        <!-- Error Message Display -->
        <?php if (!empty($error_message) || ($message)) : ?>
            <div class="error-bar">
                <?php echo $error_message; ?>                
                <?php echo $message; ?>
                <span class="close-btn" onclick="closeErrorBar()">x</span>
            </div>
        <?php endif; ?>

        <!-- Success Message Display -->
        <?php
        if (isset($_GET['registered']) && $_GET['registered'] === 'success') {
            echo "<div class='success-bar'>Registration successful!";
            echo "<span class='close-btn' onclick='closeSuccessBar()'>x</span>";
            echo "</div>";
        }
        ?>
        <div class="side-image">
        </div>
        <div class="right">
            <div class="input-box">
                <a href="index.php">
                    <img src="images/logo.png" alt="CuddleBug Logo" class="center" style="width: 50%; margin-bottom: 20px;">
                </a>
                <header>Login</header>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="input-field">
                        <input type="text" class="input" id="username" name="username" required>
                        <label for="username">Username</label>
                    </div>
                    <div class="input-field">
                        <input type="password" class="input" id="password" name="password" required>
                        <label for="password">Password</label>
                        <i class="fas fa-eye-slash eye-icon" onclick="togglePasswordVisibility('password')"></i>
                    </div>
                    <div class="input-field">
                        <input type="checkbox" id="stay_logged_in" name="stay_logged_in">
                        <label for="stay_logged_in" style="position: unset;">Stay logged in for 30 days</label>
                    </div>
                    <div class="input-field">
                        <input type="submit" class="submit" name="login" value="Sign In">
                        <div class="signin">
                            <span>Don't have an account? <a href="register.php"> Register here</a></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        function togglePasswordVisibility(id) {
            const input = document.getElementById(id);
            const icon = document.querySelector(`#${id} ~ .eye-icon`);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        function closeErrorBar() {
            var errorBar = document.querySelector('.error-bar');
            if (errorBar) {
                errorBar.style.display = 'none';
            }
        }

        function closeSuccessBar() {
            var successBar = document.querySelector('.success-bar');
            if (successBar) {
                successBar.style.display = 'none';
            }
        }
    </script>
</body>
</html>
