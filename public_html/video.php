<?php
//require_once("include/cache_start.php");
$code = isset($_GET['code']) ? $_GET['code'] : '';
$code = str_replace(".html", "", $code);


include_once("include/connect.php");
$ResultingData = array();
$siteCodeStr = '';

$pluginsRefStr = '';

if(isset($code)){

 	$Query = "SELECT * FROM `videos` WHERE `code`='$code' AND SiteID=".SITE_ID."";
	if($ResultingData = $db->get_row($Query)){
		//header("location:".$ResultingData->Code.".html");
	}
	
$siteCodeStr = 'catbase';
}

if(count($ResultingData)==0){
header('Location: content_not_found.php');   
exit;
}

$pWindowTitleTxt = @$ResultingData->title;
$pMetaKeywordsTxt = @$ResultingData->video_tags;
$pMetaDescriptionTxt = @$ResultingData->metadescription;
$pMetaAuthorTxt = "";



$body= @$ResultingData->body;
$body= str_replace('src="http://dev.tenthmatrix.co.uk/', 'src="', $body);
$body= str_replace('src="/', 'src="', $body);
$body= str_replace('src="../', 'src="', $body);
$body= str_replace('href="/', 'href="', $body);
$body= str_replace('href="../', 'href="', $body);
$body= str_replace('src="http:', 'src="https:', $body);

$video_src= @$ResultingData->video_src;

$video_src= str_replace('src="http:', 'src="https:', $video_src);
$video_src=str_replace('src="//', 'src="https://', $video_src);
					
					
?>
<?php require_once("include/main-header.php");	?>
<style type="text/css">
iframe{
width: 100%!important;
height: 300px!important;
}
</style>
</head>
<body>
<?php include_once("include/analyticstracking.php"); ?>
<div class="container" >
  <?php include_once("include/top-header.php"); ?>

            
            <div class="pg-hding">
            Videos
            </div>
        
  <div class="content-bg">
    <div class="row">
      
      <div class="col-lg-8 col-md-8 col-lg-push-4 col-md-push-4">
        <div class="content">
		<h1><?php echo @$ResultingData->title;?></h1>
		
		
<?php
echo $video_src;
?>

<?php
echo $body;

?>

  <form class="cont-form"><button type="button" class="btn btn-default" onClick="window.location.href='videos.php'">Back to Videos</button></form>        
        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-lg-pull-8 col-md-pull-8">
        <?php include_once("include/left-recipes.php"); ?>
         <?php include_once("include/upcoming-book.php"); ?>
        
      </div>
    </div>
 
  <?php include_once("include/top-footer.php"); ?>
</div>
 </div>

<?php include_once("include/footer.php"); ?>

</body>
</html>