<?php

require_once('handler.php');

$con = mysqli_connect($_POST['host'], $_POST['user'], $_POST['password']);

if( (int) mysqli_errno($con) > 0 ){
    echo json_encode(array('valid' => false, 'message' => mysqli_error($con)) );
    die();
}

$query = 'SHOW DATABASES';

$query = mysqli_query($con, $query);

$data = array();
while( $row = mysqli_fetch_array($query) ){
    $data[] = trim($row[0]);
}


$return['valid'] = true;
$return['databases'] = $data;
    
echo json_encode($return);

?>