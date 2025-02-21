<?php
session_start();
require('db/config.php');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['product_id']) && isset($data['quantity'])) {
    $product_id = mysqli_real_escape_string($con, $data['product_id']);
    $quantity = intval($data['quantity']);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

        // Check if the product ID already exists in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Add the new quantity to the existing quantity
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        // Set the quantity if the product is not in the cart
        $_SESSION['cart'][$product_id] = $quantity;
    }

    $cartTotal = array_sum($_SESSION['cart']);

    echo json_encode(['success' => true, 'cartTotal' => $cartTotal]);
} else {
    echo json_encode(['success' => false]);
}