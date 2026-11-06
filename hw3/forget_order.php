<?php
session_start(); //resume the current session so it can be cleared

//remove only the order keys, then end the session
unset($_SESSION['dessert'], $_SESSION['drink'], $_SESSION['drinkSize']);
session_destroy();

header('Location: index.php');
exit;
