<?php
require_once('pagetitle.php');
$page_title = SL_ADMIN_PAGE;

$require_admin = true;
require_once('authorizeaccess.php');
require_once('dbconnection.php');
require_once('queryutil.php');
?>

<!DOCTYPE html>
<html>
<head>
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
<?php include('navmenu.php'); ?>
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h1><?= $page_title ?></h1>
            <p><a href='index.php'>Back to Home</a></p>
            <p><a href='adddiary.php'>Add New Diary</a></p>

            <?php
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                or trigger_error('Error connecting to MySQL server for ' . DB_NAME, E_USER_ERROR);

            $query = "SELECT d.diary_id, d.title, u.display_name 
                      FROM diary d 
                      JOIN user u ON d.user_id = u.user_id 
                      ORDER BY d.diary_id DESC";

            $result = mysqli_query($dbc, $query)
                or trigger_error('Error querying diary table', E_USER_ERROR);

            if (mysqli_num_rows($result) > 0):
            ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['diary_id']) ?></td>
                            <td><a href="diarydetail.php?id=<?= $row['diary_id'] ?>"><?= htmlspecialchars($row['title']) ?></a></td>
                            <td><?= htmlspecialchars($row['display_name']) ?></td>
                            <td>
                                <a href="editdiary.php?id=<?= $row['diary_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="deletediary.php?id_to_delete=<?= $row['diary_id'] ?>" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <h3>No Diary Entries Found</h3>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
