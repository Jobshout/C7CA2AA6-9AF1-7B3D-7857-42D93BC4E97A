<?php 
ini_set("display_errors", 1);
require_once("include/lib.inc.php");


if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from manufacturers where GUID='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: manufacturer.php");
	}
}



// to update selected catgory
if(isset($_POST['submit']))
{ 
	
	$site_id=$_POST["site_id"];
	$code=$_POST["code"];
	$name=addslashes($_POST["name"]);
	
	// $new_value=addslashes($_POST["TokenText"]);
	
	$url=$_POST["url"];
	
	$UserID=$_POST["userID"];
	$Auto_Format=1;
		if(isset($_POST["chk_manual"])) {
		$Auto_Format=0;
		}
		
	$time= time();
	
	$site_guid= $db->get_var("select GUID from sites where ID='$site_id'");
	$user_guid= $db->get_var("select uuid from wi_users where ID='$UserID'");
	
		
	
		
	if(isset($_GET['GUID'])) {
		$guid= $_GET['GUID'];
		
	
	if($db->query("UPDATE manufacturers SET SiteID='".$site_id."', modified='$time', Code='$code', name='$name', url='$url', user_id='$UserID', Auto_Format=$Auto_Format, site_guid= '$site_guid', user_guid= '$user_guid'
	WHERE GUID ='$guid'")) {	
		
	$_SESSION['up_message'] = "Updated successfully";
	
	
	}
	
	 //$db->debug();
	}
	else
	{
		//echo "INSERT INTO categories (GUID,Created,Modified,SiteID,Name,Code,CategoryGroupID,Active,UserID,Type,FTS,Sync_Modified,Auto_Format) VALUES ('$GUID','$time','$time','".$site_id."','$Name','$Code','$CategoryGroupID','$Active','$UserID','$Type','$fts','$Sync_Modified',$Auto_Format)";
		
	$guid = UniqueGuid('manufacturers', 'GUID');		
	 if($db->query("INSERT INTO manufacturers (GUID, created, modified, SiteID, Code, name, url, user_id, site_guid, user_guid, Auto_Format, img_name, image, img_type, url_clicked) VALUES ('$guid', '$time', '$time', '".$site_id."', '$code', '$name', '$url', '$UserID', '$site_guid', '$user_guid', '$Auto_Format', '', '', '', '0')")) {
	 
	 
	
		$_SESSION['ins_message'] = "Inserted successfully ";
	 	
	}
	//$db->debug();
	
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
		
		
		$update_pic=$db->query("update manufacturers set img_name='$fileName', image='$content', img_type='$fileType' where GUID='".$guid."'");
		//$db->debug();
		
	} 
	
	
	}
	
	

if(isset($_SESSION['ins_message'])){
	//header("Location:manufacturers.php");
}
}
//to fetch category content
if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];
$manufacturer = $db->get_row("SELECT * FROM manufacturers where GUID ='$guid'");

		$manufacturer_id=$manufacturer->id;
		$site_id=$manufacturer->SiteID;
		$code=$manufacturer->Code;
		$name=$manufacturer->name;
		$url=$manufacturer->url;
		
		$UserID=$manufacturer->user_id;
		$Auto_Format=$manufacturer->Auto_Format;
		$img_name=$manufacturer->img_name;
		$mime = $manufacturer->img_type;
		$pictre= $manufacturer->image;
		if($mime!='' && $pictre!='') {
		$b64Src = "data:".$mime.";base64," . base64_encode($pictre);
		}
		else{
		$b64Src = "http://www.placehold.it/80x80/EFEFEF/AAAAAA";
		}
		
		$where_cond=" and SiteID ='".$site_id."' ";
			

		
// $db->debug();
}
else
{
	$guid='';
	$manufacturer_id='';
	$site_id='';
		$code='';
		$name='';
		$img_name='';
		$url='';		
		$mime = '';
		$pictre= '';
		$b64Src = "http://www.placehold.it/80x80/EFEFEF/AAAAAA";
		$UserID='';
		$Auto_Format=1;
		
		
		$where_cond='';
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
	$where_cond=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	
	
}

