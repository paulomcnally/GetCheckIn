<?php 
	require_once("../core/FoursquareAPI.class.php");
	
	// This file is intended to be used as your redirect_uri for the client on Foursquare
	
	// Set your client key and secret
	$client_key = "1OO3ZMQVJ0ITV1FBNBGFFZ5WYOPCEITOQW5AXAXYL4OH21YX";
	$client_secret = "LLXY1E0CW0ZHYM1YGXTFCJAFR1XB54D23FNWFQMSJIFQ3R1L";
	$redirect_uri = "http://getcheck.in/foursquare_callback.php";
	
	// Load the Foursquare API library
	$foursquare = new FoursquareAPI($client_key,$client_secret);
	
	$foursquare->SetAccessToken("YGT4KANPBDV4BE02KBLZKYM4BRSOYJWUP3C2ZQS5GIMIGVUO");
	
	
	// Prepare parameters
	//$params = array("query"=>"the reef","ll"=>"12.194400429726,-86.098979115486","v"=>date("Ymd"),"limit"=>10);
	$params = array("v"=>date("Ymd"));
	// Perform a request to a authenticated-only resource
	$response = $foursquare->GetPrivate("users/self/venuehistory",$params);
	$checkin = json_decode($response);

?>
<pre>
<?php echo print_r($checkin); ?>
</pre>