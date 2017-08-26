<?php 
require_once("include/lib.inc.php");
$insert_cat= $db->query("INSERT INTO site_options(guid, site_guid, name, value,status, SiteID,LastModified) 
			VALUES('5898b18a-8d9b-11e4-b9b5-40405ec230c5','986EE753-5087-4FB2-BDB0-64DE15A77FFF','job_editor_salary_fields','true',1,3320,'".time()."')");
			
$db->debug();
?>