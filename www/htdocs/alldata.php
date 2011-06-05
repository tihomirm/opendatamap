<?php
error_reporting(0);
include_once "inc/sparqllib.php";

$endpoint = "http://sparql.data.southampton.ac.uk";

$allpos = sparql_get($endpoint, "
PREFIX geo: <http://www.w3.org/2003/01/geo/wgs84_pos#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX spacerel: <http://data.ordnancesurvey.co.uk/ontology/spatialrelations/>
PREFIX org: <http://www.w3.org/ns/org#>
PREFIX gr: <http://purl.org/goodrelations/v1#>

SELECT DISTINCT ?pos ?lat ?long ?poslabel ?icon WHERE {
  ?pos a gr:LocationOfSalesOrServiceProvisioning .
  ?pos rdfs:label ?poslabel .
  OPTIONAL { ?pos spacerel:within ?b .
             ?b geo:lat ?lat . 
             ?b geo:long ?long .
             ?b a <http://vocab.deri.ie/rooms#Building> .
           }
  OPTIONAL { ?pos spacerel:within ?s .
             ?s geo:lat ?lat . 
             ?s geo:long ?long .
             ?s a org:Site .
           }
  OPTIONAL { ?pos geo:lat ?lat .
             ?pos geo:long ?long .
           }
  OPTIONAL { ?pos <http://purl.org/openorg/mapIcon> ?icon . }
  FILTER ( BOUND(?long) && BOUND(?lat) )
} ORDER BY ?poslabel
");

$allcls = sparql_get($endpoint, "
PREFIX geo: <http://www.w3.org/2003/01/geo/wgs84_pos#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX spacerel: <http://data.ordnancesurvey.co.uk/ontology/spatialrelations/>
PREFIX org: <http://www.w3.org/ns/org#>
PREFIX gr: <http://purl.org/goodrelations/v1#>
PREFIX skos: <http://www.w3.org/2004/02/skos/core#>

SELECT DISTINCT ?pos ?lat ?long ?poslabel WHERE {
  ?pos <http://purl.org/openorg/hasFeature> ?f .
  ?f a ?ft .
  ?ft rdfs:label ?ftl .
  ?pos skos:notation ?poslabel .
  OPTIONAL { ?pos spacerel:within ?b .
             ?b geo:lat ?lat . 
             ?b geo:long ?long .
             ?b a <http://vocab.deri.ie/rooms#Building> .
           }
  OPTIONAL { ?pos spacerel:within ?s .
             ?s geo:lat ?lat . 
             ?s geo:long ?long .
             ?s a org:Site .
           }
  OPTIONAL { ?pos geo:lat ?lat .
             ?pos geo:long ?long .
           }
  OPTIONAL { ?pos <http://purl.org/openorg/mapIcon> ?icon . }
  FILTER ( BOUND(?long) && BOUND(?lat) && REGEX(?ftl, '^WORKSTATION -') )
} ORDER BY ?poslabel
");

$allbus = sparql_get($endpoint, "
PREFIX geo: <http://www.w3.org/2003/01/geo/wgs84_pos#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX spacerel: <http://data.ordnancesurvey.co.uk/ontology/spatialrelations/>
PREFIX org: <http://www.w3.org/ns/org#>

SELECT ?pos ?poslabel ?lat ?long (GROUP_CONCAT(?code) as ?codes) {
  ?rstop <http://id.southampton.ac.uk/ns/inBusRoute> ?route .
  ?rstop <http://id.southampton.ac.uk/ns/busStoppingAt> ?pos .
  ?route <http://www.w3.org/2004/02/skos/core#notation> ?code .
  ?pos rdfs:label ?poslabel .
  ?pos geo:lat ?lat .
  ?pos geo:long ?long .
  FILTER ( REGEX( ?code, '^U', 'i') )
} GROUP BY ?pos ?poslabel ?lat ?long ORDER BY ?poslabel
");

echo "[";
$i = 0;
foreach($allpos as $point) {
	$point['poslabel'] = str_replace('\'', '\\\'', $point['poslabel']);
	$point['icon'] = str_replace("http://google-maps-icons.googlecode.com/files/", "http://opendatamap.ecs.soton.ac.uk/img/icon/", $point['icon']);
	$point['icon'] = str_replace("http://data.southampton.ac.uk/map-icons/lattes.png", "http://opendatamap.ecs.soton.ac.uk/img/icon/coffee.png", $point['icon']);
	if($point['icon'] == "")
		$point['icon'] = "img/blackness.png";

	echo '["'.$point['pos'].'",'.$point['lat'].','.$point['long'].',"'.str_replace("\\", "\\\\", $point['poslabel']).'","'.$point['icon'].'"],';
	//if($i++ > 114)
	//	break;
}

foreach($allbus as $point) {
	$codes = explode(' ', $point['codes']);
	sort($codes);
	$codes = array_unique($codes);
	$codes = implode('/', $codes);
	echo '["'.$point['pos'].'",'.$point['lat'].','.$point['long'].',"'.$point['poslabel'].'","http://opendatamap.ecs.soton.ac.uk/dev/colin/resources/busicon.php?r='.$codes.'"],';
}

foreach($allcls as $point) {
	echo '["'.$point['pos'].'",'.$point['lat'].','.$point['long'].',"'.$point['poslabel'].'","http://opendatamap.ecs.soton.ac.uk/img/icon/computer.png"],';
}
echo "[]]";
?>
