<?php 
include_once("include/connect.php");	
include "include/class.phpmailer.php";
$code = isset($_GET['code']) ? $_GET['code'] : '';
$code = str_replace(".html", "", $code);
if($doc=$db->get_row("select * from documents where code='".$code."' and SiteID=".SITE_ID." and Status='1' ORDER BY ID DESC LIMIT 1")) {
	$document=$doc->Document;
	$title=$doc->Title;
	$body=$doc->Body;
	$type=$doc->Type;
	$pWindowTitleTxt = $doc->PageTitle;
	$pMetaKeywordsTxt = $doc->MetaTagKeywords;
	$pMetaDescriptionTxt = $doc->MetaTagDescription;
}
else{
header('Location: content_not_found.php');   
exit;
}

if(isset($_POST['submit'])) {
	include_once 'securimage/securimage.php';
	$securimage = new Securimage();
	if ($securimage->check($_POST['captcha_code']) == false) {
		$err_msg ="* Security code must be matched !";	
	}else{
		$remoteIPStr = isset($_SERVER["HTTP_X_REAL_IP"]) ? $_SERVER["HTTP_X_REAL_IP"] : "";
		$name=addslashes($_POST['name']);
		$email=$_POST['email'];
		$web=$_POST['web'];
		$comment=addslashes($_POST['comment']);
		$time=time();
		
		$db->query("insert into blog_comments(uuid, blog_id, blog_uuid, Name, email, comments, comment_by, ip_address, OrderNum, Created, Modified, site_uuid, Server_Number, Status) 
		values(UUID(), '".$doc->ID."', '".$doc->GUID."', '$name', '$email', '$comment', '','$remoteIPStr', 0, '$time', '$time', '".SITE_GUID."', '".SERVER_NUMBER."', 0)");
		//$db->debug();
		$comment_id= $db->insert_id;
		$succ_msg= "Thanks your comment has been posted OK and will be visible soon!";	
		
		$mail = new PHPMailer(true); 		// the true param means it will throw exceptions on errors, which we need to catch
		$mail->Charset = 'utf-8';
		$mail->IsSMTP();                    // Set mailer to use SMTP
		$mail->Host = MAIL_HOST;  			// Specify main and backup server
		$mail->SMTPAuth = true;             // Enable SMTP authentication
		$mail->Username = MAIL_USERNAME;    // SMTP username
		$mail->Password = MAIL_PASSWORD;    // SMTP password
		
		$debugModeBool = false;
		try {
			
			$mail->AddReplyTo($email,$name);
			$mail->AddAddress(ADMIN_MAIL);
			//$mail->AddCC($cc_mail);
			//$mail->AddBCC($bcc_mail);
			$mail->SetFrom($email,$name);
			$mail->Subject = $name." has posted a comment";
			
			$msg = "<table border=0><tr><td colspan=\"2\">".$name." has posted a comment for the <b>".$document."</b>:</td></tr>";
			$msg .= "<tr><td></td></tr>";
			$msg .= "<tr><td>Email Address:</td><td>".$email."</td></tr>";
			if($web!=''){
			$msg .= "<tr><td>web:</td><td><a href='".$web."'>".$web."</td></tr>";
			}
			$msg .= "<tr><td>Comment:</td><td>".$comment."</td></tr>";
			$msg .= "<tr><td colspan='2'><a href='http://hh4.jobshout.co.uk:8088/dashboard/blog.php?GUID=".$doc->GUID."&cmnt#".$comment_id."'>Click here to approve this comment</a></td></tr>";
			$msg .= "</table>"; 
			
			
			
			$mail->MsgHTML($msg);
			$mail->Send();
			$mail->ClearAddresses();
			
		}catch (phpmailerException $e) {
			$err_msg=$e->errorMessage(); 
		}
		catch (Exception $e) {
			$err_msg=$e->getMessage(); 

		}
	}
}


include_once("include/main-header.php"); 

?>
 <style>
 #frm_comment label.error {
	/* remove the next line when you have trouble in IE6 with labels in list */
	color: red;
	font-style: italic;
	font-weight:normal;
	display:block;
	width:auto!important;
}

.content table tr td, .content table tr th {
	padding: 5px;
	border:1px solid #FFFFFF;
}
 </style>    
</head>
<body>
<?php include_once("include/analyticstracking.php"); ?>
<div class="container" >
  <?php include_once("include/top-header.php"); ?>

            
            <div class="pg-hding">
            <?php
			switch($type){
					case 'page' : 		
			 echo $document; 
			 break;
				case 'blog' :
			echo "Blog Post";
			}?>
            </div>
        
  <div class="content-bg">
    <div class="row">
      
      <div class="col-lg-8 col-md-8 col-lg-push-4 col-md-push-4">
        <div class="content">
		<h1><?php if($title!=''){ echo $title; } else{ echo $document; } ?></h1>
		
		
