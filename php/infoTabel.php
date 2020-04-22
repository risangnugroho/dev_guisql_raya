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


$query = 'SELECT TABLE_NAME, COLUMN_NAME, ORDINAL_POSITION, COLUMN_DEFAULT, IS_NULLABLE, 
            	COLUMN_TYPE, COLUMN_KEY, EXTRA, COLUMN_COMMENT, COLLATION_NAME, EXTRA
            FROM information_schema.`COLUMNS`
            WHERE `TABLE_SCHEMA`= \''.addslashes($_POST['database']).'\' AND `TABLE_NAME` = \''.addslashes($_POST['table']).'\'
            ORDER BY `TABLE_NAME`, `ORDINAL_POSITION`';

$query = mysqli_query($con, $query);

if( !$query OR mysqli_num_rows($query)  < 1 ){
    
    $return['valid']    = true;
    $return['database'] = $_POST['database'];
    $return['table']    = $_POST['table'];
    $return['link']     = $_POST['database'].'.'.$_POST['table'];
    $return['column']   = array();
    $return['index']    = array();
    
    echo json_encode($return);
    die();
}

$column = array();

while( $row = mysqli_fetch_assoc($query) ){
    
    $type = $row['COLUMN_TYPE'];
    
    if( !empty($row['COLLATION_NAME']) ){
        $type .= ' COLLATE '.$row['COLLATION_NAME'];
    }
    
    $type .= $row['IS_NULLABLE'] == 'YES' ? ' NULL ' : ' NOT NULL ';
    
    if( !empty($row['COLUMN_DEFAULT']) ){
        $type .= ' DEFAULT '.$row['COLUMN_DEFAULT'];
    }
    
    if( !empty($row['EXTRA']) ){
        $type .= ' '.strtoupper($row['EXTRA']);
    }
    
    $column[] = array(
        'name'      => $row['COLUMN_NAME'],
        'type'      => $type,
        'comment'   => $row['COLUMN_COMMENT'],
        'primary'   => !empty($row['COLUMN_KEY']) ? 1 : 0
    );
}

$query = 'SELECT INDEX_NAME, COLUMN_NAME
            FROM information_schema.statistics 
            WHERE TABLE_SCHEMA = \''. addslashes($_POST['database']) .'\'
                    AND `TABLE_NAME` = \''.addslashes($_POST['table']).'\'';

$query = mysqli_query($con, $query);

$index = array();

while( $row = mysqli_fetch_assoc($query) ){
    $index[] = $row;
}

$return['valid']    = true;
$return['database'] = $_POST['database'];
$return['table']    = $_POST['table'];
$return['link']     = $_POST['database'].'.'.$_POST['table'];
$return['column']   = $column;
$return['index']    = $index;

echo json_encode($return);


?>