<?

$streetview_uri = 'http://maps.google.com/cbk';

/**
 * Fetches the metadata from the street view service in JSON format
 * @param lat The latitude to get the street view data from
 * @param lon The longitude to get the street view data from
 * @param radius The radius to search around the latitude and longitude to find a valid point
 */
function streetViewMetadata($lat, $lon, $radius = 2000)
{
	global $streetview_uri;
	$url = $streetview_uri.'?output=json&v=4&dm=0&pm=0&ll='.$lat.','.$lon.'&radius='.$radius;
	
	return streetViewRequest($url);
}

/**
 * Fetches the metadata from the street view service in JSON format using a panorama id
 * @param panoid The ID of the panorama to fetch
 */
function streetViewMetadataById($panoid)
{
	global $streetview_uri;
	$url = $streetview_uri.'?output=json&v=4&dm=0&pm=0&panoid='.$panoid;
	
	return streetViewRequest($url);
}

/**
 * Requests a URL and encodes it in JSON
 * @param URL The URL to fetch
 */
function streetViewRequest($url)
{
	return json_decode(file_get_contents($url), true);
}

/**
 * Return a tile image within a street view panorama
 * @param panoid The ID of the panorama to get the tile from
 * @param x The x coordinate of the tile
 * @param y The y coordinate of the tile
 * @param zoom The zoom level of the tile
 */
function streetViewTile($panoid, $x, $y, $zoom)
{
	global $streetview_uri;
	$url = $streetview_uri.'?output=tile&panoid='.$panoid.'&zoom='.$zoom.'&x='.$x.'&y='.$y.'&fover=2&onerr=3&renderer=spherical&v=4';
	
	return imagecreatefromjpeg($url);
}

/**
 * Return a panorama image
 * @param panoid The ID of the panorama
 * @param size The width and height of the tile for each panorama
 * @param zoom The zoom level the panorama
 */
function streetViewPanorama($panoid, $size, $zoom = 5)
{
	$maxX = 1;
	$maxY = 1;
	
	switch($zoom)
	{
		default: $maxX = 1; $maxY = 1; break;
		case 1: $maxX = 2; $maxY = 1; break;
		case 2: $maxX = 4; $maxY = 2; break;
		case 3: $maxX = 6; $maxY = 3; break;
		case 4: $maxX = 13; $maxY = 7; break;
		case 5: $maxX = 26; $maxY = 13; break;
	}
	
	for($i=0;$i<$maxX;++$i)
	{
		for($j=0;$j<$maxY;++$j)
		{
			$tile = streetViewTile($panoid, $i, $j, $zoom);
			
			if(!isset($panorama))
				$panorama = imagecreatetruecolor($size*$maxX, $size*$maxY);
			
			imagecopyresized($panorama, $tile, $i*$size, $j*$size, 0, 0, $size, $size, imagesx($tile), imagesx($tile));
			
			imagedestroy($tile);
		}
	}
	
	return $panorama;
}

?>