<?php
switch($type){
					case 'page' : ?>
			<?php
		 if($doc_imgs=$db->get_results("select * from pictures where SiteID=".SITE_ID." and DocumentID='".$doc->ID."' and Status='1' order by Order_By")){
		 ?>
		 <p>
		 <?php
		 foreach($doc_imgs as $doc_img) {
			$pic_Src = "data:".$doc_img->Type.";base64," . base64_encode($doc_img->Picture);
			
		  ?>
            <img src="<?php echo $pic_Src; ?>"  class="img-responsive" style="border:#FFFFFF 3px solid;"> 
			<?php } ?>
			</p>
			<?php } ?>
					
		<?php					
		
          echo $body; 
if($objects= $db->get_results("SELECT * FROM objects where DocumentID='".$doc->ID."' AND SiteID='".SITE_ID."' order by `Order` asc")){	
	foreach($objects as $object){
		echo '<h4>'.$object->Title.'<h4>';
		echo '<p>'.$object->TextObject.'</p>';
	}
}	  
		  
		  break;
				case 'blog' :
				
$commentQuery = "Select * from `blog_comments` where `blog_id` = ".$doc->ID." AND Status=1 Order by ID DESC";

$CommentData = $db->get_results($commentQuery);

$author_data=$db->get_row("select * from wi_users where ID='".$doc->UserID."'");
				
				?>
			
			<p style="line-height:14px; vertical-align:top;margin-top:-10px;"><span class="glyphicon glyphicon-calendar" style="padding-right:5px; font-size:11px;"></span><a href="blog.php?Day=<?php echo date('d',$doc->Published_timestamp); ?>&Month=<?php echo date('n',$doc->Published_timestamp); ?>&Year=<?php echo date('Y',$doc->Published_timestamp); ?>"><?php echo date('d M Y',$doc->Published_timestamp); ?></a>
			<?php if(isset($author_data) && $author_data!='') { ?>
      &nbsp;&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-user" style="padding-right:5px; font-size:11px;"></span><a href="blog.php?author=<?php echo $author_data->code; ?>" title="Posts by <?php echo $author_data->firstname.' '.$author_data->lastname; ?>" rel="author"><?php echo $author_data->firstname.' '.$author_data->lastname; ?></a>
	  <?php } ?>
      &nbsp;&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-comment" style="padding-right:5px; font-size:11px;"></span><a href="#respond" title="Comment on <?php echo $document; ?>"><?php echo count($CommentData); ?> Comment(s)</a></p>
			
			
			
			
			<?php
		 if($doc_imgs=$db->get_results("select * from pictures where SiteID=".SITE_ID." and DocumentID='".$doc->ID."' and Status='1' order by Order_By")){
		 ?>
		 <p>
		 <?php
		 foreach($doc_imgs as $doc_img) {
			$pic_Src = "data:".$doc_img->Type.";base64," . base64_encode($doc_img->Picture);
			
		  ?>
            <img src="<?php echo $pic_Src; ?>"  class="img-responsive" style="border:#FFFFFF 3px solid;"> 
			<?php } ?>
			</p>
			<?php } ?>
			
		<?php echo $body; ?>

<br/><br/>
<h4 >Social Share</h4><br/>		<p>	
				<a style="text-decoration:none" href="http://www.facebook.com/share.php?u=http://dipna.com/<?php echo $code; ?>.html" target="_blank">
					<img class="no-preload" src="images/social-icons/facebook.png">
				</a>
			
				<a style="text-decoration:none" href="http://twitter.com/home?status=<?php echo $document; ?>%20-%20http://dipna.com/<?php echo $code; ?>.html/" target="_blank">
					<img class="no-preload" src="images/social-icons/twitter.png">
				</a>
				
							<a style="text-decoration:none" href="http://www.stumbleupon.com/submit?url=http://dipna.com/<?php echo $code; ?>.html&amp;title=<?php echo $document; ?>" target="_blank">
					<img class="no-preload" src="images/social-icons/stumbleupon.png">
				</a>
			
				<a style="text-decoration:none" href="http://delicious.com/post?url=http://dipna.com/<?php echo $code; ?>.html&amp;title=<?php echo $document; ?>" target="_blank">
					<img class="no-preload" src="images/social-icons/delicious.png">
				</a>
							<a style="text-decoration:none" href="http://digg.com/submit?url=http://dipna.com/<?php echo $code; ?>.html&amp;title=<?php echo $document; ?>" target="_blank">
					<img class="no-preload" src="images/social-icons/digg.png">
				</a>
			
				<a style="text-decoration:none" href="http://reddit.com/submit?url=http://dipna.com/<?php echo $code; ?>.html&amp;title=<?php echo $document; ?>" target="_blank">
					<img class="no-preload" src="images/social-icons/reddit.png">
				</a>
						<a style="text-decoration:none" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=http://dipna.com/<?php echo $code; ?>.html&amp;title=<?php echo $document; ?>" target="_blank">
					<img class="no-preload" src="images/social-icons/linkedin.png">
				</a>
				
				<a style="text-decoration:none" href="https://plus.google.com/share?url=http://dipna.com/<?php echo $code; ?>.html" onClick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;">
					<img class="no-preload" src="images/social-icons/googleplus.png">
				</a>					
			
				<a style="text-decoration:none" href="http://pinterest.com/pin/create/button/?url=http://dipna.com/<?php echo $code; ?>.html&amp;media=http://dipna.com/wp-content/uploads/2013/08/Dipna-Press.jpg" class="pin-it-button" count-layout="horizontal" onClick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;">
					<img class="no-preload" src="images/social-icons/pinterest.png">
				</a>	</p><br/><br/>
			

<p id="respond"></p>

<?php
if($CommentData){
	foreach($CommentData as $comnt){
	
	$MinutesPassed = intval((time() - $comnt->Created)/60);
	$TimePassed = $MinutesPassed.' Mins ago';
	$HourPassed=0;
	$DaysPassed=0;
	if($MinutesPassed>60){
		$HourPassed = intval($MinutesPassed/60);
		$MinutesPassed = $MinutesPassed%60;
		$TimePassed = $HourPassed.' Hours '.$MinutesPassed.' mins ago';
	}
	if($HourPassed>24 && isset($HourPassed)){
		$DaysPassed = intval($HourPassed/24);
		$HourPassed = $HourPassed%24;
		$TimePassed = $DaysPassed.' Days '.$HourPassed.' hours ago';
	}
	if($DaysPassed>31 && isset($DaysPassed)){
		$MonthPassed = intval($DaysPassed/31);
		$DaysPassed = $DaysPassed%31;
		$TimePassed = $MonthPassed.' Months '.$DaysPassed.' day ago';
	}?>
<p><?php echo '<a style="text-decoration:none;" ><b>'.$comnt->Name.'</b></a>';?>&nbsp;<?php echo '<span style="font-size:14px; color:#999999">('.$TimePassed.')</span>';?>:&nbsp;<?php echo $comnt->comments;?></p><br/>
<?php	
	}

}

?>



<?php if(isset($succ_msg) && $succ_msg!='') { echo '<p style="color:#ED8000">'.$succ_msg.'</p>'; } ?> 
<?php if(isset($err_msg) && $err_msg!='') { echo '<p style="color:#FF0000">'.$err_msg.'</p>'; } ?>
<h4 >Leave a Reply </h4><br/>



<form class="form-horizontal cont-form" role="form"  id="frm_comment" method="post" action="">

 <div class="form-group">
    <label for="" class="col-sm-2 control-label">Name <sup>*</sup> </label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="name" name="name" placeholder="Name">
    </div>
  </div>
  <div class="form-group">
    <label for="" class="col-sm-2 control-label">Email <sup>*</sup></label>
    <div class="col-sm-6">
      <input type="email" class="form-control" id="email" name="email" placeholder="Email">
    </div>
  </div>
  
  <div class="form-group">
    <label for="" class="col-sm-2 control-label">Website </label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="web" name="web" placeholder="Website">
    </div>
  </div>
   <div class="form-group">
    <label for="" class="col-sm-2 control-label">
	Security Code <sup>*</sup>
	</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="captcha_code" name="captcha_code" style="width:130px; display:inline;" >
	  <img id="captcha" style="border: 1px ridge; margin-left: 5px;height: 30px;width : 125px;margin-top: -2px;" src="securimage/securimage_show.php" alt="CAPTCHA Image"><a href="#" onClick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); this.blur(); return false" id="captcha_refresh"><img src="securimage/images/refresh.gif" alt="Reload Image" onClick="this.blur()" align="bottom" border="0" style="height: 30px;margin-top: -2px;">
		</a>
    </div>
  </div>
  
           <div class="form-group">
    <label for="" class="col-sm-2 control-label">Comment <sup>*</sup></label>
    <div class="col-sm-6">
       <textarea class="form-control" rows="3" id="comment" name="comment" placeholder="Enter your Comment"></textarea>
    </div>
    </div>        
                           
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-6">
      <button type="submit" class="btn btn-default" id="submit" name="submit">Submit</button>
    </div>
  </div>
</form>				
				
				<?php
				
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
 <script src="js/jquery-1.9.0.js"></script>
   <script src="js/jquery.validate.js"></script>
   <script type="text/javascript">
$.noConflict();
jQuery(document).ready(function($){

   		$("#frm_comment").validate({
		errorPlacement: function(error, element) {
    	if (element.attr("name") == "captcha_code" )
       	 error.insertAfter("#captcha_refresh");
    	else
        error.insertAfter(element);
		},
		rules: {
			name: "required",
			email: {
				required: true,
				email: true
			},
			captcha_code: "required",
			comment:  "required",
		},
		messages: {
			name: "Please enter your Name",
			email: {
				required: "Please enter your Email address",
				email: "Please enter a valid Email address"
			},
			captcha_code: "Please enter Security Code",
			comment: "Please enter your Comment.",
		}
	});
   
   });
   </script>

</body>
</html>