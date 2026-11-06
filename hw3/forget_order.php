<?php
session_start(); //resume the current session so it can be cleared

//clear the order variables before destroying the session
unset($_SESSION['dessert'], $_SESSION['drink'], $_SESSION['drinkSize']);
session_destroy();

//back to a clean form
header('Location: index.php');
exit;
