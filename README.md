## Upload input files to your Google Drive via Google Drive API using PHP.

This is an improved version of [yannisg/Google-Drive-Uploader-PHP](https://github.com/yannisg/Google-Drive-Uploader-PHP) with small fixes and changes. Credits also goes to [yannisg](https://github.com/yannisg). 

### Features:
- Supports offline token so that you don't need human intervention to authenticate each time script is run;
- Chunked upload to support large files;
- Already includes the required Google API in folder "libraries";
- Includes a simply and undestandable example in index.php with a file input and submit to a script (controllerUpload.php) that do the trick using gdrive_token.php.

### To get started:
1. Have your project already set up (if not, do it here: https://console.developers.google.com/cloud-resource-manager);
2. Have Google Drive API already activated in your project (if not, do it here: https://console.developers.google.com/apis/api/drive.googleapis.com);
3. Enter OAuth credentials of your project in both gdrive_upload.php and gdrive_token.php scripts (get from here: https://console.developers.google.com/apis/credentials);
4. Run gdrive_token.php to authenticate and save token locally to file (this you only need to do once);
5. Include gdrive_upload.php in your project and upload files through it. 

### Example:
```php
include_once("gdrive_upload.php");

$file = $_FILES["fileToUpload"]; // Name of the file input from the view page

$gdrive = new gdrive;
$gdrive->fileRequest = $file;
$gdrive->initialize();
```

### Have fun!