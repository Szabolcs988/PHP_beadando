<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


// index.php
session_start();
$page = $_GET['page'] ?? 'home';

// A header.php csak egyszer hívódik meg az oldal tetején
include 'views/header.php';

switch ($page) {
    case 'Home':
        include 'views/home.php';
        break;
    case 'Login':
        include 'views/login.php';
        break;
    case 'Register':
        include 'views/register.php';
        break;
    case 'Mnb':
        include 'views/mnb.php';
        break;
    case 'Rest':
	unset($_SESSION['rest_result']);
        include 'views/rest.php';
        break;
    case 'Pdf':
        include 'views/pdf.php';
        break;
    case 'Soap':
        include 'views/soap.php';
        break;
    case 'logout':
        session_destroy();
        header('Location: index.php?page=Home');
        exit;
        break;
    default:
        include 'views/home.php';
        break;
}


?>