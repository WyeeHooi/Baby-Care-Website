<?php
require_once('db/config.php');

    // Check if user is logged in
    session_start();
    $user_logged_in = isset($_SESSION['user_id']);
    $user_id = $user_logged_in ? $_SESSION['user_id'] : null;
    if (!$user_logged_in) {
        // Store the message in the session
        $_SESSION['message'] = 'Please login to proceed to checkout.';
        // Redirect to login page
        header('Location: login.php');
        exit;
    }

$wallet_balance = 0;
$shipping_address = '';
$cart_items = [];
$total_amount = 0;

// Retrieve user wallet balance and shipping address if logged in
if ($user_logged_in) {
    // Wallet Balance
    $sql = "SELECT wallet FROM users WHERE id = $user_id";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $wallet_balance = $row['wallet'];
    }

    // Shipping Address
    $sql = "SELECT delivery_address FROM users WHERE id = $user_id";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $shipping_address = $row['delivery_address'];
    }

    // Cart Items
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $product_ids = array_keys($_SESSION['cart']);
        $in  = str_repeat('?,', count($product_ids) - 1) . '?';
        $sql = "SELECT id, name, price, image_url FROM products WHERE id IN ($in)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param(str_repeat('i', count($product_ids)), ...$product_ids);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $product_id = $row['id'];
            $quantity = $_SESSION['cart'][$product_id];
            $row['quantity'] = $quantity;
            $row['total_price'] = $row['price'] * $quantity;
            $total_amount += $row['total_price'];
            $cart_items[] = $row;
        }
        $stmt->close();
    }
}

$username = '';
$delivery_address = '';
$phone = '';
$email = '';

