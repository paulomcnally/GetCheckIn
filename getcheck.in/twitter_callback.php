<?php 
require_once "load.php";
	
/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token'])
	{
	$_SESSION['oauth_status'] = 'oldtoken';
	header('Location: ./twitter_clearsessions.php');
	}

$connection = new TwitterOAuth($config_twitter_oauth_token	, $config_twitter_oauth_token_secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

/* Request access tokens from twitter */
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

/* Save the access tokens. Normally these would be saved in a database for future use. */
$_SESSION['access_token_twitter'] = $access_token;

// Save Session in DB
save_twitter_access_token( );

/* Remove no longer needed request tokens */
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

/* If HTTP response is 200 continue otherwise send to connect page to retry */
if (200 == $connection->http_code)
	{
	/* The user has been verified and the access tokens can be saved for future use */
	$_SESSION['status'] = 'verified';
header("Location: ".$config_domain);
exit();	
	}
	else
		{
		/* Save HTTP status for error dialog on connnect page.*/
header('Location: ./twitter_clearsessions.php');
exit();	
	}


header("Location: ".$config_domain);
exit();	
?>
