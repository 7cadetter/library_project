<!--
This page is accessed when the user clicks the 'Check Reservations' button on the homepage. It will display
all the reservations that the user has made, with 5 books being shown on each page. The elements are made
dynamically from PHP after being retrieved from the database. The user can unreserve a book which will
redirect to unreserve.php and remove it from the page. If there are more than 5 reservations made, the
user can switch to different pages to view more.
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <title>Reservations</title>
</head>
<body>
    <div id="headbar">
        <span class="title" id="headtitle">Reservations</span>

        <a href="homepage.php">
            <button type="button" id="home">Back to home</button>
        </a>

        <?php
            // Username will appear on screen
            session_start();
            if (isset($_SESSION['current-user']))
            {
                echo "<p class=\"current-user\">{$_SESSION['current-user']}</p>";
            }
            else {
                echo "<p class=\"current-user\">Not logged in</p>";
            }
        ?>

    </div>

    <div class="content">

        <?php
        // Connect to database
        require_once "database.php";

        $results_per_page = 5;
        // If page has been changed, assign page number. If not, assign 1
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $_SESSION['page'] = $page;

        // How many results have been shown already
        $offset = ($page - 1) * $results_per_page;

        /* Select results from database where the user who made the reservation
        is the same as the current user */
        $sql = "SELECT * FROM reservations r LEFT JOIN books b USING (ISBN)
                WHERE r.Username = '" . $_SESSION['current-user'] . "' LIMIT $results_per_page OFFSET $offset";
        $result = $conn->query($sql);

        // Show all results for the query
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Redirects to unreserve.php when form is submitted
                echo "<form method=\"POST\" action=\"unreserve.php\">";
                echo "<div class=\"result\">";
                echo "<span id=\"book-title\">" . $row['BookTitle'] . "</span><br>";
                echo "<span id=\"book-author\">" . $row['Author'] . "</span><br>";
                // Send ISBN, reserved status, and username to unreserve.php when submitting form
                echo "<input type='hidden' name='isbn' value='" . $row['ISBN'] . "'>";
                echo "<input type='hidden' name='reserved' value='" . $row['Reserved'] . "'>";
                echo "<input type='hidden' name='username' value='" . $row['Username'] . "'>";
                echo "<input name=\"unreserve\" type=\"submit\" id=\"unreserve\" value=\"Unreserve\">";
                echo "</div>";
                echo "</form>";
            }

            // Find how many results there are for the query
            $count_sql = "SELECT COUNT(*) AS total FROM reservations r LEFT JOIN books b USING (ISBN)
                        WHERE r.Username = '" . $_SESSION['current-user'] . "'";
            $count_result = $conn->query($count_sql);
            $total = $count_result->fetch_assoc()['total'];

            // The number of pages (Total results divided by 5)
            $total_pages = ceil($total / $results_per_page);

            // Section for changing page
            echo "<div class='pages'>";
            for ($i = 1; $i <= $total_pages; $i++) {
                // If it's the current page
                if ($i == $page)
                {
                    echo "<span class='active'>$i</span>";
                }
                else {
                    // Make it a link if it's not the current page
                    echo "<a href='?page=$i' class='inactive'>$i</a>";
                }
            }
            echo "</div>";
        } else {
            // If the current page is empty, go back to previous page if it exists
            if ($page > 1)
            {
                header("Location: reservations.php?page=" . ($page - 1));
            }
            echo "<p class=\"none-found\">No reservations.</p>";
        }
        ?>
    </div>

    <div>
        <footer id="footer">
            <p id="foot-text">Aron Mooney</p>
        </footer>
    </div>

</body>
</html>