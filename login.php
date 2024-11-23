<!-- 
This page is the first one a user should see when accessing the website. It has two input fields where the
user can enter a username and a password. If they are a valid combination in the database, the user
succesfully logs in and they are redirected to the homepage. If not, an error message will be displayed.
Also, if a user has no account, they can use the link which redirects them to signup.php where they can
create an account.
-->

<?php
    session_start();

    // Connect to database
    require_once "database.php";

    // If the input fields are filled
    if (isset($_POST['username']) && isset($_POST['password']))
    {
        $u = $conn->real_escape_string($_POST['username']);
        $p = $conn->real_escape_string($_POST['password']);

        // Return 1 if a user with that username and password exists
        $sql = "SELECT 1 FROM users WHERE Username = '$u' AND Password = '$p'";
        $result = $conn->query($sql);

        //If there is a result
        if ($result->num_rows > 0) {
            // Make note of who is logged in and send to homepage if login is successful
            $_SESSION['current-user'] = $u;
            header("Location: homepage.php");
            exit();
        } else {
            // User doesn't exist
            $_SESSION['invalid_login'] = 1;
        }
    }
?>
        
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="box" id="loginbox">
        <p class="title" id="logintitle">Log In</p>

        <?php
            // If the account didn't exist when user tried to sign in
            if (isset($_SESSION['invalid_login']) && $_SESSION['invalid_login'] == 1)
            {
                // Print error message and reset invalid variable
                echo '<span id="login-error" class="error">Username or password is wrong</span>';
                $_SESSION['invalid_login'] = 0;
            }
        ?>

        <!-- The form for login -->
        <form method="post">
            <input name="username" class="inputbox1" id="username" type="text" placeholder="Account name" maxlength="20" required></input>
            <input name="password" class="inputbox1" id="password" type="password" placeholder="Password" maxlength="6" required></input>
            <input type="submit" id="create" value="Log in">
        </form>
        
        <a class="switchlink" id="signuplink" href="signup.php">Create an account</a>
    </div>

    <div>
        <footer id="footer">
            <p id="foot-text">Aron Mooney</p>
        </footer>
    </div>
</body>
</html>