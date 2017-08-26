<?php
	session_start();
	include("connect.php");
	
	if($db->query("DELETE FROM manufacturers WHERE GUID ='".$_REQUEST['GUID']."'")) {
		$db->query("update products set mnf_id='0', mnf_uuid='' where mnf_uuid='".$_REQUEST['GUID']."'");
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: manufacturers.php");
?>