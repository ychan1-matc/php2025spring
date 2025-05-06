<?php
// require_once('pagetitle.php');
// $page_title = SL_HOME_PAGE;

require_once('dbconnection.php');
require_once('queryutil.php');
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?></title>
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
          integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
          crossorigin="anonymous">
    <link rel="stylesheet"
          href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
          integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf"
          crossorigin="anonymous">
</head>
<body>
<!-- <?php include('navmenu.php'); ?> -->
<div class="card mt-4">
  <div class="card-body">
    <h1>Welcome to the Diary Exchange</h1>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p class="lead">
            Hello, <strong><?= htmlspecialchars($_SESSION['display_name']) ?></strong>!
        </p>

        <div class="mb-3">
            <?php if ($_SESSION['access_privileges'] === 'admin'): ?>
                <a href="admin_panel.php" class="btn btn-danger">Go to Admin Panel</a>
            <?php else: ?>
                <a href="upload_diary.php" class="btn btn-primary">Upload a New Diary</a>
                <a href="view_diary.php" class="btn btn-info">Read a Random Diary</a>
            <?php endif; ?>
            <a href="logout.php" class="btn btn-outline-dark float-right">Logout</a>
        </div>
    <?php else: ?>
        <p class="nav-link">Please <a href="login.php">log in</a> or <a href="signup.php">sign up</a> to use the Diary Exchange.</p>
    <?php endif; ?>

    <?php
        if (isset($_SESSION['user_id']) && $_SESSION['access_privileges'] !== 'admin') {
          $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
              or trigger_error('Error connecting to MySQL server for ' . DB_NAME, E_USER_ERROR);
      
          $user_id = $_SESSION['user_id'];
      
          $query = "SELECT diary_id, title FROM diary WHERE user_id = ? ORDER BY last_modified DESC";
          $result = parameterizedQuery($dbc, $query, 'i', $user_id);
      
          if ($result && mysqli_num_rows($result) > 0):
      ?>
          <table class="table table-striped mt-3">
              <thead>
              <tr>
                  <th scope="col">Diary Title</th>
                  <th scope="col">Actions</th>
              </tr>
              </thead>
              <tbody>
              <?php while ($row = mysqli_fetch_assoc($result)): ?>
                  <tr>
                      <td>
                          <a class="nav-link" href="diarydetail.php?id=<?= htmlspecialchars($row['diary_id']) ?>">
                              <?= htmlspecialchars($row['title']) ?>
                          </a>
                      </td>
                      <td>
                          <a class="nav-link d-inline" href="edit_diary.php?id=<?= htmlspecialchars($row['diary_id']) ?>">
                              <i class="fas fa-edit"></i>
                          </a>
                          <a class="nav-link d-inline" href="delete_diary.php?id_to_delete=<?= htmlspecialchars($row['diary_id']) ?>">
                              <i class="fas fa-trash-alt"></i>
                          </a>
                      </td>
                  </tr>
              <?php endwhile; ?>
              </tbody>
          </table>
      <?php else: ?>
          <h5 class="mt-4">You haven’t written any diary entries yet.</h5>
      <?php endif; } ?>      
  </div>
</div>
</body>
</html>