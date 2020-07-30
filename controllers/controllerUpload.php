<?php
include_once("../gdrive_upload.php");

$file = $_FILES["fileToUpload"];

$gdrive = new gdrive;
$gdrive->fileRequest = $file;
$gdrive->initialize();

echo "<br><br><a class='upload' href='../index.php'>Go back to upload page</a>";
?>