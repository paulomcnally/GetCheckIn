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
		if( $exist )
			{
			$user_object = get_token_by_twitter( $twett->user->screen_name );
			if( twitter_is_user_registered() )
				{
				echo "<pre>";
				echo print_r( twitter_user_delete_mention( $twett->id_str ) );
				echo "</pre>";
				}
			
			}
		}
else:
	echo json_ok("No hay tweets que eliminar :)");
endif;

?>
