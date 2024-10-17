<?php

if (isset($_POST['upload-submit'])) {
    require 'DBHandler.inc.php'; // Database handler

    // Get the data from the form
    $courseId = $_POST['course_id'];
    $videoTitle = $_POST['video_title'];
    $videoFile = $_FILES['video_file'];

    // Error handling
    $fileError = $videoFile['error'];
    $fileName = $videoFile['name'];
    $fileTmpName = $videoFile['tmp_name'];
    $fileSize = $videoFile['size'];
    $fileType = $videoFile['type'];

    // Check if there was an error uploading the file
    if ($fileError !== UPLOAD_ERR_OK) {
        header("Location: ./Instructor/UploadVideo.php?error=uploaderror");
        exit();
    }

    // Set a limit on file size (e.g., 100MB)
    if ($fileSize > 100 * 1024 * 1024) { // 100 MB
        header("Location: ./Instructor/UploadVideo.php?error=filetoolarge");
        exit();
    }

    // Define the target directory for uploads
    $targetDir = "uploads/videos/"; // Ensure this directory exists and is writable
    $targetFilePath = $targetDir . basename($fileName);

    // Check file type (optional)
    $allowedTypes = ['video/mp4', 'video/mkv', 'video/avi', 'video/mov']; // Add more as needed
    if (!in_array($fileType, $allowedTypes)) {
        header("Location: ./Instructor/UploadVideo.php?error=invalidfiletype");
        exit();
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($fileTmpName, $targetFilePath)) {
        // Insert video details into the database
        $sql = "INSERT INTO videos (course_id, title, file_path) VALUES (?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ./Instructor/UploadVideo.php?error=sqlerror");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "iss", $courseId, $videoTitle, $targetFilePath);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: ./Instructor/UploadVideo.php?success=videosuccess");
        exit();
    } else {
        header("Location: ./Instructor/UploadVideo.php?error=uploadfailed");
        exit();
    }
} else {
    header("Location: ./Instructor/UploadVideo.php");
    exit();
}
