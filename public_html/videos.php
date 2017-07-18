<?php include_once("include/connect.php");
include_once("include/main-header.php"); ?>
 <style type="text/css">
 /*videos page styling*/
	/********/
	
	.panel-group{
		color:#000;
	}
	.panel-default>.panel-heading {
		background:#ed8000;
		color:#fff!important;
		text-shadow:none;
		position:relative;
	}
	.panel-default>.panel-heading a {
			color:#fff!important;
			display:block;
			text-shadow:none;
			font-weight:400;
			padding-right:25px;
	}
	.panel-group .panel-default{
		margin-bottom:10px;
	}
	
	.panel-heading .accordion-toggle:after {
		font-family: 'Glyphicons Halflings';	
  content: "\e114"; /* adjust as needed, taken from bootstrap.css */
    float: right;        /* adjust as needed */
    color:#fff;         /* adjust as needed */
	position:absolute;
	right:10px;
	top:12px;
}
.panel-heading .accordion-toggle .collapsed:after {
	float: right;        /* adjust as needed */
    color:#fff;         /* adjust as needed */
	position:absolute;
	right:10px;
	top:12px;    
    content: "\e080";    /* adjust as needed, taken from bootstrap.css */
}
.panel-body {
		background:#fff!important;
	}
	.panel-body a{
		
		font-weight:normal;
		
		text-shadow:none;
		text-decoration:none;

	}
	.panel-body a:hover{
		text-decoration:none;
	}
	.video-blk{
		max-height:350px;
		over-flow-y:visible;
		overflow-x:hidden;
	}
 </style>
</head>

<body>
<div class="container" >
  <?php include_once("include/top-header.php"); ?>
            
            <div class="pg-hding">
            Videos
            </div>
        
  <div class="content-bg">
    <div class="row">
      
      <div class="col-lg-8 col-md-8 col-lg-push-4 col-md-push-4">
       <div class="content">
          <h1>Videos Section</h1>
          
		  <?php 
if($cat_grp_id= $db->get_var("select ID from categorygroups where code='videos' and SiteID =".SITE_ID."")){
	 if($cats= $db->get_results("select * from categories where CategoryGroupID='".$cat_grp_id."' and SiteID =".SITE_ID." and ID in (select CategoryID from videocategories where SiteID =".SITE_ID." and Video_GUID in (select uuid from videos where SiteID =".SITE_ID." and active='1')) order by `Order_By_Num` asc")){									
?>

<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">


    

			
<?php 
$i=0;
foreach($cats as $cat){ 
$i++;
if($i==1){
	$first_cat=$cat->ID;
}
?>
	<div class="panel panel-default">
	<div class="panel-heading" role="tab" id="heading_<?php echo $i; ?>">
      <h4 class="panel-title">
        <a id="<?php echo $cat->ID; ?>-<?php echo $i; ?>"  class="accordion-toggle collapsed"  role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse_<?php echo $i; ?>">
         <?php echo $cat->Name; ?>     </a>
      </h4>
    </div>
		<div id="collapse_<?php echo $i; ?>" class="panel-collapse collapse " role="tabpanel" aria-labelledby="heading_<?php echo $i; ?>">
      <div class="panel-body video-blk">	
                <div class="row">
       		<div class="content_area_<?php echo $i; ?>"></div>
			
			<div class="ImageLoadingDiv_<?php echo $i; ?>" style="top: 300px; display: block; left: 36%; text-align: center; margin-top:50px; margin-bottom:50px;">
 Loading...<br>
    <img id="imgAjaxLoading" src="images/loading.gif" alt="Loading..." style="border-width: 0px;">
  </div>
  
  <div style="text-align:center; display:none;" class="display_more_images_btn_<?php echo $i; ?>"><form class="cont-form"><button type="button" class="btn btn-default" onClick="show_more()">Show more...</button></form></div>
       
       </div>
	   </div>
	   </div>
	   </div>
<?php } ?>
           


</div>
<?php } } ?>	  
		  
		  
          
          
          
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
<script type="text/javascript">
var cat;
var cat_no;
var JSONdata;
 var limit=4;
 var start=0;
 var end= new Array();
 var jsonRow='';
 var curr_tab= 0;
var hash = window.location.hash;
if(hash!=''){
var arr_hash=hash.split('parentVerticalTab');
curr_tab= Number(arr_hash[1])-1;
}

 var xhr;
 
 var num_tabs= $('.panel').length;

for( var k=0; k<=num_tabs; k++ ){
	end[k]= limit;
}


	
function get_videos(cat_id, catno){
	cat= cat_id;
	cat_no= catno;
	//$.movePage(1);
	//$('.content_area_'+cat_no).html("");
	if($('.content_area_'+cat_no).html() == ''){
	start=0;
	jsonRow = 'return_videos.php?start='+start+'&limit='+limit+'&cat='+cat;	
	load_data(jsonRow);
	}
}


	
</script>





<script type="text/javascript">
    $(document).ready(function() {
        

		
		$('.accordion-toggle').click(function(){
			var arr_id= $(this).attr('id').split('-');
			get_videos(arr_id[0], arr_id[1]);
		});
		
		$('.accordion-toggle').first().trigger('click');
		
		
		//console.log($('.resp-tabs-list').height());
		
		
		//$.movePage(1);
		//jsonRow = 'return_videos.php?start='+start+'&limit='+limit+'&cat='+cat;	
		//load_data(jsonRow);

		
		
		$('.video-blk').scroll(function(){
		console.log($(this).scrollTop());
		console.log($(this)[0].scrollHeight);
		console.log($(this).innerHeight());
		if ($(this).scrollTop() == $(this)[0].scrollHeight - $(this).innerHeight()){
		
		if(xhr.status==200 && $('.display_more_images_btn_'+cat_no).length) {
		//alert("scrolling...");
		$('.ImageLoadingDiv_'+cat_no).show();
	$('.display_more_images_btn_'+cat_no).hide();
		start=end[cat_no];
		end[cat_no]=start+limit;
		jsonRow = 'return_videos.php?start='+start+'&limit='+limit+'&cat='+cat;		
		
		load_data(jsonRow);
		
		}
		}
	});	
		
		
    });
</script>

<script type="text/javascript">
 


function show_more(){
	
	$('.ImageLoadingDiv_'+cat_no).show();
	$('.display_more_images_btn_'+cat_no).hide();
	start=end[cat_no];
	end[cat_no]=start+limit;
	jsonRow = 'return_videos.php?start='+start+'&limit='+limit+'&cat='+cat;	
	load_data(jsonRow);
}
function load_data(jsonRow){
	JSONdata=new Array();
	xhr=$.getJSON(jsonRow,function(result){
			if(result.error){
				/*code = $('#no_rec_prompt').text();
				(new Function(code))();*/
			}
			else{

				var table_html='';

				$.each(result.data, function(i,row)
				{
					
					table_html+= '<div class="col-sm-6"><div class="text-center"><a href="video.php?code='+row.code+'" title="'+row.title+'">'+row.vid_src+'</a></div><h4><a href="video.php?code='+row.code+'" title="'+row.title+'">'+row.display_title+'</a></h4></div>';

				});

				//alert(table_html);				
				$('.content_area_'+cat_no).append(table_html);
				
				
		}
		if(end[cat_no]>= result.total){
			$('.display_more_images_btn_'+cat_no).remove();
		}
		else{
			$('.display_more_images_btn_'+cat_no).show();
		}
		$('.ImageLoadingDiv_'+cat_no).hide();
		});
}

</script>
</body>
</html>