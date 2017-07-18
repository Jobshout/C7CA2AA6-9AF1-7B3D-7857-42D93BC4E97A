<?php 
include_once("include/connect.php");
if($doc=$db->get_row("select * from documents where code='news' and SiteID=".SITE_ID." and Status='1' ORDER BY ID DESC LIMIT 1")) {
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
		 <p><?php
					if($cont_txt=$db->get_var("select TokenText from tokens where code='news_text' and SiteID=".SITE_ID." and zStatus='1'")) {
					echo $cont_txt;
					}
					?></p>
		<?php
		if($new_cat_id=$db->get_var("select ID from categories where code='news' and SiteID=".SITE_ID."")) {
			if($news=$db->get_results("select d.* from documents d join documentcategories dc on d.ID=dc.DocumentID where dc.CategoryID=".$new_cat_id." and dc.SiteID=".SITE_ID." and d.SiteID=".SITE_ID." and d.Status='1' order by d.Published_timestamp desc")) {
			?>
			<ul>
			<?php
				foreach($news as $new) {
				?>
				<li><a href="<?php echo $new->Code; ?>.html"><?php echo $new->Document; ?></a>&nbsp;<?php echo date('d M Y',$new->Published_timestamp); ?>
				<p><?php if(strlen(strip_tags($new->Body))>100) { echo substr(strip_tags($new->Body),0,100).'&hellip;'; } else { echo substr(strip_tags($new->Body),0,100); } ?></p>
				</li>
				<?php
				}
				?>
				</ul>
				<?php
			}
			else {			
				echo '<h3>No News found.</h3>';
			}	
		}	
		else {			
				echo '<h3>No News found.</h3>';
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