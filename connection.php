<?php
$conn = mysqli_connect("localhost", "root", "", "test");

if($conn->connect_error){
    die("connection failed: ". $conn->connect_error);
}

// $queryTampil = "select * from user_data where username = '" . $_SESSION["username"] . "' order by id desc";
?>