<?

if (preg_match("/cacheimg\/(.+)/", $page, $tmp)) {
	$tmp = split("\/", $tmp[1]);

	foreach($tmp AS $param) {
		if(preg_match("/^id=(.+)/", $param, $value)) {
			$content_file__id = $value[1];
		}
		if(preg_match("/^w=(.+)/", $param, $value)) {
			$width = $value[1];
		}
		if(preg_match("/^h=(.+)/", $param, $value)) {
			$height = $value[1];
		}
		if(preg_match("/^ext.(.+)/", $param, $value)) {
			$extension = $value[1];
		}
	}
}

if ( $cache = content_cache_get( "content_file", $content_file__id, $width, $height ) ) {
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
	
	content_cache_clear();
	exit;
}
else {
	if ($dane = content_file_dane( $content_file__id )) {

		$filedata = base64_decode($dane["content_file__filedata"]);

		$image = new Imagick();
		$image->ReadImageBlob( $filedata );
		$geo=$image->getImageGeometry();

		if ($width && !$height || !$width && $height) {
			// Thumbnail
			$w = $width ? $width : $height;
			$h = $height ? $height : $width;
			$image->cropThumbnailImage ( $w, $h );
		}
		elseif($width && $height) {
			// scale
			$image->scaleImage( $width, $height, TRUE );
		}
		header("Content-type: ".$dane["content_file__filetype"]);
		echo $image;

		if($width || $height) {
			$cache=array(
				"content_cache__table" => "content_file",
				"content_cache__tableid" => $content_file__id,
				"content_cache__w" => $width,
				"content_cache__h" => $height,
				"content_cache__ttl" => 84600,
				"content_cache__contenttype" => $dane["content_file__filetype"],
				"content_cache__data" => $image,
			);
			content_cache_add( $cache );
		}
	}
}
?>