<?php
error_reporting(E_ALL ^ E_NOTICE);

function theme_add_rewrite_rules($wp_rewrite ) 
{
	$new_rules = array( 
		'search/(.+?)/?$' => 'index.php?q='.$wp_rewrite->preg_index(1),
		'x/(.+?)/(.+?)/?$' => 'index.php?postid='.$wp_rewrite->preg_index(1),
        'u/(.+?)/?$' => 'index.php?userid='.$wp_rewrite->preg_index(1)
		);
	$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'theme_add_rewrite_rules');

$wp->add_query_var('q');
$wp->add_query_var('postid');
//$wp->add_query_var('anonymous');
//$wp->add_query_var('via');
$wp->add_query_var('userid');

date_default_timezone_set("Asia/Jakarta");

function theme_parseQuery()
{
	if (get_query_var('q') != '' || get_query_var('postid') != '' || get_query_var('userid') != '')
	{
		add_action('template_redirect','theme_template');
	}
}
add_filter('parse_query','theme_parseQuery');

function theme_template()
{
	//hack to prevent 404 return on valid page
	global $wp_query;
	status_header( 200 );
	$wp_query->is_404 = false;

	global $template;

	if (get_query_var('q') != '')
	{
		$template = get_query_template('tpl-search');
		include ($template);
		exit();
	}
	else if (get_query_var('postid') != '')
	{
		$template = get_query_template('tpl-view');
		include ($template);
		exit();
	}
    else if (get_query_var('userid') != '')
    {
        $template = get_query_template('tpl-user');
        include ($template);
        exit();
    }

}

function elapsed($ptime) 
{
	//date_default_timezone_set("Asia/Jakarta");
	$now = strtotime("now");

	#$ptime = strtotime($ptime);
    $etime = $now - $ptime;
    if ($etime<60) return('baru');
    //$etime = $now - $ptime;
    //return($etime);
       
    $a = array( 
                2 * 30 * 24 * 60 * 60   =>  'bulan',
                2 * 24 * 60 * 60        =>  'hari',
                2  * 60 * 60            =>  'jam',
                60                      =>  'menit',
                1                       =>  'detik'
                );
    
    foreach ($a as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . ' ' . $str . ' yg lalu';
        }
    }
}

function post_url($id,$title)
{
	$url = get_bloginfo('url')."/x/$id/".sanitize_title($title)."/";
	return($url);
}

function get_error_provider($code)
{
    switch($code) {

    case "0" : $msg = "(0) Unspecified error"; break;
    case "1" : $msg = "(1) Hybriauth configuration error"; break;
    case "2" : $msg = "(2) Provider not properly configured"; break;
    case "3" : $msg = "(3) Unknown or disabled provider"; break;
    case "4" : $msg = "(4) Missing provider application credentials (your application id, key or secret)"; break;
    case "5" : $msg = "(5) Authentification failed"; break;
    case "6" : $msg = "(6) User profile request failed"; break;
    case "7" : $msg = "(7) User not connected to the provider"; break;
    case "8" : $msg = "(8) Provider does not support this feature"; break;
    default: $msg = "Unknown Code: $code"; break;
    }

    return($msg);

}

function ytembed($youtubeid)
{
    $width = 420;
    $height = 315;
    /*
    return "<object width='$width' height='$height'>
        <param name='movie' value='//www.youtube.com/v/$youtubeid?hl=en_US&amp;version=3'></param>
        <param name='allowFullScreen' value='true'></param><param name='allowscriptaccess' value='always'></param>
        <embed src='//www.youtube.com/v/$youtubeid?hl=en_US&amp;version=3' type='application/x-shockwave-flash' width='$width' height='$height' allowscriptaccess='always' allowfullscreen='true'></embed>
        </object>";
    */

    return "<iframe width='560' height='315' src='http://www.youtube.com/embed/$youtubeid' frameborder='0'></iframe>";
}

function watermarkImage ($SourceFile, $DestinationFile) { 

    $WaterMarkText = "via Lontong.Net";
    list($width, $height, $type) = getimagesize($SourceFile);
    $image_p = imagecreatetruecolor($width, $height);
    if ($type==1)
        $image = imagecreatefromgif($SourceFile);
    else if ($type==2)
        $image = imagecreatefromjpeg($SourceFile);
    else if ($type==3)
        $image = imagecreatefrompng($SourceFile);
    else
        return(FALSE);

    if (!image)
        return(FALSE);

    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);

    $black = imagecolorallocatealpha($image, 0, 0, 0, 80);
    $white = imagecolorallocatealpha($image, 0xff, 0xff, 0xff,30);

    //putenv('GDFONTPATH=' . realpath('.'));
    //$font = "arial";
    //$font = 'arial.ttf';
    $font = '/srv/www/lontong.net/public_html/wp-content/themes/funny/arial.ttf';
    $font_size = 10; 
    $x = 10;
    $y = $height - 8;

    imagefilledrectangle($image_p, $x-2, $y-10, $x+90, $y+2, $black);

    if (imagettftext($image_p, $font_size, 0, $x, $y, $white, $font, $WaterMarkText)==FALSE)
        return(FALSE);

    if ($type==1)
        $ret = imagegif ($image_p, $DestinationFile); 
    else if ($type==2)
        $ret = imagejpeg ($image_p, $DestinationFile, 100); 
    else if ($type==3)
        $ret = imagepng ($image_p, $DestinationFile); 

    if ($ret == FALSE) return(FALSE);
    
    imagedestroy($image); 
    imagedestroy($image_p); 

    return(TRUE);
}

function is_animated($filename) {

    if(!($fh = @fopen($filename, 'rb')))
        return FALSE;
    $count = 0;
    //an animated gif contains multiple "frames", with each frame having a 
    //header made up of:
    // * a static 4-byte sequence (\x00\x21\xF9\x04)
    // * 4 variable bytes
    // * a static 2-byte sequence (\x00\x2C) (some variants may use \x00\x21 ?)
    
    // We read through the file til we reach the end of the file, or we've found 
    // at least 2 frame headers
    while(!feof($fh) && $count < 2) {
        $chunk = fread($fh, 1024 * 100); //read 100kb at a time
        $count += preg_match_all('#\x00\x21\xF9\x04.{4}\x00(\x2C|\x21)#s', $chunk, $matches);
   }
    
    fclose($fh);
    return $count > 1;
}

?>