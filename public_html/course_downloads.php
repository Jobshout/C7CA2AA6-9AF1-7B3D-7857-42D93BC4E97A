<?php 
include_once("include/connect.php");
if($doc=$db->get_row("select * from documents where code='course-downloads' and SiteID=".SITE_ID." and Status='1' ORDER BY ID DESC LIMIT 1")) {
	$document=$doc->Document;
	$title=$doc->Title;
	$pWindowTitleTxt = $doc->PageTitle;
	$pMetaKeywordsTxt = $doc->MetaTagKeywords;
	$pMetaDescriptionTxt = $doc->MetaTagDescription;
}
else{
header('Location: content_not_found.php');   
exit;
}


include_once("include/main-header.php"); 
?>
    
</head>
<body>
<?php include_once("include/analyticstracking.php"); ?>
<div class="container" >
  <?php include_once("include/top-header.php"); ?>
            
            <div class="pg-hding">
          <?php echo $document; ?>
            </div>
        
  <div class="content-bg">
    <div class="row">
      
      <div class="col-lg-8 col-md-8 col-lg-push-4 col-md-push-4">
        <div class="content">
         <h1><?php if($title!=''){ echo $title; } else{ echo $document; } ?></h1>
		 
		<?php

			if($docs=$db->get_results("select p.* from pdf_docs p join pdf_to_documents pd on p.id=pd.pdf_id where pd.document_id=".$doc->ID." and p.SiteID=".SITE_ID." and pd.siteid=".SITE_ID." and p.status='1'")) {
			?>
			<ul>
			<?php
				foreach($docs as $docu) {
				?>
				<li><a target="_blank" href="download_file.php?uuid=<?php echo $docu->uuid; ?>" ><?php echo $docu->doc_title; ?></a>				
				</li>
				<?php
				}
				?>
				</ul>
				<?php
			}
			else {			
				echo '<h3>No downloads found.</h3>';
			}	
				
		?>
          
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