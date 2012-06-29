<?php 
require_once "load.php";
if( is_login("foursquare") )
		{
		session_unset($_SESSION["user_foursquare"]);
		session_unset($_SESSION["token_foursquare"]);
		}
header("Location: " . $config_domain);
exit();
?>