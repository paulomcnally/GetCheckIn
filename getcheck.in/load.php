<?php
@session_start();
@set_time_limit(0);								// http://goo.gl/30zoL
@ini_set('memory_limit',-1);						// http://goo.gl/PtGin
@date_default_timezone_set("America/Managua");	// http://goo.gl/pyGeD

$config_base = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;
$config_f_core = $config_base . "core" . DIRECTORY_SEPARATOR;
$config_f_template = $config_base . "template" . DIRECTORY_SEPARATOR;

$config_db_host	=	"host";
$config_db_user	=	"user";
$config_db_pass	=	"password";
$config_db_name	=	"database";

$config_domain = "http://getcheck.in/";

$config_foursquare_key		=	"key";
$config_foursquare_secret	=	"secret";
$config_foursquare_uri		=	$config_domain . "foursquare_callback.php";

$config_twitter_consumer_key		=	"key";
$config_twitter_consumer_secret		=	"secret";
$config_twitter_oauth_token			=	"token";
$config_twitter_oauth_token_secret	=	"token secret";
$config_twitter_oauth_callback		=	$config_domain . "twitter_callback.php";


// Class Core Includes
if( $config_core_dir = @opendir( $config_f_core ) )
	{
	while ( $config_core_file = @readdir( $config_core_dir ) )
		{
		if( $config_core_file!=".." && $config_core_file!="." && is_dir( $config_f_core.$config_core_file ) == false )
			{
			require_once $config_f_core . $config_core_file;
			}
		}
	}
$mysql		=	new MySQL( $config_db_host, $config_db_user, $config_db_pass, $config_db_name );

$foursquare_user = ( isset( $foursquare ) ) ? $foursquare : NULL;
$foursquare	=	new FoursquareAPI($config_foursquare_key,$config_foursquare_secret);

//$bitly		=	new Bitly("getcheckin", "R_530b941ca2f410da6b6b93f885a85bd5");

$twitter	=	new TwitterOAuth($config_twitter_consumer_key, $config_twitter_consumer_secret, $config_twitter_oauth_token, $config_twitter_oauth_token_secret);

$twitter_user = NULL;
?>