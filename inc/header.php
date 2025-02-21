<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
    $welcomeMessage = "Welcome, $username!";
    $logoutButton = '<a href="logout.php" class="btn btn-outline-dark shadow-none">Logout</a>';
    $myAccount = '<a href="myaccount.php" class="login-link">My Account</a>';
    $activeOrder = '<a href="myaccount.php" class="login-link">Active Order</a>';
    $orderHistory = '<a href="myaccount.php" class="login-link">Order History</a>';

} else {
    $welcomeMessage = "Welcome, Guest!";
    $loginButton = '<a href="login.php" class="btn btn-outline-dark shadow-none me-lg-3 me-2 login-button">Login</a>';
    $registerButton = '<a href="register.php" class="btn btn-outline-dark shadow-none register-button">Register</a>';
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    // Database connection (replace with your own connection details)
    $conn = new mysqli('localhost', 'root', '', 'cuddle_bug');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch wallet amount
    $stmt = $conn->prepare("SELECT wallet FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($wallet);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
} else {
    $wallet = 0.00;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'inc/links.php'; ?>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato&family=Overpass:ital,wght@0,100..900;1,100..900&display=swap');

        body {
            font-family: "Overpass", sans-serif;
            margin: 0;
            padding: 0;
        }

        .user-info {
            display: none;
            position: absolute;
            background: white;
            border: 1px solid #ddd;
            padding: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 200px;
        }

        .user-icon:hover .user-info,
        .user-info:hover {
            display: block;
        }

        .user-info {
            top: 90%;
            right: 10%;
            width: 270px;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
        }

        .user-info p {
            margin: 5px 0;
        }

        .login-button,
        .register-button,
        .user-actions a {
            display: inline-block;
            padding: 8px 16px;
            background-color: #E91E63;
            border: 1px solid #E91E63;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .login-button:hover,
        .register-button:hover,
        .user-actions a:hover {
            background-color: #000;
        }

        .icon {
            font-size: 1.5rem;
            margin-left: 1rem;
            color: #000;
        }

        .icon:hover {
            color: #E91E63;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .user-welcome,
        .user-guest {
            max-width: 300px;
        }

        .user-welcome p,
        .user-guest p {
            margin-bottom: 10px;
        }

        .user-actions,
        .user-buttons {
            margin-top: 15px;
        }

        .user-actions a,
        .user-buttons a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #E91E63;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .user-actions a:hover,
        .user-buttons a:hover {
            background-color: #000;
        }

        .fa-shopping-cart[data-count]:after {
            position: absolute;
            background: #E91E63;
            right: -20px;
            top: -15px;
            content: attr(data-count);
            font-size: 10px;
            padding: .6em .6em;
            border-radius: 50%;
            line-height: .8em;
            color: white;
            text-align: center;
            min-width: 1em;
            font-weight: bold;
        }

        .icon {
            position: relative;
            display: inline-block;
        }

        .user-icon>a:hover {
            text-decoration: none;
            color: #E91E63;
        }

        .fa-shopping-cart {
            position: relative;
        }

        .fa-shopping-cart:hover,
        .fa-wallet:hover {
            color: #E91E63;
        }

        .login-link {
            color: black;
            padding: 10px 5px;
            box-shadow: inset 0 calc(-1 * var(--bs-accordion-border-width)) 0 var(--bs-accordion-border-color);
        }
    </style>
</head>

<body>
    <nav class="bg-white py-4 relative z-20 mx-auto max-w-7xl">
        <div class="container mx-auto flex justify-between items-center px-4">
            <a href="index.php">
                <img src="images/logo.png" alt="CuddleBug Logo" class="h-14">
            </a>
            <ul class="flex space-x-5 mx-auto menu-items">
                <?php
                $menu_items = [
                    'Home' => 'index.php',
                    'Shop' => 'shop.php',
                    'Contact' => 'contact.php',
                    'FAQ' => 'faq.php'
                ];
                foreach ($menu_items as $item => $link) {
                    echo "<li><a href='$link' class='text-gray-700 font-semibold hover:text-pink-500' style='text-decoration: none;'>$item</a></li>";
                }
                ?>
            </ul>
            <div class="user-icon">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="myaccount.php"><i class="fas fa-user" style="font-size: 20px;"></i></a>
                <?php else: ?>
                    <a href="login.php"><i class="fas fa-user" style="font-size: 20px;"></i></a>
                <?php endif; ?>
                <div class="user-info">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="user-welcome">
                            <p class="text-lg font-semibold text-gray-800">Welcome,
                                <?php echo htmlspecialchars($username); ?>!</p>
                        </div>
                        <div class="user-actions mt-4">
                            <?php echo $logoutButton; ?>
                        </div>
                    <?php else: ?>
                        <div class="user-guest">
                            <p class="text-lg text-gray-800">You are in guest mode.</p>
                            <div class="user-buttons mt-4">
                                <?php echo $loginButton; ?>
                                <?php echo $registerButton; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="user-icon">
                <a href="cart.php"><i class="fas fa-shopping-cart" style="font-size: 20px; padding-left: 30px;"
                        data-count="<?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>"></i></a>
            </div>
            <div class="user-icon">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="#">
                        <i class="fas fa-wallet" style="font-size: 20px; padding-left: 30px;padding-right:5px;"></i>
                        <span class="text-black"><?php echo number_format($wallet, 2); ?></span>
                    </a>
                <?php endif; ?>
            </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userIcon = document.querySelector('.user-icon');
            const userInfo = userIcon.querySelector('.user-info');

            userInfo.style.display = 'none';

            userIcon.addEventListener('mouseenter', function () {
                userInfo.style.display = 'block';
            });

            userIcon.addEventListener('mouseleave', function () {
                setTimeout(function () {
                    if (!userInfo.matches(':hover')) {
                        userInfo.style.display = 'none';
                    }
                }, 100);
            });

            userInfo.addEventListener('mouseleave', function () {
                userInfo.style.display = 'none';
            });

            userInfo.addEventListener('mouseenter', function () {
                userInfo.style.display = 'block';
            });
        });
    </script>
</body>

</html>