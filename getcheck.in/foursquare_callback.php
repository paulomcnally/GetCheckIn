<?php 
require_once "load.php";
	
if(array_key_exists("code",$_GET))
	{
	$_SESSION["token_foursquare"] = $foursquare->GetToken($_GET['code'],$config_foursquare_uri);
	$foursquare->SetAccessToken( $_SESSION["token_foursquare"] );
	
	$params = array();
	$response = $foursquare->GetPrivate("users/self",$params);
	
	$json = json_decode($response);
	
	if( $json->meta->code == 200 && isset( $json->response->user ) )
		{
		$_SESSION["user_foursquare"] = $json->response->user;
	
		save_token();
		save_user();
		
		if( !twitter_account_exist() )
			{
header("Location: ".$config_domain."authentication.php?type=twitter");
exit();
			}
		}	
	}

header("Location: ".$config_domain);
exit();	
?>
