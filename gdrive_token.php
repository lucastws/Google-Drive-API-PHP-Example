<?php
require_once dirname(__FILE__) . "/libraries/google-api-2.7.0/vendor/autoload.php"; // If there is a newer version and you and to update get from here: https://github.com/google/google-api-php-client.git

// Credentials (get those from Google Developer Console (https://console.developers.google.com/))
$clientId = '35286266903-ucgh4mcrmt7pan8l3dets3m567fle62h.apps.googleusercontent.com';
$clientSecret = 'DcVEWwTvQG3lOTNjYj0qjOQk';
$redirectUri = 'http://localhost:8080/Google-Drive-Uploader-PHP/gdrive_token.php'; // REMEMBER to add this token script URI in your authorized redirects URIs (example: 'http://localhost/Google-Drive-Uploader-PHP/gdrive_token.php')

session_start();

$client = new Google_Client();

// Get your credentials from the console
$client->setApplicationName("Get Token");
$client->setClientId($clientId);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->setScopes(array('https://www.googleapis.com/auth/drive.file'));
$client->setAccessType("offline");
$client->setApprovalPrompt('force');

// If logout was request
if (isset($_REQUEST['logout'])) 
{
    unset($_SESSION['token']);
    $client->revokeToken();

    if(file_exists('token.txt')) unlink('token.txt');	
}
else
{
	if (isset($_SESSION['token'])) 
	{
	    $client->setAccessToken($_SESSION['token']);
	}
	else if(file_exists(__DIR__ . "/token.txt") && file_get_contents(__DIR__ . "/token.txt"))
	{
		$refreshToken = file_get_contents(__DIR__ . "/token.txt"); 
		$client->refreshToken($refreshToken);
		$tokens = $client->getAccessToken();
		$client->setAccessToken($tokens);

		$_SESSION['token'] = $client->getAccessToken();
	}
}

// If login page was request
if(isset($_GET['code'])) 
{
    $client->authenticate($_GET['code']);
    $_SESSION['token'] = $client->getAccessToken();
	$client->getAccessToken(["refreshToken"]);

    header('Location: index.php');
    return;
}

// Check if there is no need to perform auth
if($client->getAccessToken()) 
{
    $_SESSION['token'] = $client->getAccessToken();
    $token = $_SESSION['token'];
    echo "Access Token = " . $token['access_token'] . '<br>';
    echo "Refresh Token = " . $token['refresh_token'] . '<br>';
   
	$saveToken = file_put_contents("token.txt", $token['refresh_token']); // Saving the refresh token in a text file
	if($saveToken) echo 'Token saved successfully!<br>';
} 
else 
{
    $authUrl = $client->createAuthUrl();
    exit(print "<a class='login' href='$authUrl'>Connect Me!</a>");
}
?>