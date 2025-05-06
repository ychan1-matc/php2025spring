<?php
require_once('pagetitle.php');
$page_title = SL_UNAUTHORIZED_ACCESS_PAGE;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $page_title ?></title>
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
</head>
<body>
<?php include('navmenu.php'); ?>
<div class="container mt-5">
    <div class="alert alert-danger text-center">
        <h1>Unauthorized Access</h1>
        <p>You do not have permission to view this page.</p>
        <p><a href="index.php" class="btn btn-primary">Return to Home</a></p>
    </div>
</div>
</body>
</html>

