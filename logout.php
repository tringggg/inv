<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Registration</title>
<link rel = "stylesheet" type = "text/css" href = "styles.css">
</head>

<body>
<?php
require('db.php');

// If form submitted, insert values into the database
if (isset($_REQUEST['username'])) {
    // Remove backslashes and escape special characters
    $username = stripslashes($_REQUEST['username']);
    $username = mysqli_real_escape_string($con, $username);

    $email = stripslashes($_REQUEST['email']);
    $email = mysqli_real_escape_string($con, $email);

    $password = stripslashes($_REQUEST['password']);
    $password = mysqli_real_escape_string($con, $password);

    $trn_date = date("Y-m-d H:i:s");

    // ✅ Correct variable names and table columns
    $query = "INSERT INTO `users` (username, password, email, trn_date)
              VALUES ('$username', '" . md5($password) . "', '$email', '$trn_date')";

    $result = mysqli_query($con, $query);

    if ($result) {
        echo "<div class='form'>
                <h3>You are registered successfully.</h3>
                <br/>Click here to <a href='login.php'>Login</a>
              </div>";
    } else {
        echo "<div class='form'>
                <h3>⚠️ Error: Unable to register user.</h3>
                <p>" . mysqli_error($con) . "</p>
              </div>";
    }
} else {
?>

<div class="form">
    <h1>Registration</h1>
    <form name="registration" action="" method="post">
        <input type="text" name="username" placeholder="Username" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <input type="submit" name="submit" value="Register" />
    </form>
    <p>Already have an account? <a href="login.php">Login Here</a></p>
</div>

<?php } ?>
</body>
</html>
