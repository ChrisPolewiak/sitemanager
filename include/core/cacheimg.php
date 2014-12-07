<?

function content_cache_generator( $datastore, $id, $width, $height, $showimg=false, $backend="content_cache" ) {
	global $SM_CONTENTCACHE_BACKEND, $SM_CONTENTCACHE_DATASTORE;

	// WHAT
	$datastore = strtolower( $datastore );
	// WHERE
	$backend = strtolower( $backend );

	if ($datastore != "content_file")
	{
		$image_filepath = $SM_CONTENTCACHE_DATASTORE[ $datastore ]["path"];
		$image_function = $SM_CONTENTCACHE_DATASTORE[ $datastore ]["function"];

		eval(" \$dane = $image_function( '$id' ); ");
		if (! $dane)
			return 0;

		if($SM_CONTENTCACHE_DATASTORE[ $datastore ]["backend"] != "internal")
		{
			$backend = $SM_CONTENTCACHE_DATASTORE[ $datastore ]["backend"];
			$fn = $SM_CONTENTCACHE_BACKEND[ $backend ]["fn_open"];
			if(function_exists($fn))
			{
				eval(" \$filedata = $fn(\"" . $dane["forum_photo__photo_path"] . "\"); ");
			}
			else
				error("Not found backend function '$fn'");
sm_trace( "file_get_contents ( ".$dane[ $image_filepath ]." )" );
		}
		else
		{
			$filedata = $dane[ $image_filedata ];
		}
sm_trace( "filedata" );

		if(!$filedata)
			$ERROR = "Can't open source file";
		else
		{
			$size = @GetImageSizeFromString( $filedata );
sm_trace( "GetImageSizeFromString" );
		}

		$mimetype = "";
		if(!$size)
			$ERROR = "Not a image";
		else
		{
			$mimetype = $size["mime"];
sm_trace( "mimetype" );
		}
	}
	else
	{
		if ($dane = content_file_dane( $id ))
		{
sm_trace( "content_file_dane" );
			$filedata = base64_decode($dane["content_file__filedata"]);
sm_trace( "base64_decode" );
			$mimetype = $dane["content_file__filetype"];
		}
		else
			$ERROR[] = "Can't load file";
	}

	if( $filedata && preg_match("/^image/", $mimetype) )
	{
		$image = new Imagick();
		$image->ReadImageBlob( $filedata );

sm_trace( "ReadImageBlob" );

		$geo=$image->getImageGeometry();

sm_trace( "getImageGeometry" );

		if ($width && !$height || !$width && $height)
		{
			// Thumbnail
			$w = $width ? $width : $height;
			$h = $height ? $height : $width;
			$image->cropThumbnailImage ( $w, $h );

sm_trace( "cropThumbnailImage" );

		}
		elseif($width && $height)
		{
			// scale
			$image->scaleImage( $width, $height, TRUE );

sm_trace( "scaleImage" );

		}

		if($showimg && !SM_TRACE)
		{
			header("Content-type: ".$dane["content_file__filetype"]);
			echo $image;
		}

		if($width || $height) {

sm_trace( "cache upload start" );

			$cache=array(
				"content_cache__table"		=> $datastore,
				"content_cache__tableid"	=> $id,
				"content_cache__w"			=> $width,
				"content_cache__h"			=> $height,
				"content_cache__ttl"		=> CACHE_IMAGE_TIMEOUT,
				"content_cache__contenttype"	=> $mimetype,
				"content_cache__data"		=> $image->getImageBlob(),
			);
			if(content_cache_add( $cache, $backend ))
			{
				if($showimg && !SM_TRACE)
				{
					header("Content-Type: image/jpg");
					echo $image->getImageBlob();
				}
			}
			else {
				echo "ERROR: $ERROR";
			}

sm_trace( "cache upload finish" );

		}
		return 1;
	}
	else {
		if($showimg) {
			$image = new Imagick();
			$draw = new ImagickDraw();
			$color_bg = new ImagickPixel('#ffffff');
			$image->newImage($width, $height, $color_bg);
			$draw->setFillColor('black');
			$image->annotateImage($draw, 10, 45, 0, $ERROR);
			$image->setImageFormat('png');
if(!SM_TRACE)
{
			header("Content-Type: image/png");
			echo $image;
}
else
			echo "ERROR: $ERROR";
		}
		return 0;
	}
}

?>
