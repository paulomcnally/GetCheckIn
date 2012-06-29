<?php 
require_once "load.php";
$type = ( isset( $_GET['type'] ) ) ? $_GET['type'] : NULL;

if( is_null( $type ) )
	{
header("Location: ".$config_domain);
exit();
	}

$is_login = is_login( $type );

switch( $type )
	{
	case "foursquare":
		if( !$is_login )
			{
header("Location: ".$foursquare->AuthenticationLink($config_foursquare_uri));
exit();
			}
	break;
	
	
	case "twitter":
		$twitter_connection = new TwitterOAuth($config_twitter_consumer_key, $config_twitter_consumer_secret);
		$request_token = $twitter_connection->getRequestToken($config_twitter_oauth_callback);
		$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
		switch ($twitter_connection->http_code)
			{
  			case 200:
    		/* Build authorize URL and redirect user to Twitter. */
    		$url = $twitter_connection->getAuthorizeURL($token);
header('Location: ' . $url);
exit();
    		break;
  			default:
    			/* Show notification if something went wrong. */
  				echo 'Could not connect to Twitter. Refresh the page or try again later.';
			}
	break;
	
	
	default:
header("Location: ".$config_domain);
exit();	
	}
?>