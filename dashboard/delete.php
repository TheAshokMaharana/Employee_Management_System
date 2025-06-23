<?php 
    include("../db.php");

    if (isset($_GET['delete'])) {
    
        $id = $_GET['delete'];  

        $sql = $conn->prepare("delete from employees where id = ?");

        $sql->bind_param("i", $id);

        if ($sql->execute()) {
            header("Location:employee_mng.php");
        } else {
            header("Location:../auth/login.php");    
        }
        
    } else {
        header("Location:../auth/login.php");
    }
    
?>