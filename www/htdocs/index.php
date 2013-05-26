<!DOCTYPE html>
<?php
include 'config.php';

if($config['selection_required'] && count($versionparts) < 2)
{
	include 'selectmap.php';
	die();
}

$q = $_GET['q'];
if(isset($_GET['lat']) && $_GET['lat'] != "")
	$lat = $_GET['lat'];
else
	$lat = $config['default lat'];
if(isset($_GET['long']) && $_GET['long'] != "")
	$long = $_GET['long'];
else
	$long = $config['default long'];
if(isset($_GET['zoom']) && $_GET['zoom'] != "")
	$zoom = $_GET['zoom'];
else
	$zoom = $config['default zoom'];;
$uri = $_GET['uri'];
$zoomuri = $_GET['zoomuri'];
$clickuri = $_GET['clickuri'];
?>
<html>
	<head>
		<title><?php echo $config['Site title'] ?></title>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="keywords" content="<?php echo $config['Site keywords'] ?>" />
		<meta name="description" content="<?php echo $config['Site description'] ?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=8" />
		<link rel="apple-touch-icon" href="img/opendatamap.png" />
		<link rel="apple-touch-icon-precomposed" href="img/opendatamap.png" />
		<link rel="shortcut icon" href="img/opendatamap.png" />
		<script src="http://www.google.com/jsapi"></script>
		<script type="text/javascript" src="js/fixie.js"></script>
		<script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
		<script type="text/javascript" src="js/all.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/reset.css" type="text/css">
		<link rel="stylesheet" href="css/index.css" type="text/css">
<?php if(file_exists('modules/'.$version.'/style.css')) { ?>
		<link rel="stylesheet" href="modules/<?php echo $version ?>/style.css" type="text/css">
<?php } ?>
<?php
foreach(glob('/home/opendatamap/opendatamap.ecs.soton.ac.uk/www/htdocs/modules/'.$versionparts[0].'/resources/thumbnails/'.$versionparts[1].'_*.png') as $image)
{
	$image = str_replace('/home/opendatamap/opendatamap.ecs.soton.ac.uk/www/htdocs/modules/'.$versionparts[0].'/resources/thumbnails/', '', $image);
?>
                <meta property="og:image" content="http://opendatamap.ecs.soton.ac.uk/thumbnails/food/<?= $image ?>" />
<?php
}
?>
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo $config['Site title'] ?>" />
		<meta property="og:description" content="<?php echo $config['Site description'] ?>" />
	</head>
	<body onload="initialize(<?php echo $lat.', '.$long.', '.$zoom.", '".$uri."', '".$zoomuri."', '".$clickuri."', '".$_GET['v']."', ".$config['default map'] ?>)">
<? include_once 'googleanalytics.php'; ?>
		<div id="spinner">
			<img src="img/ajax-loader.gif" />
			<br/>
			<br/>Please wait while the map loads...
		</div>
<?php if(has('sidebar')) { ?>
		<div id="sidebar">
			<?php include 'sidebar.php' ?>
		</div>
<?php } ?>
<?php if(has('sidebar-hidden')) { ?>
		<div id="sidebar" style='display:none'>
			<?php include 'sidebar.php' ?>
		</div>
<?php } ?>
<?php if(!has('-title')) { ?>
		<div id="title" style='height:2em; padding:0.5em;'>
			<?php echo $config['Site title'] ?>
			<span style='font-size: 0.5em'>
				<a href='list'>Select different map</a>
			</span>
		</div>
<?php } ?>
		<div id="map_canvas" style='<?php echo $config['map style'] ?><?php if(!has('-title')) { echo " top:2em;"; } ?>'></div>
		<img id="geobutton" <?php show('geobutton') ?> src='img/geoloc.png' onclick="geoloc()" alt="Geo-locate me!" title="Geo-locate me!" />
		<div class="toggleicons" id="toggleicons" <?php show('toggleicons') ?>>
<?
foreach($config['categories'] as $catid => $catname)
{
	$catidparts = explode('/', $catid, 2);
	if(count($catidparts) == 2)
	{
		$imgpath = 'modules/'.$catidparts[0].'/icons/'.$catidparts[1];
	}
	else
	{
		$imgpath = 'img/icon/'.$catid;
	}
?>
			<div title='<?= tidyCatName($catname) ?>' class='togglebutton' style='background-image:url(<?= $imgpath ?>/ntw.blank.png)' onclick="toggle('<?= $catid ?>')">
				<span class='label'><?= str_replace(' and ', ' <span style=\'font-size:0.8em\'>&amp;</span> ', $catname) ?></span>
				<input class='togglebox' style='cursor:pointer' type='checkbox' name='<?= tidyCatName($catname) ?>' id='<?= $catid ?>' onclick="toggle('<?= $catid ?>');" <?= isset($config['selected'][$catid]) ? 'checked=\'checked\'' : '' ?>/>
			</div>
<?
}
?>
			<img src='img/left.png' id='iconexpand' onclick='$("#toggleicons").removeClass("offset")' title='Expand' />
			<img src='img/right.png' id='iconcollapse' onclick='$("#toggleicons").addClass("offset")' title='Collapse' />
		</div>
		<form id='search' <?php show('search') ?>action="" onsubmit='return false'>
			<input id="inputbox" style='width:206px' value='<?php echo $q ?>' onFocus="searchResults_enter();" onBlur="delayHide('list', 1000);">
				<img id="clear" src='http://www.picol.org/images/icons/files/png/16/search_16.png' onclick="searchResults_setInputBox('', false); searchResults_updateFunc();" alt="Clear search" title="Clear search" />
			</input>
			<ul style='display:none' id="list"></ul>
		</form>
<?php if(has('bookmarks')) { ?>
		<div id="bookmarks" style='display:none'>
			<?php include 'bookmarks.php' ?>
		</div>
<?php } ?>
		<div id="search-small" <?php show('search') ?>>
			<img src='img/search.png' onclick="window.location='m'" alt="Search" title="Search" />
		</div>
		<div id="credits">
			<?php $include = true; include 'credits.php' ?>
		</div>
		<div id="credits-small">
			<a href="credits.php">Application Credits</a>
		</div>
	</body>
</html>
