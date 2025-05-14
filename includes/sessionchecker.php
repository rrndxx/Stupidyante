<?php
session_start();

function adminCheck()
{
    if (!isset($_SESSION['first_name']) || $_SESSION['role'] !== 'admin') {
        header('Location: ../../login.php');
        exit();
    }
}

function studentCheck()
{
    if (!isset($_SESSION['first_name']) || $_SESSION['role'] !== 'student') {
        header('Location: ../../login.php');
        exit();
    }
}

?>