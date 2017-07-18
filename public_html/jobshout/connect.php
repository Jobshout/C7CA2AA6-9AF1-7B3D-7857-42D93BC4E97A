<?php

include_once "con_details.php";
include_once "../include/constants.php";
$db_user=DB_USER;
$db_pass=DB_PASSWORD;
$db_name=DB_NAME;
$db_host=DB_HOST;

// Include ezSQL core
include_once "ez_sql_core.php";
// Include ezSQL database specific component
include_once "ez_sql_mysql.php";

 $db = new ezSQL_mysql($db_user,$db_pass,$db_name,$db_host);
 	
 $dbhistory = new ezSQL_mysql($history_db_user,$db_pass,$history_db_name,$db_host);	
 
 $dbdictionary = new ezSQL_mysql($dictionary_db_user,$db_pass,$dictionary_db_name,$db_host);	
?>