## Upload input files to your Google Drive via Google Drive API using PHP.

This is an improved version of [yannisg/Google-Drive-Uploader-PHP](https://github.com/yannisg/Google-Drive-Uploader-PHP) with small fixes and changes. Credits also goes to [yannisg](https://github.com/yannisg). 

### Features:
- Supports offline token so that you don't need human intervention to authenticate each time script is run;
- Chunked upload to support large files;
- Upload to specific folders (check the comment inside "controllerUpload.php");
- Download files using IDs (you can test it right away after uploading a file and copying the ID given);
- Already includes the required Google API in folder "libraries";
- Includes a simply and undestandable example in index.php with a file input and submit to a script ("controllerUpload.php") that does the upload trick;
- Includes a simply and undestandable example in index.php with a text input and submit to a script ("controllerUpload.php") that does the download trick.

### To get started:
1. Have your project already set up (if not, do it here: https://console.developers.google.com/cloud-resource-manager);
2. Have Google Drive API already activated in your project (if not, do it here: https://console.developers.google.com/apis/api/drive.googleapis.com);
3. Enter OAuth credentials of your project in both "classGdrive.php" and "gdrive_token.php" scripts (get from here: https://console.developers.google.com/apis/credentials);
4. Certify that "gdrive_token.php" is being loaded in your pages so you can authenticate once and save token locally to file as well as checks if there is no need to authenticate again;
5. Include "classGdrive.php" in your project and upload files through it (just as the way is used in "controllerUpload.php"). 

### Example:
```php
include_once "classes/classGdrive.php";
require_once "/../gdrive_token.php";

$file = $_FILES["fileToUpload"]; // Name of the file input from the view page
$token = $_SESSION["token"]; // Getting the token previously stored in session by gdrive_token.php

$gdrive = new gdrive;
$gdrive->fileRequest = $file;
$gdrive->initialize($token);
$gdrive->processFile();
```

### Have fun!