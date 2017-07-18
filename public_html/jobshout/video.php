<?php
require_once("include/lib.inc.php");



if(isset($_GET['UUID']) && $_GET['UUID']!=''){
	$query_chk="select count(*) as num from videos where uuid='".$_GET['UUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: video.php");
	}
}


				if(isset($_POST['submit']))
			
				{
				
				
				/*if($_FILES['video_src']['name']!='')
				 {
					$file_name_arr=explode(".",$_FILES['video_src']['name']);
					$file_name_ext=$file_name_arr[count($file_name_arr)-1];
					
					  $bnr_picture_name = $file_name_arr[0] .'-'.md5(uniqid(rand(), true)).'.'.$file_name_ext;
					  copy($_FILES['video_src']['tmp_name'],  'videos/'.$bnr_picture_name);
				  }*/
				
					/*if($_POST['GUID'] != '' && $_POST['title'] !='' && $_POST['video_src'] !='' && $_POST['url'] !='' && $_POST['video_tags'] !='' && $_POST['categories'] !='')
					{*/
					/*$time= date("Y-m-d H:i:s");
					$unix_timestamp= strtotime($time);*/
					//echo $unix_timestamp;
					
				    $time= date("Y-m-d H:i:s");
					$unix_timestamp= strtotime($time);
					$Code=$_POST["Code"];
					$title = addslashes($_POST["title"]);
					$video_src = addslashes($_POST['video_src']);
					$body = addslashes($_POST['video_body']);
					//$url = $_POST["url"];
					$metatags = addslashes($_POST["metatags"]);
					$metadescr = addslashes($_POST["metadescr"]);
					/*$category_gropuID = $_POST["category_gropuID"];*/
					//$fts = $_POST["fts"];
					$Active = $_POST["status"];
				    //$category_gropuID=$_POST["category_gropuID"];
					$zStatus=$_POST["status"];
					$arr_pub_date=explode('/', $_POST["Sync_Modified"]);
					$Published_timestamp=$arr_pub_date[1].'/'.$arr_pub_date[0].'/'.$arr_pub_date[2];
					$pub_time=$_POST["pub_time"];
					if($pub_time==''){
						$pub_time=date('h:i A');
					}
					$pub_time_string=$Published_timestamp." ".$pub_time;
					$published_timestamp=strtotime($pub_time_string);
					$site_id=$_POST["site_id"];
					$site_guid= $db->get_var("select GUID from sites where ID='$site_id'");
					$UserID=$_POST["userID"];
					$user_guid= $db->get_var("select uuid from wi_users where ID='$UserID'");
					$AutoFormatMetaData=1;
		if(isset($_POST["chk_manual_metatags"])) {
			$AutoFormatMetaData=0;
		}
		
		$Auto_Format=1;
		if(isset($_POST["Auto_Format"])) {
		$Auto_Format=0;
		}
			$video_sort_order=intval($_POST['video_sort_order']);



 if($Active==1){ $status="Active"; } else { $status="Inactive"; }
 
 $fts=$metatags.' '.$metadescr;
 
 
 
        if(isset($_GET['UUID'])){
				$UUID = $_GET["UUID"];	
				//echo "UPDATE videos set SiteID=$site_id,categories = '$category_gropuID', title = '$title', video_src = '$video_src', video_tags = '$video_tags', active = '$Active', modified = '$unix_timestamp' where uuid = '$UUID' ";
					
			if($db->query("UPDATE videos set SiteID=$site_id, code='$Code', title = '$title', video_src = '$video_src', body = '$body', video_tags = '$metatags', metadescription = '$metadescr', active = '$Active',published_timestamp='$published_timestamp', modified = '$unix_timestamp', UserID= '$UserID', AutoFormatMetaData=$AutoFormatMetaData,  AutoFormat='$Auto_Format', video_sort_order='$video_sort_order' where uuid = '$UUID' ")) {
						$_SESSION['up_message']= "Updated successfully ";
						
						}
								
							}
				
  
  
           else
				{
            		//echo "INSERT INTO videos (uuid,SiteID, categories, title, published_timestamp, video_src, url, video_tags, fts, active, created, modified)  VALUES ('$UUID','".$site_id."','$category_gropuID','$title','$unix_timestamp','$video_src','','$video_tags','$fts','$Active','$unix_timestamp', '$unix_timestamp')";
				$UUID = UniqueGuid('videos', 'uuid');
				
			if($db->query("INSERT INTO videos (uuid,SiteID, code, title, video_src, body, url, video_tags, metadescription, fts, active,published_timestamp, created, modified, UserID, AutoFormatMetaData, AutoFormat, video_sort_order, image, image_type, image_name)  VALUES ('$UUID','".$site_id."', '$Code', '$title','$video_src', '$body', '','$metatags', '$metadescr', '$fts','$Active','$published_timestamp','$unix_timestamp','$unix_timestamp', '$UserID', '$AutoFormatMetaData', '$Auto_Format', '$video_sort_order', '', '', '')")) {        
			
			//$db->debug();
			$_SESSION['ins_message']= "Inserted successfully";
			header("Location:videos.php");
			
			}
							
				}
				
				
				

	
	
	$time= time();
	
	
	$db->query("delete FROM videocategories WHERE SiteID='".$site_id."' AND Video_GUID='$UUID'");
	
	
	$categories = $_POST["cat"];
	foreach($categories as $categoriesID){
		if($categoriesID != ''){ 
			
			$selectCategory = $db->get_row("SELECT CategoryGroupID,Server_Number,GUID FROM categories WHERE SiteID='".$site_id."' AND ID='$categoriesID'");
			$CategoryGroupID = $selectCategory->CategoryGroupID;
			$Server_Number = $selectCategory->Server_Number;
			$Category_GUID = $selectCategory->GUID;
			$CategoryGroupGUID= $db->get_var("select GUID from categorygroups where ID='$CategoryGroupID'");
			
			$vcGUID=UniqueGuid('videocategories', 'GUID');
			
			
			$insert_cat= $db->query("INSERT INTO videocategories(ID, Created, Modified, SiteID,CategoryGroupID, CategoryID,GUID,Server_Number,Category_GUID,Video_GUID, Sync_Modified, UserID, Site_GUID, CategoryGroup_GUID, User_GUID) 
			VALUES(Null,'$time','$time','".$site_id."','$CategoryGroupID','$categoriesID','$vcGUID','$Server_Number','$Category_GUID','$UUID','0', '$UserID', '$site_guid',  '$CategoryGroupGUID', '$user_guid')");
			
		}
	}
	
	if($_FILES['fileinput']['size'] > 0)
	{
		$fileName = $_FILES['fileinput']['name'];
		$tmpName  = $_FILES['fileinput']['tmp_name'];
		$fileSize = $_FILES['fileinput']['size'];
		$fileType = $_FILES['fileinput']['type'];

		$fp = fopen($tmpName, 'r');
		$content = fread($fp, filesize($tmpName));
		$content = addslashes($content);
		fclose($fp);
		if(!get_magic_quotes_gpc())
		{
			$fileName = addslashes($fileName);
		}
		
		
		$update_pic=$db->query("update videos set image_name='$fileName', image='$content', image_type='$fileType' where uuid='".$UUID."'");
		//$db->debug();
		
	} 
				
		
				
				
			}
				
				 if(isset($_GET['UUID'])){
				 
				 
				 //echo "SELECT uuid, categories, title, video_src, video_tags, active FROM videos where uuid = '".$_REQUEST['GUID']."'";
			
$user3 = $db->get_row("SELECT * FROM videos where uuid = '".$_GET['UUID']."'");
					$site_id=$user3->SiteId;
                    $uuid=$user3->uuid;
					$Code=$user3->code;
					$title=$user3->title;
					$video_src=$user3->video_src;
					$video_body=$user3->body;
					$metatags=$user3->video_tags;
					$metadescr=$user3->metadescription;
					$AutoFormatMetaData=$user3->AutoFormatMetaData;
					$Auto_Format=$user3->AutoFormat;
					$active=$user3->active;
					$UserID=$user3->UserID;
					$zStatus=$user3->active;
					$video_sort_order= $user3->video_sort_order;
					$published_timestamp=date("d/m/Y",$user3->published_timestamp);
					$time_string = date('h:i A',$user3->published_timestamp);
					$Name=$user3->image_name;
					$mime = $user3->image_type;
		$pictre= $user3->image;
		if($mime!='' && $pictre!='') {
		$b64Src = "data:".$mime.";base64," . base64_encode($pictre);
		}
		else{
		$b64Src = "http://www.placehold.it/80x80/EFEFEF/AAAAAA";
		}
					$where_cond=" and SiteID ='".$site_id."' ";
			$vc = $db->get_results("SELECT CategoryID FROM videocategories WHERE Video_GUID='$uuid'");
$cat_ids=array();
//$db->debug();
if($vc != ''){
 foreach($vc as $vc1){
 	
	$cat_ids[]= $vc1->CategoryID;
	
}
}		
					
					}
					 else
		  {
		  		   $site_id='';
		           $uuid='';
				   $Code='';
				   $title='';
				   $video_src='';
				   $video_body='';
				   $metatags='';
				   $metadescr='';
				   $Telephone='';
				   $active='';
			       $UserID='';
				   $zStatus=2;
				   $AutoFormatMetaData=1;
				   $Auto_Format=1;
				   $video_sort_order='';
				   $published_timestamp=date('d/m/Y');;
				   $time_string=date("h:i A");
				   $Name='';
				   $mime = '';
		$pictre= '';		
		$b64Src = "http://www.placehold.it/80x80/EFEFEF/AAAAAA";
				 $cat_ids=array();  
				   $where_cond='';
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
	$where_cond=" and SiteID in ('".$_SESSION['site_id']."') ";
	}

		  }
	require_once('include/main-header.php');	    
