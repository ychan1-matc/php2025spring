<?php
//require_once('pagetitle.php');
//$page_title = SL_SIGNUP_PAGE;

require_once('dbconnection.php');
require_once('queryutil.php');
session_start();

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or trigger_error('Error connecting to MySQL: ' . mysqli_connect_error(), E_USER_ERROR);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['user_name']);
    $password = $_POST['password'];
    $access = 'user';

    if (!empty($username) && !empty($password) && !empty($access)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO user_information (user_name, password_hash, access, date_created) VALUES (?, ?, ?, NOW())";
        $result = parameterizedQuery($dbc, $query, 'sss', $username, $password_hash, $access);

        if ($result) {
            echo "Signup successful. <a href='login.php'>Login here</a>";
        } else {
            echo "Signup failed.";
        }
    } else {
        echo "Please fill all fields.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <!-- <title><?= $page_title?></title> -->
    <title>Sign-up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
</head>
<body>
<!-- <?php include('navmenu.php'); ?> -->
<div class="container mt-5">
    <h2>Sign Up</h2>

    <?php if (!empty($error_message)) : ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <?php if (!empty($success_message)) : ?>
        <div class="alert alert-success"><?= $success_message ?></div>
    <?php endif; ?>

    <form method="post" class="form-group">
        <label>Username:</label>
        <input type="text" name="user_name" class="form-control" required>

        <label>Password:</label>
        <input type="password" name="password" class="form-control" required>
        
        <br>
        <input type="submit" value="Sign Up" class="btn btn-primary">
    </form>
</div>
</form>
</body>