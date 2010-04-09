<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
  <html>
  <head>
    <title>PHP Errors</title>
    <style type="text/css">
      body { text-align: center; background: #C33; margin: 0; padding: 10px; font-family: monospace; }
      #title { font-size: 30px; font-weight: bold; position: relative; width: 250px; left: 10px; top: 10px; border: dashed #000; padding: 10px; margin: 0 0 20px 0; }
      #errorTable { margin: auto; }
      #errorTable th { font-size: 20px; }
      .error { background: white; margin: 15px; }
      .error td { color: #A33; padding: 34px; }
    </style>
  </head>
  <body>
  <div id="title">PHP Errors</div>
  <form id="clearErrors" method="get" action="emptyPHPerrors.php">
	<input type="text" name="pass" size="10" />
	<input type="submit" value="Clear Error Log" />
  </form>
  <table id="errorTable">
    <tr>
      <th>DateTime</th>
      <th>Error #</th>
      <th>Type</th>
      <th>Message</th>
      <th>Script Name</th>
      <th>Line #</th>
    </tr>
    <xsl:for-each select="errors/error">
    <xsl:sort select="datetime" order="descending"/>
    <tr class="error">
      <td class="test"><xsl:value-of select="datetime"/></td>
      <td><xsl:value-of select="num"/></td>
      <td><xsl:value-of select="type"/></td>
      <td><xsl:value-of select="msg"/></td>
      <td><xsl:value-of select="scriptname"/></td>
      <td><xsl:value-of select="linenum"/></td>
    </tr>
    </xsl:for-each>
  </table>
  </body>
  </html>
</xsl:template>

</xsl:stylesheet>
