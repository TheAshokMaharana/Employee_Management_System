<?php

    session_start();

    $conn = new mysqli("localhost","","","test");

    if (!$conn) {

        echo "<script>alert('Database Not Connected ". $conn->connect_error."');</script>";
        
    } 

?>