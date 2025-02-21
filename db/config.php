<?php

$con = mysqli_connect("localhost", "root", "", "cuddle_bug");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

?>