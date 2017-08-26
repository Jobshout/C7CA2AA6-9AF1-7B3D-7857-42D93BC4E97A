<?php 
ini_set("display_errors", 1);
require_once("include/lib.inc.php");


if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from products where GUID='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: product.php");
	}
}



// to update selected catgory
if(isset($_POST['submit']))
{ 
	
	$site_id=$_POST["site_id"];
	$code=$_POST["code"];
	$name=addslashes($_POST["name"]);
	$descr=addslashes(str_replace("http://cdn.jobshout.com","",$_POST["descr"]));
	// $new_value=addslashes($_POST["TokenText"]);
	$status=$_POST["status"];
	$price=$_POST["price"];
	$url=$_POST["url"];
	$mnf_id=$_POST["mnf_id"];
	
	$UserID=$_POST["userID"];
	$Auto_Format=1;
		if(isset($_POST["chk_manual"])) {
		$Auto_Format=0;
		}
		
	$time= time();
	
	$mnf_uuid='';
	$site_guid= $db->get_var("select GUID from sites where ID='$site_id'");
	$user_guid= $db->get_var("select uuid from wi_users where ID='$UserID'");
	if($mnf_id>0){
		$mnf_uuid= $db->get_var("select GUID from manufacturers where id='$mnf_id'");
	}
	
		
	
		
	if(isset($_GET['GUID'])) {
		$guid= $_GET['GUID'];
		
	
	if($db->query("UPDATE products SET SiteID='".$site_id."', Modified='$time', Code='$code', Name='$name', Description='$descr', price='$price', product_url='$url', status='$status', UserID='$UserID', Auto_Format=$Auto_Format, Site_GUID= '$site_guid', User_GUID= '$user_guid', mnf_id= '$mnf_id', mnf_uuid= '$mnf_uuid'
	WHERE GUID ='$guid'")) {	
		
	$_SESSION['up_message'] = "Updated successfully";
	
	
	}
	
	 //$db->debug();
	}
	else
	{
		//echo "INSERT INTO categories (GUID,Created,Modified,SiteID,Name,Code,CategoryGroupID,Active,UserID,Type,FTS,Sync_Modified,Auto_Format) VALUES ('$GUID','$time','$time','".$site_id."','$Name','$Code','$CategoryGroupID','$Active','$UserID','$Type','$fts','$Sync_Modified',$Auto_Format)";
		
	$guid = UniqueGuid('products', 'GUID');		
	 if($db->query("INSERT INTO products (GUID, Created, Modified, SiteID, Code, Name, Description, img_name, image, img_type, price, product_url, status, UserID, Site_GUID, User_GUID, Auto_Format, mnf_id, mnf_uuid) VALUES ('$guid', '$time', '$time', '".$site_id."', '$code', '$name', '$descr', '', '', '', '$price', '$url', '$status', '$UserID', '$site_guid', '$user_guid', '$Auto_Format', '$mnf_id', '$mnf_uuid')")) {
	 
	 
	
		$_SESSION['ins_message'] = "Inserted successfully ";
	 	
	}
	//$db->debug();
	
	}
	
	if($product = $db->get_row("SELECT ID,GUID FROM products where GUID ='".$guid."'")) {
	
	
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
		
		
		$update_pic=$db->query("update products set img_name='$fileName', image='$content', img_type='$fileType' where GUID='".$guid."'");
		//$db->debug();
		
	} 
	

	$productId=$product->ID;
		
	$product_GUID=$product->GUID;
	
	$time= time();

	
	$db->query("delete FROM productcategories WHERE SiteID='".$site_id."' AND ProductID='$productId'");
	
	if(isset($_POST['cats'])){
	
	$categories = $_POST['cats'];
	foreach($categories as $categoriesID){
		if($categoriesID != ''){ 
			
			$selectCategory = $db->get_row("SELECT CategoryGroupID,Server_Number,GUID FROM categories WHERE SiteID='".$site_id."' AND ID='$categoriesID'");
			$CategoryGroupID = $selectCategory->CategoryGroupID;
			$Server_Number = $selectCategory->Server_Number;
			$Category_GUID = $selectCategory->GUID;
			
			$dcGUID=UniqueGuid('productcategories', 'GUID');
			
			
			$insert_cat= $db->query("INSERT INTO productcategories(ID, Created, Modified, SiteID,CategoryGroupID, CategoryID,ProductID, GUID,Server_Number,Category_GUID,Product_GUID, Sync_Modified, UserID, Site_GUID, User_GUID) 
			VALUES(Null,'$time','$time','".$site_id."','$CategoryGroupID','$categoriesID','$productId','$dcGUID','$Server_Number','$Category_GUID','$product_GUID','0', '$UserID', '$site_guid', '$user_guid')");
			
		}
	}
	}
	
	$db->query("delete FROM product_options WHERE SiteID='".$site_id."' AND product_guid='$product_GUID'");
	
	if(isset($_POST['opts'])){
	
	$opts = $_POST['opts'];
	foreach($opts as $opt){
		
			
			$poGUID=UniqueGuid('product_options', 'GUID');
			
			$insert_cat= $db->query("INSERT INTO product_options(GUID, product_guid, option_uuid, SiteID) VALUES('$poGUID', '$product_GUID', '$opt', '$site_id')");
			
		
	}
	}
}

if(isset($_SESSION['ins_message'])){
	header("Location:products.php");
}
}
//to fetch category content
if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];
$product = $db->get_row("SELECT * FROM products where GUID ='$guid'");

		$product_id=$product->ID;
		$site_id=$product->SiteID;
		$code=$product->Code;
		$name=$product->Name;
		$descr=$product->Description;
		$price=$product->price;
		$url=$product->product_url;
		$Active=$product->status;
		$UserID=$product->UserID;
		$Auto_Format=$product->Auto_Format;
		$mnf_id=$product->mnf_id;
		
		$img_name=$product->img_name;
		$mime = $product->img_type;
		$pictre= $product->image;
		if($mime!='' && $pictre!='') {
		$b64Src = "data:".$mime.";base64," . base64_encode($pictre);
		}
		else{
		$b64Src = "http://www.placehold.it/80x80/EFEFEF/AAAAAA";
		}
		
		$where_cond=" and SiteID ='".$site_id."' ";
		
		
		$arr_product_cats=array();
