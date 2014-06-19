<?php require_once("config.php"); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo BASIC_DOMAIN_NAME; ?> | Short URL's</title>
	<meta charset="utf-8" />
	<meta name="description" content="birk.it URL shortening service.">
    <link rel="stylesheet" href="css/main.css" />
	<script type="text/javascript" src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/actions.js"></script>
</head>
<body>
    <div class="header">
		<h1><?php echo BASIC_DOMAIN_NAME; ?>  URL shortner</h1>
		<h2><?php echo $QUOTES[rand(0,count($QUOTES)-1)]; ?></h2>
	</div>
	<div class="container">
		<form>
			<input class="box" id="box" type="text" name="urlinput" placeholder="Enter a URL..." /><br />
			<input class="submit" type="submit" name="submit" onclick="doaction(); return false;" />
		</form>
	</div>
	<div class="infobox">
		<p>Made by <a href="http://www.a-birkett.co.uk" target="_blank">Anthony Birkett</a> for personal use, but open for the public.</p>
		<h3>Super secret hidden features</h3>
		<p>Enter a shortened URL to get the original</p>
		<p>If someone has already shortened the same URL, you will get the same result</p>
		<p>URL's don't have to be valid. Hide abusive messages for your friends!</p>
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px" data-ad-client="ca-pub-3491498523960183" data-ad-slot="5861200158"></ins>
		<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
	</div>
	<div class="infobox">
	<p>&copy; <?php echo date('Y'); ?> Anthony Birkett</p>
	<p>By using this website, you agree to do so at your own risk. I take no responsibility for content linked to from this website, and make no guarantees of availability.
			Cookies are not used by this website, but may be used by Google AdSense.</p>
	</div>
</body>
</html>