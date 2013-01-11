<?

$content_file__id = $_GET["id"];
$w = $_GET["w"];
$h = $_GET["h"];

if ( $cache = content_cache_get( "content_file", $content_file__id, $w, $h ) ) {
	$image = new Imagick();

	switch($cache["content_cache__encode"]) {
		case "base64":
			$filedata = base64_decode( $cache["content_cache__data"] );
			break;
	}
	$image = new Imagick();
	$image->ReadImageBlob( $filedata );
	header("Content-type: ".$cache["content_cache__contenttype"]);
	echo $image;
	exit;
}
else {
	if ($dane = content_file_dane( $content_file__id )) {

		$filedata = base64_decode($dane["content_file__filedata"]);
		$image = new Imagick();
		$image->ReadImageBlob( $filedata );
		$geo=$image->getImageGeometry();

		if ($w && !$h || !$w && $h) {
			// Thumbnail
			$w = $w ? $w : $h;
			$h = $h ? $h : $w;
			$image->cropThumbnailImage ( $w, $h );
		}
		elseif($w && $h) {
			// scale
			$image->scaleImage( $w, $h, TRUE );
		}
		header("Content-type: ".$dane["content_file__filetype"]);
		echo $image;

		$cache=array(
			"content_cache__table" => "content_file",
			"content_cache__tableid" => $content_file__id,
			"content_cache__w" => $_GET["w"],
			"content_cache__h" => $_GET["h"],
			"content_cache__ttl" => 60,
			"content_cache__contenttype" => $dane["content_file__filetype"],
			"content_cache__data" => $image,
		);
		content_cache_add( $cache );

	}
}
?>