<?php

// SQL server connection information
$sql_details = array(
    'user' => 'root',
    'pass' => '',
    'db'   => 'u1045575_rdb_channel',
    'host' => 'localhost'
    // ,'charset' => 'utf8' // Depending on your PHP and MySQL config, you may need this
);


 
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See https://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - https://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'leads_tbl';
 
// Table's primary key
$primaryKey = 'ID_LEADS';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'ID_LEADS', 'dt' => 0 ),
    array( 'db' => 'CODE_LEADS', 'dt' => 1 ),
    array( 'db' => 'NAMA_LEADS',  'dt' => 2 ),
    array( 'db' => 'NAMA_CALON_NASABAH',   'dt' => 3 ),
    array( 'db' => 'ALAMAT',     'dt' => 4 ),
    array( 'db' => 'KECAMATAN', 'dt' => 5 ),
    array( 'db' => 'KABUPATEN',  'dt' => 6 ),
    array( 'db' => 'PROVINSI',   'dt' => 7 ),
    array( 'db' => 'NAMA_PIC',     'dt' => 8 ),
    array( 'db' => 'NO_HP', 'dt' => 9 ),
    array( 'db' => 'CODE_CABANG',  'dt' => 10 ),
    array( 'db' => 'CABANG',   'dt' => 11 ),
    array( 'db' => 'OUTLET',     'dt' => 12 ),
    array( 'db' => 'NAME_INPUTTER', 'dt' => 13 ),
    array( 'db' => 'NPP_INPUTTER',  'dt' => 14 ),
    array( 'db' => 'TIME_INPUTTER',   'dt' => 15 ),

    array(
        'db'        => 'ID_LEADS',
        'dt'        => 16,
        'formatter' => function( $d, $row ) {
            return '<a href="edit.php?id=' . $row["ID_LEADS"] . '" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#actionModal">Action</a>';
        }
    ),

    // array(
    //     'db'        => 'start_date',
    //     'dt'        => 4,
    //     'formatter' => function( $d, $row ) {
    //         return date( 'jS M y', strtotime($d));
    //     }
    // ),
    // array(
    //     'db'        => 'salary',
    //     'dt'        => 5,
    //     'formatter' => function( $d, $row ) {
    //         return '$'.number_format($d);
    //     }
    // )
);
 

 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);
?>