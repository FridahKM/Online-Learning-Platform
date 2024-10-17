<?php

//FUNCTIONS FOR ERROR HANDLING WHEN SIGNING UP
//1.Empty Fields
function emptyFields($userFullName, $userEmail, $userGender, $userPassword, $passwordRepeat) {
	$results = null;
  
	if(empty($userFullName) || empty($userEmail) || empty($userGender) || empty($userPassword) || empty($passwordRepeat)) {
		$results = true;
	} else {
		$results = false;
	}
	return $results;
}

//2.Invalid Email
function invalidEmail($userEmail) {
	$results = null;
  
	if(!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
		$results = true;
	} else {
		$results = false;
	}
	return $results;
}

//3.Different Password Entry
function passDifferent($userPassword, $passwordRepeat) {
	$results = null;
  
	if($userPassword !== $passwordRepeat) {
		$results = true;
	} else {
		$results = false;
	}
	return $results;
}

//4. Existing user with same email
function existEmail($conn, $userEmail) {
	$results = null;
  
	//Get data from user using prepared statement
	$sql = "SELECT * FROM users WHERE email = ?;";
  	$stmt = mysqli_stmt_init($conn);

  	//Error detected returns user to signup page
  	if(!mysqli_stmt_prepare($stmt, $sql)) {
  		header("Location: ../Accounts/Signup.php?error=sqlerror");
  		exit();
  	}

  	//No error detected gets the data from the database
  	mysqli_stmt_bind_param($stmt, "s", $userEmail);
  	mysqli_stmt_execute($stmt);
  	$resultData = mysqli_stmt_get_result($stmt);

  	//Check to see if the email provided is in database
    if($row = mysqli_fetch_assoc($resultData)) {
    	return $row;
    } else {
    	$results = false;
    	return $results;
    }

}

//5. Weak Password
function weakPassword($userPassword) {
	$results = null;
  
	if(strlen($userPassword)<=7) {
		$results = true;
	} else {
		$results = false;
	}
	return $results;
}

//FUNCTION TO ADD NEW USER TO DATABASE
function createUser($conn, $userFullName, $userEmail, $userPassword) {
    // Prepare the SQL statement to insert data into the database
    $sql = "INSERT INTO users (fullname, email, password) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    // Check for SQL statement preparation errors
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../Accounts/Signup.php?error=sqlerror");
        exit();
    }

    // Hash the password
    $hashedPass = password_hash($userPassword, PASSWORD_DEFAULT);

    // Bind the user data to the SQL statement and execute it
    mysqli_stmt_bind_param($stmt, "sss", $userFullName, $userEmail, $hashedPass);
    mysqli_stmt_execute($stmt);

    // Close the prepared statement
    mysqli_stmt_close($stmt);

    // Log the event after successful signup
    $event = "Created Account";
    addLogs($conn, $userEmail, $event);

    // Redirect to the signup page with a success message
    header("Location: ../Accounts/Signup.php?signup=success");
    exit();
}

function sendOTP($conn, $userEmail){
  $recipient = $userEmail;
  $otp = rand(100000, 999999);
  $hashedOTP = password_hash($otp, PASSWORD_DEFAULT);
  
  $query = "UPDATE users SET token = ? WHERE email = ?" ;  
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $otp, $recipient);
  if (!$stmt->execute()) trigger_error("Error in executing query: " . $stmt->error, E_USER_ERROR);
  $stmt->close();
      
  // Email content
  $sender = "kamuimusee@gmail.com";
  $subject = "One Time Sign In Password";
  $body = "Dear user,\n\nPlease use the following OTP to sign in:\n$otp\n\nIf you did not request for the OTP, please ignore this email.\n\nBest regards,\nYour Website Team";

  // Send the password reset email
  require_once "sendEmails.inc.php";
  sendEmails($sender, $recipient, $subject, $body);
}

function updateCourse($conn, $courseId, $courseName, $courseLength, $Description) {
      // Prepare statement to update data in the database
    $sql = "UPDATE courses SET course_name = ?, period = ?, description = ? WHERE course_id = ?";
    $stmt = mysqli_stmt_init($conn);

    // Error detected, return user to appropriate page
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ./Instructor/CreateCourse.php?error=sqlerror");
        exit();
    }

    // No error detected, update the data in the database
    mysqli_stmt_bind_param($stmt, "ssdssi", $courseName, $university, $courseLength, $location, $courseType, $courseId);
    mysqli_stmt_execute($stmt);

    // Close the SQL prepared statement
    mysqli_stmt_close($stmt);

    // Redirect user after successful update
    header("Location: ./Instructor/CreateCourse.php?error=updatecoursesuccess");
    exit();
    }