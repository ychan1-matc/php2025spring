<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($require_admin) && $require_admin && $_SESSION['access'] !== 'admin') {
    header("Location: unauthorizedaccess.php");
    exit;
}
?>
