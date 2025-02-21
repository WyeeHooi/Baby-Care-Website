<?php
require_once('db/config.php');

    // Check if user is logged in
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $user_logged_in = isset($_SESSION['user_id']);
    $user_id = $user_logged_in ? $_SESSION['user_id'] : null;
    // fetch active orders
    $sql = "SELECT id, total_amount, status, created_at FROM orders WHERE user_id = $user_id AND status IN ('Pending Payment', 'Shipping', 'Processing')";
    $active_order = $con->query($sql);

    //fetch past orders from database
    $sql = "SELECT id, total_amount, status, created_at FROM orders WHERE user_id = $user_id AND status IN ('Cancelled', 'Completed')";
    $past_order = $con->query($sql);

    // extract user delivery address
    $sql = "SELECT delivery_address FROM users WHERE id = $user_id";
    $d_address = $con->query($sql);
    // extract user billing address
    $sql = "SELECT billing_address FROM users WHERE id = $user_id";
    $b_address = $con->query($sql);

    // extract user wallet amount
    $sql = "SELECT wallet FROM users WHERE id = $user_id";
    $wallet = $con->query($sql);
    //list out all the user transaction history
    $sql = "SELECT id, created_at, total_amount FROM orders WHERE user_id = $user_id";
    $history = $con->query($sql);
    // extract user details
    $sql = "SELECT username, email, phone FROM users WHERE id = $user_id";
    $user_detail = $con->query($sql);

    // Handle AJAX request to update addresses
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['address_type'])) {
    $address_type = $_POST['address_type'];
    $new_address = $_POST['new_address'];
    
    if ($address_type === 'delivery') {
        $update_stmt = $con->prepare("UPDATE users SET delivery_address=? WHERE id=?");
    } else {
        $update_stmt = $con->prepare("UPDATE users SET billing_address=? WHERE id=?");
    }

    $update_stmt->bind_param("si", $new_address, $user_id);
    $update_stmt->execute();
    $update_stmt->close();

    // Return the updated address
    echo $new_address;
    exit();
}

    $errorMessages = '';
$fieldErrors = [];

