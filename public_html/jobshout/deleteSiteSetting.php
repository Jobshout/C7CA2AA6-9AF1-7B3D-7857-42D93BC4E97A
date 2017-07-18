<?php
	include("connect.php");
	
	if($db->query("DELETE FROM site_options WHERE GUID ='".$_REQUEST['GUID']."'")) {
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: site_settings.php");
?>