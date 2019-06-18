<?php
include_once ("utf8.inc");

global $wpdb;

$q = get_query_var('q');
$qu = get_query_var('qunicode');
if ($q!="") {
	
	if (strlen($q) != strlen(utf8_decode($q)))
	{
		wp_redirect(get_bloginfo('url')."/");
		return;
		
		$kw = $q;
	}
	else
		$kw = str_replace("-"," ",$q);

	$sql = "SELECT keyword FROM mp3.blacklist WHERE enabled=1";
	$res = $wpdb->get_results($sql);
	foreach($res as $b)
	{
		if (stripos($kw, $b->keyword)!==false)
		{
			//ada blacklist cuk
			wp_redirect(get_bloginfo('url')."/not-found/");
			return;
		}
	}

}
else if ($qu!="")
{
	//tidak support unicode dulu deh
	wp_redirect(get_bloginfo('url')."/not-found/");
	return;

	//convert again lah
	$arr = explode("_", $qu);

	$tmp = array();
	foreach($arr as $a)
	{
		if ($a != "") {
			$tmp[] = hexdec($a);
		}
	}
	$kw =unicodeToUtf8($tmp);
}

global $the_title,$the_description;
$the_title = "[MP3] ".ucwords($kw)." Free Download";
$the_description = "Listen or download ".$kw." free mp3 at ".get_bloginfo('title').". Play other similar songs as well. Download gratis / gratuit!";
?>

<?php get_header(); ?>

<div class='span8'>

<?php include "searchbox.php"; ?>

<!--
<p style='text-align:center'>
<script type='text/javascript'>
var adParams = {a: '11920181', size: '468x60',serverdomain: 'ads.adk2.com'   };
</script>
<script type='text/javascript' src='http://cdn.adk2.com/adstract/scripts/smart/smart.js'></script>
</p>
-->

<h2>Songs results for: <?=$kw?></h2>

<?php
$sql = "SELECT result FROM mp3.searchkw WHERE keyword='".$kw."'";
$res = $wpdb->get_row($sql);
if ($wpdb->num_rows>0)
{
	echo "<!-- from cache -->";

	$csv = str_ireplace(",", "','", $res->result);
	$csv = "'".$csv."'";

	$sql = "SELECT * FROM mp3.ytentry WHERE youtubeid IN (".$csv.")";
	//echo $sql;

	$res = $wpdb->get_results($sql);

	echo "<table class='table table-condensed table-result'>";
	$i=0;
	foreach($res as $r)
	{

		$clip = "http://i.ytimg.com/vi/".$r->youtubeid."/1.jpg";
		$title = $r->title;
		$length = $r->length;
		$html .= format_output($r->youtubeid,$clip,$title,$length);

		if ($i==3)
		{
			$html .= "
<tr><td colspan='3'>
	
<p style='text-align:center'>
<!--Copy and paste the code below into the location on your website where the ad will appear.-->
<script type='text/javascript'>
var adParams = {a: '11920181', size: '300x250',serverdomain: 'ads.adk2.com'   };
</script>
<script type='text/javascript' src='http://cdn.adk2.com/adstract/scripts/smart/smart.js'></script>
</p>

	<div id='social' style='background-color:#941d58;color:#fff;padding:10px'>
	<p>Please SUPPORT US ! Share <u>MP3Jojo</u> to your friends and the world :)</p>
	<!-- AddThis Button BEGIN -->
	<div class='addthis_toolbox addthis_default_style'>
	<a class='addthis_button_facebook_like' fb:like:layout='button_count'></a>
	<a class='addthis_button_tweet'></a>
	<a class='addthis_button_google_plusone' g:plusone:size='medium'></a>
	<a class='addthis_counter addthis_pill_style'></a>
	</div>
</div></td></tr>";
		}

		$i++;

	}
	echo $html;
	echo "</table>";
}
else
{
	echo "<!-- from API -->";

	$url = "http://gdata.youtube.com/feeds/api/videos?category=Music&format=5&q=".urlencode($kw)."&alt=json&max-results=20";
	$str = file_get_contents($url);
	$arr = json_decode($str,TRUE);

	//print_r($arr);

	$sql = "";
	echo "<table class='table table-condensed table-result'>";
	for ($i=0; $i<count($arr['feed']['entry']); $i++)
	{
		$clip = $arr['feed']['entry'][$i]['media$group']['media$thumbnail'][1]['url'];
		$title = $arr['feed']['entry'][$i]['title']['$t'];
		$content = $arr['feed']['entry'][$i]['content']['$t'];
		$length = $arr['feed']['entry'][$i]['media$group']['yt$duration']['seconds'];
		//$view = $arr['feed']['entry'][$i]['yt$statistics']['viewCount'];
		$youtubeid = get_youtube_id($clip);

		$html .= format_output($youtubeid,$clip,$title,$length);

		if ($i==3)
		{
			$html .= "
<tr><td colspan='3'><div id='social' style='background-color:#941d58;color:#fff'>
	<p>Support our website, LIKE us if you like the results</p>
	<!-- AddThis Button BEGIN -->
	<div class='addthis_toolbox addthis_default_style'>
	<a class='addthis_button_facebook_like' fb:like:layout='button_count'></a>
	<a class='addthis_button_tweet'></a>
	<a class='addthis_button_google_plusone' g:plusone:size='medium'></a>
	<a class='addthis_counter addthis_pill_style'></a>
	</div>
</div></td></tr>";
		}

		$sql .= "('$youtubeid','".mysql_real_escape_string($title)."','".mysql_real_escape_string($content)."',$length,NOW())";
		$csv .= $youtubeid;
		if ($i < count($arr['feed']['entry'])-1) { 
			$sql.=",";
			$csv.=",";
		}

	}
	echo $html;
	echo "</table>";

	if( function_exists('mysql_set_charset') )	mysql_set_charset('utf8');
	else $wpdb->query("SET NAMES 'utf8'");

	$sql = "INSERT IGNORE INTO mp3.ytentry (youtubeid,title,content,length,added) VALUES ".$sql;
	$wpdb->query($sql);

	if ($_GET['fromsearch']=='1') $formsearch = 1;
	else $formsearch = 0;
	
	$sql = "INSERT IGNORE INTO mp3.searchkw (keyword,formsearch,added,result) VALUES ('".mysql_real_escape_string($kw)."',$formsearch,NOW(),'".mysql_real_escape_string($csv)."')";
	$wpdb->query($sql);

}
?>

<p style='font-size:12px;color:#666'>Mp3 available in different bit rates : 128,256,320kbps but depends on video quality. Make sure to pick the high quality video for conversions</p>


</div> <!-- end span8 -->

<div id='playthemusic' class='modal hide noborder'>
	<div id='playthemusiccontent'></div>

	<div id="social" style='max-width:300px;margin:0px auto;margin-bottom: 10px'>
		<!-- AddThis Button BEGIN -->
		<div class="addthis_toolbox addthis_default_style">
		<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
		<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
		<a class="addthis_counter addthis_pill_style"></a>
		</div>
		<!-- AddThis Button END -->
	</div>
</div>


<?php get_sidebar(); ?>
<?php get_footer(); ?>