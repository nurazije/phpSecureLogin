<?php
    require_once '../class/user.php';
    require_once 'config.php';

    $email = Isset($_POST['email']) ? $_POST['email'] : '';
    $fname = Isset($_POST['fname']) ? $_POST['fname'] : '';
    $lname = Isset($_POST['lname']) ? $_POST['lname'] : '';
    $pass = Isset($_POST['pass']) ? $_POST['pass'] : '';

    if($user->registration( $email, $fname, $lname, $pass)) {
        print 'A confirmation mail has been sent, please confirm your account registration!';
        die;
    } else {
        $user->printMsg();
        die;
    }
