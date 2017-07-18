<?php 
require_once("include/lib.inc.php");

if(isset($_POST['save_global_settings'])){

	$active=$_POST["status"];
	$system_data = array();
	foreach ($_POST as $key => $value) {
		if($key!="save_global_settings" || $key!="status"){
			$system_data[$key]=$value;
		}
    }
	$system_json_data=json_encode($system_data);
	if($get_guid = $db->get_var("select guid from system_options where name='smtp_details' and GUID <> ''")){
		$update = $db->query("UPDATE system_options SET json_object_data='$system_json_data', status='$active' WHERE GUID ='$get_guid'");
	}else{
		$GUID = UniqueGuid('system_options', 'GUID');
		$insert = $db->query("INSERT INTO system_options (guid, name, json_object_data, status) VALUES('".$GUID."', 'smtp_details', '$system_json_data', '$active')");
	}
	header("Location: settings.php");
	exit;
}

if(isset($_POST['save_site_settings'])){

	$site_id=$_POST["site_id"];
	$site_guid= $db->get_var("select GUID from sites where ID='$site_id'");
	
	$site_host=$_POST["site_host"];
	$site_username=$_POST["site_username"];
	$site_password=$_POST["site_password"];
	$site_port=$_POST["site_port"];
	$site_smtp_status=$_POST["site_smtp_status"];
	
	$site_stmp_data = array();
	$site_stmp_data['smtp_host']=$site_host;
	$site_stmp_data['smtp_username']=$site_username;
	$site_stmp_data['smtp_password']=$site_password;
	$site_stmp_data['smtp_port']=$site_port;
	$_stmp_data = json_encode($site_stmp_data);
	if($get_smtp_guid = $db->get_var("select guid from site_options where name='smtp_details' and GUID <> '' and site_guid= '".$site_guid."'")){
		$update = $db->query("UPDATE site_options SET json_object_data='$_stmp_data', status='$site_smtp_status' WHERE GUID ='$get_smtp_guid'");
	}else{
		$GUID = UniqueGuid('site_options', 'GUID');
		$insert = $db->query("INSERT INTO site_options (guid, site_guid, name, json_object_data, status) VALUES('".$GUID."', '$site_guid',  'smtp_details', '$_stmp_data', '$site_smtp_status')");
	}
	if(isset($_REQUEST['check']) && is_array($_REQUEST['check'])) {
		$ppd_id=$_REQUEST['check'];
		$selected_options= json_encode($ppd_id);
		if($get_menu_guid = $db->get_var("select guid from site_options where name='selected_menu_options' and GUID <> '' and site_guid= '".$site_guid."'")){
			$update = $db->query("UPDATE site_options SET json_object_data='$selected_options', status='$site_smtp_status' WHERE GUID ='$get_menu_guid'");
			// $db->debug();
		}else{
			$GUID = UniqueGuid('site_options', 'GUID');
			$insert = $db->query("INSERT INTO site_options (guid, site_guid, name, json_object_data, status) VALUES('".$GUID."', '$site_guid', 'selected_menu_options', '$selected_options', '$site_smtp_status')");
		}
	}
	header("Location: settings.php?tab#tab2");
	exit;
}

