<?php
require_once('pagetitle.php');
$page_title = SL_DETAILS_PAGE;

require_once('dbconnection.php');
require_once('queryutil.php');
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
          crossorigin="anonymous">
    <title><?= $page_title ?></title>
</head>
<body>
<?php include('navmenu.php'); ?>

<div class="card mt-4">
    <div class="card-body">
        <?php
        if (isset($_GET['id'])):
            $diary_id = intval($_GET['id']);

            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                or trigger_error('Error connecting to MySQL: ' . mysqli_connect_error(), E_USER_ERROR);

            $query = "SELECT d.title, d.entry, d.image, d.last_modified, u.display_name
                      FROM diary d JOIN user u ON d.user_id = u.user_id
                      WHERE d.diary_id = ?";
            $result = parameterizedQuery($dbc, $query, 'i', $diary_id);

            if ($result && mysqli_num_rows($result) === 1):
                $row = mysqli_fetch_assoc($result);
        ?>

        <h2><?= htmlspecialchars($row['title']) ?></h2>
        <p><em>By <?= htmlspecialchars($row['display_name']) ?> on <?= $row['last_modified'] ?></em></p>

        <?php if (!empty($row['image'])): ?>
            <img src="<?= htmlspecialchars($row['image']) ?>" class="img-fluid mb-3" style="max-height:300px;" alt="Diary image">
        <?php endif; ?>

        <div class="border p-3 bg-light">
            <?= $row['entry'] ?>
        </div>

        <?php else: ?>
            <h4>No diary details found.</h4>
        <?php endif; ?>
        <?php else: ?>
            <h4>No diary ID provided.</h4>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
