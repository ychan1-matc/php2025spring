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
    $access = $_POST['access'];

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
<form method="post">
    Username: <input type="text" name="user_name" required><br>
    Password: <input type="password" name="password" required><br>
    Access: 
    <select name="access">
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select><br>
    <input type="submit" value="Sign Up">
</form>
</body>