<?php
/**
 * Load
 * CopyLeft:	McNally Developers
 * Author:		Paulo McNally | paulo[at]mcnallydevelopers.com
 * Created:		2011-02-06 22:44:34
 * Last Update:	2011-02-06 22:44:34
 * Description:	Lee los Twitts de mi cuenta y los procesa
 */

// This file containt all requeriment files
require dirname( dirname( __FILE__ ) ) . "/load.php";


 
$mentions=$twitter->get('statuses/mentions'); 


if( count($mentions)>0 ):
	foreach( $mentions as $twett )
		{
		$venueId = "Empty";
		$checkinId = "Empty";
		$message_status = NULL;
		$twitter_post = NULL;
		
		$exist = mention_exist( $twett->id_str ) ;
		if( !$exist )
			{
			
			// Get Token by Twitter Screen Name
			$user_object = get_token_by_twitter( $twett->user->screen_name );
			if( !is_null( $user_object ) )
				{
				$regex = '/@[gG][eE][tT][cC][hH][eE][cC][kK][iI][nN]\s(\w{1,})\s(.*)/';
				
				if( preg_match_all( $regex, $twett->text, $match ) > 0 )
					{
					$venueId	=	( isset( $match[1][0] ) ) ? $match[1][0] : $venueId;
					$shout		=	( isset( $match[2][0] ) ) ? $match[2][0] : "Empty";
					
					$foursquare->SetAccessToken( $user_object->token );
					
					// Verify is Search or ID
					if( $venueId === "search" )
						{
						$recent_checkins = json_decode( $foursquare->GetPrivate("users/self/checkins",array("limit"=>1,"v"=>date("Ymd"))) );
						if( $recent_checkins->meta->code == 200 )
							{
							//Get Latitud and Longitud last Checkin Venue
							$ll = $recent_checkins->response->checkins->items[0]->venue->location->lat.",".$recent_checkins->response->checkins->items[0]->venue->location->lng;
							// Make a Search
							$search_result = json_decode( $foursquare->GetPrivate("venues/search",array("query"=>$shout,"ll"=>$ll,"v"=>date(Ymd),"limit"=>1)) );
							if( $search_result->meta->code == 200 )
								{
								if( count( $search_result->response->venues ) > 0 )
									{
									$vanues_A = array(); 
									foreach( $search_result->response->venues as $venue )
										{
										save_venue( $venue->id, $venue->name." ".$venue->location->address );
										array_push( $vanues_A, serialize_venue_name( $venue->name." ".$venue->location->address ) );
										$twitter_post = twitter_post( "ID: " .  short_id( $venue->id ) . " Info: " . $venue->name." ".$venue->location->address, $twett->id_str, $twett->user->screen_name );
										}
									$vanues_S = implode( " ", $vanues_A );
									$message_status = $vanues_S;
									echo json_ok($message_status);
									}
									else
										{
										$message_status = "No results were found for the term: " . $shout;
										$twitter_post = twitter_post( $message_status, NULL, $twett->user->screen_name );
										echo json_error($message_status);
										twitter_user_delete_mention( $twett->id_str );
										}
								}
								else
									{
									$message_status = "Error: Venue search error.";
									$twitter_post = twitter_post( $message_status, NULL, $twett->user->screen_name );
									echo json_error($message_status);
									twitter_user_delete_mention( $twett->id_str );
									}
							}
							else
								{
								$message_status = "Error: User checkin search error.";
								$twitter_post = twitter_post( $message_status, NULL, $twett->user->screen_name );
								echo json_error($message_status);
								twitter_user_delete_mention( $twett->id_str );
								}
						}
						else
							{
							$venueIdDB = get_venue_id($venueId);
							$venueId = ( is_null( $venueIdDB ) ) ? $venueId : $venueIdDB;
							
								// Check if venueId is valid
							
							$venue_params = array("v"=>date("Ymd"));
							$venue_response = $foursquare->GetPrivate("venues/".$venueId,$venue_params);
							$venue = json_decode($venue_response);
							
							if( $venue->meta->code == 400 )
								{
								$venueId = "Error Venue ID";
								// Error
								$message_status = "Error: Invalid VenueID or Name";
								$twitter_post = twitter_post( $message_status, NULL, $twett->user->screen_name );
								echo json_error($message_status);
								twitter_user_delete_mention( $twett->id_str );
								}
								else
									{
									// Valid venueId
									if( $venue->meta->code == 200 )
										{
										if( is_null( $venueIdDB ) )
											{
											save_venue(  $venue->response->venue->id, $venue->response->venue->name." ".$venue->response->venue->location->address );
											}
										// Make CheckIn
										$foursquare->SetAccessToken( $user_object->token );
										$params = array("venueId"=>$venueId,"shout"=>$shout,"broadcast"=>"public,facebook,twitter,followers","v"=>date("Ymd"));
								
										$response = $foursquare->GetPrivate("checkins/add",$params,true);
										$checkin = json_decode($response);
								
										if( $checkin->meta->code == 400 )
											{
											//Error ChekIn
											$message_status = $checkin->meta->errorDetail;
											$twitter_post = twitter_post( $message_status, NULL, $twett->user->screen_name );
											echo json_error($message_status);
											twitter_user_delete_mention( $twett->id_str );
											}
											else
												{
												if( $venue->meta->code == 200 )
													{
													// CheckIn Sucesfull
													$checkinId = $checkin->response->checkin->id;
													// Bitly Short Url
													
													$url_checkin = bitly_v3_shorten($user_object->canonicalUrl."/checkin/".$checkinId);
													//$url_checkin = $bitly->shorten($user_object->canonicalUrl."/checkin/".$checkinId);
													$message_status = "#CheckIn Successfull " . $checkinId . " " .  $url_checkin["hash"];
													$twitter_post = twitter_post( $message_status, NULL, $twett->user->screen_name );
													echo json_ok($message_status);
													twitter_user_delete_mention( $twett->id_str );
													}
												}
										}
									}
							}
					
					}
					else
						{
						$message_status = "Error: Incorrect format! Correct format: Mention VenueID Message";
						$twitter_post = twitter_post( $message_status, NULL, $twett->user->screen_name );
						echo json_error($message_status);
						twitter_user_delete_mention( $twett->id_str );
						}
				}
				else
					{
					$message_status = "Error: You have not linked your Twitter account Foursquare. Sign in http://getcheck.in/";
					$twitter_post = twitter_post( $message_status, NULL, $twett->user->screen_name );
					echo json_error($message_status);
					twitter_user_delete_mention( $twett->id_str );
					}
			// Save Mention
			
			$twitter_id_response = ( isset( $twitter_post->id_str ) ) ? $twitter_post->id_str : 0;
			mention_save( $user_object->id, $twett->id_str, $twett->user->screen_name, $checkinId, $venueId, $twett->text, $twitter_id_response, $message_status );
			}
		}
else:
	echo json_ok("No hay tweets que procesar :)");
endif;

?>
