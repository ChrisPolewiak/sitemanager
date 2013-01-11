<?
/**
 * visuality
 * 
 * @author		Chris Polewiak <chris@polewiak.pl>
 * @version		5.0.0
 * @package		core
 * @category	visuality
 */

/**
 * @category	visuality
 * @package		core
 * @version		5.0.0
*/
function make_searchable_url($url, $name) {
	$name = preg_replace("/([^\w\d\-\_])/s", "_", $name);
	return $url."/".$name;
}

/**
 * @category	visuality
 * @package		core
 * @version		5.0.0
*/
function filesize_convert( $filesize, $stat="" ) {
	switch($stat) {
		case "k": 
			return number_format(round($filesize/1024, 3),0,"."," ") . " kb";
		case "m": 
			return number_format(round($filesize/1024/1024, 3),0,"."," ") . " Mb";
		case "g": 
			return number_format(round($filesize/1024/1024/1024, 3),0,"."," ") . " Gb";
		default:
			if ( $filesize < 1024*1024*1024 ) {
				if ( $filesize < 1024*1024 ) {
					if ( $filesize < 1024 ) {
						return $filesize . " b";
					}
					else
						return number_format(round($filesize/1024, 2),2,"."," ") . " kb";
				}
				else
					return number_format(round($filesize/1024/1024, 2),2,"."," ") . " Mb";
			}
			else
				return number_format(round($filesize/1024/1024/1024, 2),2,"."," ") . " Gb";
	}
}

/**
 * @category	visuality
 * @package		core
 * @version		5.0.0
*/
function time_convert( $sec ) {
	// hours
	$d = intval($sec/86400);
	$h = intval( ($sec-($d*86400)) / 3600 );
	$m = intval( ($sec-($d*86400)-($h*3600)) / 60 );
	$s = intval(  $sec-($d*86400)-($h*3600)-($m*60) ) ;
	return array($d, $h, $m, $s);
}

/**
 * @category	visuality
 * @package		core
 * @version		5.0.0
*/
function count_download_time( $filesize, $bandwidth ) {

	$true_bandwidth = $bandwidth*0.8;
	$filesize_bits = $filesize*8;

	$download_sec = round($filesize_bits/$true_bandwidth,0);

	// hours
	$hours = intval($download_sec/3600);
	$minutes = intval(( $download_sec - ($hours*3600) ) / 60);
	$seconds = ( $download_sec - ($hours*3600) - ($minutes*60) ) ;

	return array($hours, $minutes, $seconds);
}
 
