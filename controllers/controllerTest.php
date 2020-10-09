<?php
include_once dirname(__FILE__) . "/../classes/classGdrive.php";

$submit = $_POST["submit"];

switch($submit)
{
	case "Upload":
	{
		$file = $_FILES["fileToUpload"];

		$gdrive = new gdrive;
		$gdrive->fileRequest = $file;

		/* 
			If you want to upload the file into a folder, get the folder id 
				(the string after "[...]/folders/" in the folder's url), 
				uncomment and write below. 
		*/
		//$gdrive->fileParentId = "";

		$gdrive->initialize();
		$gdrive->processFile();

		echo "<br><br><a class='main' href='../index.php'>Go back to main page</a>";

		break;
	}
	case "Download":
	{
		$gdrive = new gdrive;
		$gdrive->fileId = $_POST["fileIdToDownload"];

		$gdrive->initialize();
		$gdrive->downloadFile();

		echo "<br><br><a class='main' href='../index.php'>Go back to main page</a>";

		break;
	}
}
?>