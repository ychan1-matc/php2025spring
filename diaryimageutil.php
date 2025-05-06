<?php
require_once 'diarylistingfileconstants.php';

/**
 * Validates the uploaded diary image file.
 * Returns an error message string, or an empty string if valid or no file uploaded.
 */
function validateDiaryImageFile()
{
    $error_message = "";

    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {

        $allowed_types = ['image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'];

        if ($_FILES['image_file']['size'] > SL_MAX_FILE_SIZE) {
            $error_message = "The diary image file must be less than " . SL_MAX_FILE_SIZE . " bytes.";
        }

        $image_type = $_FILES['image_file']['type'];
        if (!in_array($image_type, $allowed_types)) {
            $error_message .= ($error_message ? " " : "");
            $error_message .= "The diary image must be of type JPG, PNG, or GIF.";
        }

        // Optional: Validate actual image content
        if (!@getimagesize($_FILES['image_file']['tmp_name'])) {
            $error_message .= ($error_message ? " " : "");
            $error_message .= "The uploaded file is not a valid image.";
        }

    } elseif (isset($_FILES['image_file']) && $_FILES['image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $error_message = "Error uploading diary image file.";
    }

    return $error_message;
}

/**
 * Processes and saves the uploaded image file.
 * Returns the relative path of the saved file, or null if no file uploaded.
 */
function addDiaryImageFileReturnPathLocation()
{
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $file_name = uniqid('diary_', true) . '_' . basename($_FILES['image_file']['name']);
        $target_path = SL_UPLOAD_PATH . $file_name;

        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_path)) {
            return $target_path;
        }
    }

    return null;
}

/**
 * Removes a previously uploaded diary image file.
 */
function removeDiaryImageFile($file_path)
{
    if (file_exists($file_path)) {
        @unlink($file_path);
    }
}
?>
