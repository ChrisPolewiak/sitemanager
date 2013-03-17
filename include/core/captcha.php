<?php

require $INCLUDE_DIR."/core/secureimage.php";

$w = isset($_REQUEST["w"]) ? $_REQUEST["w"] : 50;
$w = $w<=100 ? $w : 100;

$h = isset($_REQUEST["h"]) ? $_REQUEST["h"] : 0;
$h = $h<=300 ? $h : 300;

$img = new Securimage();
$img->case_sensitive  = false;
$img->image_height    = $w ? $w : 50;
$img->image_width     = $h ? $h : (int)($img->image_height * 2.875);
$img->charset         = "ABCDEFGHIJKLMNOPQRSTUWVXYZ";

$img->perturbation    = 0.9;
$img->image_bg_color  = new Securimage_Color("#ffffff");
$img->text_color      = new Securimage_Color(rand(0, 128),rand(0, 128),rand(0, 128));
$img->num_lines       = 0;
$img->line_color      = new Securimage_Color(rand(128, 200),rand(128, 200),rand(128, 200));
$img->background_directory = dirname(__FILE__) . '/backgrounds/';
$img->image_type      = SI_IMAGE_JPEG;
$img->ttf_file		  = $INCLUDE_DIR."/resources/Merriweather_Sans_700.ttf";

$img->show();

?>