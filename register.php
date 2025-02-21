<?php
require_once('db/config.php');

$errorMessages = '';
$fieldErrors = [];

$username = '';
$email = '';
$phone = '';
$address = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = [];

    if (strlen($username) < 5 || !preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        $fieldErrors['username'] = "Invalid username. Must be at least 5 characters long and contain only letters and numbers.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $fieldErrors['email'] = "Invalid email format.";
    }

    if (!preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
        $fieldErrors['phone'] = "Invalid phone number format.";
    }

    if (empty($address)) {
        $fieldErrors['address'] = "Address is required.";
    }

    if (strlen($password) < 8 || !preg_match('/[a-zA-Z]/', $password) || !preg_match('/[0-9]/', $password) || preg_match('/[%$@#&!]/', $password)) {
        $fieldErrors['password'] = "Password must be at least 8 characters long and contain letters, numbers, and special characters.";
    }

    if ($password !== $confirm_password) {
        $fieldErrors['confirm_password'] = "Passwords do not match.";
    }

    if (!empty($fieldErrors)) {
        $errorMessages = "<p class='error-bar'>" . implode("<br>", $fieldErrors) . "</p>";
    }

    if (empty($fieldErrors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $con->prepare("INSERT INTO users (username, email, phone, delivery_address, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $phone, $address, $hashed_password);

        if ($stmt->execute()) {
            header("Location: login.php?registered=success"); // Redirect to login page with success parameter
            exit();
        } else {
            $errorMessages .= "<p class='error-bar'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register | CuddleBug</title>
    <link rel="shortcut icon" href="images/icon.ico" type="image/x-icon">
    <?php include 'inc/links.php'; ?>
    <link rel="stylesheet" href="register.css">

</head>
<body>
    <section>
        <div class="side-image">
        </div>
        <div class="right">
            <div class="input-box">
                <a href="index.php">
                    <img src="images/logo.png" alt="CuddleBug Logo" class="center" style="width: 50%; margin-bottom: 20px; text-align: center;">
                </a>
                <header>Register</header>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <?php
                    if (!empty($errorMessages)) {
                        echo "<div class='error-bar fixed top-0 left-0 w-full bg-red-500 text-white text-center z-50'>";
                        echo $errorMessages;
                        echo "<span class='close-btn' onclick='closeErrorBar()'>x</span>";
                        echo "</div>";
                    }
                    ?>
                    <div class="form-columns">
                        <div class="input-column">
                            <div class="input-field">
                                <input type="text" class="input" id="username" name="username" required value="<?php echo htmlspecialchars($username); ?>">
                                <label for="username">Username</label>
                                <div id="username-error" class="error"></div>
                            </div>
                            <div class="input-field">
                                <input type="email" class="input" id="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">
                                <label for="email">Email</label>
                                <div id="email-error" class="error"></div>
                            </div>
                            <div class="input-field">
                                <input type="tel" class="input" id="phone" name="phone" required value="<?php echo htmlspecialchars($phone); ?>">
                                <label for="phone">Phone Number</label>
                                <div id="phone-error" class="error"></div>
                            </div>
                        </div>
                        <div class="input-column">
                            <div class="input-field">
                                <input type="text" class="input" id="address" name="address" required value="<?php echo htmlspecialchars($address); ?>">
                                <label for="address">Address</label>
                                <div id="address-error" class="error"></div>
                            </div>
                            <div class="input-field">
                                <input type="password" class="input" id="password" name="password" required oninput="checkPasswordStrength()">
                                <label for="password">Password</label>
                                <i class="fas fa-eye-slash eye-icon" onclick="togglePasswordVisibility('password')"></i>
                            </div>
                            <div class="input-field">
                                <input type="password" class="input" id="confirm-password" name="confirm_password" required oninput="checkPasswordMatch()">
                                <label for="confirm-password">Confirm Password</label>
                                <i class="fas fa-eye-slash eye-icon" onclick="togglePasswordVisibility('confirm-password')"></i>
                            </div>
                        </div>
                    </div>
                    <div class="password-strength">
                        <span id="strength-bar"></span>
                        <div id="password-feedback" class="password-feedback"></div>
                        <div id="password-match" class="password-feedback"></div>
                    </div>
                    <input type="submit" name="register" class="submit" value="Register">
                </form>
                <div class="signin">
                    <span>Already have an account? <a href="login.php"> Sign in here</a></span>
                </div>
            </div>
        </div>
    </section>

    <script>
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('strength-bar');
            const feedback = document.getElementById('password-feedback');
            let strength = 0;
            let feedbackMessage = "Strong password must not contain:<br>";

            if (password.length >= 8) {
                strength++;
            } else {
                feedbackMessage += "• Less than 8 characters<br>";
            }
            if (password.match(/[a-z]+/)) {
                strength++;
            } else {
                feedbackMessage += "• No lowercase letters<br>";
            }
            if (password.match(/[A-Z]+/)) {
                strength++;
            } else {
                feedbackMessage += "• No uppercase letters<br>";
            }
            if (password.match(/[0-9]+/)) {
                strength++;
            } else {
                feedbackMessage += "• No numbers<br>";
            }
            if (!password.match(/[%$@#&!]+/)) {
                strength++;
            } else {
                feedbackMessage += "• No special characters (%$@#&!)<br>";
            }

            strengthBar.className = '';
            feedback.className = 'password-feedback';

            if (strength < 3) {
                strengthBar.classList.add('weak');
                feedback.classList.add('weak');
                feedbackMessage = "Weak password:<br>" + feedbackMessage;
            } else if (strength < 5) {
                strengthBar.classList.add('medium');
                feedback.classList.add('medium');
                feedbackMessage = "Medium password:<br>" + feedbackMessage;
            } else {
                strengthBar.classList.add('strong');
                feedback.classList.add('strong');
                feedbackMessage = "Strong password!";
            }

            feedback.innerHTML = feedbackMessage;
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            const matchMessage = document.getElementById('password-match');

            if (password === '' && confirmPassword === '') {
                matchMessage.textContent = '';  
            } else if (password === confirmPassword) {
                matchMessage.textContent = 'Passwords match';
                matchMessage.style.color = 'green';
            } else {
                matchMessage.textContent = 'Passwords do not match';
                matchMessage.style.color = 'red';
            }
        }

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

        function validateUsername() {
            var username = document.getElementById('username').value;
            var usernameError = document.getElementById('username-error');
            var regex = /^[a-zA-Z0-9]+$/;
            var lettersRegex = /[a-zA-Z]/;

            usernameError.classList.remove('valid');
            if (username.length < 5) {
                usernameError.textContent = 'Username must be at least 5 characters long';
            } else if (!regex.test(username)) {
                usernameError.textContent = 'Username can only contain alphabetic characters and numbers';
            } else if (!lettersRegex.test(username)) {
                usernameError.textContent = 'Username cannot be only numbers';
            } else {
                usernameError.textContent = 'Username looks good!';
                usernameError.classList.add('valid');
                document.getElementById('username').classList.add('valid');
            }
        }

        function validateEmail() {
            var email = document.getElementById('email').value;
            var emailError = document.getElementById('email-error');
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            emailError.classList.remove('valid');
            if (!emailRegex.test(email)) {
                emailError.textContent = 'Invalid email format';
            } else {
                emailError.textContent = 'Email looks good!';
                emailError.classList.add('valid');
                document.getElementById('email').classList.add('valid');
            }
        }

        function validatePhone() {
            var phone = document.getElementById('phone').value;
            var phoneError = document.getElementById('phone-error');
            var regex = /^\+?[0-9]{10,15}$/;

            if (!regex.test(phone)) {
                phoneError.textContent = 'Invalid phone number format.';
            } else {
                phoneError.textContent = 'Phone number looks good!';
                phoneError.classList.add('valid');
                document.getElementById('phone').classList.add('valid');
            }
        }

        function validateAddress() {
            var address = document.getElementById('address').value;
            var addressError = document.getElementById('address-error');

            if (address.trim() === '') {
                addressError.textContent = 'Address is required.';
            } else {
                addressError.textContent = 'Address looks good!';
                addressError.classList.add('valid');
                document.getElementById('address').classList.add('valid');
            }
        }

        document.getElementById('username').addEventListener('input', validateUsername);
        document.getElementById('email').addEventListener('input', validateEmail);
        document.getElementById('phone').addEventListener('input', validatePhone);
        document.getElementById('address').addEventListener('input', validateAddress);

        function closeErrorBar() {
            const errorBar = document.querySelector('.error-bar');
            errorBar.style.display = 'none';
        }
    </script>
</body>
</html>
