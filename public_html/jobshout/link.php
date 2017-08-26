<?php 
require_once("include/lib.inc.php");


if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from links where GUID='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: link.php");
	}
}


// to update selected catgory
if(isset($_POST['submit']))
{ 
	
	$site_id=$_POST["site_id"];
	$link_label=$_POST["link_label"];
	$link_url=$_POST["link_url"];
	$order_num=$_POST["order_num"];
	$short_url=$_POST["short_url"];
	$link_type=$_POST["link_type"];
	$Source=$_POST["source"];
	$Active=$_POST["status"];
	$DocumentID=intval($_POST["docu"]);
	$Document_GUID=$db->get_var("select GUID from documents where ID='$DocumentID'");
	$time= time();
	
	$site_guid=$db->get_var("select GUID from sites WHERE ID ='$site_id'");
	
	
	if(isset($_GET['GUID'])) {
		$GUID= $_GET['GUID'];
	if($db->query("UPDATE links SET SiteID='".$site_id."', Modified='$time', Label='$link_label', Link='$link_url', short_url='$short_url', Type='$link_type', OrderNum='$order_num', DocumentID='$DocumentID', Document_GUID='$Document_GUID', Site_GUID='$site_guid', Source='$Source', Active='$Active' WHERE GUID ='$GUID'")) {
		
		$link_id=$db->get_var("select ID from links WHERE GUID ='$GUID'");
		
		$del_cats=$db->query("delete from links_categories where LinkID='$link_id' or Link_GUID='$GUID'");
		
		if(isset($_POST['cats'])) {
			$link_cat_ids=$_POST['cats'];
			foreach($link_cat_ids as $link_cat_id){
				if($link_cat_id!=''){
					$curr_guid= UniqueGuid('categories', 'GUID');
					$cat_guid=$db->get_var("select GUID from categories where ID='$link_cat_id'");
					$insert_cat=$db->query("insert into links_categories(Created, Modified, SiteID, CategoryID, LinkID, GUID, Site_GUID, Category_GUID, Link_GUID) values('$time', '$time', '".$site_id."', '$link_cat_id', '$link_id', '$curr_guid', '$site_guid', '$cat_guid', '$GUID' )");
				}
			}
		}
	
	$_SESSION['up_message'] = "Successfully updated";
	}
	 //$db->debug();
	}
	else
	{
	
	$GUID=UniqueGuid('links', 'GUID');

	 if($db->query("INSERT INTO links (GUID, Created, Modified, SiteID, Label, Link, short_url, Type, DocumentID, Document_GUID, Source, Active, Site_GUID,OrderNum) VALUES ('$GUID', '$time', '$time', '".$site_id."', '$link_label', '$link_url', '$short_url', '$link_type', '$DocumentID', '$Document_GUID', '$Source', '$Active', '$site_guid','$order_num')")) {
	 
	 $link_id=$db->get_var("select ID from links WHERE GUID ='$GUID'");
	if(isset($_POST['cats'])) {
		$link_cat_ids=$_POST['cats'];
		foreach($link_cat_ids as $link_cat_id){
			if($link_cat_id!=''){
				$curr_guid= UniqueGuid('categories', 'GUID');
				$cat_guid=$db->get_var("select GUID from categories where ID='$link_cat_id'");
				$insert_cat=$db->query("insert into links_categories(Created, Modified, SiteID, CategoryID, LinkID, GUID, Site_GUID, Category_GUID, Link_GUID) values('$time', '$time', '".$site_id."', '$link_cat_id', '$link_id', '$curr_guid', '$site_guid', '$cat_guid', '$GUID' )");
			}
		}
	}
	
	$_SESSION['ins_message'] = "Successfully Inserted";
	header("Location:links.php");
	}
	//$db->debug();
	
	}
}
//to fetch category content
if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];
$link = $db->get_row("SELECT * FROM links where GUID ='$guid'");

		$site_id=$link->SiteID;
		$link_label=$link->Label;
		$link_url=$link->Link;
		$order_num=$link->OrderNum;
		$short_url=$link->short_url;
		$link_type=$link->Type;
		$DocumentID=intval($link->DocumentID);
		$Source=$link->Source;
		$Active=$link->Active;
		$where_cond=" and SiteID ='".$site_id."' ";

