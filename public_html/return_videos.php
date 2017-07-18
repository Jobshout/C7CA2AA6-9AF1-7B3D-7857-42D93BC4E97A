<?php
require_once("include/connect.php");
$start=$_GET['start'];
$limit=$_GET['limit'];
$data= array();


	
		/*$query = "SELECT ID, Created, Code, Document, Body, BodyContent, Tags, MetaTagDescription, FFAlpha80_2 FROM documents WHERE Type='blog' AND SiteID=".SITE_ID." Order by ID desc";*/
		if($_REQUEST['cat']!=''){
			$total= $db->get_var("SELECT count(*) from videos where SiteID =".SITE_ID." and active=1 and uuid in (select Video_GUID from videocategories where CategoryID='".$_REQUEST['cat']."' and SiteID =".SITE_ID." )");
			$query = "SELECT * from videos where SiteID =".SITE_ID." and active=1 and uuid in (select Video_GUID from videocategories where CategoryID='".$_REQUEST['cat']."' and SiteID =".SITE_ID." ) Order by video_sort_order asc"; 
		}
		else{
			$total= $db->get_var("SELECT count(*) from videos where SiteID =".SITE_ID." and active=1 ");		
			$query = "SELECT * from videos where SiteID =".SITE_ID." and active=1 Order by video_sort_order DESC"; 
		}
		
		$result['total']= $total;
		
		$query.= " limit ".$start.", ".$limit;

		$dbResultsData = $db->get_results( $query );

        for( $j=0; $j <= count($dbResultsData)-1; $j++ ):
		
		//$video_src=str_replace('src="//', 'src="https://', $dbResultsData[$j]->video_src);
		if($dbResultsData[$j]->image != ''){
		$b64Src = "data:".$dbResultsData[$j]->image_type.";base64," . base64_encode($dbResultsData[$j]->image);
		}
		else{
		$b64Src = "images/video-player-image.png";
		}
		$video_src="<img alt=\"".$dbResultsData[$j]->title."\" src=\"".$b64Src."\" class=\"img-responsive img-thumbnail center-block \" />";
		/*$script_pos1= strpos($video_src, '<script');
		$script_pos2= strpos($video_src, '</script>') + 9;
		$video_src= substr($video_src,0,$script_pos1).substr($video_src,$script_pos2);*/
		$video_title= $dbResultsData[$j]->title;
		if(strlen($video_title)>20){
		$video_title= substr($dbResultsData[$j]->title,0,20)."&hellip;";
		}
		
		$data[$j]['vid_src']= $video_src;
		$data[$j]['code']= $dbResultsData[$j]->code;
		$data[$j]['title']= $dbResultsData[$j]->title;
		$data[$j]['display_title']= $video_title;

   		endfor;
		
		$result['data']= $data;
		
		if(count($dbResultsData)==0)
			$result['error']="No more record found.";

	
	
echo json_encode($result);

?>
