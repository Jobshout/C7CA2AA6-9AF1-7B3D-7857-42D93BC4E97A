<?php 
include_once("include/connect.php");
if($doc=$db->get_row("select * from documents where code='news' and SiteID=".SITE_ID." and Status='1' ORDER BY ID DESC LIMIT 1")) {
	$document=$doc->Document;
	$title=$doc->Title;
	$pWindowTitleTxt = $doc->PageTitle;
	$pMetaKeywordsTxt = $doc->MetaTagKeywords;
	$pMetaDescriptionTxt = $doc->MetaTagDescription;
}


include_once("include/main-header.php"); 
?>
    
</head>
<body>
<?php include_once("include/analyticstracking.php"); ?>
<div class="container" >
  <?php include_once("include/top-header.php"); ?>
            
            <div class="pg-hding">
           404 - Page Not Found
            </div>
        
  <div class="content-bg">
    <div class="row">
      
      <div class="col-lg-8 col-md-8 col-lg-push-4 col-md-push-4">
        <div class="content">
         <h1>404 - Page Not Found</h1>
		 <p>The page your looking for might not exist any more or might have been moved.

		Go to our <a href="index.php">home page</a> to find what you're looking for.</p>
				
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