$arr_link_cats=array();
$link_cats = $db->get_results("SELECT CategoryID FROM `links_categories` WHERE SiteID='".$site_id."' AND LinkID='".$link->ID."'");
//$db->debug();
if($link_cats != ''){
 foreach($link_cats as $link_cat){
	$arr_link_cats[]= $link_cat->CategoryID;
	
}
}		

}
else
{
	$guid='';
	$site_id='';
		$link_label='';
		$link_url='';
		$order_num='';
		$short_url='';
		$link_type='';
		$DocumentID='';
		$Source=2;
		$Active=2;
		$where_cond='';
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
	$where_cond=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$arr_link_cats=array();
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
			<a href="links.php">Links</a>
		</li>
		<li>
			<a href="#">Link</a>
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
                        
                        <div class="span6">
							
							<form class="form_validation_reg" method="post" action="">
							
							<?php
											//$user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
											if($user_access_level>=11 && !isset($_SESSION['site_id'])) {
											?>
											<div class="formSep">
									<div class="row-fluid">
										<div class="span10">
												<label class="control-label">Site Name (code)<span class="f_req">*</span></label>
												
												
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
										<div class="span10">
												<label class="control-label">Site Name (code)<span class="f_req">*</span></label>
												
												
													<select onChange="" name="site_id" id="site_id_sel" >
												<option value=""></option>
												<?php
												if($sites=$db->get_results("select id, GUID, name,Code from sites where ID='$site_id' ")){
													foreach($sites as $site){ ?>
														<option <?php if($site_id==$site->id) { ?> selected="selected" <?php } ?> value="<?php echo $site->id; ?>"><?php echo $site->name.' ('.$site->Code.')'; ?></option>	
													<?php }
												}else {
													$sites=$db->get_results("select id,name from sites where id in ('".$_SESSION['site_id']."') order by zStatus asc, Name ASC limit 0,100");
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
										<div class="span10">
											<label>Label<span class="f_req">*</span></label>
											<input type="hidden" value="<?php if($guid!='') { echo $guid; } ?>" name="GUID" class="textbox">

											<input type="text" name="link_label" class="span12" id="link_label" value="<?php echo $link_label; ?>"/>
											
											
										</div>
										
											</div>
										</div>
										
										
									
								<div class="formSep">
									<div class="row-fluid">
										<div class="span10">
											<label>URL<span class="f_req">*</span></label>
					 							
													<input type="text" class="span12" name="link_url" id="link_url" value="<?php echo $link_url;?>" />
										</div>
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span10">
											<label>Short URL<span class="f_req">*</span></label>
					 								<span class="span6" style="margin-left:0px;" >
													<input type="text" class="span12" name="short_url" id="short_url" value="<?php echo $short_url;?>" />
													<div id="unique_err" style="font-size:11px;font-weight:700;color:#C62626"></div>
													</span>
													&nbsp;
													<button class="btn btn-gebo" type="button" name="auto_generate" id="auto_generate">Auto Generate</button>
													
													
										</div>
										
									</div>
								</div>
								
								
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span10">
											<label>Type<span class="f_req">*</span></label>
											
											
											<select onChange="" name="link_type" id="link_type" >
					<option value="seo_friendly_link" <?php if($link_type=='seo_friendly_link') { ?>  selected="selected" <?php } ?> >SEO Friendly Link</option>
											
				<option value='short_link' <?php if($link_type=='short_link') { ?>  selected="selected" <?php } ?> >Short Link</option>
				
											</select>
											
											
											
										</div>
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span10">
											<label>Document</label>

											 <div class="ui-widget">
											<select onChange="" name="docu" id="docu"  style="width:350px">
												<option value=""></option>
												<?php
													
														
														if($doc = $db->get_row("SELECT ID,Document,Code FROM `documents` WHERE ID='$DocumentID' ")){
														?>
														<option  value="<?php echo $doc->ID; ?>" selected><?php echo $doc->Document." (".$doc->Code.")"; ?></option>	
														<?php
													}
													elseif(isset($_GET['GUID'])) {
													if($docs = $db->get_results("SELECT ID,Document,Code FROM `documents` WHERE 1 $where_cond ORDER BY `Document` limit 0,100 ")){
													foreach($docs as $doc) {
													?>
													<option  value="<?php echo $doc->ID; ?>" ><?php echo $doc->Document." (".$doc->Code.")"; ?></option>
													<?php
													} } }
												?>
											</select>
											</div>
										</div>
										
									</div>
									
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
										
										<div class="span10">
											<label>Categories</label>
											
											<select name="cats[]" id="cats" multiple="multiple" style="width:500px;" size="6">
		<option value="">-- Select Category --</option>
			<?php
			if(isset($_GET['GUID']) || isset($_SESSION['site_id'])) {
			
			if($categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE 1 $where_cond ORDER BY Name"))
			{
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				?>
				
				<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE 1 and CategoryGroupID='$categorygroupId' $where_cond ORDER BY Name")){
				?>
				<optgroup label="<?php echo $categorygroupName; ?>">
				<?php
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
						
				?>
				<option <?php if(in_array($categoryID, $arr_link_cats)) { echo "selected"; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categoryName;  if($top_level){ echo ' ('.$top_level.')'; } ?>
				</option>
				<?php
				}
				?>
				</optgroup>
				<?php
				}
				?>
				
				<?php
			} }
			?>
			
			<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE 1 and CategoryGroupID not in (select ID from categorygroups ) $where_cond ORDER BY Name")){
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
						
				?>
				<option <?php if(in_array($categoryID, $arr_link_cats)) { echo "selected"; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categoryName; if($top_level){ echo ' ('.$top_level.')'; }  ?>
				</option>
				<?php
				}
				}
				?>
			
			
			<?php } 
		?>
		</select>
										</div>
										
									</div>
									
								</div>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span10">
											<label>Order<span class="f_req">*</span></label>
											<input type="text" class="span12" name="order_num" id="order_num" value="<?php echo $order_num;?>" />
										</div>
									</div>
								</div>
				<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Source<span class="f_req">*</span></label>
											<label class="radio inline">
												<input type="radio" value="1" name="source" <?php if($Source == 1 || $Source == 2) { echo ' checked'; } ?> />
												Internal
											</label>
											<label class="radio inline">
												<input type="radio" value="0" name="source" <?php if($Source == 0) { echo ' checked'; } ?> />
												External
											</label>
										</div>
									</div>
								</div>
								
					<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Status<span class="f_req">*</span></label>
											<label class="radio inline">
												<input type="radio" value="1" name="status" <?php if($Active == 1 || $Active == 2) { echo ' checked'; } ?> />
												Active
											</label>
											<label class="radio inline">
												<input type="radio" value="0" name="status" <?php if($Active == 0) { echo ' checked'; } ?> />
												Inactive
											</label>
										</div>
									</div>
								</div>
								
								
								
								<div class="form-actions">
									<button class="btn btn-gebo" type="submit" name="submit">Save changes</button>
									<!--<button class="btn" onclick="window.location.href='categories.php'">Cancel</button>-->
								</div>
							</form>
                        </div>
                    </div>
                        
                </div>
            </div>
            
			<!-- sidebar -->
            <aside>
                <?php require_once('include/sidebar.php');?>
			</aside>
            
            <?php require_once('include/footer.php');?>
			 
			
            
			
			<script>
	
 
  $(function() {
    $( "#docu" ).combobox();
   
	
  });

  </script>
			
			<script>
				$(document).ready(function() {
					//* regular validation
					gebo_validation.reg();
					
					
					$('#link_url').keypress(function(e){
						var k = e.which;
    					/* numeric inputs can come from the keypad or the numeric row at the top */
   						 if ( (k<48 || k>57) && (k<65 || k>90) && (k<97 || k>122) && (k!=45) && (k!=46) && (k!=95) && (k!=8) && (k!=0)) {
        					e.preventDefault();
							alert("Allowed characters are A-Z, a-z, 0-9, _, -, .");
        					return false;
    					}
					
					});
					
					$('#short_url').keypress(function(e){
						var k = e.which;
    					/* numeric inputs can come from the keypad or the numeric row at the top */
   						 if ( (k<65 || k>90) && (k<97 || k>122) && (k!=46) && (k!=8) && (k!=0)) {
        					e.preventDefault();
							alert("Allowed characters are a-z, 0-9, .");
        					return false;
    					}
					
					});
					
					
					$('#short_url').blur(function(){
						if($(this).val()!=''){
							var short_url=$(this).val();
							var dataString = 'short_url='+short_url;
							<?php if(isset($_GET['GUID'])) { ?>
							dataString += '&guid=<?php echo $_GET['GUID']; ?>';
							<?php } ?>
							get_check_short_url(dataString);					
						}
						else{
							$('#unique_err').html('');
						}
					});	
					
					$('#auto_generate').click(function(){
						var dataString = '';
						get_check_short_url(dataString);					
					});	
					
					
					$('#document').blur(function() {
						if($('#document').val()!=''){
						$.__contactSearch();
						}
					});
					$.__contactSearch = function() {
					var ID = '';
						var keyword = $("#document").val() ;
						var ids=$("#docu").val();
						if(ids){
							ID = ids;
							
						}
						
						$.ajax({
						  url: "search-documents.php",
						  data: 'keyword='+keyword + '&id=' +ID,
						  cache: false
						}).done(function( html ) {
						
							$("#docu").empty();
							$("#docu").append(html);
						});
						$('#docu').show();
					}
					
					$("#site_id_sel").change(function(){
						var site_id=$(this).val();
						$.ajax({
						type: "POST",
						url: "categories_list.php",
						data: {'site_id' : site_id, 'type' : 'page'},
						success: function(response){
						
							$("#cats").html(response);
						}
					 });
					 $( "#docu" ).empty();
					$( "#docu" ).append('<option value=""></option>');
					$('.custom-combobox-input').val('');
					});
					
				});
				
				function get_check_short_url(dataString){
					$.ajax({
						type: "POST",
						url: "get_or_check_short_url.php",
						data: dataString,
						dataType: "json",
						success: function(response){
							console.log(response);
							$('#short_url').val(response.value);
							$('#unique_err').html(response.msg);
						}
					 });
				}
				
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
								link_label: { required: true },
								link_url: { required: true },
								short_url: { required: true },
								source: { required: true },
								status: { required: true },
								
								Sync_Modified: { required: true },
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