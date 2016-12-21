<?php
    require_once '../class/user.php';
    require_once 'config.php';

    $email = Isset($_POST['email']) ? $_POST['email'] : '';
    $code = Isset($_POST['code']) ? $_POST['code'] : '';
    if($user->emailActivation( $email, $code)) {
        die;
    } else {
        $user->printMsg();
        die;
    }

