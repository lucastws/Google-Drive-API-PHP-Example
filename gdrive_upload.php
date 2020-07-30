<?php
class gdrive
{	
	// Credentials (get those from google developer console https://console.developers.google.com/)
	var $clientId = '';
	var $clientSecret = '';
	var $redirectUri = ''; // REMEMBER to add this token script URI in your authorized redirects URIs (example: 'http://localhost/Google-Drive-Uploader-PHP/gdrive_token.php')
	
	// Variables
	var $fileRequest;
	var $fileMimeType;
	var $fileName;
	var $filePath;
	var $client;
		
	function __construct()
	{
		require_once 'libraries/google-api-2.7.0/vendor/autoload.php'; // If there is a newer version and you and to update get from here: https://github.com/google/google-api-php-client.git
		$this->client = new Google_Client();
	}
	
	function initialize()
	{
		echo nl2br("Initializing class...\n");
		$client = $this->client;
		
		$client->setClientId($this->clientId);
		$client->setClientSecret($this->clientSecret);
		$client->setRedirectUri($this->redirectUri);

		$refreshToken = file_get_contents(__DIR__ . "/token.txt"); 
		$client->refreshToken($refreshToken);
		$tokens = $client->getAccessToken();
		$client->setAccessToken($tokens);
		
		$client->setDefer(true);
		$this->processFile();
		
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
		
		$file = new Google_Service_Drive_DriveFile();
		$file->name = $this->fileName;
		$chunkSizeBytes = 1 * 1024 * 1024;
		
		$fileRequest = $this->fileRequest;
		$fileTmpName = $this->fileRequest['tmp_name'];
		$fileMimeType = $this->fileMimeType;
		
		$service = new Google_Service_Drive($client);
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
			echo nl2br("File '" . $this->fileName . "' successfully uploaded!");
			$result = $status;
		}

		fclose($handle);

		$client->setDefer(false); // Reset to the client to execute requests immediately in the future.
	}
}
?>