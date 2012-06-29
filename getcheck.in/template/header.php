<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="og:title" content="Get CheckIn" />
<meta property="og:type" content="website" />
<meta property="og:url" content="http://www.getcheck.in" />
<meta property="og:image" content="http://getcheck.in/template/img/getcheckin_logo_facebook.png" />
<meta property="og:image:type" content="image/png" />
<meta property="og:image:width" content="198" />
<meta property="og:image:height" content="198" />
<meta property="og:description" content="You CheckIn on Foursquare via Tweets on Twitter." />
<title>Get CheckIn</title>
<link href="<?php $GLOBALS['config_domain']; ?>icon.ico" rel="shortcut icon" type="image/x-icon" /> 
<link type="text/css" href="template/style.css" rel="stylesheet" />
</head>

<body>
	<div id="wrapper">
		<div id="header">
			<div class="wrap">
            	<div id="logo"><a href="<?php $GLOBALS['config_domain']; ?>" target="_self"><img src="template/img/getcheckin_logo.png" alt="Logo" /></a></div>
    			<div id="menu">
                	<?php if( is_login( "foursquare" ) ): ?>
        			<a href="logout.php" target="_self">Logout</a>                  
                    <?php endif; ?>
        		</div>
    		</div>
		</div>
		<div id="container" class="wrap">