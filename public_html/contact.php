<?php 
include_once("include/connect.php");

include "include/class.phpmailer.php";

if(isset($_POST['submit'])) 
{
	
	$name=addslashes($_POST['name']);
	$phone=$_POST['phone'];
	$email=$_POST['email'];
	$subject=$_POST['subject'];
	$query=addslashes($_POST['query']);
	
	$timestamp = time();
	$nowDate = date('Y-m-d');
	$nowTime = date('H:i:s');
	
	$notes_arr = array('Query' => $query);
	
	$query_enq="INSERT INTO `web_enquiries` (
						`SiteID`, `Created`,`Modified`,`Code`,`Title`,`Firstname`,`Middlename`,`Lastname`,
						`Telephone`,`Fax`,`Email`,`CustomerID`,`zDeleted`,`zStatus`,`zPassword`,
						`zCookie`,`DateRegistered`,`TimeRegistered`,`Name`,`Telephone_daytime`,
						`Mobile`,`JobTitle`,`GUID`,`Server_Number`,`Site_GUID`,`Customer_GUID`,`WYSIWYG_Editor_type`,
						`Notice_period`,`Notes`,`External_ID`,`StatusAlerts`,`Sync_Modified`,`Email_Preferences`,
						`Rank`, `enquiry_type`
					)
					VALUES (
						'".SITE_ID."', '$timestamp', '$timestamp', '$email', '', '', '', 
						'$phone', '', '', '$email', '0', '0', 'ACTIVE', '', 
						'', '$nowDate', '$nowTime', '$name', 
						'$phone', '$phone', '', UUID(), '".SERVER_NUMBER."', '".SITE_GUID."', '', '0', 	
						'', '".json_encode($notes_arr)."', '', '0', '0', '0', '', '$subject'
					)";
	$db->query($query_enq);
	
	
	$msg = "<table border=0><tr><td colspan=\"2\">We have just received the following enquiry from Dipna.com :</td></tr>";
	$msg .= "<tr><td></td></tr>";
	$msg .= "<tr><td>Name:</td><td>".$name."</td></tr>";
	$msg .= "<tr><td>Email Address:</td><td>".$email."</td></tr>";
	$msg .= "<tr><td>Contact Number:</td><td>".$phone."</td></tr>";
	$msg .= "<tr><td>Subject:</td><td>".$subject."</td></tr>";
	$msg .= "<tr><td>Query:</td><td>".$query."</td></tr>";
	$msg .= "</table>"; 
	$mail = new PHPMailer(true); 		// the true param means it will throw exceptions on errors, which we need to catch
	$mail->IsSMTP();                    // Set mailer to use SMTP
	$mail->Host = MAIL_HOST;  // Specify main and backup server
	$mail->SMTPAuth = true;             // Enable SMTP authentication
	$mail->Username = MAIL_USERNAME;    // SMTP username
	$mail->Password = MAIL_PASSWORD;     // SMTP password
	//$mail->SMTPSecure = 'tls';
	
	//mail to user for confirmation
	$debugModeBool = false;
	try {
		
		
		$mail->AddReplyTo($email);
		$mail->AddAddress(ADMIN_MAIL);
		$mail->AddCC(CC_MAIL);
		$mail->AddBCC(BCC_MAIL);
		$mail->SetFrom($email,$name);
		$mail->Subject = $subject." Enquiry";
		
		$mail->MsgHTML($msg);
		$mail->Send();
		$mail->ClearAddresses();
		$succ_msg="Your enquiry has been submitted successfully";
	}
	catch (phpmailerException $e) {
		$err_msg=$e->errorMessage(); 
	}
	catch (Exception $e) {
		$err_msg=$e->getMessage(); 
	
	}//Sending email to admin ENDS Here.
}


if($doc=$db->get_row("select * from documents where code='contact' and SiteID=".SITE_ID." and Status='1' ORDER BY ID DESC LIMIT 1")) {
	$document=$doc->Document;
	$title=$doc->Title;
	$pWindowTitleTxt = $doc->PageTitle;
	$pMetaKeywordsTxt = $doc->MetaTagKeywords;
	$pMetaDescriptionTxt = $doc->MetaTagDescription;
}
else{
header('Location: content_not_found.php');   
exit;
}

include_once("include/main-header.php"); 
?>
 <style>
 #frm_contact label.error {
	/* remove the next line when you have trouble in IE6 with labels in list */
	color: red;
	font-style: italic;
	font-weight:normal;
	display:block;
	width:auto!important;
}
 </style>   
</head>
<body>
<?php include_once("include/analyticstracking.php"); ?>
<div class="container" >
  <?php include_once("include/top-header.php"); ?>
            
            <div class="pg-hding">
           <?php echo $document; ?>
            </div>
        
  <div class="content-bg">
    <div class="row">
      
      <div class="col-lg-8 col-md-8 col-lg-push-4 col-md-push-4">
        <div class="content">
         <h1><?php if($title!=''){ echo $title; } else{ echo $document; } ?></h1>
<?php if(isset($succ_msg) && $succ_msg!='') { echo '<p style="color:#ED8000">'.$succ_msg.'</p>'; } ?> 
<?php if(isset($err_msg) && $err_msg!='') { echo '<p style="color:#FF0000">'.$err_msg.'</p>'; } ?>         

<p ><?php
					if($cont_txt=$db->get_var("select TokenText from tokens where code='contact_txt' and SiteID=".SITE_ID." and zStatus='1'")) {
					echo $cont_txt;
					}
					?></p><br/><br/>

<form class="form-horizontal cont-form" role="form"  id="frm_contact" method="post" action="contact.php">

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
    <label for="" class="col-sm-2 control-label">Phone <sup>*</sup></label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="phone" name="phone" placeholder="Contact Number">
    </div>
  </div>
  <div class="form-group">
                <label for="" class="col-sm-2 control-label">Subject <sup>*</sup></label>
                <div class="col-sm-6">
                <select class="form-control" id="subject" name="subject" >
                <option value="">--Select Subject--</option>
                <option value="Press">Press</option>
                <option value="Book">Book</option>
                <option value="Classes">Classes</option>
                <option value="Events">Events</option>
                <option value="Recipes">Recipes</option>
                <option value="Suggestions">Suggestions</option>
                <option value="General">General</option>
                
                
                </select>
                </div>
                
                   </div>
                   
           <div class="form-group">
    <label for="" class="col-sm-2 control-label">Query <sup>*</sup></label>
    <div class="col-sm-6">
       <textarea class="form-control" rows="3" id="query" name="query" placeholder="Enter your questions, query etc."></textarea>
    </div>
    </div>        
                           
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-6">
      <button type="submit" class="btn btn-default" id="submit" name="submit">Submit</button>
    </div>
  </div>
</form>  
        </div>
      </div>
      <div class="col-lg-4 col-md-4 pd-top-15 col-lg-pull-8 col-md-pull-8" >
      <?php include_once("include/upcoming-book.php"); ?>
       <?php include_once("include/facebook-plugin.php"); ?>
        
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

   		$("#frm_contact").validate({
		rules: {
			name: "required",
			email: {
				required: true,
				email: true
			},
			phone: "required",
			subject: "required",
			query:  "required",
		},
		messages: {
			name: "Please enter your Name",
			email: {
				required: "Please enter your Email address",
				email: "Please enter a valid Email address"
			},
			phone: "Please enter your Contact Number",
			subject: "Please select Subject",
			query: "Please enter your questions, query etc.",
		}
	});
   
   });
   </script>

</body>
</html>