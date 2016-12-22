<?php
    require_once '../class/user.php';
    require_once 'config.php';

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);

    if($user->registration($email, $fname, $lname, $pass)) {
        print 'A confirmation mail has been sent, please confirm your account registration!';
        die;
    } else {
        $user->printMsg();
        die;
    }
