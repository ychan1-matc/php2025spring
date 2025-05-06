<?php
require_once('dbconnection.php');
require_once('queryutil.php');
session_start();

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or trigger_error('Error connecting to MySQL: ' . mysqli_connect_error(), E_USER_ERROR);

$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['user_name']);
    $display_name = trim($_POST['display_name']);
    $password = $_POST['password'];
    $access = 'user';

    if (!empty($username) && !empty($display_name) && !empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO user(user_name, display_name, password_hash, access) VALUES (?, ?, ?, ?)";
        $result = parameterizedQuery($dbc, $query, 'ssss', $username, $display_name, $password_hash, $access);

        if ($result) {
            $success_message = "Signup successful. <a href='login.php'>Login here</a>";
        } else {
            $error_message = "Signup failed. Username may already exist.";
        }
    } else {
        $error_message = "Please fill all fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign-up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
</head>
<body>
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

        <label>Display Name:</label>
        <input type="text" name="display_name" class="form-control" required>

        <label>Password:</label>
        <input type="password" name="password" class="form-control" required>

        <br>
        <input type="submit" value="Sign Up" class="btn btn-primary">
    </form>
</div>
</body>
</html>
