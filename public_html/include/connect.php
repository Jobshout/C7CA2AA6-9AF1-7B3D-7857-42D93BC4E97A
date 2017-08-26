<?php
// Include ezSQL core
include_once "ez_sql_core.php";
// Include ezSQL database specific component
include_once "ez_sql_mysql.php";

include_once "../../config/C7CA2AA6-9AF1-7B3D-7857-42D93BC4E97A.php";

$db = new ezSQL_mysql(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
?>
