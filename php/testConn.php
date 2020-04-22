<?php

require_once('handler.php');

$con = mysqli_connect($_POST['host'], $_POST['user'], $_POST['password']);

if( (int) mysqli_errno($con) > 0 ){
    echo json_encode(array('valid' => false, 'message' => mysqli_error($con)) );
    die();
}

$data = array(
        'host'  => trim($_POST['host']),
        'user'  => trim($_POST['user']),
        'password'  => trim($_POST['password'])
);

$data['valid'] = true;

echo json_encode($data);

?>