//$db->debug();
?>

    </head>
    <body>
		<div id="maincontainer" class="clearfix">
			<!-- header -->
            <header>
                 <?php require_once('include/top-header.php');?>
            </header>
            
            <!-- main content -->
            <div id="contentwrapper">
                <div class="main_content">
                    <nav>
                      <div id="jCrumbs" class="breadCrumb module">
	<ul>
		<li>
			<a href="home.php"><i class="icon-home"></i></a>
		</li>
		<li>
			<a href="index.php">Dashboard</a>
		</li>
		<li>
			<a href="videos.php">Videos</a>
		</li>
		<li>
			<a href="#">Video</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
                    </nav>
                   
					<!--<h3 class="heading"><?php //if(isset($_GET['UUID'])) { echo 'Update Video'; } else { echo 'Add New Video'; } ?> </h3>-->
					<div id="validation" ><span style="color:#00CC00;font-size:18px">
					<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
					</span></div><br/>
							<br>
                    <div class="row-fluid">
					
						<div class="span12">
							
							
							
							<div class="row-fluid">
							
							
								<div class="span8">
									<form class="form-horizontal form_validation_reg" name="form1" id="form1" enctype="multipart/form-data" method="post" >
									
								
										<fieldset>
										
										
							<?php
											//$user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
											if($user_access_level>=11 && !isset($_SESSION['site_id'])) {
											?>
											
<div class="control-group formSep">
												<label class="control-label">Site Name (code)<span class="f_req">*</span></label>
												<div class="controls">												
													<select name="site_id" id="site_id_sel" >
												<option value=""></option>
												<?php
												if($site=$db->get_row("select id, GUID, name,Code from sites where ID='$site_id'")){ ?>
														<option <?php if($site_id==$site->id) { ?> selected="selected" <?php } ?> value="<?php echo $site->id; ?>"><?php echo $site->name.' ('.$site->Code.')'; ?></option>	
													<?php 
												}else{
													$sites=$db->get_results("select id, GUID, name,Code from sites order by zStatus asc, Name ASC limit 0,100 ");
													foreach($sites as $site){ ?>
													<option value="<?php echo $site->id; ?>"><?php echo $site->name.' ('.$site->Code.')'; ?></option>	
													<?php }
												}				
												?>
												</select>
													
													
												</div>
											</div>
											<?php
											}
										 elseif(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
	 									{
											$site_arr=explode("','",$_SESSION['site_id']);
											if(count($site_arr)>1) {
											?>
											<div class="control-group formSep">
												<label class="control-label">Site Name (code)<span class="f_req">*</span></label>
												
												<div class="controls">
													<select onChange="" name="site_id" id="site_id_sel" >
												<option value=""></option>
												<?php
												if($sites=$db->get_results("select id, GUID, name,Code from sites where ID='$site_id' ")){
													foreach($sites as $site){ ?>
														<option <?php if($site_id==$site->id) { ?> selected="selected" <?php } ?> value="<?php echo $site->id; ?>"><?php echo $site->name.' ('.$site->Code.')'; ?></option>	
													<?php }
												}else {
													$sites=$db->get_results("select id,name from sites where id in ('".$_SESSION['site_id']."') order by zStatus asc, Name ASC limit 0,100 ");
													foreach($sites as $site)
													{
													?>
													<option value="<?php echo $site->id; ?>"><?php echo $site->name; ?></option>	
													<?php } 
												} ?>
											</select>
																				
													
												</div>
											</div>
											
											<?php
											} else {
										?>
										<input type="hidden" name="site_id" id="site_id" value="<?php if($site_id!='') { echo $site_id; } else { echo $_SESSION['site_id']; } ?>" >
										<?php
										} }
										?>	
										
										
												<div class="control-group formSep">
												<label class="control-label">Category<span class="f_req">*</span></label>
												<div class="controls">
												
												 <select onChange="generate_metatags()"  name="cat[]" id="cat" size="6" multiple="multiple" >
													
										<?php 
										if($cat_grp_id= $db->get_var("select ID from categorygroups where code='videos' ".$where_cond."")){
											if($cats= $db->get_results("select * from categories where CategoryGroupID='".$cat_grp_id."' ".$where_cond."")){
												foreach($cats as $cat){
												?>
												<option <?php if(in_array($cat->ID, $cat_ids)) { echo "selected"; } ?> value="<?php echo $cat->ID; ?>"><?php echo $cat->Name; ?></option>
												<?php
												}
											
											}
										}
										?>	

												   </select>
												   <span class="help-block">&nbsp;</span>
																						
											</div></div>
											<div class="control-group formSep">
												<label class="control-label">Title<span class="f_req">*</span></label>
												<div class="controls text_line">
													<input type="hidden" value="<?php if($uuid!=''){ echo $uuid; } ?>" name="UUID" id="UUID" >
													<input type="text"  name="title" id="title" class="span12" value="<?php echo $title; ?>" onKeyUp="generate_code('Auto_Format','title','Code')" onBlur="generate_code('Auto_Format','title','Code'); generate_metatags();">
													<span class="help-block">&nbsp;</span>
												</div></div>
												
												<div class="control-group formSep">
												<label class="control-label">Code<span class="f_req">*</span></label>
												<div class="controls text_line">				
													<input type="text" class="span12" name="Code" id="Code" <?php if($Auto_Format!=0) { ?> readonly="readonly" <?php } ?> value="<?php echo $Code;?>" onChange="format_manual_code('Code')" />
													<span class="help-block">URL (SEO friendly)</span>
													<span class="help-block">
													<input type="checkbox" name="Auto_Format" id="Auto_Format" value="0" <?php if($Auto_Format==0) { ?> checked="checked" <?php } ?>  />
													I want to manually enter code</span>
												</div></div>
												
												<div class="control-group formSep">
												<label class="control-label">Body Source<span class="f_req">*</span></label>
												<div class="controls text_line">
													
													<textarea name="video_src" id="video_src" rows="5" cols="15"><?php echo $video_src; ?></textarea>
													<span class="help-block">&nbsp;</span>
												</div></div>
												
												<div class="control-group formSep">
												<label class="control-label">Body<span class="f_req">*</span></label>
												<div class="controls text_line">
													
													<textarea name="video_body" id="video_body" rows="10" cols="15"><?php echo $video_body; ?></textarea>
													<span class="help-block">&nbsp;</span>
												</div></div>
												
												
												<!--
												<div class="control-group formSep">
												<label class="control-label">Upload Video</label>
												<div class="controls text_line">
												<input name="video_src" type="file" id="video_src" >
												</div></div>-->
												
												<div class="control-group formSep">
												<label for="fileinput" class="control-label">Thumbnail Image </label>
												<div class="controls">
													<div data-fileupload="image" class="fileupload fileupload-new">
														<input type="hidden" />
														<div style="width: 80px; height: 80px;" class="fileupload-new thumbnail"><img src="<?php echo $b64Src; ?>" alt="" width="80" height="80" id="usr_img" /></div>
														<div style="width: 80px; height: 80px; line-height: 80px;" class="fileupload-preview fileupload-exists thumbnail"></div>
														<span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" id="fileinput" name="fileinput" /></span>
														<a data-dismiss="fileupload" class="btn fileupload-exists" href="#">Remove</a>
													</div>	
												</div>
												<?php if($pictre!='' && $mime!='') { ?>
												<!--<a target="_blank" href="download_image.php?GUID=<?php echo $_GET['UUID']; ?>" title="Download Image" ><i class="splashy-download"></i><?php echo $Name; ?></a>-->
												<?php } ?>
											</div>
												
																							
												<div class="control-group formSep">
												<label class="control-label">Meta Tags</label>
												<div class="controls text_line">
													
													<textarea  name="metatags" id="metatags"  rows="5" cols="30" class="span12" <?php if($AutoFormatMetaData!=0) { ?> readonly="readonly" <?php } ?> ><?php echo $metatags; ?></textarea>
												</div></div>
												
												<div class="control-group formSep">
												<label class="control-label">Meta Description</label>
												<div class="controls text_line">
													
													<textarea  name="metadescr" id="metadescr"  rows="5" cols="30" class="span12" <?php if($AutoFormatMetaData!=0) { ?> readonly="readonly" <?php } ?> ><?php echo $metadescr; ?></textarea>
													<span class="help-block">
													<input type="checkbox" name="chk_manual_metatags" id="chk_manual_metatags" value="0" <?php if($AutoFormatMetaData==0) { ?> checked="checked" <?php } ?> />
													I want to manually enter meta tags</span>
												</div></div>
												
												<div class="control-group formSep">
												<label class="control-label">Sort Order </label>
												<div class="controls text_line">
													<input type="text"  name="video_sort_order" id="video_sort_order" class="input-xlarge" value="<?php echo $video_sort_order; ?>" >
												</div></div>
												
												
												<div class="control-group formSep">
												<label class="control-label">User <span class="f_req">*</span></label>
												<div class="controls text_line">
													<select onChange="" name="userID" id="UserID">
												<option value="">-- Select User --</option>
											
											</select>
												</div></div>
												
												
										<div class="control-group formSep">
												<label class="control-label">Status <span class="f_req">*</span></label>
											<div class="controls text_line">	
											<label class="radio inline">
												<input type="radio" value="1" name="status" <?php if($zStatus == 1 || $zStatus == 2) { echo ' checked'; } ?>/>
												Active
											</label>
											<label class="radio inline"> 
												<input type="radio" value="0" name="status" <?php if($zStatus == 0) { echo ' checked'; } ?>/>												Inactive
												
											</label>
										</div></div>
									
												
												
												<div class="control-group formSep">
												<label class="control-label">Published<span class="f_req">*</span></label>
												<div class="controls text_line">
												<div class="input-append date" id="dp2">
									<input class="input-small"  type="text" readonly="readonly"  name="Sync_Modified" id="Sync_Modified" value="<?php echo $published_timestamp; ?>" data-date-format="dd/mm/yyyy"  /><span class="add-on"><i class="splashy-calendar_day"></i></span>
								</div>
								
								<div>
									<span class="help-block">&nbsp;</span>
									<input type="text" class="span3" id="tp_2" name="pub_time" value="<?php echo $time_string; ?>" readonly="readonly" placeholder="Published Time" />
								<span class="help-block">&nbsp;</span>
								</div>
													
													
												</div></div>
												
												
												
												
												<div class="form-actions">
													<button class="btn btn-gebo" type="submit" name="submit" id="submit">Submit</button>
												
												</div>
											
										</fieldset>
									</form>
								</div>
							</div>
						</div>
					</div>
                        
                </div>
            </div>
            
			<!-- sidebar -->
            <aside>
			 <?php require_once('include/sidebar.php');?>
			</aside>
			
			 <?php require_once('include/footer.php');?>
			 
			  <!-- datepicker -->
            <script src="lib/datepicker/bootstrap-datepicker.min.js"></script>
			<!-- timepicker -->
            <script src="lib/datepicker/bootstrap-timepicker.min.js"></script>


  <script>
				$(document).ready(function() {
					//* regular validation
					
					gebo_validation.reg();
					//* datepicker
				
					gebo_datepicker.init();
					
					$('#tp_2').timepicker({
				defaultTime: '<?php echo $time_string; ?>',
				minuteStep: 1,
				disableFocus: true,
				template: 'dropdown'
			});
			
			var site_id=$('[name="site_id"]').val();
					var usr_id='<?php echo $UserID; ?>';
					var	login_usr_id='<?php echo $user_details->ID; ?>';
					$.ajax({
					type: "POST",
					url: "user_list.php",
					data: {'site_id' : site_id, 'usr_id' : usr_id, 'login_usr_id' : login_usr_id},
					success: function(response){
						
						$("#UserID").html(response);
					}
					});
					
					$("#site_id_sel").change(function(){
						var site_id=$(this).val();
						
						var usr_id='<?php echo $UserID; ?>';
						var	login_usr_id='<?php echo $user_details->ID; ?>';
						$.ajax({
						type: "POST",
						url: "user_list.php",
						data: {'site_id' : site_id, 'usr_id' : usr_id, 'login_usr_id' : login_usr_id},
						success: function(response){
							
							$("#UserID").html(response);
						}
					 });				
					});
			

			$('#submit').click(function(){
				tinyMCE.triggerSave();
			});	
			
			$('.splashy-calendar_day').click(function(){
				$('#Sync_Modified').datepicker( "show" );
			});
			
			$("#fileinput").change(function(){
					if (this.files && this.files[0]) {
            			var reader = new FileReader();

            			reader.onload = function (e) {
						$('#usr_img').attr('src', e.target.result);
           				 };

           				 reader.readAsDataURL(this.files[0]);
       				 }
					//$("#usr_img").attr("src",img);
					
					
				});
			
			$("#Auto_Format").click(function(){
						var status=$(this).attr("checked");
						if(status=="checked"){
							$('#Code').attr("readonly",false);
							$('#Code').val("");
						}
						else
						{
							$('#Code').attr("readonly",true);
							$('#Code').val("");
							generate_code('Auto_Format','title','Code');
						}
					
					});
			
			$('#Code').keypress(function(e){
						var k = e.which;
    					/* numeric inputs can come from the keypad or the numeric row at the top */
   						 if ( (k<48 || k>57) && (k<65 || k>90) && (k<97 || k>122) && (k!=45) && (k!=95) && (k!=8) && (k!=0)) {
        					e.preventDefault();
							alert("Allowed characters are A-Z, a-z, 0-9, _, -");
        					return false;
    					}
					
					});
			
			$("#chk_manual_metatags").click(function(){
						var status=$(this).attr("checked");
						if(status=="checked"){						
							$('#metatags').attr("readonly",false);
							$('#metatags').val("");
							$('#metadescr').attr("readonly",false);
							$('#metadescr').val("");
						}
						else
						{						
							$('#metatags').attr("readonly",true);
							$('#metatags').val("");
							$('#metadescr').attr("readonly",true);
							$('#metadescr').val("");
							generate_metatags();
						}
					
					});
			
			$(document).click(function(event){
				//console.log($(event.target).closest('div').attr('id'));
				if($(event.target).closest('div').attr('id')!='dp2') {
					$('#Sync_Modified').datepicker( "hide" );
				}
			});	
			
			$("#site_id_sel").change(function(){
					var site_id=$(this).val();
						
						if(site_id!=''){
							$.ajax({
								type: "POST",
								url: "return_sitecode.php",
								data: {'site_id' : site_id},
								success: function(response){						
									//$("#UserID").html(response);
									var i, t = tinyMCE.editors;
									for (i in t){
										if (t.hasOwnProperty(i)){
											t[i].remove();
										}
									}
									tiny_options['moxiemanager_rootpath']= "/home/dipna/public_html";
									tiny_options['moxiemanager_path']= "/home/dipna/public_html";
									tinymce.init(tiny_options);
								}
							 });
						}
					});
			
			
				});
				
				
					
					
				gebo_datepicker = {
				init: function() {
					$('#Sync_Modified').datepicker({"autoclose": true});	
				}
			};
				
				
				//* validation
				gebo_validation = {
					
					reg: function() {
						reg_validator = $('.form_validation_reg').validate({
							onkeyup: false,
							errorClass: 'error',
							validClass: 'valid',
							highlight: function(element) {
								$(element).closest('div').addClass("f_error");
							},
							unhighlight: function(element) {
								$(element).closest('div').removeClass("f_error");
							},
							errorPlacement: function(error, element) {
								$(element).closest('div').append(error);
							},
							rules: {
							 'cat[]': { required: true },
								 title: { required: true },
								 code: { required: true },
								  site_id: { required: true },
								   
								video_src: { required: true },
								 video_body: { required: true },
								status:{ required: true },
								Sync_Modified:{ required: true },
								
								
								
								
							},
							invalidHandler: function(form, validator) {
								$.sticky("There are some errors. Please corect them and submit again.", {autoclose : 5000, position: "top-right", type: "st-error" });
							}
						})
					}
				};
			</script>

<?php
//BSW 20140805 2:13AM handles images paths correctly now

$pSiteRootFolderPath="";

if((!isset($_COOKIE['sitecode']) || $_COOKIE['sitecode']=='') && $site_id!='') { 
$pSiteRoot=$db->get_row("SELECT Code, RootDirectory FROM sites where ID ='".$site_id."' ");

if($pSiteRoot->RootDirectory!='')
{
$pSiteRootFolderPath=$pSiteRoot->RootDirectory;
}
else{
$pSiteRootFolderPath=$pSiteRoot->Code;
}

}

?>			
			
<script type="text/javascript" src="tinymce/tinymce.min.js"></script>
<script type="text/javascript">
//tinymce.PluginManager.load('moxiemanager', '/js/moxiemanager/plugin.min.js');

var tiny_options=new Array();
tiny_options['selector']= "textarea#video_src";
tiny_options['theme']= "modern";
tiny_options['plugins']= ["advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",       
        "emoticons template paste ",       
        " media moxiemanager",
		"print preview hr anchor pagebreak"       
    ];
tiny_options['toolbar1']= " preview | media";

tiny_options['relative_urls']= true;
tiny_options['remove_script_host']= false;
tiny_options['document_base_url']='https://www.dipna.com/';
tiny_options['moxiemanager_rootpath']= "/home/dipna/public_html";
tiny_options['moxiemanager_path']= "/home/dipna/public_html";

tinymce.init(tiny_options);


var tiny_options1=new Array();
tiny_options1['selector']= "textarea#video_body";
tiny_options1['theme']= "modern";
tiny_options1['plugins']= "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality emoticons template paste textcolor moxiemanager";
tiny_options1['theme_advanced_buttons1']= "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect";
tiny_options1['theme_advanced_buttons2']= "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor";
tiny_options1['theme_advanced_buttons3']= "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen";
tiny_options1['theme_advanced_buttons4']= "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak";
tiny_options1['theme_advanced_toolbar_location']= "top";
tiny_options1['theme_advanced_toolbar_align']= "left";
tiny_options1['theme_advanced_statusbar_location']= "bottom";
tiny_options1['theme_advanced_resizing']=true;
<?php if($connect_to=="Dev"){ ?>
tiny_options1['relative_urls']=true;
<?php }else{ ?>
tiny_options1['relative_urls']=false;
<?php } ?>
tiny_options1['remove_script_host']=false;

tiny_options1['document_base_url']='https://www.dipna.com/';
tiny_options1['moxiemanager_rootpath']= "/home/dipna/public_html/";
tiny_options1['moxiemanager_path']= "/home/dipna/public_html/";

tiny_options1['setup'] = function(editor) {  editor.on('blur', function (e) {  generate_metatags();  }); };
tinymce.init(tiny_options1);


/*tinymce.init({
	selector: "textarea#video_src",
   theme: "modern",
	plugins: [
	
		"advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
       
        "emoticons template paste ",
       
        " media",
		"print preview hr anchor pagebreak"
       
    ],
    toolbar1: " preview | media",

});*/

function generate_metatags(){
	tinyMCE.triggerSave();
				var status=$('#chk_manual_metatags').prop("checked");
				if(status!=true){
					var site=$('[name="site_id"]').val();
					var title= $('#title').val();
					var content =  $('#video_body').val();
					var body = content.replace(/(<([^>]+)>)/ig,"");
					var cat ='';
					if($('#cat').val()){
						cat = $('#cat').val().join();
					}
					
					//var	jsonRow= "return_metatags_data.php?document_name="+document_name+"&body="+body+"&category1="+category1+"&category2="+category2+"&category3="+category3+"&category4="+category4+"&site_id="+site+"&location="+location;
					$.ajax({                
						type: "POST",
						url: "return_meta_data.php",
						data: {site_id : site, title: title, body: body, cat : cat },
						cache: false,
						dataType: "json",  
						success:  function(response){ 
							if(response){
								
								if(response.MetaTagKeywords!=''){
									$('#metatags').val(response.MetaTagKeywords);
								}if(response.MetaTagDescription!=''){
									$('#metadescr').val(response.MetaTagDescription);
								}
							}
						}
					});
				}
			}

</script>
            
		</div>
	</body>
</html>
