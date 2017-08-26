<?php
	session_start();
	include("connect.php");
	
	if($db->query("DELETE FROM options WHERE GUID ='".$_REQUEST['GUID']."'")) {
		$db->query("delete FROM product_options WHERE option_uuid='".$_REQUEST['GUID']."'");
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: options.php");
?>