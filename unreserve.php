<!--
This PHP file is accessed when the 'Uneserve' button is pressed in reservations.php. It removes the
reservation from the Reservations table, and updates the Reserved column in the Books table to be 'N'.
It then redirects back to the page it came from.
-->

<?php
    session_start();

    // If a book was attempted to be unreserved
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        // Connect to database
        require_once "database.php";

        $i = $conn->real_escape_string($_POST['isbn']);
        $r = $_POST['reserved'];
        $u = $_SESSION['current-user'];

        // Change reserved status to N
        $sql = "UPDATE books SET Reserved = 'N' WHERE ISBN = '$i'";

        // Delete the reservation from the table
        $sql2 = "DELETE FROM reservations WHERE ISBN = '$i'";

        
        $conn->query($sql);
        $conn->query($sql2);

        // Redirect back to reservations
        header("Location: reservations.php?page=" . $_SESSION['page']);
        exit();
    }
?>