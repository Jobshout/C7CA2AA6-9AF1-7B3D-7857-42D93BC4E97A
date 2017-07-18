<?php
if(isset($_POST['connect_to'])){
	//--unset last searched session on site switch--
	if(isset($_SESSION['last_search'])){ unset($_SESSION['last_search']); }
	
	setcookie('connect_to', $_POST['connect_to']);//bsw 20131128 removed , time()+60*60*24*365 connect to should only last user close browser
	$connect_to=$_POST['connect_to'];
}
elseif(isset($_COOKIE['connect_to'])){
	$connect_to=$_COOKIE['connect_to'];
}
else{
	$connect_to="Live";
}

if($connect_to=="Staging"){
	$history_db_user="jobshout_history";
	$history_db_name='jobshout_live_history';
	$dictionary_db_user="jobshout_admin";
	$dictionary_db_name='dictionary';
}
elseif($connect_to=="Live"){
	$history_db_user="jobshout_history";
	$history_db_name='jobshout_live_history';
	$dictionary_db_user="jobshout_admin";
	$dictionary_db_name='dictionary';
}
elseif($connect_to=="Dev"){
	$history_db_user="jobshout_history";
	$history_db_name='jobshout_live_history';
	$dictionary_db_user="jobshout_admin";
	$dictionary_db_name='dictionary';
}
?>