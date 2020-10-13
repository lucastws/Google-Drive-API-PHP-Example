<?php
include_once dirname(__FILE__) . "/../classes/classGdrive.php";
require_once dirname(__FILE__) . "/../gdrive_token.php";

$submit = $_POST["submit"];
$refreshToken = $_SESSION["token"]["refresh_token"];

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

		$gdrive->initialize($refreshToken);
		$gdrive->processFile();

		echo "<br><br><a class='main' href='../index.php'>Go back to main page</a>";

		break;
	}
	case "Download":
	{
		$gdrive = new gdrive;
		$gdrive->fileId = $_POST["fileIdToDownload"];

		$gdrive->initialize($refreshToken);
		$gdrive->downloadFile();

		echo "<br><br><a class='main' href='../index.php'>Go back to main page</a>";

		break;
	}
}
?>