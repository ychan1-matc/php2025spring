<?php
require_once('pagetitle.php');
$page_title = SL_ADD_PAGE;

require_once('authorizeaccess.php');
require_once('dbconnection.php');
require_once('diarylistingfileconstants.php');
require_once('diaryimageutil.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$title = $entry = "";
$display_form = true;

if (isset($_POST['add_diary_submission'], $_POST['diary_title'], $_POST['diary_entry'])) {
    $title = trim($_POST['diary_title']);
    $entry = trim($_POST['diary_entry']);

    $file_error_message = validateDiaryImageFile();

    if (empty($file_error_message)) {
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or trigger_error('Error connecting to MySQL: ' . mysqli_connect_error(), E_USER_ERROR);

        $user_id = $_SESSION['user_id'];
        $diary_image_path = addDiaryImageFileReturnPathLocation();

        $query = "INSERT INTO diary (user_id, title, entry, image, last_modified) VALUES (?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($dbc, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'isss', $user_id, $title, $entry, $diary_image_path);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_stmt_close($stmt);
                mysqli_close($dbc);
                header('Location: index.php');
                exit;
            } else {
                echo "<div class='alert alert-danger mt-3'>Failed to add diary entry. Please try again.</div>";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "<div class='alert alert-danger mt-3'>Error preparing statement: " . mysqli_error($dbc) . "</div>";
        }

        mysqli_close($dbc);
    } else {
        echo "<div class='alert alert-warning mt-3'>Error: $file_error_message</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <script src="https://cdn.tiny.cloud/1/0mj8938plg4piugblwezujxncksix0uz3lcdvfacdy26x30f/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: '#diary_entry',
        height: 300,
        menubar: false,
        plugins: 'link image code lists',
        toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | code'
      });
    </script>
</head>
<body>
<?php include('navmenu.php'); ?>

<div class="container mt-4">
    <h1><?= $page_title ?></h1>

    <?php if ($display_form): ?>
        <form enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
            <div class="form-group">
                <label>Diary Title</label>
                <input type="text" class="form-control" name="diary_title" value="<?= htmlspecialchars($title) ?>" required>
            </div>

            <div class="form-group">
                <label>Diary Entry</label>
                <textarea id="diary_entry" name="diary_entry" class="form-control" rows="10" required><?= htmlspecialchars($entry) ?></textarea>
            </div>

            <div class="form-group">
                <label>Optional Image</label>
                <input type="file" class="form-control-file" name="image_file">
            </div>

            <button class="btn btn-primary" type="submit" name="add_diary_submission">Add Diary</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
