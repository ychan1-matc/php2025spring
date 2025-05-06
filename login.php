<?php
//require_once('pagetitle.php');
//$page_title = SL_LOGIN_PAGE;

require_once('dbconnection.php');
require_once('queryutil.php');
session_start();

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or trigger_error('Error connecting to MySQL: ' . mysqli_connect_error(), E_USER_ERROR);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['user_name']);
    $password = $_POST['password'];

    $query = "SELECT id, password_hash, access_privileges FROM user_information WHERE user_name = ?";
    $result = parameterizedQuery($dbc, $query, 's', $username);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password_hash'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['access_privileges'] = $row['access_privileges'];
            header("Location: movielisting.php");
            exit;
        }
    }
    echo "Invalid login.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <!-- <title><?= $page_title?></title> -->
   <title>log-in</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
</head>
<body>
<!-- <?php include('navmenu.php'); ?> -->
<div class="container mt-5">
    <h2>Login</h2>
    <?php if (!empty($error_message)) : ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>
    <form method="post" class="form-group">
        <label>Username:</label>
        <input type="text" name="user_name" class="form-control" required>
        <label>Password:</label>
        <input type="password" name="password" class="form-control" required>
        <br>
        <input type="submit" value="Login" class="btn btn-primary">
    </form>
    <p>Create new account <a href='signup.php'>here</a>.</p>
</div>
</br>
<p class='nav-link'>Create new account <a href='signup.php'> HERE</a></p>
</body>