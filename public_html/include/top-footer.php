<div class="footer">
          <div class="row">
          		<div class="col-sm-6 col-md-3">
                <h2>Quick Links</h2>
				
				<?php
	if($cat_grp=$db->get_var("select ID from categorygroups where SiteID=".SITE_ID." and code='sitenav' and Active='1'")) {
		if($top_categories=$db->get_results("select * from categories c join documents d on c.Code=d.Code where c.CategoryGroupID=".$cat_grp." and c.SiteID=".SITE_ID." and d.SiteID=".SITE_ID." and c.Active='1' and d.Status='1'")) {
		?>
		 <ul class="quicklinks">
		 <?php
			foreach($top_categories as $top_category) {
				?>
			 <li><a href="<?php echo $top_category->Code; ?>.html"><?php echo $top_category->Name; ?></a> </li>
			<?php
			}
			?>
			<li><a href="videos.php">Videos</a> </li>
			</ul>
			<?php
		}
	}
	?>	
              </div>
			  
			  
			  <div class="col-sm-6 col-md-3">
			 <?php
		if($post_cat_id=$db->get_var("select ID from categories where code='posts' and SiteID=".SITE_ID."")) {
		?>
			  
		<h2>Archives</h2>
		<?php
		if($posts=$db->get_results("select DISTINCT (DATE_FORMAT( FROM_UNIXTIME( `Published_timestamp` ) , '%M %Y' )) AS Date from documents d join documentcategories dc on d.ID=dc.DocumentID where dc.CategoryID=".$post_cat_id." and dc.SiteID=".SITE_ID." and d.SiteID=".SITE_ID." and d.Type='blog' and d.Status='1' order by d.Published_timestamp desc limit 0,4")) {
		?>
		<ul class="recent-posts">
			<?php
			$months_arr=array("January"=>"1","February"=>"2","March"=>"3","April"=>"4","May"=>"5","June"=>"6","July"=>"7","August"=>"8","September"=>"9","October"=>"10","November"=>"11","December"=>"12");
				foreach($posts as $post) {
				$date_arr=explode(" ",$post->Date);
				
				?>
		<li><a href="blog.php?Month=<?php echo $months_arr[$date_arr[0]]; ?>&Year=<?php echo $date_arr[1]; ?>"><?php echo $post->Date; ?></a>
				</li>
				<?php
				}
				?>
				</ul>
		<?php
		}
		
		}
    ?>
 
              </div>
			  
			  
			  
              <div class="col-sm-6 col-md-3">
			  <?php
		if($post_cat_id=$db->get_var("select ID from categories where code='posts' and SiteID=".SITE_ID."")) {
		?>
		<h2>Recent Posts</h2>
		<?php
			if($posts=$db->get_results("select d.* from documents d join documentcategories dc on d.ID=dc.DocumentID where dc.CategoryID=".$post_cat_id." and dc.SiteID=".SITE_ID." and d.SiteID=".SITE_ID." and d.Type='blog' and d.Status='1' order by d.Published_timestamp desc limit 0,4")) {
			?>
			<ul class="recent-posts">
			<?php
				foreach($posts as $post) {
				?>
				<li><a href="<?php echo $post->Code; ?>.html"><?php echo $post->Document; ?></a>
				</li>
				<?php
				}
				?>
				</ul>
				<?php
			}
			?>
			
		<?php
		}		
		?>
              	   
        </div>
        <div class="col-sm-6 col-md-3">
          <h2>Contact Us</h2>
          <?php
					if($cont_txt=$db->get_var("select TokenText from tokens where code='contact_footer' and SiteID=".SITE_ID." and zStatus='1'")) {
					echo $cont_txt;
					}
					?>
              </div>
              <!--<div class="col-sm-6 col-md-3">
              <h2>Follow Us</h2>
              <p>Join us on social networks:</p>
              <div class="social-icon"><a href="https://www.facebook.com/ChefDipna" target="_blank"><img src="images/facebook.jpg" width="32" height="32" alt="Facebook" title="Facebook"></a> <a href="https://twitter.com/dipnaanand" target="_blank"><img src="images/twitter.jpg" width="32" height="32" alt="Twiiter" title="Twitter"></a></div>
              </div>-->
          </div>
          
    <p class="text-right" style="margin-bottom:0px; padding-bottom:10px;" >All Rights Reserved. <!--Powered by <a style="color: #ed8000;" target="_blank" href="http://www.tenthmatrix.co.uk/" title="Web site designed by Tenthmatrix" class="copyright">Tenthmatrix</a>--></p>
<div class="credits"><span style="font-size:10px;" ><strong>Powered by:</strong></span> <a target="_blank" href="http://www.tenthmatrix.co.uk/" title="Web site designed by Tenthmatrix" ><img src="images/powered-by-logo.png" style=" vertical-align:middle;"></a></div>
  </div>