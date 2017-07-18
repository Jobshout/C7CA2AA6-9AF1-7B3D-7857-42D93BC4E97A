<?php 
include_once("include/connect.php");
include_once("include/main-header.php"); 
?>
    
</head>
<body>
<?php include_once("include/analyticstracking.php"); ?>
<div class="container" >
  
   <?php include_once("include/top-header.php"); ?>
  <div class="flexslider">
                <ul class="slides">
                
                <li >
                        <img src="images/banner1.jpg" alt="">
                        <p class="flex-caption">
                              <span class="main">Author of Beyond Brilliant Book</span>
                            <br>
                           <!-- <span class="secondary clearfix">Lorem ipsum dolor sit amet, consectetur adipiscing elit</span>-->
                        </p>
                    </li>
                
                
                    <li>
                    <img src="images/banner.jpg" alt="">
                    <p class="flex-caption">
                        <span class="main"> Passionate about cooking </span>
                         
                    <br>
                            <!--<span class="secondary clearfix">Donec accumsan nunc sed ipsum dapibus consectetur</span>-->
                    </p>
                    </li>
                    
                    
                    
                    <li >
                        <img src="images/banner2.jpg" alt="">
                        <p class="flex-caption">
                              <span class="main">Love to Teach Cooking</span>
                            <br>
                           <!-- <span class="secondary clearfix">Lorem ipsum dolor sit amet, consectetur adipiscing elit</span>-->
                        </p>
                    </li>
                    
                    <li >
                       <img src="images/banner3.jpg" alt="">
                        <p class="flex-caption">
                            <span class="main">Passionate about healthy food!</span>
                        </p>
                    </li>
                    
                    
                     <li >
                       <img src="images/banner4.jpg" alt="">
                        <p class="flex-caption">
                            <span class="main">Passionate about cooking</span>
                        </p>
                    </li>
                    
                </ul><!--//slides-->
           </div>
            
  <div class="socials">
					<p> <img src="images/brilliantrestaurant.png" class="pull-left">
					<?php
					if($brilliant_txt=$db->get_var("select TokenText from tokens where code='brilliantrestaurant_txt' and SiteID=".SITE_ID." and zStatus='1'")) {
					echo $brilliant_txt;
					}
					?>
					<Br/>
                    <a target="_blank" href="http://www.brilliantrestaurant.com/" class="pull-right clearfix">Visit Brilliant Restaurant</a></p>
			</div>
        
  <div class="content-bg">
    <div class="row">
      
      <div class="col-lg-8 col-md-8 col-lg-push-4 col-md-push-4" >
        <div class="content">
          <h1>About Dipna</h1>
          <p class="clearfix"><img src="images/chef_dipna.jpg" alt="Chef Dipna" class="pull-left img-responsive mrgnritbtm15">
          <?php
					if($about_txt=$db->get_var("select TokenText from tokens where code='home_page_about' and SiteID=".SITE_ID." and zStatus='1'")) {
					echo $about_txt;
					}
					?>
<br/>
            <a href="about.html" class="pull-right">read more</a></p>
          <img src="images/content_seprator.png" class="img-responsive" >
          <div class="row">
            <div class="col-sm-6 col-md-6">
<?php
if($doc=$db->get_row("select * from documents where code='food-as-medicine' and SiteID=".SITE_ID." and Status='1' ORDER BY ID DESC LIMIT 1")) {
?>
<div class="indx-cnt-blk"> <h1>Healthy Food Tips</h1>
<?php
			if($objects= $db->get_results("SELECT * FROM objects where DocumentID='".$doc->ID."' AND SiteID='".SITE_ID."' order by rand() limit 0,2")){	
	foreach($objects as $object){
	?>
	 <div class="health-tip">
	 <?php
		echo '<h4>'.$object->Title.'</h4>';
		if(strlen($object->TextObject)>200){
		echo '<p>'.substr($object->TextObject,0,200).'&hellip;</p>';
		}
		else{
		echo '<p>'.$object->TextObject.'</p>';
		}
		?>
	</div>
	<?php
	}
}
?>
<a href="food-as-medicine.html" class="pull-right clearfix">view all</a>
              </div>

<?php
}
?>	 
            </div>
            <div class="col-sm-6 scol-md-6">
			
<?php
		if($new_cat_id=$db->get_var("select ID from categories where code='news' and SiteID=".SITE_ID."")) {
		?>
		<div class="indx-cnt-blk"> <h1>News & Press</h1>
		<?php
			if($news=$db->get_results("select d.* from documents d join documentcategories dc on d.ID=dc.DocumentID where dc.CategoryID=".$new_cat_id." and dc.SiteID=".SITE_ID." and d.SiteID=".SITE_ID." and d.Status='1' order by d.Published_timestamp desc limit 0,4")) {
			?>
			<ul>
			<?php
				foreach($news as $new) {
				?>
				<li><a href="<?php echo $new->Code; ?>.html"><?php echo $new->Document; ?></a>&nbsp;<?php echo date('d M Y',$new->Published_timestamp); ?>
				</li>
				<?php
				}
				?>
				</ul>
				<?php
			}
			?>
			 <a href="news.php" class="pull-right clearfix">view all</a>
              </div>
		<?php
		}		
		?>
			
              
                        
                 
              </div>
          </div>
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