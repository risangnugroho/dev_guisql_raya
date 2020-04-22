<?php

require_once('handler.php');

if( empty($_POST['query']) ){
    echo json_encode(array('valid' => false, 'message' =>  'Table required') );
    die();
}

$con = mysqli_connect($_POST['host'], $_POST['user'], $_POST['password'], $_POST['database'] );

if( (int) mysqli_errno($con) > 0 ){
    echo json_encode(array('valid' => false, 'message' => mysqli_error($con)) );
    die();
}


$query = trim($_POST['query']);

$query = mysqli_query($con, $query);

if( $query instanceof mysqli_result ){
    
    if( !$query OR mysqli_num_rows($query)  < 1 ){
    
        $return['valid']    = false;
        $return['message']  = mysqli_error($con);
        
        echo json_encode($return);
        
        //require_once('kirim.php');
        die();
    }

    $data = array();
    while( $row = mysqli_fetch_assoc($query) ){
        $data[] =$row;
    }
    
    $return['valid']    = true;
    $return['rows']     = $data;
    $return['isRow']    = 1;
    
    echo json_encode($return);
    
    //require_once('kirim.php');
    die();
    
}else{
    
    $a = mysqli_affected_rows($con);


    $return['valid']    = true;
    $return['rows']     = 'Affectted : '.$a.' rows';
    $return['isRow']    = 0;
    
    echo json_encode($return);
    
    //require_once('kirim.php');
    die();
    
}




?>