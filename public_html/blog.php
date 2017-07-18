<?php 
include_once("include/connect.php");

@extract($_REQUEST);
	if(isset($_REQUEST['Month']) && isset($_REQUEST['Year']) && $_REQUEST['Month']!='' && $_REQUEST['Year']!=''){
		$month = $_REQUEST['Month'];
		$year = $_REQUEST['Year'];
	}
	else{
		$month = '';
		$year = '';
	} 
	if(isset($_REQUEST['Day']) && $_REQUEST['Day']!='') {
		$day=$_REQUEST['Day'];
	}
	else{
		$day='';
	}
	
	 
	
		$months_arr1=array("January","February","March","April","May","June","July","August","September","October","November","December");
		$page_heading="Blog Post";
		$post_cat_id=$db->get_var("select ID from categories where code='posts' and SiteID=".SITE_ID."");
        $limit = 20;
        $query='';
        $query.= "select d.* from documents d join documentcategories dc on d.ID=dc.DocumentID where dc.CategoryID=".$post_cat_id." and dc.SiteID=".SITE_ID." and d.SiteID=".SITE_ID." and d.Type='blog' and d.Status='1'";
    
        if(isset($month) && isset($year) && $month!='' && $year!='')
        {
			if(isset($Day) && $Day!='') {
            	$StartDate = mktime(0,0,0,$month,$Day,$year);
           		$EndDate = mktime(23,59,59,$month,$Day,$year);
				$page_heading=$Day." ".$months_arr1[$month]." ".$year;
			}
			else{
				$StartDate = mktime(0,0,0,$month,1,$year);
           		$EndDate = mktime(0,0,0,$month,31,$year);
				$page_heading=$months_arr1[$month]." ".$year;
			}
            
            $query.=" AND (d.Published_timestamp >= $StartDate AND 
                               d.Published_timestamp <= $EndDate)";
			
        }
		
		if(isset($_REQUEST['author']) && $_REQUEST['author']!='') {
			$author_id=$db->get_var("select ID from wi_users where code='".$_REQUEST['author']."'");
			$query.=" AND d.UserID='".$author_id."'";
		}
    
        $query.=" GROUP BY d.Published_timestamp Order by d.Published_timestamp Desc";
        
        $db->query('SET NAMES utf8');

        $dbResultsData = $db->get_results( $query );



include_once("include/main-header.php"); 
?>
    
</head>
<body>
<?php include_once("include/analyticstracking.php"); ?>
<div class="container" >
  <?php include_once("include/top-header.php"); ?>
            
            <div class="pg-hding">
           Blog Post
            </div>
        
  <div class="content-bg">
    <div class="row">
      
      <div class="col-lg-8 col-md-8 col-lg-push-4 col-md-push-4">
        <div class="content">
		<h1><?php echo $page_heading; ?></h1>
		
		<?php
			if($dbResultsData) {
			
				foreach($dbResultsData as $post) {
				
				$num_comment = $db->get_var("Select count(*) as num from `blog_comments` where `blog_id` = ".$post->ID." AND Status=1 Order by ID DESC");
				$author_data=$db->get_row("select * from wi_users where ID='".$post->UserID."'");
				
				?>
				<h3><a href="<?php echo $post->Code; ?>.html"><?php echo $post->Document; ?></a></h3>
				
				<p style="line-height:14px; vertical-align:top;"><span class="glyphicon glyphicon-calendar" style="padding-right:5px; font-size:11px;"></span><a href="blog.php?Day=<?php echo date('d',$post->Published_timestamp); ?>&Month=<?php echo date('n',$post->Published_timestamp); ?>&Year=<?php echo date('Y',$post->Published_timestamp); ?>"><?php echo date('d M Y',$post->Published_timestamp); ?></a>
			<?php if(isset($author_data) && $author_data!='') { ?>
      &nbsp;&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-user" style="padding-right:5px; font-size:11px;"></span><a href="blog.php?author=<?php echo $author_data->code; ?>" title="Posts by <?php echo $author_data->firstname.' '.$author_data->lastname; ?>"  rel="author"><?php echo $author_data->firstname.' '.$author_data->lastname; ?></a>
	 <?php } ?>
      &nbsp;&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-comment" style="padding-right:5px; font-size:11px;"></span><a href="<?php echo $post->Code; ?>.html#respond" title="Comment on <?php echo $post->Document; ?>"><?php echo $num_comment; ?> Comment(s)</a></p>
				

				<p><?php if(strlen(strip_tags($post->Body))>250) { echo substr(strip_tags($post->Body),0,250).'&hellip;'; } else { echo strip_tags($post->Body); } ?></p>
				
				<?php
				}
			}else {
			
				echo '<h3>No record found.</h3>';
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