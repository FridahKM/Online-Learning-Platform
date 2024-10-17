<?php
    if(isset($_POST['signin-submit'])) {

        //get data from form
        $userEmail = $_POST['email'];
        $userPassword = $_POST['password'];

        //link database handler file for connection to database
        require_once 'DBHandler.inc.php';
        //link functions file for various user functions included
        require_once 'Functions.inc.php';       

        //ERROR HANDLING WHEN LOGGING IN
        //Empty fields
        if(emptyLoginFields($userEmail, $userPassword) != false) {
            header("Location: ../Accounts/Signin.php?error=emptyfields");
            exit();
        } 
        
        //call function that will log in the admin
        loginUser($conn, $userEmail, $userPassword);
    } else if(isset($_POST['instructor-signin-submit'])) {

        //get data from form
        $userEmail = $_POST['email'];
        $userPassword = $_POST['password'];

        //link database handler file for connection to database
        require_once 'DBHandler.inc.php';
        //link functions file for various log in functions included
        require_once 'Functions.inc.php';       
        //link functions file for various admin functions included
        require_once 'adminFunctions.inc.php';

        //ERROR HANDLING WHEN LOGGING IN
        //Empty fields
        if(emptyLoginFields($userEmail, $userPassword) != false) {
            header("Location: ../Accounts/Signin.php?error=emptyfields");
            exit();
        } 
    //call function that will log in the user
    loginAdminUser($conn, $userEmail, $userPassword);
    } 
    
    else {
        header("Location: ../Index.php");
    exit();
    }   

    
