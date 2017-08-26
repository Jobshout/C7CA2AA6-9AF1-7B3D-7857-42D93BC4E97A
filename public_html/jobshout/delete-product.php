<?php
	session_start();
	include("connect.php");
	
	if($db->query("DELETE FROM products WHERE GUID ='".$_REQUEST['GUID']."'")) {
		$db->query("delete FROM productcategories WHERE Product_GUID='".$_REQUEST['GUID']."'");
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: products.php");
?>