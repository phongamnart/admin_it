<?php 
session_start();
isset( $_SESSION['user_name'] ) ? $user_name = $_SESSION['user_name'] : $user_name = "";

include("config/app.php");

$current_page = "Home";
?>