$username = '';
$email = '';
$phone = '';
$delivery_address = '';
$billing_address = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];

    // Validate inputs
    if (strlen($username) < 5 || !preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        $fieldErrors['username'] = "Invalid username. Must be at least 5 characters long and contain only letters and numbers.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $fieldErrors['email'] = "Invalid email format.";
    }

    if (!preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
        $fieldErrors['phone'] = "Invalid phone number format.";
    }

    if ($password && (strlen($password) < 8 || !preg_match('/[a-zA-Z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[%$@#&!]/', $password))) {
        $fieldErrors['password'] = "Password must be at least 8 characters long and contain letters, numbers, and special characters.";
    }

    if (!empty($fieldErrors)) {
        $errorMessages = "<p class='error-bar'>" . implode("<br>", $fieldErrors) . "</p>";
    }

    // Update user details if no validation errors
    if (empty($fieldErrors)) {
        $sql = "UPDATE users SET username=?, email=?, phone=?, delivery_address=?, billing_address=?";
        $params = [$username, $email, $phone, $delivery_address, $billing_address];

        if ($password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password=?";
            $params[] = $hashed_password;
        }

        $sql .= " WHERE id=?";
        $params[] = $user_id;

        $stmt = $con->prepare($sql);
        $stmt->bind_param(str_repeat("s", count($params) - 1) . "i", ...$params);

        if ($stmt->execute()) {
            echo "<p class='success-bar'>Account details updated successfully!</p>";
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
    <title>My Account | CuddleBug</title>
    <link rel="shortcut icon" href="images/icon.ico" type="image/x-icon">
    <?php include 'inc/links.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php include 'inc/header.php'; ?>
    <link rel="stylesheet" href="myaccount.css">
</head>
<body class="bg-white"> 

<section class="container mx-auto max-w-7xl mt-8 px-4 mb-12">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">My Account</h2>
    <div class="row w-100 overflow-hidden flex">
        <div class="sidebar col-lg-2 ps-3">
            <a href="#orders" onclick="showContent(event, 'orders')"><i
                    class="fas fa-shopping-cart pe-3 ps-2"></i>Orders</a>
            <a href="#addresses" onclick="showContent(event, 'addresses')"><i
                    class="fas fa-home pe-3 ps-2"></i>Addresses</a>
            <a href="#wallet" onclick="showContent(event, 'wallet')"><i class="far fa-credit-card pe-3 ps-2"></i>My
                Wallet</a>
            <a href="#account-details" onclick="showContent(event, 'account-details')"><i
                    class="fas fa-id-card-alt pe-3 ps-2"></i>My Profile</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt pe-3 ps-2"></i>Logout</a>
        </div>
        <div class="content col-lg-10">
            <div id="orders" class="active">
                <div>
                    <h3 class="text-xl font-bold mb-6 text-gray-800">Active Orders</h3>
                    <div class="mt-3 mb-5">
                        <table class="table table-bordered min-w-full bg-white border border-1 table-striped">
                            <thead>
                                <tr>
                                    <th class="py-2" width="20%">Order ID</th>
                                    <th class="py-2" width="15%">Date</th>
                                    <th class="py-2" width="17.5%">Total Amount</th>
                                    <th class="py-2" width="17.5%">Status</th>
                                    <th class="py-2" width="30%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if ($active_order->num_rows > 0) {
                                        while($order = $active_order->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>#". $order['id'] ."</td>";
                                            echo "<td>". $order['created_at'] ."</td>";
                                            echo "<td>RM". $order['total_amount'] ."</td>";
                                            echo "<td>". $order['status'] ."</td>";
                                            echo "<td><button class='btn btn-primary'>View</button>";
                                            if ($order['status'] == 'pending payment') {
                                                echo "<button class='btn btn-primary bg-black hover:bg-pink-600 text-white ms-3 rounded-lg'>Cancel Order</button>";
                                            }
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>No active orders found</td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-bold mb-6 text-gray-800">Past Orders</h3>
                    <div>                    
                        <table class="table table-bordered min-w-full bg-white border border-1 table-striped">
                            <thead>
                                <tr>
                                    <th class="py-2" width="20%">Order ID</th>
                                    <th class="py-2" width="20%">Date</th>
                                    <th class="py-2" width="27.5%">Total Amount</th>
                                    <th class="py-2" width="27.5%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if ($past_order->num_rows > 0) {
                                        while($order = $past_order->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>#". $order['id'] ."</td>";
                                            echo "<td>". $order['created_at'] ."</td>";
                                            echo "<td>RM". $order['total_amount'] ."</td>";
                                            echo "<td>". $order['status'] ."</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No past orders found</td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div id="addresses">
                <h3 class="text-xl font-bold mb-6 text-gray-800">Addresses</h3>
                <div class="d-flex flex-row flex-nowrap">
                    <!-- Delivery Address -->
                    <div class="delivery-address p-4 w-50 mx-2">
                        <?php
                    
                            if ($d_address->num_rows > 0) {
                                $row = $d_address->fetch_assoc();
                                $delivery_address = $row['delivery_address'];
                            } else {
                                $delivery_address = "No address found";
                            }
                         ?>
                        <div class="d-flex flex-row justify-content-between mb-5">
                            <h4 class="text-l font-bold text-gray-800 text-uppercase">Delivery Address</h4>
                            <button onclick="toggleEditForm('delivery')">Edit</button>
                        </div>
                        <p id="delivery_address_content">
                            <?php echo $delivery_address; ?>
                        </p>
                        <form id="delivery_edit_form" style="display: none;" onsubmit="updateAddress(event, 'delivery')">
                            <input type="text" id="new_delivery_address" name="new_delivery_address" value="<?php echo $delivery_address; ?>" class="w-full px-3 py-2 mb-4 border rounded my-3">
                            <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded">Save</button>
                            <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded" onclick="toggleEditForm('delivery')">Cancel</button>
                        </form>
                    </div>
                        
                    <!-- Billing Address -->
                    <div class="billing-address p-4 w-50 mx-2">
                        <?php
                    
                            if ($b_address->num_rows > 0) {
                                $row = $b_address->fetch_assoc();
                                $billing_address = $row['billing_address'];
                            } else {
                                $billing_address = "-";
                            }
                        ?>
                        <div class="d-flex flex-row justify-content-between mb-5">                            
                            <h4 class="text-l font-bold text-gray-800 text-uppercase">Billing Address</h4>
                            <button onclick="toggleEditForm('billing')">Edit</button>
                        </div>
                        <p id="billing_address_content">
                            <?php echo $billing_address; ?>
                        </p>
                        <form id="billing_edit_form" style="display: none;" onsubmit="updateAddress(event, 'billing')">
                            <input type="text" id="new_billing_address" name="new_billing_address" value="<?php echo $billing_address; ?>" class="w-full px-3 py-2 mb-4 border rounded my-3">
                            <input type="checkbox" id="same_as_delivery" name="same_as_delivery" value="1" onchange="copyDeliveryAddress()"><br />
                            <label for="same_as_delivery">Same as delivery address</label>
                            <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded">Save</button>
                            <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded" onclick="toggleEditForm('billing')">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>            


            <div id="wallet">
                <h3 class="text-xl font-bold mb-6 text-gray-800">Wallet Balance</h3>
                <div class="my-5">
                    <h4 class="text-l font-bold text-gray-800 text-uppercase mb-3">balance Amount</h4>
                    <p class="text-green-600">
                    <?php                     
                    echo "RM " . number_format($wallet, 2);
                    ?>
                    </p>
                </div>
                <hr>
                <div class="my-5">
                <h4 class="text-l font-bold text-gray-800 text-uppercase mb-3">Transaction history</h4>
                    <table class="table min-w-full table-striped">
                        <thead>
                            <tr>
                                <th class="py-2">Transaction ID</th>
                                <th class="py-2">Date</th>
                                <th class="py-2">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($history->num_rows > 0) {
                                while($transaction = $history->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>#". $transaction['id'] ."</td>";
                                    echo "<td>". $transaction['created_at'] ."</td>";
                                    echo "<td>RM". $transaction['total_amount'] ."</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No transaction history found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="account-details">
                <h3 class="text-xl font-bold mb-6 text-gray-800">Account Details</h3>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <?php if ($errorMessages): ?>
                        <div class="error-bar">
                            <?php echo $errorMessages; ?>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-lg-6 updateUser py-2">
                            <label for="username" class="text-l font-bold text-gray-800 text-uppercase">Username:</label><br />
                            <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
                            <span id="username-error"></span>

                            <label for="email" class="text-l font-bold text-gray-800 text-uppercase mt-4">Email:</label><br />
                            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                            <span id="email-error"></span><br>

                            <label for="phone" class="text-l font-bold text-gray-800 text-uppercase mt-4">Phone:</label><br />
                            <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" required>
                            <span id="phone-error"></span><br>

                        </div>
                        <div class="col-lg-6 updateUser py-2">
                            <label for="password" class="text-l font-bold text-gray-800 text-uppercase">Password</label><br>
                            <div class="d-flex flex-row position-relative updateUser">    
                                <input type="password" id="password" name="password" required oninput="checkPasswordStrength()">                        
                                <i class="fas fa-eye-slash eye-icon" onclick="togglePasswordVisibility('password')"></i>
                            </div>
                            <span id="password-error"></span><br>
                    
                            <label for="confirm-password" class="text-l font-bold text-gray-800 text-uppercase mt-4">Confirm Password</label><br>
                            <div class="d-flex flex-row position-relative updateUser">
                                <input type="password" class="input" id="confirm-password" name="confirm_password" required oninput="checkPasswordMatch()">                        
                                <i class="fas fa-eye-slash eye-icon" onclick="togglePasswordVisibility('confirm-password')"></i>
                            </div>
                            <span id="confirm-password-error"></span><br>
                        </div>

                    </div>   
                    <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded mt-5">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</section>



<?php include 'inc/footer.php'; ?>

<script>
        function showContent(event, sectionId) {
            event.preventDefault();
            var contents = document.querySelectorAll('.content > div');
            var links = document.querySelectorAll('.sidebar a');

            contents.forEach(function (content) {
                content.classList.remove('active');
            });

            links.forEach(function (link) {
                link.classList.remove('active');
            });

            document.getElementById(sectionId).classList.add('active');
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelector('.sidebar a').click();
        });

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const feedback = document.getElementById('password-error');
            let feedbackMessage = "";

            if (password.length < 8) {
                feedbackMessage += "• Must more than 8 characters<br>";
            }
            if (!password.match(/[a-z]+/)) {
                feedbackMessage += "• Must have least one lowercase letters<br>";
            }
            if (!password.match(/[A-Z]+/)) {
                feedbackMessage += "• Must have at least one uppercase letters<br>";
            }
            if (!password.match(/[0-9]+/)) {
                feedbackMessage += "• Must at least have numbers<br>";
            }
            if (password.match(/[%$@#&!]+/)) {
                feedbackMessage += "• Should no contain special characters (%$@#&!)<br>";
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

        document.getElementById('username').addEventListener('input', validateUsername);
        document.getElementById('email').addEventListener('input', validateEmail);
        document.getElementById('phone').addEventListener('input', validatePhone);

        function closeErrorBar() {
            const errorBar = document.querySelector('.error-bar');
            errorBar.style.display = 'none';
        }

        function toggleEditForm(addressType) {
            const formId = addressType + '_edit_form';
            const form = document.getElementById(formId);
            form.style.display = form.style.display === 'block' ? 'none' : 'block';
        }

        function updateAddress(event, addressType) {
            event.preventDefault();
            const newAddress = document.getElementById('new_' + addressType + '_address').value;
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById(addressType + '_address_content').innerText = xhr.responseText;
                    toggleEditForm(addressType);
                }
            };
            xhr.send("address_type=" + addressType + "&new_address=" + encodeURIComponent(newAddress));
        }

        function copyDeliveryAddress() {
            if (document.getElementById('same_as_delivery').checked) {
                const deliveryAddress = document.getElementById('new_delivery_address').value;
                document.getElementById('new_billing_address').value = deliveryAddress;
            } else {
                document.getElementById('new_billing_address').value = '';
            }
        }
    </script>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<!-- Bootstrap JS Bundle with Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
