<?php
require_once 'diarylistingfileconstant.php';


function validateDiaryImageFile()
{
    $error_message = "";

    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK) {
        

        $allowed_types = ['image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'];

        if ($_FILES['image_file']['size'] > SL_MAX_FILE_SIZE) {
            $error_message = "The diary image file must be less than " . SL_MAX_FILE_SIZE . " Bytes.";
        }


        $image_type = $_FILES['image_file']['type'];
        if (!in_array($image_type, $allowed_types)) {
            $error_message .= empty($error_message) ? "" : " ";
            $error_message .= "The diary image must be of type JPG, PNG, or GIF.";
        }
    } 
    elseif (isset($_FILES['image_file']) && $_FILES['image_file']['error'] != UPLOAD_ERR_NO_FILE) {
        $error_message = "Error uploading diary image file.";
    }

    return $error_message;
}


function adddiaryImageFileReturnPathLocation()
{
    $diary_file_path = SL_UPLOAD_PATH . SL_DEFAULT_diary_FILE_NAME; 


    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK)
    {
        $diary_file_path = SL_UPLOAD_PATH . basename($_FILES['image_file']['name']);
    }

    return $diary_file_path;
}


function removediaryImageFile($file_path)
{
    if ($file_path !== SL_UPLOAD_PATH . SL_DEFAULT_diary_FILE_NAME) { 
        @unlink($file_path);
    }
}
?>
