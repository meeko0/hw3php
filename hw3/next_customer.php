<?php
//U1 shared-kiosk handoff (lab prototype, PHP only)
//Forget Order clears the order, but the browser keeps sending the same PHPSESSID cookie;
//this also drops that cookie so the next customer starts a brand-new session
session_start();

unset($_SESSION['dessert'], $_SESSION['drink'], $_SESSION['drinkSize']);
session_destroy();

//expire the session cookie with the same path/domain it was set with
$cookie = session_get_cookie_params();
setcookie(session_name(), '', time() - 3600, $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly']);

header('Location: index.php?handoff=1');
exit;
