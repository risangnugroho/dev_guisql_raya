<?php

require_once('handler.php');

$con = mysqli_connect($_POST['host'], $_POST['user'], $_POST['password'] );

if( (int) mysqli_errno($con) > 0 ){
    echo json_encode(array('valid' => false, 'message' => mysqli_error($con)) );
    die();
}

$query = 'SELECT TABLE_SCHEMA, TABLE_NAME, TABLE_TYPE
            FROM information_schema.`TABLES` a
            WHERE a.`TABLE_SCHEMA` = \''. addslashes($_POST['database']) .'\'';

$query = mysqli_query($con, $query);

//var_dump($query);die();

if( !$query OR mysqli_num_rows($query)  < 1 ){
    echo json_encode( array('valid' => true, 'tables' => array(), 'schema' => $_POST['database'] ) );
    die();
}

$tabel = array();
$views = array();
while( $row = mysqli_fetch_assoc($query) ){
    //var_dump($row);
    if( $row['TABLE_TYPE'] == 'BASE TABLE' ){
        $tabel[] = $row['TABLE_NAME'];
    }else{
        $views[] = $row['TABLE_NAME'];
    }
    
}

$return['valid']    = true;
$return['tables']   = $tabel;
$return['views']    = $views;
$return['schema']   = $_POST['database'];

echo json_encode($return);


?>