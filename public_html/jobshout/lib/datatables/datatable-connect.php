<?php
include_once "../../con_details.php";
include_once "../../../include/constants.php";
$db_user=DB_USER;
$db_pass=DB_PASSWORD;
$db_name=DB_NAME;
$db_host=DB_HOST;
/* Database connection information */
	 //$gaSql['user']       = "root";
//	 $gaSql['password']   = "SanJose^D";
//	 $gaSql['db']         = "jobshout_live";
//	 $gaSql['server']     = "pma26.tenthmatrix.co.uk";


	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
	 * no need to edit below this line
	 */
	
	/* 
	 * MySQL connection
	 */
	// $gaSql['link'] =  mysql_pconnect( $db_host, $db_user, $db_pass  ) or
	$gaSql['link'] =  @mysql_connect( $db_host, $db_user, $db_pass  ) or
		die( 'Could not open connection to server' );
	
	mysql_select_db( $db_name, $gaSql['link'] ) or 
		die( 'Could not select database '. $db_name );
		
?>