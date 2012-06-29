<?php 
require_once "load.php";
?>
<?php get_template("header"); ?>
<?php if( !is_login( "foursquare" ) ): ?>
	<div align="center">
    	<a href="http://getcheck.in/authentication.php?type=foursquare" target="_self">
        	<img src="template/img/en/login_button.png" alt="Login Button" />
        </a>
    </div>
<?php endif; ?>

<!-- Left -->
<?php if( is_login( "foursquare" ) ): ?>
<?php save_my_venues(); ?>
<div id="cleft">
<?php $foursquaremap = new Foursquaremap($_SESSION["token_foursquare"]); ?>

	<!-- Welcome Messaget -->
	<div id="welcome" class="border radius">
		<div class="userpic">
    		<a href="javascript:void(0);">
        		<img src="<?php echo $_SESSION["user_foursquare"]->photo; ?>" alt="Photo" />
        	</a>
		</div>
    	<div class="userdetail">
    		<h1><?php echo $_SESSION["user_foursquare"]->firstName . " " . $_SESSION["user_foursquare"]->lastName; ?></h1>
            <div>
            	<span class="capt">Checkins:</span> <span class="data"><?php echo $_SESSION["user_foursquare"]->checkins->count; ?></span>
                &nbsp;
                <span class="capt">Badges:</span> <span class="data"><?php echo $_SESSION["user_foursquare"]->badges->count; ?></span>
                &nbsp;
                <span class="capt">Friends:</span> <span class="data"><?php echo $_SESSION["user_foursquare"]->friends->count; ?></span>
                
             </div>
    	</div>
	</div>
    <!-- End - Welcome Messaget -->
    
	<!-- Foursquare Twitter Account Check -->
    <?php if(!property_exists($_SESSION["user_foursquare"]->contact,"twitter")): ?>
	<div class="bbox notify border radius">
		<a href="https://es.foursquare.com/settings/sharing" target="_blank" >Es necesario que vincules tu cuenta de Twitter</a>
    </div>
    <?php else: ?>
    <div class="bbox notify border radius">
        <a target="_blank" href="http://www.twitter.com/<?php echo $_SESSION["user_foursquare"]->contact->twitter; ?>">@<?php echo $_SESSION["user_foursquare"]->contact->twitter; ?></a>
    </div>
    <?php endif; ?>
    <!-- END - Foursquare Twitter Account Check -->
    
    <!-- All Chekins -->
    <?php if( count( $foursquaremap->allCheckins ) > 0 ): ?>
    <div class="bbox border radius" style="padding:10px;">
    	<?php $url_check_in =  (property_exists($_SESSION["user_foursquare"]->contact,"twitter")) ? $_SESSION["user_foursquare"]->contact->twitter : $_SESSION["user_foursquare"]->id; ?>
		<?php foreach( $foursquaremap->allCheckins as $checkin ): ?>
        <div><a href="<?php echo "https://es.foursquare.com/" . $url_check_in; ?>/checkin/<?php echo $checkin->id; ?>" target="_blank"><?php echo $checkin->venue->name; ?> | <?php echo date("d/m/Y",$checkin->createdAt); ?></a></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <!-- End - All Chekins -->


</div>
<!-- End - Left -->

<!-- Right -->
<div id="cright">
<?php if( isset( $foursquaremap ) ): ?>
	<!-- Last CheckIn Map -->
	<div class="bbox border radius" style="padding:10px;">
    
    <?php $foursquaredir = array(); ?>
    <?php if( !empty( $foursquaremap->venueAddress ) ) { array_push( $foursquaredir, $foursquaremap->venueAddress ); } ?>
    <?php if( !empty( $foursquaremap->venueCity ) ) { array_push( $foursquaredir, $foursquaremap->venueCity ); } ?>
    <?php if( !empty( $foursquaremap->venueState ) ) { array_push( $foursquaredir, $foursquaremap->venueState ); } ?>
    <?php if( !empty( $foursquaremap->venueCountry ) ) { array_push( $foursquaredir, $foursquaremap->venueCountry ); } ?>
    
    	<div style="position:relative; overflow:hidden; background-image:url(<?php echo $foursquaremap->venueIcon ?>); background-repeat:no-repeat; background-position:5px 5px; height:40px; padding-left:42px; line-height:42px; font-weight:bold;" class="border radius"><?php echo $foursquaremap->venueName ?> (<?php echo $foursquaremap->venueType ?>)</div>
        <div align="center" style="margin:10px 0; height:300px; width:296px; background-image:url(<?php echo $foursquaremap->getMapUrl(300, 300); ?>); background-repeat:no-repeat; background-position:center;" class="border radius"></div>
        <?php if( count( $foursquaredir ) > 0 ): ?>
        <div style="position:relative;overflow:hidden;padding:5px;font-weight:bold;margin-bottom:10px;" class="border radius"><?php echo implode(", ", $foursquaredir); ?></div>
        <?php endif; ?>
        <div style="position:relative;overflow:hidden;padding:5px;font-weight:bold;" class="border radius"><?php echo $foursquaremap->comment; ?></div>
    </div>
    <!-- End - Last CheckIn Map -->
<?php endif; ?>    
    
    <!--
	<div style="position:relative; overflow:hidden;">
		
		<div>
    		<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FGet-CheckIn%2F372776792735058&amp;send=false&amp;layout=button_count&amp;width=150&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;appId=255124784497783" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:150px; height:21px;" allowTransparency="true">
            </iframe>
    	</div>

		
		<div>
    		<a href="https://twitter.com/GetCheckIn" class="twitter-follow-button" data-show-count="false" data-lang="es" data-size="large">Segui @GetCheckIn</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
    	</div>
	</div>
    -->
</div>
<!-- End - Right -->
<?php endif; ?>
<?php get_template("footer"); ?>