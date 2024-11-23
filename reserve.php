<!--
This PHP file is accessed when the 'Reserve' button is pressed in results.php. It updates the database to
add the user and book combination to the Reservations table, and updates the Reserved column in the Books table
to be 'Y'. It then redirects back to the page it came from.
-->

<?php
    session_start();

    // If a book was attempted to be reserved
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reserve']))
    {
        // Connect to database
        require_once "database.php";

        $i = $conn->real_escape_string($_POST['isbn']);
        $r = $_POST['reserved'];
        $u = $_SESSION['current-user'];

        // Change reserved status to Y
        $sql = "UPDATE books SET Reserved = 'Y' WHERE ISBN = '$i'";

        // Add the book and user into the reservations table
        $sql2 = "INSERT INTO reservations (ISBN, Username, ReservedDate) VALUES ('$i', '$u', '" . date('Y-m-d') . "')";

        $conn->query($sql);
        $conn->query($sql2);

        // Redirect back to results page, including the query in the URL
        header("Location: results.php?" . $_SESSION['query'] . "&page=" . $_SESSION['page']);
        exit();
    }
?>
