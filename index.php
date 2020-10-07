<?php
session_start();

if (!isset($_SESSION['token'])) {
	if(!file_get_contents(__DIR__ . "/token.txt")) exit(header("Location: gdrive_token.php"));
	else $_SESSION['token'] = file_get_contents(__DIR__ . "/token.txt");
}
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title>Google Drive PHP Uploader</title>
</head>
<body>
	<form action="controllers/controllerUpload.php" method="post" enctype="multipart/form-data">
		Select file to upload:
		<input type="file" name="fileToUpload">
		<br>
		<input type="submit" value="Upload" name="submit">
		<br>
		<br>
	</form>
	<a class="logout" href="gdrive_token.php?logout">Logout</a>
</body>
</html>