if($smtp_system_details = $db->get_row("SELECT * FROM system_options where name='smtp_details' and GUID <> ''")){
	$smtp_status=$smtp_system_details->status;
	$json_object_data=json_decode($smtp_system_details->json_object_data, true);
	$system_smtp_host= $json_object_data['smtp_host'];
	$system_smtp_username= $json_object_data['smtp_username'];
	$system_smtp_password= $json_object_data['smtp_password'];
	$system_smtp_port= $json_object_data['smtp_port'];
}else{
	$system_smtp_host= '';
	$system_smtp_username= '';
	$system_smtp_password= '';
	$system_smtp_port= '';
	$smtp_status= '';
}
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!=''){
	$site_id=$_SESSION['site_id'];
	$site_guid= $db->get_var("select GUID from sites where ID='$site_id'");
	$selected_site_id=" and site_guid='$site_guid'";
}else{
	$site_id='';
	$selected_site_id='and 1';
}
if($smtp_site_details = $db->get_row("SELECT * FROM site_options where name='smtp_details' and GUID <> '' $selected_site_id")){
	// $db->debug();
	$site_guid=$smtp_system_details->site_guid;
	if($site_id=='' && $site_guid!=''){
	$site_id= $db->get_var("select ID from sites where GUID='$site_guid'");
	}
	$site_smtp_status=$smtp_system_details->status;
	$site_json_object_data=json_decode($smtp_site_details->json_object_data, true);
	$site_smtp_host= $site_json_object_data['smtp_host'];
	$site_smtp_username= $site_json_object_data['smtp_username'];
	$site_smtp_password= $site_json_object_data['smtp_password'];
	$site_smtp_port= $site_json_object_data['smtp_port'];
}else{
	$site_smtp_host= '';
	$site_guid= '';
	$site_id='';
	$site_smtp_username= '';
	$site_smtp_password= '';
	$site_smtp_port= '';
	$site_smtp_status= '';
}
if($smtp_menu_details = $db->get_row("SELECT * FROM site_options where name='selected_menu_options' $selected_site_id")){
	// $db->debug();
	$site_menu=json_decode($smtp_menu_details->json_object_data);
	$site_guid=$smtp_menu_details->site_guid;
	if($site_id=='' && $site_guid!=''){
		$site_id= $db->get_var("select ID from sites where GUID='$site_guid'");
	}
}else{
	$site_menu=array();
	$site_guid='';
	$site_id='';
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
		<li>Settings</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
                    </nav>
					<?php if(isset($error_msg) && $error_msg!=''){ ?>
					<div id="validation" ><span style="color:#FF0000;font-size:18px">
					<?php echo implode("<br>",$error_msg); ?>
					 </span></div><br>
					 <?php } ?>
					<div id="validation" ><span style="color:#00CC00;font-size:18px">
					<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
					 </span></div><br>
					
                    <div class="row-fluid">
						<div class="">
							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li id="li1" class="active"><a href="#tab1" data-toggle="tab">Global Settings</a></li>
									<li id="li2"><a href="#tab2" data-toggle="tab" id="site_settings">Site Settings</a></li>
								</ul>
								<div class="tab-content">
								
									<div class="tab-pane active" id="tab1">
									<a name="tab1" ></a>
										<div class="row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<div class="span9">
														<div class="form-horizontal well">
															<fieldset>
																<form action="" method="post" class="form_validation_reg" enctype="multipart/form-data">
																	<div class="control-group">
																		<label class="control-label">SMTP Host</label>
																		<div class="controls">
																			<input type="text" class="span7" name="smtp_host" id="smtp_host" value="<?php echo $system_smtp_host;?>" />
																			<span class="help-block">&nbsp;</span>
																		</div>
																	</div>
																	<div class="control-group">
																		<label class="control-label">SMTP Username</label>
																		<div class="controls">
																			<input type="text" class="span7" name="smtp_username" id="smtp_username" value="<?php echo $system_smtp_username;?>" />
																			<span class="help-block">&nbsp;</span>
																		</div>
																	</div>
																	<div class="control-group">
																		<label class="control-label">SMTP Password</label>
																		<div class="controls">
																			<input type="text" class="span7" name="smtp_password" id="smtp_password" value="<?php echo $system_smtp_password;?>" />
																			<span class="help-block">&nbsp;</span>
																		</div>
																	</div>
																	<div class="control-group">
																		<label class="control-label">SMTP Port</label>
																		<div class="controls">
																			<input type="text" class="span7" name="smtp_port" id="smtp_port" value="<?php echo $system_smtp_port;?>" />
																			<span class="help-block">&nbsp;</span>
																		</div>
																	</div>
																			
																	<div class="control-group">
																		<label class="control-label">Status <span class="f_req">*</span></label>
																		<div class="controls">	
																			<label class="radio inline">
																				<input type="radio" value="1" name="status" <?php if($smtp_status == 1) { echo ' checked'; } ?>/> Active
																			</label>
																			<label class="radio inline"> 
																				<input type="radio" value="0" name="status" <?php if($smtp_status == 0) { echo ' checked'; } ?>/> Inactive
																			</label>
																		</div>
																	</div>
																	<input class="btn btn-gebo" type="submit" name="save_global_settings" id="submit" value="Save changes">
																	<!--<a class="btn btn-gebo" href="javascript:void(0)" onClick="save_global_settings()">Save changes</a>-->
																</form>
															</fieldset>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									
									
									<div class="tab-pane" id="tab2">
										<a name="tab2" ></a>
										 <div class="row-fluid">
											<div class="span12">
												<div class="row-fluid">
												<form action="" method="post" class="form_validation_reg" enctype="multipart/form-data">
													<div class="span9">
														<div class="form-horizontal well">
															<fieldset>
																<!-- Site dropdown starts here-->
																<?php include_once("sites_dropdown.php"); ?>
																<!-- Site dropdown ends here-->
																<div class="control-group">
																	<label class="control-label">SMTP Host</label>
																	<div class="controls">
																		<input type="text" class="span7" name="site_host" id="site_host" value="<?php echo $site_smtp_host;?>" />
																		<span class="help-block">&nbsp;</span>
																	</div>
																</div>
																<div class="control-group">
																	<label class="control-label">SMTP Username</label>
																	<div class="controls">
																		<input type="text" class="span7" name="site_username" id="site_username" value="<?php echo $site_smtp_username;?>" />
																		<span class="help-block">&nbsp;</span>
																	</div>
																</div>
																<div class="control-group">
																	<label class="control-label">SMTP Password</label>
																	<div class="controls">
																		<input type="text" class="span7" name="site_password" id="site_password" value="<?php echo $site_smtp_password;?>" />
																		<span class="help-block">&nbsp;</span>
																	</div>
																</div>
																<div class="control-group">
																	<label class="control-label">SMTP Port</label>
																	<div class="controls">
																		<input type="text" class="span7" name="site_port" id="site_port" value="<?php echo $site_smtp_port;?>" />
																		<span class="help-block">&nbsp;</span>
																	</div>
																</div>
																<div class="control-group">
																	<label class="control-label">Status <span class="f_req">*</span></label>
																	<div class="controls">	
																		<label class="radio inline">
																			<input type="radio" value="1" name="site_smtp_status" <?php if($site_smtp_status == 1) { echo ' checked'; } ?>/> Active
																		</label>
																		<label class="radio inline"> 
																			<input type="radio" value="0" name="site_smtp_status" <?php if($site_smtp_status == 0) { echo ' checked'; } ?>/> Inactive
																		</label>
																	</div>
																</div>
																
															</fieldset>
														</div>
													</div>
													 <div class="span3">
									
														<div class="well form-inline">
															<p class="f_legend">Select Menu options</p>
															<div class="controls">
																<input class="menu" type="checkbox" value="banners" name="check[]" <?php if(in_array('banners', $site_menu)) { echo "checked"; } ?>> Banners<br/>
																<input class="menu" type="checkbox" value="bookmarks" name="check[]" <?php if(in_array('bookmarks', $site_menu)) { echo "checked"; } ?>> Bookmarks<br/>
																<input class="menu" type="checkbox" value="blogs" name="check[]" <?php if(in_array('blogs', $site_menu)) { echo "checked"; } ?>> Blog Posts<br/>
																<input class="menu" type="checkbox" value="campaigns" name="check[]" <?php if(in_array('campaigns', $site_menu)) { echo "checked"; } ?>> Campaigns<br/>
																<input class="menu" type="checkbox" value="contacts" name="check[]" <?php if(in_array('contacts', $site_menu)) { echo "checked"; } ?>> Contacts<br/>
																<input class="menu" type="checkbox" value="components" name="check[]" <?php if(in_array('components', $site_menu)) { echo "checked"; } ?>> Components<br/>
																<input class="menu" type="checkbox" value="customers" name="check[]" <?php if(in_array('customers', $site_menu)) { echo "checked"; } ?>> Customers<br/>
																<input class="menu" type="checkbox" value="enquiries" name="check[]" <?php if(in_array('enquiries', $site_menu)) { echo "checked"; } ?>> Enquiries<br/>
																<input class="menu" type="checkbox" value="images" name="check[]" <?php if(in_array('images', $site_menu)) { echo "checked"; } ?>> Images<br/>
																<input class="menu" type="checkbox" value="jobs" <?php if(in_array('jobs', $site_menu)) { echo "checked"; } ?> name="check[]"> Job board<br/>
																<input class="menu" type="checkbox" value="leads" name="check[]" <?php if(in_array('leads', $site_menu)) { echo "checked"; } ?>> Leads<br/>
																<input class="menu" type="checkbox" value="knowledge_base" name="check[]" <?php if(in_array('knowledge_base', $site_menu)) { echo "checked"; } ?>> Knowledge Base<br/>
																<input class="menu" type="checkbox" value="mailing_list" name="check[]" <?php if(in_array('mailing_list', $site_menu)) { echo "checked"; } ?>> Mailing List<br/>
																<input class="menu" type="checkbox" value="newsletter" name="check[]" <?php if(in_array('newsletter', $site_menu)) { echo "checked"; } ?>> Newsletter Subscribers<br/>
																<input class="menu" type="checkbox" value="pdf_documents" name="check[]" <?php if(in_array('pdf_documents', $site_menu)) { echo "checked"; } ?>> PDF Documents<br/>
																<input class="menu" type="checkbox" value="talent_search" name="check[]" <?php if(in_array('talent_search', $site_menu)) { echo "checked"; } ?>> Talent Search<br/>
																<input class="menu" type="checkbox" value="templates" name="check[]" <?php if(in_array('templates', $site_menu)) { echo "checked"; } ?>> Templates<br/>
																<input class="menu" type="checkbox" value="tokens" name="check[]" <?php if(in_array('tokens', $site_menu)) { echo "checked"; } ?>> Tokens<br/>
																<input class="menu" type="checkbox" value="uk_locations" name="check[]" <?php if(in_array('uk_locations', $site_menu)) { echo "checked"; } ?>> UK Locations<br/>
																<input class="menu" type="checkbox" value="videos" name="check[]" <?php if(in_array('videos', $site_menu)) { echo "checked"; } ?>> Videos<br/>
																<input class="menu" type="checkbox" value="browser_categories" name="check[]" <?php if(in_array('browser_categories', $site_menu)) { echo "checked"; } ?>> View Content by Category<br/>
																<input class="menu" type="checkbox" value="categories" name="check[]" <?php if(in_array('categories', $site_menu)) { echo "checked"; } ?>> Web Categories<br/>
																<input class="menu" type="checkbox" value="category_groups" name="check[]" <?php if(in_array('category_groups', $site_menu)) { echo "checked"; } ?>> Web Category Groups<br/>
																<input class="menu" type="checkbox" value="pages" name="check[]" <?php if(in_array('pages', $site_menu)) { echo "checked"; } ?>> Web Pages<br/>
																<input class="menu" type="checkbox" value="sites" name="check[]" <?php if(in_array('sites', $site_menu)) { echo "checked"; } ?>> Web Sites<br/>
																<input class="menu" type="checkbox" value="users" name="check[]" <?php if(in_array('users', $site_menu)) { echo "checked"; } ?>> Web Users<br/>
																<span class="help-block">&nbsp;</span>
															</div>
														</div>
													</div>
													<input class="btn btn-gebo" type="submit" name="save_site_settings" id="submit" value="Save changes">
													<!--<a class="btn btn-gebo" href="javascript:void(0)" onClick="save_site_settings()">Save changes</a>-->
													</form>
												</div>

											</div>
										</div>
									</div>
																							
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

            </div>
			<script type="text/javascript">
			 function save_menu_settings(){
				selected='';
				$('.menu').each(function(){
					if($(this).is(":checked")){
						if(selected==''){
							selected=$(this).val();
						}
						else{
							selected+=","+$(this).val();
						}
					}
				});
				console.log(selected);
			 }
			 $(document).ready(function() {
				<?php if(isset($_GET['tab']) && !isset($_POST['save_site_settings'])){ ?>
				$('#site_settings').trigger('click');
				<?php } ?>
			});
			</script>
	</body>
</html>