<?php
    session_start();    
    require_once '../controller/userController.php';
    UserController::login();
?>