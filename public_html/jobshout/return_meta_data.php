<?php
require_once("include/lib.inc.php");
require_once("include/Metakeywords.php");

$output = array();
$title = isset($_POST['title']) ? $_POST['title'] : '';
$body = isset($_POST['body']) ? $_POST['body'] : '';
$cat = isset($_POST['cat']) ? $_POST['cat'] : '';

$site_id = isset($_POST['site_id']) ? $_POST['site_id'] : '';
$categoriesStr='';

	//meta tag keywords
	$categoriesArr = explode(',',$cat);
	$categoryNameArr=array();
	if(count($categoriesArr)>0){
		foreach($categoriesArr as $categoriesID){
			if($categoriesID != ''){ 
				$categoryNameArr[] = $db->get_var("SELECT Name FROM categories WHERE SiteID='".$site_id."' AND ID='$categoriesID'");
			}
		}
		if(count($categoryNameArr)>0){
			$categoriesStr= implode(",",$categoryNameArr);
		}
	}
	
	
	//initiate meta keywords class
	$inst_Metakeywords = new Metakeywords();
	$create_MetaTagKeywords=$title;
	
	if($body!=''){
		//$create_MetaTagKeywords.='- '.$body;
		if($body!=''){
			$k_body=substr($body,0,512);
			$getlast_position = strrpos($k_body, ".");
			if($getlast_position!=''){
				$k_body=substr($k_body,0,$getlast_position);
			}
			$create_MetaTagKeywords.='- '.$k_body;
		}
	}
	if($categoriesStr!=''){
		$create_MetaTagKeywords.='- '.$categoriesStr;
	}
	$output['MetaTagKeywords']= $inst_Metakeywords->get( $create_MetaTagKeywords );

	//meta tag description
	$create_MetaTagDescription=$title;
	
	if($body!=''){
		$create_MetaTagDescription.='- '.$body;
	}
	$create_MetaTagDescription=substr($create_MetaTagDescription,0,512);
	$getlast_position = strrpos($create_MetaTagDescription, ".");
	if($getlast_position!=''){
		$create_MetaTagDescription=substr($create_MetaTagDescription,0,$getlast_position);
	}
	$create_MetaTagDescription=strip_tags($create_MetaTagDescription);
	$create_MetaTagDescription = str_replace("&nbsp;"," ",$create_MetaTagDescription);
	$create_MetaTagDescription = str_replace("\n"," ",$create_MetaTagDescription);
	$output['MetaTagDescription']=$create_MetaTagDescription;
if(count($output)>0){
	echo json_encode($output);
}
?>