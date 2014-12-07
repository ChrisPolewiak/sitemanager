<?

$datastore = "content_file";

if (preg_match("/cacheimg\/(.+)/", $page, $tmp))
{
	$tmp = explode("\/", $tmp[1]);

	foreach($tmp AS $param)
	{
		if(preg_match("/^id=(.+)/", $param, $value))
			$id = $value[1];

		if(preg_match("/^w=(.+)/", $param, $value))
			$width = $value[1];

		if(preg_match("/^h=(.+)/", $param, $value))
			$height = $value[1];

		if(preg_match("/^ext.(.+)/", $param, $value))
			$extension = $value[1];

		if(preg_match("/^d.(.+)/", $param, $value))
			$datastore = $value[1];

		if(preg_match("/^t.(.+)/", $param, $value))
			$datatype = $value[1];

		if(preg_match("/^nowm$/", $param, $value))
			$nowatermark = true;

	}
}

if ( $cache = content_cache_get( $datastore, $id, $width, $height ) )
{
sm_trace( "content_cache_get" );

	$image = new Imagick();

	switch($cache["content_cache__encode"])
	{
		case "base64":
			$filedata = base64_decode( $cache["content_cache__data"] );
sm_trace( "base64_decode" );
			break;
	}

	$image = new Imagick();
	$image->ReadImageBlob( $filedata );
if(!SM_TRACE)
{
	header("Content-type: ".$cache["content_cache__contenttype"]);
	echo $image;
}
	
	exit;
}
else
{
	content_cache_generator( $datastore, $id, $width, $height, $showimg=true );
	sm_trace( "content_cache_generator" );

}
?>
