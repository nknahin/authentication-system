<?php

ob_start();
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include 'confiq.php';
define('BASE_URL', 'http://localhost/practice/');
            
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <div class="wrapper">
        <div class="container">

            <div class="nav">
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>index.php">Home</a></li>
                    
                    <?php if(!isset($_SESSION['user'])): ?>
                    <li><a href="<?php echo BASE_URL; ?>registration.php">Registration</a></li>
                    <li><a href="<?php echo BASE_URL; ?>login.php">Login</a></li>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['user'])): ?>
                    <li><a href="<?php echo BASE_URL; ?>dashboard.php">Dashboard</a></li>
                    <li><a href="<?php echo BASE_URL; ?>logout.php">Logout</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="main">