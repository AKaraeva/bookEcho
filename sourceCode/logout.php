<?php 
session_start();

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Destroy the session and redirect to the login page
    session_destroy();
    // Remove the persistent session cookie by setting an expiration time in the past
    setcookie("persistent_cookie", "true", time() - 3600, "/");
    header("Location: login.php");
    exit();
} else {
    // If the "Logout" link was not clicked, you can handle other actions here
    // For example, change the button name from "Login" to "Logout" without destroying the session
    // ...
}
?>
