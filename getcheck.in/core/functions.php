<?php
function get_now()
	{
	return date("Y-m-d H:i:s");
	}

function get_template( $file = NULL )
	{
	if( !is_null( $file ) )
		{
		$patch = $GLOBALS['config_f_template'] . $file . ".php";
		if( file_exists( $patch ) )
			{
			require_once $patch;
			}
			else
				{
				echo $patch;
				}
		}
	}
	
function is_login($type=NULL)
	{
	if( !is_null( $type ) )
		{
		if( isset( $_SESSION["user_".$type] ) )
			{
			return true;
			}
		}
	return false;
	}

function is_login_token($type=NULL)
	{
	if( !is_null( $type ) )
		{
		if( isset( $_SESSION["token_".$type] ) )
			{
			return true;
			}
		}
	return false;
	}

function save_token()
	{
	$GLOBALS['mysql']->query("INSERT INTO tokens(id, token, registered) VALUES('".$_SESSION["user_foursquare"]->id."','".$_SESSION["token_foursquare"]."','".get_now()."') ON DUPLICATE KEY UPDATE token = '".$_SESSION["token_foursquare"]."', last_update = '".get_now()."'");
	}

function save_user()
	{
	$GLOBALS['mysql']->query("REPLACE INTO users
	(
	id,
	firstName, 
	lastName, 
	photo, 
	gender, 
	homeCity, 
	canonicalUrl, 
	relationship, 
	type, 
	pings, 
	badges, 
	mayorships, 
	checkins, 
	friends, 
	following, 
	requests, 
	tips, 
	todos, 
	photos
	)
	VALUES
	(
	'".$_SESSION["user_foursquare"]->id."',
	'".$_SESSION["user_foursquare"]->firstName."',
	'".$_SESSION["user_foursquare"]->lastName."',
	'".$_SESSION["user_foursquare"]->photo."',
	'".$_SESSION["user_foursquare"]->gender."',
	'".$_SESSION["user_foursquare"]->homeCity."',
	'".$_SESSION["user_foursquare"]->canonicalUrl."',
	'".$_SESSION["user_foursquare"]->relationship."',
	'".$_SESSION["user_foursquare"]->type."',
	'".$_SESSION["user_foursquare"]->pings."',
	'".$_SESSION["user_foursquare"]->badges->{"count"}."',
	'".$_SESSION["user_foursquare"]->mayorships->{"count"}."',
	'".$_SESSION["user_foursquare"]->checkins->{"count"}."',
	'".$_SESSION["user_foursquare"]->friends->{"count"}."',
	'".$_SESSION["user_foursquare"]->following->{"count"}."',
	'".$_SESSION["user_foursquare"]->requests->{"count"}."',
	'".$_SESSION["user_foursquare"]->tips->{"count"}."',
	'".$_SESSION["user_foursquare"]->todos->{"count"}."',
	'".$_SESSION["user_foursquare"]->photos->{"count"}."'
	)");
	// Score
	$GLOBALS['mysql']->query("REPLACE INTO scores(id, recent, max, checkinsCount) VALUES('".$_SESSION["user_foursquare"]->id."','".$_SESSION["user_foursquare"]->scores->recent."','".$_SESSION["user_foursquare"]->scores->{"max"}."','".$_SESSION["user_foursquare"]->scores->{"checkinsCount"}."')");
	// Contact
	$GLOBALS['mysql']->query("REPLACE INTO contact(id, phone, email, twitter, facebook) VALUES('".$_SESSION["user_foursquare"]->id."','".$_SESSION["user_foursquare"]->contact->phone."','".$_SESSION["user_foursquare"]->contact->email."','".$_SESSION["user_foursquare"]->contact->twitter."','".$_SESSION["user_foursquare"]->contact->facebook."')");
	}


function mention_exist($id)
	{
	if( !$n = $GLOBALS['mysql']->query("SELECT id FROM mentions WHERE twitter_id='".$id."'") )
		{
		return false;
		}
	return true;
	}


function mention_save($id, $twitter_id, $screen_name, $checkin_id, $vanue_id, $twitter_text, $twitter_id_response, $response_text )
	{
	$GLOBALS['mysql']->query("INSERT INTO mentions(id, twitter_id, screen_name, checkin_id, vanue_id, twitter_text, registered, twitter_id_response, response_text) VALUES('".$id."','".$twitter_id."','".$screen_name."','".$checkin_id."', '".$vanue_id."','".$GLOBALS['mysql']->html($twitter_text)."','".get_now()."','".$twitter_id_response."','".$response_text."')");
	}

function get_token_by_twitter( $screen_name )
	{
	twitter_user_init( $screen_name );
	return $GLOBALS['mysql']->row("SELECT T.id, T.token, U.canonicalUrl FROM contact C, tokens T, users U
WHERE C.twitter = '".$screen_name."'
AND C.id = T.id
AND U.id = T.id");
	}

function json_ok($msg)
	{
	return '{"status":true,"msg":"'.$msg.'"}';
	}

function json_error($msg)
	{
	return '{"status":false,"msg":"'.$msg.'"}';
	}


function twitter_clean($s)
	{
	$a=array("'",'"');foreach($a as $b){$s=str_ireplace($b,"",$s);}return $s;
	}
function twitter_limit($s)
	{
	return substr($s,0,140);
	}

function twitter_post( $text = NULL, $in_reply = NULL, $screen_name = NULL  )
	{
	if( !is_null( $text ) )
		{
		// Mention
		if( !is_null( $screen_name ) )
			{
			$text = "@" . $screen_name . " " . $text;
			}
		// Sufix Tag
		$text .= " #GetCheckIn";
		// Set default Params
		$parameters = array('status' => html_entity_decode(utf8_encode(twitter_clean(twitter_limit( $text )))), "include_entities" => 1);
		// Reply Tweet by ID
		if( !is_null( $in_reply ) )
			{
			$parameters["in_reply_to_status_id"] = $in_reply;
			}
		return json_decode(json_encode( $GLOBALS['twitter']->post('statuses/update', $parameters)));
		}
	return NULL;
	}


function save_twitter_access_token()
	{
	$GLOBALS['mysql']->query("INSERT INTO twitter(
	id,
	oauth_token,
	registered,
	oauth_token_secret,
	user_id,
	screen_name
	) VALUES(
	'".$_SESSION["user_foursquare"]->id."',
	'".$_SESSION['access_token_twitter']["oauth_token"]."',
	'".get_now()."',
	'".$_SESSION['access_token_twitter']["oauth_token_secret"]."',
	'".$_SESSION['access_token_twitter']["user_id"]."',
	'".$_SESSION['access_token_twitter']["screen_name"]."'
	) ON DUPLICATE KEY UPDATE 
	oauth_token = '".$_SESSION['access_token_twitter']["oauth_token"]."',
	oauth_token_secret = '".$_SESSION['access_token_twitter']["oauth_token_secret"]."',
	user_id = '".$_SESSION['access_token_twitter']["user_id"]."',
	screen_name = '".$_SESSION['access_token_twitter']["screen_name"]."',
	last_update = '".get_now()."'");
	}

function twitter_account_exist()
	{
	if( $n = $GLOBALS['mysql']->query("SELECT registered FROM twitter WHERE id = " . $_SESSION["user_foursquare"]->id) )
		{
		return true;
		}
		else
			{
			return false;
			}
	}

function serialize_venue_name( $venue_name )
	{
	$result = preg_replace("/[^A-Za-z0-9 ]/", '', $venue_name);
	$vname_A = explode( " ", $result);
	$result = implode("",$vname_A);
	return $result;
	}

function save_venue($id, $name)
	{
	$GLOBALS['mysql']->query("REPLACE INTO venues(id, name, registered) VALUES('".$id."','".short_id($id)."','".get_now()."')");
	}

function save_my_venues( )
	{
	$query = "REPLACE INTO venues(id, name, registered) VALUES";
	$params = array("v"=>date("Ymd"));
	$GLOBALS['foursquare']->SetAccessToken( $_SESSION["token_foursquare"] );
	$response = json_decode( $GLOBALS['foursquare']->GetPrivate("users/self/venuehistory",$params) );
	if( $response->meta->code == 200 )
		{
		if( $response->response->venues->{"count"} > 0 )
			{
			$venues_A = array();
			foreach( $response->response->venues->items as $item )
				{
				array_push( $venues_A, "('".$item->venue->id."','".short_id($item->venue->id)."','".get_now()."')" );
				}
			$GLOBALS['mysql']->query($query.implode(",",$venues_A));
			}
		}
	}

function get_venue_id($venue_name)
	{
	return $GLOBALS['mysql']->value("SELECT id FROM venues WHERE name = '".$venue_name."';");
	}

function venue_search( $venue_name_string )
	{
	$twitter_post = NULL;
	$checkins = json_decode( $GLOBALS['foursquare']->GetPrivate("checkins/recent",array("v"=>date("Ymd"))) );
	if( $checkins->meta->code == 200 )
		{
		}
		else
			{
			$twitter_post = twitter_post( $checkins->meta->errorDetail, $GLOBALS['twett']->id_str, $GLOBALS['twett']->user->screen_name );
			}
	
	
	$params = array("query"=>$venue_name_string,"ll"=>"12.194400429726,-86.098979115486","v"=>date("Ymd"),"limit"=>10);
	$response = json_decode( $GLOBALS['foursquare']->GetPrivate("users/self/venuehistory",$params) );
	}

function short_id($input) {
  $base32 = array (
    'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
    'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',
    'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
    'y', 'z', '0', '1', '2', '3', '4', '5'
    );

  $hex = md5($input);
  $hexLen = strlen($hex);
  $subHexLen = $hexLen / 8;
  $output = array();

  for ($i = 0; $i < $subHexLen; $i++) {
    $subHex = substr ($hex, $i * 8, 8);
    $int = 0x3FFFFFFF & (1 * ('0x'.$subHex));
    $out = '';

    for ($j = 0; $j < 6; $j++) {
      $val = 0x0000001F & $int;
      $out .= $base32[$val];
      $int = $int >> 5;
    }

    $output[] = $out;
  }

  return $output[0];
}

function twitter_user_init( $screen_name )
	{
	if( $n = $GLOBALS['mysql']->query( "SELECT oauth_token, oauth_token_secret FROM twitter WHERE screen_name = '".$screen_name."'" ) )
		{
		$GLOBALS['twitter_user'] = new TwitterOAuth( $GLOBALS['config_twitter_consumer_key'], $GLOBALS['config_twitter_consumer_secret'], $GLOBALS['mysql']->last_result[0]->oauth_token, $GLOBALS['mysql']->last_result[0]->oauth_token_secret);
		}
	}

function twitter_is_user_registered(  )
	{
	if( !is_null( $GLOBALS['twitter_user'] ) )
		{
		return true;
		}
	return false;
	}

function twitter_user_delete_mention( $id = NULL )
	{
	$parameters = array('id' => $id, "include_entities" => 1);
		// Reply Tweet by ID
		if( !is_null( $id ) )
			{
			return json_decode(json_encode( $GLOBALS['twitter_user']->post('statuses/destroy/'.$id, $parameters)));
			}
	return NULL;
	}
?>