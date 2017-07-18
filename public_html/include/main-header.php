<?php
header('Content-Type: text/html; charset=utf-8'); //header('Content-Type: text/html; charset=ISO-8859'); 
if(!isset($pWindowTitleTxt))$pWindowTitleTxt = "Dipna Anand - Celebrity Curry Chef";
if(!isset($pMetaKeywordsTxt))$pMetaKeywordsTxt = "";
if(!isset($pMetaDescriptionTxt))$pMetaDescriptionTxt = "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php if(isset($pWindowTitleTxt))echo $pWindowTitleTxt;?></title>
<meta name="keywords" content="<?php if(isset($pMetaKeywordsTxt))echo $pMetaKeywordsTxt;?>"></meta>
<meta name="description" content="<?php if(isset($pMetaDescriptionTxt))echo $pMetaDescriptionTxt;?>"></meta>

<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->