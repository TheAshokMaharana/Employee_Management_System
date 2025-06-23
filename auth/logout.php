<?php
    
    include("../db.php");

    session_destroy();

    header("Location:../auth/login.php");

?>