<?php
    session_start();
    require_once '../controller/UserController.php';    
    UserController::logout();     
?>