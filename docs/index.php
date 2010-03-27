<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<link rel="stylesheet" type="text/css" href="style.css" />
	
	<script type="text/javascript" src="js/mootools-1.2.1-core.js"></script>
	<script type="text/javascript" src="js/mootools-1.2-more.js"></script>
	<script type="text/javascript" src="js/ImageZoom.js"></script>
	
	<title>MooKit Documentation</title>
	<style type="text/css">
		* { margin: 0; text-align: center; }
		body { background: #AA5; font-family: monospace; font-size: 15px; }
		#phpDoc, #jsDoc { font-size: 35px; font-weight: bold; }
		#phpDoc { float: left; width: 50%; height: 600px; }
		#jsDoc { float: left; width: 50%; height: 600px; }
		#jsDoc iframe { background: #FFF;  }
		#DIA {  }
	</style>
</head>
<body>
	
	<div id="container">
		<!-- Image zoom start -->
		<div id="zoomer_thumb">
			<a href="MooKit.big.png" target="_blank"><img src="MooKit.small.png" width="20%" height="20%" /></a>
		</div>
		<div id="zoomer_big_container"></div>
		<!-- Image zoom end -->
		<p id="report"> Move the grey rectangle on the thumbnail to see the zoomed area. You can also drag the "big" image and see the region zoomed getting updated on the thumbnail.</p>
	</div>
	<div id="phpDoc">
		PHP Documentation
		<iframe src="phpDocs/index.html" width="100%" height="100%"> </iframe> 
	</div>
	<div id="jsDoc">
		JavaScript Documentation
		<iframe src="jsDocs/index.html" width="100%" height="100%"> </iframe> 
	</div>
</body>
</html>