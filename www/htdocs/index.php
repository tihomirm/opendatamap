<!DOCTYPE html>
<?php
error_reporting(0);
?>
<html>
<head>
<title>University of Southampton Open Linked Data Map</title>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<script src="http://www.google.com/jsapi"></script>
<script type="text/javascript" src="resources/jquery-1.5.min.js"></script>
<script type="text/javascript" src="resources/geoloc.js"></script>
<script type="text/javascript" src="resources/toggle.js"></script>
<script type="text/javascript" src="resources/credits.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/reset.css" type="text/css">
<link rel="stylesheet" href="css/index.css" type="text/css">
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-20609696-4']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
</head>
<body onload="initialize()">
<div id="spinner"><img src="resources/ajax-loader.gif"></div>
<div id="map_canvas"></div>
<img id="geobutton" src='resources/geoloc.png' onclick="geoloc()" alt="Geo-locate me!" title="Geo-locate me!" />
<div class="toggleicons" id="toggleicons">
<!--
   <img src="resources/transport.png" id="Transport" title="Transport" alt="Transport" onclick="toggle('Transport'); updateFunc();" />
   <img src="resources/catering.png" id="Catering" title="Catering" alt="Catering" onclick="toggle('Catering'); updateFunc();" />
   <img src="resources/services.png" id="Services" title="Services" alt="Services" onclick="toggle('Services'); updateFunc();" />
   <img src="resources/entertainment.png" id="Entertainment" title="Entertainment" alt="Entertainment" onclick="toggle('Entertainment'); updateFunc();" />
   <img src="resources/health.png" id="Health" title="Health" alt="Health" onclick="toggle('Health'); updateFunc();" />
   <img src="resources/religion.png" id="Religion" title="Religion" alt="Religion" onclick="toggle('Religion'); updateFunc();" />
   <img src="resources/retail.png" id="Retail" title="Retail" alt="Retail" onclick="toggle('Retail')" />
   <img src="resources/education.png" id="Education" title="Education" alt="Education" onclick="toggle('Education'); updateFunc();" />
   <img src="resources/general.png" id="General" title="General" alt="General" onclick="toggle('General'); updateFunc();" />
-->
</div>
<form action="" onsubmit="return false">
   <input id="inputbox" style='width:200px' value='<?php echo $_GET['q'] ?>'>
   <img id="clear" src='http://www.picol.org/images/icons/files/png/16/search_16.png' onclick="document.getElementById('inputbox').value=''; updateFunc();" alt="Clear search" title="Clear search">
   </input>
<ul id="list"></ul>
</form>
<div id="credits"><?php include 'credits.php' ?></div>
<div id="credits-small"><a href="credits.php">Application Credits</a></div>
</body>
<script type="text/javascript" src="alldata.php?lat=<?php echo $_GET['lat'] ?>&long=<?php echo $_GET['long'] ?>&zoom=<?php echo $_GET['zoom'] ?>">
</script>
</html>
