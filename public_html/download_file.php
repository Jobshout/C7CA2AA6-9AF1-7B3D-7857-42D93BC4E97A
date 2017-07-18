<?php
include_once("include/connect.php");
if (isset($_GET['uuid'])) {
    $fileid = $_GET['uuid'];
    try {
      $sql = "SELECT * FROM `pdf_docs` WHERE `uuid` = '".$fileid."' and SiteID=".SITE_ID."";
        $results = $db->get_row($sql);
        
            $filename = $results->doc_name;
            $mimetype = $results->doc_type;
            $filedata = $results->doc_blob;
            header("Content-length: ".strlen($filedata));
            header("Content-type: $mimetype");
            header("Content-disposition: download; filename=$filename"); //disposition of download forces a download
            echo $filedata; 
            // die();
       
    } //try
    catch (Exception $e) {
        $error = '<br>Database ERROR fetching requested file.';
        echo $error;
        die();    
    } //catch
} //isset
?>