// Retrieve user info if logged in
if ($user_logged_in) {
    $sql = "SELECT username, email, phone, delivery_address FROM users WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $delivery_address = $row['delivery_address'];
        $phone = $row['phone'];
        $email = $row['email'];
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout | CuddleBug</title>
    <link rel="shortcut icon" href="images/icon.ico" type="image/x-icon">
    <?php include 'inc/links.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php include 'inc/header.php'; ?>
    <link rel="stylesheet" href="checkout.css">
</head>
<body class="bg-white"> 

<section class="container mx-auto max-w-7xl mt-8 px-4">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Review Your Order</h2>    
</section>


    <div class="d-flex flex-row mb-10">
        <div class="col-lg-6 py-4 px-5 shipping-box">
            <p class="text-xl text-pink-600 mb-3">Shipping Details</p>
            <div class="user-deliveryAddress mb-3">
                <p class="text-l font-bold text-gray-800 text-uppercase">Recipient Name:</p>
                <p><?php echo htmlspecialchars($username); ?></p>
                <p class="text-l font-bold text-gray-800 text-uppercase mt-3">Shipping Address:</p>
                <p><?php echo htmlspecialchars($delivery_address); ?></p>
                <p class="text-l font-bold text-gray-800 text-uppercase mt-3">Contact Number:</p>
                <p><?php echo htmlspecialchars($phone); ?></p>
                <p class="text-l font-bold text-gray-800 text-uppercase mt-3">Mail:</p>
                <p><?php echo htmlspecialchars($email); ?></p>
            </div>
            <hr>
            <div class="mt-3 delivery-policy">
                <p>Your order will be delivered at your place (shipping address provided).</p>

                <p class="mb-3 mt-4"><strong>Delivery SOP</strong></p>

                <ol style="list-style: decimal;padding-left:1rem;">
                    <li>Kindly note that the delivery time 10am - 6pm. We apologise that we cannot guarantee to fulfil your request if you indicated a specific delivery time.</li>
                    <li>Driver will call you upon arrival. Please ensure your phone isn’t on silent.</li>
                    <li>Waiting time: First 10 mins: Free of charge. After 10 mins: RM 2.00 is chargeable for every subsequent 5 minutes. Max. Waiting time : 20 mins.</li>
                    <li>An extra handling fees of RM10 will be charged for any failed delivery attempt.</li>
                    <li>RM5 surcharge applied for door to door service (applicable to Condo / Apartment / Flats/ High Rise Buildings only)</li>
                    <li>Kindly check the items upon receiving to prevent any possible mistake during delivery.</li>
                </ol>

                <p class="mb-3 mt-4"><strong>Delivery Delay</strong></p>

                <p>You might experience some slight delay on delivery due to traffic condition, we apologise and thank you for your understanding.</p>

                <p>Thank you once again and please feel free to contact our customer service.</p>

                <p>Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our privacy policy.</p>

            </div>
        </div>

        <div class="col-lg-6 px-5 order-box">
            <div class="bg-gray-50 p-4">
                <p class="text-xl text-gray-600 mb-3">Your Order</p>
                <div class="user-cartOrder">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="cart-item d-flex flex-row flex-nowrap py-2">
                            <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>" width="100">
                            <p class="px-3"><?php echo $item['name']; ?> <br ><span class="text-gray text-xs">Quantity: <?php echo $item['quantity']; ?></span></p>
                            <p class="px-2 text-nowrap">RM <?php echo number_format($item['total_price'], 2); ?></p>
                        </div><br />
                    <?php endforeach; ?>                    
                </div>
            </div>

            <div class="bg-white py-4">
                <div class="d-flex flex-row justify-content-between">
                    <p class="text-xl font-bold text-gray-800 text-uppercase lh-lg">Subtotal:</p>
                    <p class="text-xl lh-lg">RM <?php echo number_format($total_amount, 2); ?></p>
                </div>

                <div class="d-flex flex-row justify-content-between">
                    <p class="text-xl font-bold text-gray-800 text-uppercase lh-lg">Shipping Fee:</p>
                    <p class="text-xl lh-lg">RM 5.00</p>
                </div>

                <div class="d-flex flex-row justify-content-between">
                    <p class="text-xl font-bold text-gray-800 text-uppercase lh-lg">Total Amount:</p>
                    <p class="text-xl lh-lg">RM <span id="total_amount"><?php echo number_format($total_amount + 5, 2); ?></span></p>
                </div>
                
                <div class="d-flex flex-row justify-content-between">
                    <p class="text-xl font-bold text-gray-800 text-uppercase lh-lg">Wallet Balance:</p>
                    <p class="text-xl lh-lg">RM <?php echo number_format($wallet_balance, 2); ?></p>
                </div>    
        
                <div>
                    <div id="balance-message" class="text-red-500"></div>                    
                    <input type="hidden" id="wallet_balance" value="<?php echo $wallet_balance; ?>">
                    <button id="pay_now_button" class="btn-primary text-white px-4 py-2 rounded w-100" onclick="processPayment()">Place Order Now</button>
                </div>
            </div>
        </div>
    </div>



<?php include 'inc/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
        checkWalletBalance();
    });

    function checkWalletBalance() {
        var walletBalance = parseFloat(document.getElementById('wallet_balance').value);
        var totalAmount = parseFloat(document.getElementById('total_amount').innerText);

        if (walletBalance < totalAmount) {
            document.getElementById('balance-message').innerText = "Insufficient wallet balance, please pay as soon as possible after placing order.";
        } else {
            document.getElementById('balance-message').innerText = "";
        }
    }

        function processPayment() {
        var walletBalance = parseFloat(document.getElementById('wallet_balance').value);
        var totalAmount = parseFloat(document.getElementById('total_amount').innerText);

        var status = walletBalance >= totalAmount ? 'processing' : 'pending payment';
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "process_order.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    if (status === 'processing') {
                        alert('Order Placed successfully and payment processed.');
                    } else {
                        alert('Order Placed Successfully but Payment is Pending.');
                    }
                    window.location.href = 'myaccount.php';
                } else {
                    alert('Error placing order. Please try again.');
                }
            }
        };
        xhr.send("status=" + status + "&total_amount=" + totalAmount);
    }
    </script>


<!-- Bootstrap JS Bundle with Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<!-- Bootstrap JS Bundle with Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
