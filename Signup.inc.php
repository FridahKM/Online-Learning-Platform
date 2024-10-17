<?php
// Check if the form is submitted
if (isset($_POST['submit'])) {
    require 'DBHandler.inc.php'; // Database connection handler
    require_once 'Functions.inc.php'; // Include functions for error handling

    // Get data from the form
    $userFullName = $_POST['fullname'];
    $userEmail = $_POST['email'];
    $userPassword = $_POST['password'];
    $passwordRepeat = $_POST['passwordchk'];

    // Error handling functions
    
    // 1. Check for empty fields
    if (emptyFields($userFullName, $userEmail, $userPassword, $passwordRepeat) !== false) {
        header("Location: ../Accounts/Signup.php?error=emptyfields");
        exit();
    }

    // 2. Check for a valid email
    if (invalidEmail($userEmail) !== false) {
        header("Location: ../Accounts/Signup.php?error=invalidemail");
        exit();
    }

    // 3. Check if passwords match
    if (passDifferent($userPassword, $passwordRepeat) !== false) {
        header("Location: ../Accounts/Signup.php?error=passdifferent");
        exit();
    }

    // 4. Check if the email already exists
    if (existEmail($conn, $userEmail) !== false) {
        header("Location: ../Accounts/Signup.php?error=existemail");
        exit();
    }

    // 5. Check for a weak password
    if (weakPassword($userPassword) !== false) {
        header("Location: ../Accounts/Signup.php?error=weakpassword");
        exit();
    }

    // If everything is valid, create the user in the database
    createUser($conn, $userFullName, $userEmail, $userPassword);

} else {
    // Redirect to the signup page if the form wasn't accessed correctly
    header("Location: ../Index.php");
    exit();
}
