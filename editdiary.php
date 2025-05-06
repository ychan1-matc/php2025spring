<?php
require_once('pagetitle.php');
$page_title = SL_EDIT_PAGE;

require_once('authorizeaccess.php');
require_once('dbconnection.php');
require_once('diaryimageutil.php');
require_once('diarylistingfileconstants.php');
require_once('queryutil.php');


$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or trigger_error('Error connecting to MySQL server for ' . DB_NAME, E_USER_ERROR);

$diary_title = '';
$diary_entry = '';
$current_diary_image = '';
$id_to_update = null;

// Handle POST submission first
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_diary_submission'])) {
    $id_to_update = intval($_POST['id_to_update']);
    $diary_title = trim($_POST['diary_title']);
    $diary_entry = trim($_POST['diary_entry']);

    $query = "SELECT image FROM diary WHERE diary_id = ?";
    $result = parameterizedQuery($dbc, $query, 'i', $id_to_update);
    $row = mysqli_fetch_assoc($result);
    $current_diary_image = $row['image'];

    // Handle image
    if (!empty($_FILES['image_file']['name'])) {
        $file_error_message = validateDiaryImageFile();
        if (empty($file_error_message)) {
            $new_diary_image = addDiaryImageFileReturnPathLocation();
            removediaryImageFile($current_diary_image);
        } else {
            echo "<p class='text-danger'>$file_error_message</p>";
            $new_diary_image = $current_diary_image;
        }
    } else {
        $new_diary_image = $current_diary_image;
    }

    // Update the diary
    $query = "UPDATE diary SET title = ?, entry = ?, image = ?, last_modified = NOW() WHERE diary_id = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'sssi', $diary_title, $diary_entry, $new_diary_image, $id_to_update);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: diarydetail.php?id=$id_to_update");
    exit;
}

// Otherwise, it's a GET request to load existing diary
if (isset($_GET['id'])) {
    $id_to_update = intval($_GET['id']);

    $query = "SELECT * FROM diary WHERE diary_id = ?";
    $result = parameterizedQuery($dbc, $query, 'i', $id_to_update);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $diary_title = $row['title'];
        $diary_entry = $row['entry'];
        $current_diary_image = $row['image'];
    } else {
        echo "<div class='alert alert-warning'>Diary not found.</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>No diary ID provided.</div>";
    exit;
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
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code'
        });
    </script>
</head>
<body>
<?php include('navmenu.php'); ?>
<div class="container mt-4">
    <h1><?= $page_title ?></h1>

    <form enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
        <div class="form-group">
            <label>Diary Title</label>
            <input type="text" class="form-control" name="diary_title" value="<?= htmlspecialchars($diary_title) ?>" required>
        </div>

        <div class="form-group">
            <label>Diary Entry</label>
            <textarea id="diary_entry" class="form-control" name="diary_entry" required><?= htmlspecialchars($diary_entry) ?></textarea>
        </div>

        <div class="form-group">
            <label>Current Image</label><br>
            <?php if (!empty($current_diary_image)): ?>
                <img src="<?= htmlspecialchars($current_diary_image) ?>" class="img-thumbnail" style="max-height: 200px;" alt="Diary image">
            <?php else: ?>
                <p>No image uploaded.</p>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Upload New Image (optional)</label>
            <input type="file" class="form-control-file" name="image_file">
        </div>

        <input type="hidden" name="id_to_update" value="<?= htmlspecialchars($id_to_update) ?>">
        <button class="btn btn-primary" type="submit" name="edit_diary_submission">Update Diary</button>
    </form>
</div>
</body>
</html>
