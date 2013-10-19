<?

function content_cache_generator( $datastore, $id, $width, $height, $showimg=false ) {
	global $CACHEIMG; 

	$debug=false;

$debug ? $start = microtime(true) : "";

	if ($datastore != "content_file")
	{

		$image_filepath = $CACHEIMG[ strtolower($datastore) ]["path"];
		$image_filedata = $CACHEIMG[ strtolower($datastore) ]["data"];
		$image_function = $CACHEIMG[ strtolower($datastore) ]["function"];

echo $debug ? microtime(true)-$start."\tstart<br>\n" : "";

		eval(" \$dane = $image_function( '$id' ); ");

		if (! $dane)
			return 0;
 echo $debug ? microtime(true)-$start."\tget data from sql to resize<br>\n" : "";

		if($CACHEIMG[ strtolower($datastore) ]["type"] == "file")
		{
			$filedata = file_get_contents( $dane[ $image_filepath ] );
			$size = GetImageSize($dane[ $image_filepath ]);
			$mimetype = $size["mime"];
		}
		else
		{
			$filedata = $dane[ $image_filedata ];
		}

 echo $debug ? microtime(true)-$start."\tget filedata (datastore)<br>\n" : "";

	}
	else
	{

		if ($dane = content_file_dane( $id ))
		{
			$filedata = base64_decode($dane["content_file__filedata"]);
			$mimetype = $dane["content_file__filetype"];
		}

 echo $debug ? microtime(true)-$start."\tget filedata (content_file_dane)<br>\n" : "";

	}

	if($filedata)
	{
		$image = new Imagick();
		$image->ReadImageBlob( $filedata );

 echo $debug ? microtime(true)-$start."\tReadImageBlob<br>\n" : "";

		$geo=$image->getImageGeometry();

 echo $debug ? microtime(true)-$start."\tgetImageGeometry<br>\n" : "";

		if ($width && !$height || !$width && $height)
		{
			// Thumbnail
			$w = $width ? $width : $height;
			$h = $height ? $height : $width;
			$image->cropThumbnailImage ( $w, $h );

 echo $debug ? microtime(true)-$start."\tcropThumbnailImage<br>\n" : "";

		}
		elseif($width && $height)
		{
			// scale
			$image->scaleImage( $width, $height, TRUE );

 echo $debug ? microtime(true)-$start."\tscaleImage<br>\n" : "";

		}

		if($showimg)
		{
			header("Content-type: ".$dane["content_file__filetype"]);
			echo $image;
		}

		if($width || $height) {

 echo $debug ? microtime(true)-$start."\tpre-content_cache_add<br>\n" : "";

			$cache=array(
				"content_cache__table" => $datastore,
				"content_cache__tableid" => $id,
				"content_cache__w" => $width,
				"content_cache__h" => $height,
				"content_cache__ttl" => CACHE_IMAGE_TIMEOUT,
				"content_cache__contenttype" => $mimetype,
				"content_cache__data" => $image,
			);
			content_cache_add( $cache );

 echo $debug ? microtime(true)-$start."\tcontent_cache_add<br>\n" : "";

		}
	}
}

?>