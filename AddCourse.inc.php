<?php

if (isset($_POST['add-course-submit'])) {
    require 'DBHandler.inc.php'; // Database handler

    // Get the data from the form
    $courseTitle = $_POST['course_title'];
    $courseDescription = $_POST['course_description'];
    $courseDuration = $_POST['course_duration'];

    // Error handling
    if (empty($courseTitle) || empty($courseDescription) || empty($courseDuration)) {
        header("Location: add_course.php?error=emptyfields");
        exit();
    }

    // Prepare SQL statement to insert course into the database
    $sql = "INSERT INTO courses (title, description, duration) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);

    // Check for SQL errors
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: add_course.php?error=sqlerror");
        exit();
    }

    // Bind parameters and execute the statement
    mysqli_stmt_bind_param($stmt, "sss", $courseTitle, $courseDescription, $courseDuration);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirect to the add course page with a success message
    header("Location: add_course.php?success=coursesuccess");
    exit();
} else {
    header("Location: add_course.php");
    exit();
}
