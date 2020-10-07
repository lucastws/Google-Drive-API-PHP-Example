<?php
include_once("../gdrive_upload.php");

$file = $_FILES["fileToUpload"];

$gdrive = new gdrive;
$gdrive->fileRequest = $file;

/* 
	If you want to upload the file into a folder, get the folder id 
		(the string after "[...]/folders/" in the folder's url), 
		uncomment and write below. 
*/
//$gdrive->fileParentId = "1AfkQwf3D0WvFUKlLi3IgIXQUI35fbFDX";

$gdrive->initialize();

echo "<br><br><a class='upload' href='../index.php'>Go back to upload page</a>";
?>