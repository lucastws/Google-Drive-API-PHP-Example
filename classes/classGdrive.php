<?php
class gdrive
{	
	// Credentials (get those from google developer console https://console.developers.google.com/)
	var $clientId = '';
	var $clientSecret = '';
	var $redirectUri = ''; // REMEMBER to add this token script URI in your authorized redirects URIs (example: 'http://localhost/Google-Drive-Uploader-PHP/gdrive_token.php')
	var $client;

	// Files
	var $fileRequest;
	var $fileId;
	var $fileParentId;
	var $fileMimeType;
	var $fileName;
	var $filePath;
		
	function __construct()
	{
		require_once dirname(__FILE__) . "/../libraries/google-api-2.7.0/vendor/autoload.php"; // If there is a newer version and you and to update get from here: https://github.com/google/google-api-php-client.git
		$this->client = new Google_Client();
	}
	
	function initialize($refreshToken)
	{
		echo nl2br("Initializing class...\n");
		$client = $this->client;
		
		$client->setClientId($this->clientId);
		$client->setClientSecret($this->clientSecret);
		$client->setRedirectUri($this->redirectUri);

		try
		{ 
			$client->refreshToken($refreshToken);
			$tokens = $client->getAccessToken();
			$client->setAccessToken($tokens);
		}
		catch(Exception $ex) 
		{
			exit(print "Error uploading a file: " . $ex);
		} 	
	}
	
	function processFile()
	{
		$fileRequest = $this->fileRequest;
		$fileName = $this->fileRequest['name'];
		$fileTmpName = $this->fileRequest['tmp_name'];

		echo nl2br("Processing file: '$fileName'...\n");
		$path_parts = pathinfo($fileName);
		$this->filePath = $path_parts['dirname'];
		$this->fileName = $path_parts['basename'];

		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$this->fileMimeType = finfo_file($finfo, $fileTmpName);
		finfo_close($finfo);
		
		echo nl2br("File MIME type: '" . $this->fileMimeType . "'...\n");
		
		$this->uploadFile();
	}
	
	function uploadFile()
	{
		$client = $this->client;
		$client->setDefer(true);

		$service = new Google_Service_Drive($client);
		
		$file = new Google_Service_Drive_DriveFile();
		$file->name = $this->fileName;
		$chunkSizeBytes = 1 * 1024 * 1024;

		// Folder to upload file to
		if(isset($this->fileParentId)) $file->setParents(array($this->fileParentId));
		
		$fileRequest = $this->fileRequest;
		$fileTmpName = $this->fileRequest['tmp_name'];
		$fileMimeType = $this->fileMimeType;
	
		$request = $service->files->create($file);

		// Create a media file upload to represent our upload process.
		$media = new Google_Http_MediaFileUpload(
			$client,
			$request,
			$fileMimeType,
			null,
			true,
			$chunkSizeBytes
		);
		$media->setFileSize(filesize($fileTmpName));


		// Upload process below
		$filesize = filesize($fileTmpName);
		echo nl2br("Uploading file: '" . $this->fileName . "' (Size: " . $filesize / 1000 . "kB)...\n");

		$status = false; // This will be false until the process is complete.

		// While not reached the end of file marker keep looping and uploading chunks
		$handle = fopen($fileTmpName, "rb");
		while (!$status && !feof($handle)) {
			$chunk = fread($handle, $chunkSizeBytes);
			$status = $media->nextChunk($chunk);  
		}
		
		// The final value of $status will be the data from the API for the object that has been uploaded.
		$result = false;
		if($status != false) {
			echo nl2br("File '" . $this->fileName . "' successfully uploaded!\n");
			$result = $status;

			echo nl2br('Uploaded file id: ' . $result['id']);
		}

		fclose($handle);

		$client->setDefer(false);
	}

	function downloadFile()
	{
		$client = $this->client;

		$service = new Google_Service_Drive($client);

		$file_drive = $service->files->get($this->fileId);
		$file_media = $service->files->get($this->fileId, ['alt' => 'media']);

		$fileName = $file_drive->getName();
		$fileMimeType = $file_drive->getMimeType();

		$tmpFilePath = "../tmp_download/" . $fileName;

		file_put_contents($tmpFilePath, $file_media->getBody());

		header('Content-Type: ' . $fileMimeType);
		header('Content-Disposition: attachment; filename=' . $fileName);
		header('Pragma: no-cache');

		while (ob_get_level()) {
    		ob_end_clean();
		}

		echo file_get_contents($tmpFilePath);

		unlink($tmpFilePath);
	}
}
?>