<!--
This is a simple PHP file that destroys the session and redirects to the login page when the 'Log out' button
is pressed on the homepage
-->

<?php
    session_start();
    session_destroy();
    header("Location: login.php"); // Redirect to the login page after logout
    exit();
?>