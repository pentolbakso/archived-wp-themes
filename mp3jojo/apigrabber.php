<?php

/*
Template Name: API (hidden)
*/

global $wpdb;
$APIKey= "d6b3df6ab6bead4ee3b74c9429dea40b";

if( function_exists('mysql_set_charset') )	mysql_set_charset('utf8');
else $wpdb->query("SET NAMES 'utf8'");

//top artist --------------
$url = "http://ws.audioscrobbler.com/2.0/?method=chart.gettopartists&api_key=$APIKey&limit=44&format=json";
$json = file_get_contents($url);

$sql = "REPLACE INTO mp3.apicache (apiname,rawdata,added) VALUES('lastfm.topartist','".mysql_real_escape_string($json)."',NOW())";
$wpdb->query($sql);

//top tracks --------------
$url = "http://ws.audioscrobbler.com/2.0/?method=chart.gettoptracks&api_key=$APIKey&format=json";
$json = file_get_contents($url);

$sql = "REPLACE INTO mp3.apicache (apiname,rawdata,added) VALUES('lastfm.gettoptracks','".mysql_real_escape_string($json)."',NOW())";
$wpdb->query($sql);

//apple rss ---------------
$url = "http://itunes.apple.com/WebObjects/MZStore.woa/wpa/MRSS/newreleases/sf=143441/limit=50/rss.xml";
$rss = simplexml_load_file($url);
$arr = array();
foreach ($rss->channel->item as $item) {
    $title = $item->title;
    $arr[] = $title;
}
$json = json_encode($arr);

$sql = "REPLACE INTO mp3.apicache (apiname,rawdata,added) VALUES('apple.newreleases','".mysql_real_escape_string($json)."',NOW())";
$wpdb->query($sql);

echo "DONE";

?>