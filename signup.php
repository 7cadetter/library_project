<!--
This page allows the user to create an account by filling each of the given input fields. The username must not
exist in the database already, the password field must match the password confirmation field, and the mobile
number must consist of only numbers. If these are all valid, an account will be entered into the database and
the user will be redirected to the homepage. If not, an error message will display.
-->

<?php
    session_start();

    // Connect to database
    require_once "database.php";

    // If all input fields are submitted
    if (
        $_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($_POST['username']) && 
        isset($_POST['password']) && 
        isset($_POST['password-conf']) &&
        isset($_POST['fname']) && 
        isset($_POST['surname']) && 
        isset($_POST['address1']) && 
        isset($_POST['address2']) && 
        isset($_POST['city']) && 
        isset($_POST['telephone']) && 
        isset($_POST['mobile'])
    ) {
        $u = $conn->real_escape_string(trim($_POST['username']));
        $p = $conn->real_escape_string(trim($_POST['password']));
        $pc = $conn->real_escape_string(trim($_POST['password-conf']));
        $f = $conn->real_escape_string(trim($_POST['fname']));
        $s = $conn->real_escape_string(trim($_POST['surname']));
        $a1 = $conn->real_escape_string(trim($_POST['address1']));
        $a2 = $conn->real_escape_string(trim($_POST['address2']));
        $c = $conn->real_escape_string(trim($_POST['city']));
        $t = $conn->real_escape_string(trim($_POST['telephone']));
        $m = $conn->real_escape_string(trim($_POST['mobile']));

        // Return 1 if username + password combination exists already
        $sql1 = "SELECT 1 FROM users WHERE Username = '$u'";
        $result = $conn->query($sql1);

        // If the combination does exist
        if ($result->num_rows > 0) {
            $_SESSION['invalid_user'] = 1;
        }

        // If the password confirmation doesn't match the password
        else if ($p != $pc)
        {
            $_SESSION['invalid_pass'] = 1;
        }

        // If the mobile number is not numeric
        else if (!preg_match('/^\d+$/', trim($_POST['mobile'])))
        {
            $_SESSION['invalid_phone'] = 1;
        }

        // If everything is OK
        else {

            $sql2 = "INSERT INTO users (username, password, firstname, surname, addressline1, addressline2, city, telephone, mobile) VALUES ('$u', '$p', '$f', '$s', '$a1', '$a2', '$c', '$t', '$m')";
            $conn->query($sql2);

        
            // Send to homepage if account creation is successsful
            header("Location: homepage.php");
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="box" id="signupbox">
        <p class="title" id="signuptitle">Sign Up</p>
        <?php
            // Error message if username is invalid
            if (isset($_SESSION['invalid_user']) && $_SESSION['invalid_user'] == 1)
            {
                echo '<span class="error">Username is taken</span>';
                $_SESSION['invalid_user'] = 0;
            }

            // Error message if password is invalid
            else if (isset($_SESSION['invalid_pass']) && $_SESSION['invalid_pass'] == 1)
            {
                echo '<span class="error">Passwords don\'t match</span>';
                $_SESSION['invalid_pass'] = 0;
            }

            // Error message if mobile number is invalid
            else if (isset($_SESSION['invalid_phone']) && $_SESSION['invalid_phone'] == 1)
            {
                echo '<span class="error">Phone number is invalid</span>';
                $_SESSION['invalid_phone'] = 0;
            }
        ?>
        
        <!-- The form containing all input fields -->
        <form method="post">
            <input name="username" class="inputbox2" id="username2" type="text" placeholder="Account name" maxlength="20" required>
            <input name="password" class="inputbox2" id="password2" type="password" placeholder="Password" maxlength="6" required>
            <input name="password-conf" class="inputbox2" id="password-conf" type="password" placeholder="Password (Confirm)" maxlength="6" required>
            <input name="fname" class="inputbox2" id="firstname" type="text" placeholder="First Name" required>
            <input name="surname" class="inputbox2" id="surname" type="text" placeholder="Surname" required>
            <input name="address1" class="inputbox2" id="address1" type="text" placeholder="Address Line 1" required>
            <input name="address2" class="inputbox2" id="address2" type="text" placeholder="Address Line 2" required>
            <input name="city" class="inputbox2" id="city" type="text" placeholder="City" required>
            <input name="telephone" class="inputbox2" id="telephone" type="text" placeholder="Telephone Number" maxlength="7" required>
            <input name="mobile" class="inputbox2" id="mobile" type="text" placeholder="Mobile Number" maxlength="10" required>
            <input type="submit" id="create" value="Create account">
        </form>

        <a class="switchlink" id="loginlink" href="login.php">Log in to an account</a>

    </div>

    <div>
    <footer id="footer">
        <p id="foot-text">Aron Mooney</p>
    </footer>
    </div>
    
</body>
</html>