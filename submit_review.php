<?php
session_start();
require('db/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = mysqli_real_escape_string($con, $_POST['product_id']);
    $rating = mysqli_real_escape_string($con, $_POST['reviewRating']);
    $title = mysqli_real_escape_string($con, $_POST['reviewTitle']);
    $comment = mysqli_real_escape_string($con, $_POST['reviewText']);

    // Check if product exists
    $check_product_sql = "SELECT id FROM products WHERE id = '$product_id'";
    $result = mysqli_query($con, $check_product_sql);

    if (mysqli_num_rows($result) > 0) {
        // Insert review into database
        $insert_review_sql = "INSERT INTO product_reviews (product_id, user_id, rating, title, comment, created_at) 
                              VALUES ('$product_id', '$user_id', '$rating', '$title', '$comment', NOW())";

        if (mysqli_query($con, $insert_review_sql)) {
            header("Location: product_description.php?id=" . $product_id . "&review_success=true");
            exit();
        } else {
            echo "Error: " . $insert_review_sql . "<br>" . mysqli_error($con);
        }
    } else {
        echo "Error: Product does not exist.";
        exit();
    }
} else {
    // Redirect if not a POST request or user not logged in
    header("Location: login.php");
    exit();
}
?>
