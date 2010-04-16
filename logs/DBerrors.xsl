<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
  <html>
  <head>
    <title>MooKit DB Errors</title>
    <style type="text/css">
      body { text-align: center; color: #0CC; background: #33C; margin: 0; padding: 10px; font-family: monospace; }
      #title { font-size: 30px; font-weight: bold; position: relative; width: 250px; left: 10px; top: 10px; border: dashed #000; padding: 10px; margin: 0 0 20px 0; }
      #errorTable { margin: auto; }
      #errorTable th { font-size: 20px; }
      .error { background: white; margin: 15px; }
      .error td { color: #A33; padding: 34px; }
      #PHPerrors { width: 80px; height: 35px; background: #C33; position: absolute; right: 10px; top: 10px; }
      #PHPerrors a:link, a:visited, a:active  { color: #000; }
      #PHPerrors a:hover { color: #FFF; }
    </style>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/mootools/1.2.4/mootools-yui-compressed.js"></script>
    <script type="text/javascript">
	window.addEvent('domready',function() {
		$('clearErrors').addEvent('submit',function(e) {
			e.stop();
			this.set('send',{
				onRequest: function() {					
					document.body.tween('background-color','#00F');
				},
				onSuccess: function() {
					(function(){ document.body.tween('background-color','#33C'); (function() { location.reload(); }).delay(1000); }).delay(1000);
				}
			}).send();
		});
	});

    </script>
  </head>
  <body>
  <div id="title">MooKit DB Errors</div>
  <div id="PHPerrors"><a href="PHPerrors.xml">View PHP Errors</a></div>
  <form id="clearErrors" method="post" action="trimXMLerrors.php">
	<input type="text" name="trim" size="10" />
	<input type="hidden" name="errorLog" value="DBerrors.xml" />
	<input type="submit" value="Clear Error Log" />
  </form>
  <table id="errorTable">
    <tr>
      <th>DateTime</th>
      <th>Error Type</th>
      <th>Message</th>
    </tr>
    <xsl:for-each select="errors/error">
    <xsl:sort select="datetime" order="descending"/>
    <tr class="error">
      <td class="test"><xsl:value-of select="datetime"/></td>
      <td><xsl:value-of select="type"/></td>
      <td><xsl:value-of select="msg"/></td>
    </tr>
    </xsl:for-each>
  </table>
  </body>
  </html>
</xsl:template>

</xsl:stylesheet>
