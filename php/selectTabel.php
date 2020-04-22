<?php

require_once('handler.php');

if( empty($_POST['table']) ){
    echo json_encode(array('valid' => false, 'message' =>  'Table required') );
    die();
}

$con = mysqli_connect($_POST['host'], $_POST['user'], $_POST['password'], $_POST['database'] );

if( (int) mysqli_errno($con) > 0 ){
    echo json_encode(array('valid' => false, 'message' => mysqli_error($con)) );
    die();
}

$page   = !empty($_POST['page']) ? (int) $_POST['page'] : 1;
$offset = !empty($_POST['offset']) ? (int) $_POST['offset'] : 500;

$first  = ( $page - 1 ) * $offset;

$query = 'SELECT *
            FROM `'.$_POST['table'].'`
            LIMIT '.$first.', '.$offset;

$query = mysqli_query($con, $query);

//var_dump($query);die();

if( !$query OR mysqli_num_rows($query)  < 1 ){
   
    $return['valid']    = true;
    $return['rows']     = 0;
    $return['page']     = $page;
    $return['show']     = 0;
    $return['total']    = 0;
    
    echo json_encode($return);
    die();
}

$rows = array();
while( $row = mysqli_fetch_assoc($query) ){
    
    $rows[] = $row;
}

$query = 'SELECT COUNT(*) as jumlah FROM `'.$_POST['table'].'`';
$query = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($query);


$return['valid']    = true;
$return['rows']     = $rows;
$return['page']     = $page;
$return['show']     = count($rows);
$return['total']    = !empty($row['jumlah']) ? (int) $row['jumlah'] : 0;

echo json_encode($return);


?>