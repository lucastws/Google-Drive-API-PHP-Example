<?php
require_once dirname(__FILE__) . "/gdrive_token.php"
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title>Google Drive Api PHP Example</title>
</head>
<body>
	<h1>Upload & Download Examples</h1>
	<form action="controllers/controllerTest.php" method="post" enctype="multipart/form-data">
		Select file to upload:
		<input type="file" name="fileToUpload">
		<br>
		<input type="submit" value="Upload" name="submit">
		<br>
		<br>
		Input file id to download:
		<input type="text" name="fileIdToDownload" size="50">
		<br>
		<input type="submit" value="Download" name="submit">
		<br>
		<br>
	</form>
	<a class="logout" href="gdrive_token.php?logout">Logout</a>
</body>
</html>