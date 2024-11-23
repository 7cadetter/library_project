<!--
This page is accessed when a user searches for books on the homepage. It will display all the books in the
database that match the search query the user has selected (title/author or category). Elements are created
dynamically with PHP based on data retrieved from the database. If a book is not already reserved, the user
can reserve it, which will redirect to reserve.php. If there are more than 5 results for the query, the
user can switch to different pages to view more.
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>
<body>

    <div id="headbar">
        <span class="title" id="headtitle">Search Results</span>

        <a href="homepage.php">
            <button type="button" id="home">Back to home</button>
        </a>

        <?php
            session_start();
            // Username will appear on screen
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
        // If page has been changed, assign page number to page. If not, assign 1
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $_SESSION['page'] = $page;

        // How many results have been shown already
        $offset = ($page - 1) * $results_per_page;

        // If the search input was filled
        if (isset($_GET['search']))
        {
            $query = $conn->real_escape_string($_GET['search']);
            // Makes note of the search query in the URL
            $_SESSION['query'] = "search=$query";

            /* Select results from database where book title or author
            has the search query in it */
            $sql = "SELECT * FROM books b LEFT JOIN reservations r USING (ISBN) 
                    WHERE b.BookTitle LIKE '%$query%' OR b.Author LIKE '%$query%' 
                    LIMIT $results_per_page OFFSET $offset";

            // Find how many results there are for the query
            $count_sql = "SELECT COUNT(*) AS total FROM books b 
            WHERE b.BookTitle LIKE '%$query%' OR b.Author LIKE '%$query%'";
        }
        // If the category input was filled
        else if (isset($_GET['category'])) {

            $query = $conn->real_escape_string($_GET['category']);
            // Makes note of the search query in the URL
            $_SESSION['query'] = "category=$query";

            // Find the category code related to the category description
            $cat_code_result = $conn->query("SELECT CategoryID FROM category WHERE CategoryDescription = '$query'");
            $cat_code_row = $cat_code_result->fetch_assoc();
            $cat_code = $cat_code_row['CategoryID'];

            // Select results from database where book has the category code
            $sql = "SELECT * FROM books b LEFT JOIN reservations r USING (ISBN)
                    WHERE b.Category = '$cat_code' LIMIT $results_per_page OFFSET $offset";

            // Find how many results there are for the query
            $count_sql = "SELECT COUNT(*) AS total FROM books b LEFT JOIN reservations r USING (ISBN)
                          WHERE b.Category = '$cat_code'";
        }

        $result = $conn->query($sql);

        // Show all results for the query
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Redirects to reserve.php when form is submitted
                echo "<form method=\"POST\" action=\"reserve.php\">";
                echo "<div class=\"result\">";
                echo "<span id=\"book-title\">" . $row['BookTitle'] . "</span><br>";
                echo "<span id=\"book-author\">" . $row['Author'] . "</span><br>";
                // Send ISBN, reserved status, and username to unreserve.php when submitting form
                echo "<input type='hidden' name='isbn' value='" . $row['ISBN'] . "'>";
                echo "<input type='hidden' name='reserved' value='" . $row['Reserved'] . "'>";
                echo "<input type='hidden' name='username' value='" . $row['Username'] . "'>";
                // If the reservation was made by the current user
                if ($row['Reserved'] == 'Y' && $row['Username'] == $_SESSION['current-user']) {
                    echo "<p class=\"status\">You have reserved this book</p>";
                }
                // If the reservation was not made by the current user
                else if ($row['Reserved'] == 'Y' && $row['Username'] != $_SESSION['current-user']){
                    echo "<p class=\"status\">This book has already been reserved</p>";
                }
                // If there was no reservation made
                else {
                    echo "<input name=\"reserve\" type=\"submit\" id=\"reserve\" value=\"Reserve book\">";
                }
                echo "</div>";
                echo "</form>";

            }
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
            echo "<p class=\"none-found\">No results found for '$query'.</p>";
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