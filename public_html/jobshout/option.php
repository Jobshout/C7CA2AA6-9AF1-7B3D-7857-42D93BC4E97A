<?php 
ini_set("display_errors", 1);
require_once("include/lib.inc.php");


if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from options where uuid='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: option.php");
	}
}



// to update selected catgory
if(isset($_POST['submit']))
{ 
	
	$site_id=$_POST["site_id"];
	$code=$_POST["code"];
	$name=addslashes($_POST["name"]);
	$values=array();
	
	foreach($_POST['value'] as $key=>$val){
		if(trim($val) != ''){
			$values[$key]=$val;
		}
	}	
	$arr_vals= json_encode($values);
		
	if(isset($_GET['GUID'])) {
		$guid= $_GET['GUID'];
		
		if($db->query("UPDATE options SET SiteID='".$site_id."', option_name='$name', option_value='$arr_vals'	WHERE uuid ='$guid'")) {			
			$_SESSION['up_message'] = "Updated successfully";		
		}
		
		 //$db->debug();
	}
	else
	{
		//echo "INSERT INTO categories (GUID,Created,Modified,SiteID,Name,Code,CategoryGroupID,Active,UserID,Type,FTS,Sync_Modified,Auto_Format) VALUES ('$GUID','$time','$time','".$site_id."','$Name','$Code','$CategoryGroupID','$Active','$UserID','$Type','$fts','$Sync_Modified',$Auto_Format)";
		
	$guid = UniqueGuid('options', 'uuid');		
	 if($db->query("INSERT INTO options (uuid, SiteID, option_name, option_value) VALUES ('$guid', '".$site_id."', '$name', '$arr_vals')")) {

		$_SESSION['ins_message'] = "Inserted successfully ";
	 	
	}
	//$db->debug();
	
	}
	
	


if(isset($_SESSION['ins_message'])){
	header("Location:options.php");
}
}
//to fetch category content
if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];
$product = $db->get_row("SELECT * FROM options where uuid ='$guid'");

		$site_id=$product->SiteID;
		$name=$product->option_name;
		$values=json_decode($product->option_value);
		
		
		$where_cond=" and SiteID ='".$site_id."' ";
	
// $db->debug();
}
else
{
	$guid='';
	$site_id='';
		$name='';
		$values= array();
		
		
		$where_cond='';
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
	$where_cond=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	
	
}
$cnt=0;

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
			<a href="options.php">Options</a>
		</li>
		<li>
			<a href="#">Option</a>
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
                        <form class="form_validation_reg" method="post" action="" >
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
												<label>Option Name <span class="f_req">*</span></label>			 							
													<input type="text" class="span12" name="name" id="name" value="<?php echo $name;?>"  />
												</div>
											</div>
										</div>
										
									<?php
									if(count($values)>0){
									
									foreach($values as $val){
									?>
									<div class="formSep">
									<div class="row-fluid">									
										<div class="controls">
												<label>Value <?php echo $cnt+1; ?> <?php if($cnt==0){ ?><span class="f_req">*</span><?php } ?></label>															 							
													<input type="text" class="span12" name="value[<?php echo $cnt; ?>]" id="value_<?php echo $cnt; ?>" value="<?php echo $val; ?>"  />												
												</div>
											</div>
										</div>
									<?php $cnt++; } } else { ?>	
									<div class="formSep">
									<div class="row-fluid">									
										<div class="controls">
												<label>Value 1 <span class="f_req">*</span></label>															 							
													<input type="text" class="span12" name="value[0]" id="value_0" value=""  />												
												</div>
											</div>
										</div>
									<?php $cnt++; } ?>	
									<a id="add_more" href="javascript:void(0)" >+ Add more values</a>
								
								
							
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
			 var next=<?php echo $cnt; ?>;
				$(document).ready(function() {
					//* regular validation
					
					gebo_validation.reg();
					
					
					
					$('#add_more').click(function(){
						$(this).before('<div class="formSep"><div class="row-fluid"><div class="controls"><label>Value '+Number(next+1)+'</label><input type="text" class="span12" name="value['+next+']" id="value_'+next+'" /></div></div></div>');
						next++;
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
								'value[0]': { required: true },
								
								
								
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