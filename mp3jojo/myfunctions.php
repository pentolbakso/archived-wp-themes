<?php
include_once ("utf8.inc");

function get_youtube_id($url)
{
	//http://i.ytimg.com/vi/fd_nopTFuZA/1.jpg

	$arr = explode("/", $url);
	$id = $arr[4];
	return($id);
}

function searchurl($kw)
{
	if (strlen($kw) != strlen(utf8_decode($kw)))
	{
		$out = utf8ToUnicode($kw);
		for ($i=0;$i < count($out);$i++)
			$newkw .= dechex($out[$i])."_";

		$kw = $newkw;
		$url = get_bloginfo('url')."/media-uni/".sanitize_title($kw)."-mp3/";
    }
    else
    {
		$url = get_bloginfo('url')."/media/".sanitize_title($kw)."-mp3/";
	}

	
	return($url);
}

function format_output($youtubeid,$clip,$title,$length)
{
	if (strlen($title) != strlen(utf8_decode($title)))	{
		//change the title a bit
		$urltitle = "the song";
    }
    else
    	$urltitle = $title;

	$duration = ((int)($length/60))." min ".($length%60)." sec";
	$downloadurl = get_bloginfo('url')."/track/$youtubeid/".sanitize_title($urltitle)."-mp3/";

	$ret = "<tr>
		<td><img src='$clip'/><td>
		<td>
		<strong style='font-size:16px'>$title</strong>
		<p style='font-size:13px'>Play or download $title free.High quality available on mp3, mp4, 3gp and wmv format.</p>
		<p style='font-size:12px' class='mediainfo'>Play time: $duration
		<a class='btn btn-play' href='#playthemusic' data-toggle='modal' yid='$youtubeid'><i class='icon icon-white icon-play'></i> Play</a>
		<a class='btn btn-download' href='$downloadurl' target='_blank'><i class='icon icon-white icon-download'></i> Download</a>
		<a class='btn btn-share' href='http://www.facebook.com/sharer.php?u=$downloadurl&t=".urlencode($urltitle)."' target='_blank'><i class='icon icon-white icon-share'></i> Share</a>
		</p>
		</td></tr>";

	return($ret);
}

?>