<?php
require_once('db/config.php');

if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    
    $query = "INSERT INTO subscriptions (email) VALUES ('$email')";
    $result = mysqli_query($con, $query);

    if ($result) {
        $response = array(
            'status' => 'success',
            'message' => 'Subscription successful!'
        );
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Error: ' . mysqli_error($con)
        );
    }

    echo json_encode($response);
}

mysqli_close($con);
?>