require_once('include/main-header.php'); 
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
			<a href="manufacturers.php">Manufacturers</a>
		</li>
		<li>
			<a href="#">Manufacturer</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
               
                    </nav>
                    
					<!--<h3 class="heading"><?php //if(isset($_GET['GUID'])) { echo "Update"; } else { echo "Add"; } ?> Category</h3>-->
							<div id="validation" ><span style="color:#00CC00;font-size:18px">
							<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
							</span></div><br/>
                    <div class="row-fluid">
                        <form class="form_validation_reg" method="post" action="" enctype="multipart/form-data">
                        <div class="span7">
							
							
							
									<?php
											// $user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
											if($user_access_level>=11 && !isset($_SESSION['site_id'])) {
											?>
											<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
												<label >Site Name (code)<span class="f_req">*</span></label>
																								
													<select onChange="" name="site_id" id="site_id_sel"  style="width:350px">
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
											</div>
											<?php
											}
										 elseif(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
	 									{
											$site_arr=explode("','",$_SESSION['site_id']);
											if(count($site_arr)>1) {
											?>
											<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
												<label >Site Name (code)<span class="f_req">*</span></label>
												
												
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
											</div>
											
											<?php
											} else {
										?>
										<input type="hidden" name="site_id" id="site_id" value="<?php if($site_id!='') { echo $site_id; } else { echo $_SESSION['site_id']; } ?>" >
										<?php
										} }
										?>	
							
							
								<div class="formSep">
									<div class="row-fluid">
										
										<div class="controls">
												<label>Name <span class="f_req">*</span></label>
															 							
													<input type="text" class="span12" name="name" id="name" value="<?php echo $name;?>" onKeyUp="generate_code('chk_manual','name','code')" onBlur="generate_code('chk_manual','name','code')" />
												</div>
											</div>
										</div>
										
									<div class="formSep">
									<div class="row-fluid">
										
										<div class="controls">
												<label>Code <span class="f_req">*</span></label>
															 							
													<input type="text" class="span12" name="code" id="code" value="<?php echo $code;?>" <?php if($Auto_Format!=0) { ?> readonly="readonly" <?php } ?> />
													<span class="help-block">URL (SEO friendly)</span>
													<span class="help-block">
													<input type="checkbox" name="chk_manual" id="chk_manual" value="0" <?php if($Auto_Format==0) { ?> checked="checked" <?php } ?>  /> I want to manually enter code</span>
												</div>
											</div>
										</div>
										
									<div class="control-group formSep">
												<label for="fileinput" class="control-label">Picture </label>
												<div class="controls">
													<div data-fileupload="image" class="fileupload fileupload-new">
														<input type="hidden" />
														<div style="width: 80px; height: 80px;" class="fileupload-new thumbnail"><img src="<?php echo $b64Src; ?>" alt="" width="80" height="80" id="usr_img" /></div>
														<div style="width: 80px; height: 80px; line-height: 80px;" class="fileupload-preview fileupload-exists thumbnail"></div>
														<span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" id="fileinput" name="fileinput" /></span>
														<a data-dismiss="fileupload" class="btn fileupload-exists" href="#">Remove</a>
													</div>	
												</div>
												
											</div>
										
									<div class="formSep">
									<div class="row-fluid">
										
										<div class="controls">
												<label>URL </label>				 							
													
													<input type="text" class="span12" name="url" id="url" value="<?php echo $url;?>" />
													<span class="help-block">&nbsp;</span>
												</div>
											</div>
										</div>	

								
								<div class="formSep">
									<div class="row-fluid">
									  <div class="span8">
											<label>User <span class="f_req">*</span></label>
											<select onChange="" name="userID" id="UserID">
												<option value="">-- Select User --</option>
											
											</select>
										</div>
									</div>
								</div>
								
								
							
								<div class="form-actions">
									<button class="btn btn-gebo" type="submit" name="submit" id="submit">Save changes</button>
									<!--<button class="btn" onclick="window.location.href='categories.php'">Cancel</button>-->
								</div>
							
                        </div>
						
						
						</form>
                    </div>
                        
                </div>
            </div>
            
			<!-- sidebar -->
            <aside>
                <?php require_once('include/sidebar.php');?>
			</aside>
            
            <?php require_once('include/footer.php');?>
			 
		
			
			<script>
			 
				$(document).ready(function() {
					//* regular validation
					
					gebo_validation.reg();
					
					$("#chk_manual").click(function(){
						var status=$(this).attr("checked");
						if(status=="checked"){
							$('#code').attr("readonly",false);
							$('#code').val("");
						}
						else
						{
							$('#code').attr("readonly",true);
							$('#code').val("");
							generate_code('chk_manual','name','code');
						}
					
					});
					
					$('#code').keypress(function(e){
						var k = e.which;
    					/* numeric inputs can come from the keypad or the numeric row at the top */
   						 if ( (k<48 || k>57) && (k<65 || k>90) && (k<97 || k>122) && (k!=45) && (k!=95) && (k!=8) && (k!=0)) {
        					e.preventDefault();
							alert("Allowed characters are A-Z, a-z, 0-9, _, -");
        					return false;
    					}
					
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
					
					
					
				});
				
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
								site_id: { required: true },
								name: { required: true },
								code: { required: true },								
								userID: { required: true },

							},
							invalidHandler: function(form, validator) {
								$.sticky("There are some errors. Please corect them and submit again.", {autoclose : 5000, position: "top-right", type: "st-error" });
							}
						})
					}
				};
			</script>

			
		</div>
	</body>
</html>