/**
 * @category	visuality
 * @package		core
 * @version		5.0.0
*/
function cms_get_foto_resize( $fileurl, $width=false, $height=false, $proportion=false ) {
	global $ROOT_DIR, $CACHE_IMAGE_TIMEOUT, $SET_FILES_GROUP;

	// generuje kod dla adresu pliku
	$filemd5 = md5($fileurl);
	$fileext = preg_replace("/.+\.(.+?)$/", "\\1", basename($fileurl));

	// pozwala na odczyt pliku w zależności czy jest on w serwisie (podany jako '/sqldata/..../'), czy też jest z innego serwera ('http://....')
	if (!preg_match("/^http\:\/\//", $fileurl)){
		$fileurl = $ROOT_DIR."/html".$fileurl;
	}
	else {
		$file_from_www = true;
	}

	$cached_subdir1 = substr($filemd5,1,1);
	$cached_subdir2 = substr($filemd5,2,1);
	$width = $width ?  $width : "";
	$height = $height ? $height : "";
	$filename = $filemd5 ."-". $width ."-". $height ."-". substr($proportion,0,1) .".". $fileext;


	// struktura katalogów
	if (!is_dir($ROOT_DIR."/html/sqldata/cacheimg")){
		mkdir($ROOT_DIR."/html/sqldata/cacheimg", 0770);
		chgrp($ROOT_DIR."/html/sqldata/cacheimg", $SET_FILES_GROUP);
		chmod($ROOT_DIR."/html/sqldata/cacheimg", 0770);
	}
	if (!is_dir($ROOT_DIR."/html/sqldata/cacheimg/".$cached_subdir1)){
		mkdir($ROOT_DIR."/html/sqldata/cacheimg/".$cached_subdir1, 0770);
		chgrp($ROOT_DIR."/html/sqldata/cacheimg/".$cached_subdir1, $SET_FILES_GROUP);
		chmod($ROOT_DIR."/html/sqldata/cacheimg/".$cached_subdir1, 0770);
	}
	if (!is_dir($ROOT_DIR."/html/sqldata/cacheimg/".$cached_subdir1."/".$cached_subdir2)){
		mkdir($ROOT_DIR."/html/sqldata/cacheimg/".$cached_subdir1."/".$cached_subdir2, 0700);
		chgrp($ROOT_DIR."/html/sqldata/cacheimg/".$cached_subdir1."/".$cached_subdir2, $SET_FILES_GROUP);
		chmod($ROOT_DIR."/html/sqldata/cacheimg/".$cached_subdir1."/".$cached_subdir2, 0770);
	}

	// tworzenie docelowych nazw pliku na serwerze oraz URL do wyświetlenia pliku
	$destination_dir = $ROOT_DIR."/html/sqldata/cacheimg/".$cached_subdir1."/".$cached_subdir2;
	$destination_url = "/sqldata/cacheimg/".$cached_subdir1."/".$cached_subdir2;

	// sprawdzanie daty pliku
	// jeżeli plik jest nowszy niż wersja w cache lub wersja cache jest starsza niż zdefiniowany okres to generuje nowy plik cache
	if(!$file_from_www && is_file($fileurl)) {
		$filetime_src   = filemtime($fileurl);
	}
	if (is_file($destination_dir."/".$filename)) {
		$filetime_cache = filemtime($destination_dir."/".$filename);
	}

	if (
		!is_file($destination_dir."/".$filename)
		||
		time()-$filetime_cache>$CACHE_IMAGE_TIMEOUT
		||
		$filetime_cache<$filetime_src
		){

		if($file_from_www) {
			copy($fileurl, "/tmp/". $filemd5 .".". $fileext);
		}

		if ($width || $height) {
			root_img_resize( array(
				"src" => $fileurl,
				"width" => $width,
				"height" => $height,
				"bestfit" => true,
				"trg" => $destination_dir."/".$filename
			));
		}
		else {
			copy($fileurl, $destination_dir."/".$filename);
		}
		chgrp($destination_dir."/".$filename, $SET_FILES_GROUP);
		chmod($destination_dir."/".$filename, 0664);

		if($file_from_www) {
			remove("/tmp/". $filemd5 .".". $fileext);
		}
	}
	return $destination_url."/".$filename;
}

/**
 * @category	visuality
 * @package		core
 * @version		5.0.0
*/
function cms_text_image_resize( $body ) {
	preg_match_all("/<img.+?>/i", $body, $find);
	if( sizeof($find[0])>0 ) {
		foreach($find[0] AS $image){
			$_w = $_h = $_s = $_s = "";
			if(preg_match("/width=[\'\"]*(\d+)[\'\"]*/is",$image,$tmp)){
				$_w = $tmp[1];
			}
			if(preg_match("/height=[\'\"]*(\d+)[\'\"]*/si",$image,$tmp)){
				$_h = $tmp[1];
			}
			if(preg_match("/src=[\'\"]*([^\'^\"]+)[\'\"]*/is",$image,$tmp)){
				$_s = $tmp[1];
			}
			if($_w && $_h){
				$_s2 = cms_get_foto_resize( $_s, $width=$_w, $height=$_h, $proportion=true );
			}
			elseif($_w && !$_h){
				$_s2 = cms_get_foto_resize( $_s, $width=$_w, $height="", $proportion=false );
			}
			elseif(!$_w && $_h){
				$_s2 = cms_get_foto_resize( $_s, $width="", $height=$_h, $proportion=false );
			}
			
			$_s = preg_replace("/(\/)/s", "\/", $_s);
			$image2 = preg_replace("/$_s/is", $_s2, $image);
			$tofind[] = "/". preg_replace("/(\/)/s", "\/", $image) ."/";
			$torepl[] = $image2;
		}
		$body = preg_replace($tofind, $torepl, $body);
	}
	return $body;
}

?>