<?php


set_error_handler("liat_error", E_ALL);

function liat_error($errno = 0, $errstr = 'Error', $errfile = '', $errline = ''){

    $data['valid']   = false;
    $data['no']      = $errno;
    $data['message'] = $errstr; 
    $data['file']    = $errfile; 
    $data['line']    = $errline;
    
    //if( strpos($errstr, 'stream_socket_client') === false ){
    echo json_encode($data);
    die();
    //}
   
}

if( empty($_POST['host']) ){
    
    $data['valid']      = false;
    $data['message']    = 'Host Harus Diisi';
    
    echo json_encode($data);
    die();
    
}

if( empty($_POST['user']) ){
    
    $data['valid']      = false;
    $data['message']    = 'User Harus Diisi';
    
    echo json_encode($data);
    die();
    
}

$_POST['password'] = !empty($_POST['password']) ? $_POST['password'] : '';
$_POST['database'] = !empty($_POST['database']) ? $_POST['database'] : '';

?>