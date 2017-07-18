<?php
		if($re_cat_id=$db->get_var("select ID from categories where code='recipes' and SiteID=".SITE_ID."")) {
			if($recipes=$db->get_results("select d.* from documents d join documentcategories dc on d.ID=dc.DocumentID where dc.CategoryID=".$re_cat_id." and dc.SiteID=".SITE_ID." and d.SiteID=".SITE_ID." and d.Status='1' order by rand() limit 0,2")) {

			?>
			<div>
          <h1><img src="images/receipe_heading.png" alt="Receipe" class="img-responsive"></h1>
        </div>
			<?php
				foreach($recipes as $recipe) {
				?>
				<div class="receipebx">
          <div class="row ">
		  <?php
		  	$text_classes='col-sm-9 col-xs-8 col-lg-12 col-md-7';
		  if($reci_img=$db->get_row("select * from pictures where SiteID=".SITE_ID." and DocumentID='".$recipe->ID."' and Status='1' order by Order_By limit 0,1")){
			$pic_Src = "data:".$reci_img->Type.";base64," . base64_encode($reci_img->Picture);
			$text_classes='col-sm-9 col-xs-8 col-lg-8 col-md-7';
		  ?>
            <div class="col-sm-3 col-xs-4 col-lg-4 col-md-5" style="margin-right:0px; padding-right:0px;"> <img src="<?php echo $pic_Src; ?>"  class="img-responsive"> </div>
			<?php } ?>
            <div class="<?php echo $text_classes; ?>" style="margin-right:0px; padding-right:0px;" >
              <h4><a href="<?php echo $recipe->Code; ?>.html"><?php echo $recipe->Document; ?></a></h4>
              <p><?php if(strlen(strip_tags($recipe->Body))>75) { echo substr(strip_tags($recipe->Body),0,75).'&hellip;'; } else { echo strip_tags($recipe->Body); } ?></p>
            </div>
          </div>
        </div>
				<?php
				}
				?>
			
				<?php
			}
		}		
		?>