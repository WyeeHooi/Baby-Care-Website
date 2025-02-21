<?php
session_start();
require_once('db/config.php');

if (isset($_GET['id']) && isset($_GET['quantity'])) {
    $product_id = intval($_GET['id']);
    $quantity = intval($_GET['quantity']);

    // Fetch product stock from the database
    $stmt = $con->prepare("SELECT stock FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($stock);
    $stmt->fetch();
    $stmt->close();

    // Check if the product is already in the session cart and get the quantity
    $current_cart_quantity = isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id] : 0;

    // Check if the requested quantity plus the current cart quantity does not exceed the stock
    if ($stock > ($quantity + $current_cart_quantity)) {
        echo json_encode(['available' => true]);
    } else {
        echo json_encode(['available' => false]);
    }
}
?>

