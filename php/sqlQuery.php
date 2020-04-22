<?php

require_once('handler.php');


if( empty($_POST['query']) ){
    echo json_encode(array('valid' => false, 'message' =>  'Table required') );
    die();
}

$con = odbc_connect($_POST['host'], $_POST['user'], $_POST['password']);

if( !empty( odbc_error($con) ) ){
    echo json_encode(array('valid' => false, 'message' => odbc_error($con)) );
    die();
}

$query = trim($_POST['query']);


$res   = odbc_exec($con, $query);

if ( ! preg_match('/^\s*"?(SET|INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|TRUNCATE|LOAD DATA|COPY|ALTER|GRANT|REVOKE|LOCK|UNLOCK)\s+/i', $query)){
    
    $data = array();
    while( $row =odbc_fetch_array($res) ){
        
        $row[key($row)] = trim($row[key($row)]);
        
        if(  strpos(key($row), 'descr') !== false ){
            $row[key($row)] = mb_convert_encoding($row[key($row)], 'UTF-8', 'HTML-ENTITIES');
        }
        
        $data[] = $row;
    }
    
    $return['valid'] = true;
    $return['data']  = $data;
    $return['isRow'] = 1;
    
}else{
	
    $return['valid'] = odbc_num_rows($res) ? odbc_num_rows($res): false;
    $return['isRow'] = 0;
}
		

echo json_encode($return);

?>