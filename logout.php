<!-- Author: Alex Spalvieri
     ID: 200403578 -->
<?php
    session_start();
    if (isset($_SESSION['userid'])) {
        session_destroy();
        unset($_SESSION['userid']);
        $alert = "Logged out successfully!";
        require("index.php");
        exit();
    }
?>