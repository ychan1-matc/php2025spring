<?php
require_once('pagetitle.php');
$page_title = SL_REMOVE_PAGE;

require_once('authorizeaccess.php');
require_once('dbconnection.php');
require_once('queryutil.php');
require_once('diaryimageutil.php');

$user_id = $_SESSION['user_id'];
$user_access = $_SESSION['access_privileges'];
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
</head>
<body>
<?php include('navmenu.php'); ?>
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h1>Remove a Diary Entry</h1>

            <?php
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                or trigger_error('Error connecting to MySQL server for ' . DB_NAME, E_USER_ERROR);

            // DELETE confirmed
            if (isset($_POST['delete_diary_submission'], $_POST['id'])) {
                $id = intval($_POST['id']);

                // First get the image path
                $query = "SELECT image FROM diary WHERE diary_id = ?";
                $result = parameterizedQuery($dbc, $query, 'i', $id);
                $row = mysqli_fetch_assoc($result);
                removediaryImageFile($row['image']);

                $query = "DELETE FROM diary WHERE diary_id = ?";
                $stmt = mysqli_prepare($dbc, $query);
                mysqli_stmt_bind_param($stmt, 'i', $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                header("Location: index.php");
                exit;

            } elseif (isset($_POST['do_not_delete_diary_submission'])) {
                header("Location: index.php");
                exit;

            } elseif (isset($_GET['id_to_delete'])) {
                $id = intval($_GET['id_to_delete']);

                $query = "SELECT * FROM diary WHERE diary_id = ?";
                $result = parameterizedQuery($dbc, $query, 'i', $id);

                if ($result && mysqli_num_rows($result) === 1):
                    $row = mysqli_fetch_assoc($result);

                    // Ownership or admin check
                    if ($row['user_id'] != $user_id && $user_access !== 'admin') {
                        echo "<div class='alert alert-danger'>You do not have permission to delete this diary.</div>";
                        exit;
                    }
            ?>
                <h3 class="text-danger">Are you sure you want to delete the following diary entry?</h3>
                <table class="table table-bordered">
                    <tr><th>Title</th><td><?= htmlspecialchars($row['title']) ?></td></tr>
                    <tr><th>Entry</th><td><?= nl2br(htmlspecialchars($row['entry'])) ?></td></tr>
                    <tr><th>Image</th>
                        <td>
                            <?php if (!empty($row['image'])): ?>
                                <img src="<?= htmlspecialchars($row['image']) ?>" class="img-thumbnail" style="max-height: 200px;" alt="Diary image">
                            <?php else: ?>
                                <em>No image</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr><th>Last Modified</th><td><?= htmlspecialchars($row['last_modified']) ?></td></tr>
                </table>

                <form method="POST" action="<?= $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group row">
                        <div class="col-sm-2">
                            <button class="btn btn-danger" type="submit" name="delete_diary_submission">Delete Diary</button>
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-secondary" type="submit" name="do_not_delete_diary_submission">Cancel</button>
                        </div>
                        <input type="hidden" name="id" value="<?= $id ?>">
                    </div>
                </form>

            <?php
                else:
                    echo "<div class='alert alert-warning'>Diary entry not found.</div>";
                endif;

            } else {
                header("Location: index.php");
                exit;
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
