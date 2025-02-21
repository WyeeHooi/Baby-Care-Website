<?php
require_once('db/config.php');
session_start();

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $status = $_POST['status'];
    $total_amount = $_POST['total_amount'];
    
    $con->begin_transaction();

    try {
        // Insert into orders table
        $sql = "INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ids", $user_id, $total_amount, $status);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Insert into order_items table
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("iii", $order_id, $product_id, $quantity);
            $stmt->execute();
            $stmt->close();

            // Update product stock
            $sql = "UPDATE products SET stock = stock - ? WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ii", $quantity, $product_id);
            $stmt->execute();
            $stmt->close();
        }

        

        // Update user's wallet balance if status is processing
        if ($status === 'processing') {
            $wallet_balance = $_SESSION['wallet_balance'];
            $new_balance = $wallet_balance - $total_amount;

            $sql = "UPDATE users SET wallet = ? WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("di", $new_balance, $user_id);
            $stmt->execute();
            $stmt->close();

            $_SESSION['wallet_balance'] = $new_balance;
        }

        // Commit the transaction
        $con->commit();
        
        // Clear the cart session variable
        unset($_SESSION['cart']);

        $response['success'] = true;
    } catch (Exception $e) {
        // Rollback the transaction if an error occurs
        $con->rollback();
        $response['error'] = $e->getMessage();
    }
}

echo json_encode($response);
?>
