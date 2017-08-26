<?php 

ini_set("file_uploads", "On");

echo "file_uploads = " . ini_get('file_uploads') . "\n";
echo 'display_errors = ' . ini_get('display_errors') . "\n";
echo 'register_globals = ' . ini_get('register_globals') . "\n";
echo 'post_max_size = ' . ini_get('post_max_size') . "\n";
echo 'post_max_size+1 = ' . (ini_get('post_max_size')+1) . "\n";

//phpinfo();
?>