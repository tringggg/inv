<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

<?php
require('db.php');
session_start();

// If form submitted, check login
if (isset($_POST['username'])) {
    // Remove backslashes and escape special characters
    $username = stripslashes($_REQUEST['username']);
    $username = mysqli_real_escape_string($con, $username);

    $password = stripslashes($_REQUEST['password']);
    $password = mysqli_real_escape_string($con, $password);

    // Check if user exists in database
    $query = "SELECT * FROM `users` WHERE Username='$username' AND Password='" . md5($password) . "'";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    $rows = mysqli_num_rows($result);

    if ($rows == 1) {
        $_SESSION['username'] = $username;
        // Redirect user to index.php
        header("Location: index.php");
        exit();
    } else {
        echo "<div class='form'>
                <h3>Username or password is incorrect.</h3>
                <br/>Click here to <a href='login.php'>Login</a>
              </div>";
    }
} else {
?>

<div class="form">
    <h1>Log In</h1>
    <form action="" method="post" name="login">
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Password" required />
        <input name="submit" type="submit" value="Login" />
    </form>
    <p>Not registered yet? <a href="registration.php">Register Here</a></p>
</div>

<?php
}
?>

</body>
</html>
