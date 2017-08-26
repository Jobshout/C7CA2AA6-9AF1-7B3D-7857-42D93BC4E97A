<div class="header"> 
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="index.php"><img src="images/chef_dipna.png" class="img-responsive"></a> </div>

    <div class="navbar-collapse collapse  main-nav" id="navbar-collapse" style="height: auto;">
    <div class="row hidden-xs text-right" style="margin-top:30px;"><a href="https://www.facebook.com/ChefDipna" target="_blank"><img src="images/facebook.jpg" width="24" height="24" alt="facebook"></a> <a href="https://twitter.com/dipnaanand" target="_blank"><img src="images/twitter.jpg" width="24" height="24" alt="Twitter"></a> </div>
	
	<?php
	if($cat_grp=$db->get_var("select ID from categorygroups where SiteID=".SITE_ID." and code='sitenav' and Active='1'")) {
		if($top_categories=$db->get_results("select c.* from categories c join documents d on c.Code=d.Code where c.CategoryGroupID=".$cat_grp." and c.SiteID=".SITE_ID." and d.SiteID=".SITE_ID." and c.Active='1' and d.Status='1' order by c.ID")) {
		?>
		 <ul class="nav navbar-nav navbar-right">
		 <?php
			foreach($top_categories as $top_category) {
				?>
			 <li class="nav-item">
			 <?php
			 	if($docs_in_cat=$db->get_results("select d.* from documents d join documentcategories dc on d.ID=dc.DocumentID where dc.CategoryID=".$top_category->ID." and dc.SiteID=".SITE_ID." and d.SiteID=".SITE_ID." and d.Type='page' and d.Status='1' order by d.ID")) {
			 ?>
			 	<a data-hover="dropdown" data-delay="0" data-close-others="false" href="<?php echo $top_category->Code; ?>.html"><?php echo $top_category->Name; ?> <b class="glyphicon glyphicon-chevron-down"></b></a>
			 	<ul class="dropdown-menu">
			 <?php
			 		foreach($docs_in_cat as $doc_in_cat) {
			 ?>
			 		<li><a href="<?php echo $doc_in_cat->Code; ?>.html"><?php echo $doc_in_cat->Document; ?></a></li>
			 <?php
			 		}
			 ?>
			 	</ul>
			 <?php
			 	}
			 else {
			 ?>
			 	<a href="<?php echo $top_category->Code; ?>.html"><?php echo $top_category->Name; ?></a>
			 <?php
			 }
			 ?>
			 </li>
			<?php
			}
			?>
			</ul>
			<?php
		}
	}
	?>	
                    <!--//nav-->
                </div>
  </div>