$product_cats = $db->get_results("SELECT CategoryID FROM `productcategories` WHERE SiteID='".$site_id."' AND ProductID='".$product->ID."'");
//$db->debug();
if($product_cats != ''){
 foreach($product_cats as $product_cat){
	$arr_product_cats[]= $product_cat->CategoryID;
	
}
}	

$arr_product_opts=array();
$product_opts = $db->get_results("SELECT option_uuid FROM `product_options` WHERE SiteID='".$site_id."' AND product_guid='".$product->GUID."'");
//$db->debug();
if($product_opts != ''){
 foreach($product_opts as $product_opt){
	$arr_product_opts[]= $product_opt->option_uuid;
}
}			

		
// $db->debug();
}
else
{
	$guid='';
	$product_id='';
	$site_id='';
		$code='';
		$name='';
		$descr='';
		$price='';
		$url='';		
		$Active=2;
		$UserID='';
		$Auto_Format=1;
		$mnf_id=0;
		$mime = '';
		$pictre= '';
		$b64Src = "http://www.placehold.it/80x80/EFEFEF/AAAAAA";
		$arr_product_cats=array();
		$arr_product_opts=array();
		
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
			<a href="products.php">Products</a>
		</li>
		<li>
			<a href="#">Product</a>
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
										
									<div class="formSep">
									<div class="row-fluid">
										
										<div class="controls">
												<label>Description </label>				 							
													<textarea cols="60" rows="5" name="descr" id="descr" style="width:500px" ><?php echo $descr;?></textarea>
													<span class="help-block">&nbsp;</span>
													
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
												<label>Price <span class="f_req">*</span></label>				 							
													
													<input type="text" class="span12" name="price" id="price" value="<?php echo $price;?>" />
													
												</div>
											</div>
										</div>	
										
										<div class="formSep">
									<div class="row-fluid">
										
										<div class="controls">
												<label>URL </label>				 							
													
													<input type="text" class="span12" name="url" id="url" value="<?php echo $url;?>" />
													
												</div>
											</div>
										</div>	

								
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span8">
											<label><span class="error_placement">Categories </span> </label>
											<select name="cats[]" id="cats" multiple="multiple" style="width:500px;" size="6">
		<option value="">-- Select Category --</option>
			<?php
			if(isset($_GET['GUID']) || isset($_SESSION['site_id'])) {
			
			if($categorygroups = $db->get_row("SELECT ID,Name FROM `categorygroups` WHERE Code='products' $where_cond"))
			{
			
				$categorygroupId = $categorygroups->ID;
				
				?>
				
				<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE 1 and CategoryGroupID='$categorygroupId' $where_cond ORDER BY Name")){
				?>
				
				<?php
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
						
				?>
				<option <?php if(in_array($categoryID, $arr_product_cats)) { echo "selected"; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categoryName;  if($top_level){ echo ' ('.$top_level.')'; } ?>
				</option>
				<?php
				}
				?>
				
				
				
				<?php
			 } } }
			?>
			
			
		</select>
										</div>
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
									  <div class="span8">
											<label>Manufacturer </label>
											<select onChange="" name="mnf_id" id="mnf_id">
											<option value="0">-- Select Manufacturer --</option>
											<?php
											$manfs= $db->get_results("select * from manufacturers where 1 $where_cond");
											foreach($manfs as $mnf) {
											?>
												<option <?php if($mnf_id == $mnf->id) { ?> selected="selected" <?php } ?> value="<?php echo $mnf->id; ?>"><?php echo $mnf->name; ?></option>
											<?php } ?>
											</select>
										</div>
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span8">
											<label><span class="error_placement">Options </span> </label>
											<select name="opts[]" id="opts" multiple="multiple" style="width:500px;" size="6">
		<option value="">-- Select Option --</option>
			<?php
			if(isset($_GET['GUID']) || isset($_SESSION['site_id'])) {
			
			
				?>
				
				<?php
				if($options = $db->get_results("SELECT * FROM `options` WHERE 1 $where_cond ORDER BY option_name")){
				?>
				
				<?php
				foreach($options as $opt){
					
						
				?>
				<option <?php if(in_array($opt->uuid, $arr_product_opts)) { echo "selected"; } ?> value='<?php echo $opt->uuid; ?>' >
						<?php echo $opt->option_name;  ?>
				</option>
				<?php
				}
				?>
				
				
				
				<?php
			 }  }
			?>
			
			
		</select>
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
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span10">
											<label><span class="error_placement">Status </span> <span class="f_req">*</span></label>
											<label class="radio inline">
												<input type="radio" value="1" name="status" <?php if($Active == 1 || $Active == 2) { echo ' checked'; } ?>/>
												Active
											</label>
											<label class="radio inline">
												<input type="radio" value="0" name="status" <?php if($Active == 0) { echo ' checked'; } ?>/>
												Inactive
											</label>
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
					
					$('#submit').click(function(){
						tinyMCE.triggerSave();
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
								descr: { required: true },
								price: { required: true },
								userID: { required: true },
								status: { required: true },
								
								
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
var tiny_options=new Array();
tiny_options['selector']= "textarea#descr";
tiny_options['theme']= "modern";
tiny_options['plugins']= "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality emoticons template paste textcolor moxiemanager";
tiny_options['theme_advanced_buttons1']= "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect";
tiny_options['theme_advanced_buttons2']= "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor";
tiny_options['theme_advanced_buttons3']= "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen";
tiny_options['theme_advanced_buttons4']= "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak";
tiny_options['theme_advanced_toolbar_location']= "top";
tiny_options['theme_advanced_toolbar_align']= "left";
tiny_options['theme_advanced_statusbar_location']= "bottom";
tiny_options['theme_advanced_resizing']= true;
tiny_options['relative_urls']=false;
tiny_options['remove_script_host']=false;
tiny_options['document_base_url']='https://www.dipna.com/';

tiny_options['moxiemanager_rootpath']= "/home/dipna/public_html";
tiny_options['moxiemanager_path']= "/home/dipna/public_html";

tinymce.init(tiny_options);



</script>


			
		</div>
	</body>
</html>