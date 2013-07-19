<?

$streetview_uri = 'http://maps.google.com/cbk';

function streetViewMetadata($lat, $lon, $radius = 2000)
{
	global $streetview_uri;
	$url = $streetview_uri.'?output=json&v=4&dm=0&pm=0&ll='.$lat.','.$lon.'&radius='.$radius;
	
	return streetViewRequest($url);
}

function streetViewMetadataById($panoid)
{
	global $streetview_uri;
	$url = $streetview_uri.'?output=json&v=4&dm=0&pm=0&panoid='.$panoid;
	
	return streetViewRequest($url);
}

function streetViewRequest($url)
{
	return json_encode(file_get_contents($url));
}

?>
