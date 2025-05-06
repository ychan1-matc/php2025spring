<?php
require_once('pagetitle.php');
$page_title = isset($page_title) ? $page_title : "";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<html>
<head>
  <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
        integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
        crossorigin="anonymous">
  <title>Nav Menu</title>
</head>
<body>
  <nav class="navbar sticky-top navbar-expand-md navbar-dark" style="background-color: #569f32;">
    <a class="navbar-brand" href="index.php">
      <img src="resources/happy_face_icon.png" width="30" height="30" class="d-inline-block align-top" alt="">
      <?= SL_HOME_PAGE ?>
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
        aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-item nav-link<?= $page_title == SL_HOME_PAGE ? ' active' : '' ?>" href="index.php">Home</a>

        <?php if (isset($_SESSION['access_privileges']) && $_SESSION['access_privileges'] === 'admin'): ?>
          <a class="nav-item nav-link<?= $page_title == SL_ADMIN_PAGE ? ' active' : '' ?>" href="adminpanel.php">Admin Panel</a>
        <?php elseif (isset($_SESSION['access_privileges']) && $_SESSION['access_privileges'] === 'user'): ?>
          <a class="nav-item nav-link<?= $page_title == SL_ADD_PAGE ? ' active' : '' ?>" href="adddiary.php">Add Diary</a>
          <a class="nav-item nav-link<?= $page_title == SL_VIEW_PAGE ? ' active' : '' ?>" href="viewdiary.php">Random Diary</a>
        <?php endif; ?>

        <?php if (!isset($_SESSION['user_id'])): ?>
          <a class="nav-item nav-link<?= $page_title == SL_LOGIN_PAGE ? ' active' : '' ?>" href="login.php">Login</a>
          <a class="nav-item nav-link<?= $page_title == SL_SIGNUP_PAGE ? ' active' : '' ?>" href="signup.php">Sign Up</a>
        <?php else: ?>
          <a class="nav-item nav-link" href="logout.php">
            Logout (<?= htmlspecialchars($_SESSION['display_name']) ?>)
          </a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <!-- Bootstrap JS Dependency -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
        integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
        crossorigin="anonymous"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
        integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
        crossorigin="anonymous"></script>

</body>
</html>
