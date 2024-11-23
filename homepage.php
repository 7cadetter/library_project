<!--
This page is the homepage of the library website, which allows the user to search for books in the database
(based on title / author or category), and check which reservations they have made. The user can also log out
of their account from this page.
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>
<body>
    <div id="headbar">
        <span class="title" id="headtitle">Library</span>


        <a href="logout.php">
            <button type="button" id="home">Log out</button>
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

    <!-- Box for search -->
    <div class="homebox" id="search">
        <span class="boxtitle">Search</span>

        <!-- Section for searching by title/author -->
        <div class="by" id="bytitle">
            <span class="bytext">Search by author or title</span>
            <!-- Inputting form will redirect to results page -->
            <form method="GET" action="results.php">
                <input name="search" type="text" id="searchbar" required>
                <input type="submit" id="searchsubmit" value="Search">
            </form>
        </div>

        <!-- Section for searching by category (dropdown) -->
        <div class="by" id="bycategory">
            <span class="bytext">Search by category</span>
            <!-- Inputting form will redirect to results page -->
            <form method="GET" action="results.php">
                <select name="category" id="category">
                    <?php
                    // Connect to database
                    require_once "database.php";

                    // List of all category names
                    $sql = "SELECT CategoryDescription FROM category";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Make a new option for each category (value should be lowercase)
                            echo "<option value='" . strtolower($row['CategoryDescription']) ."'>"
                            . $row['CategoryDescription'] . "</option>";
                        }
                    }

                    ?>
                </select>
                <input type="submit" id="searchsubmit" value="Search">
            </form>
        </div>
    </div>

    <!-- Box for checking reservations -->
    <button id="reservations" onclick="window.location.href='reservations.php'">Check Reservations</button>

    <div>
        <footer id="footer">
            <p id="foot-text">Aron Mooney</p>
        </footer>
    </div>

</body>
</html>