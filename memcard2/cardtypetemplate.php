<?php
/* 
Template Name: CardTypeTemplate 
*/
?>

<?php
include_once "myfunctions.php";
global $wpdb;

$cameraid= get_query_var('id');
$cardtype = get_query_var('ctype');

$sql = "SELECT * FROM memorycard.mem_camera WHERE slug='$cameraid' LIMIT 1";
$row = $wpdb->get_row($sql);

$sql = "SELECT * FROM memorycard.mem_card WHERE type='$cardtype' ORDER BY cap_gb ASC,brand ASC";
$res = $wpdb->get_results($sql);

$sql = "SELECT * FROM memorycard.mem_cardtype WHERE type='$cardtype'";
$card = $wpdb->get_row($sql);

global $the_title,$the_noindex,$the_canonical,$the_description;
$the_title = strtoupper($cardtype)." Cards for ".$row->camera_name;

$permalink = "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
?>

<?php get_header(); ?>

<h2><?=strtoupper($cardtype)?> Cards for <?=$row->camera_name?></h2>

<div id="social">
	<div class='fb-like'><fb:like href="<?=$permalink?>" send="false" layout="box_count" width="450" show_faces="false"></fb:like></div>
	<div class='twitter'><a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical" data-via="">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
</div>

<p>Below are list of <?=strtoupper($cardtype)?> cards that supported by <?=$row->camera_name?>.
If you want to know what is the best card for <?=$row->camera_name?> and your needs, click on 'orange' button below</p>

<p><img src='<?=get_bloginfo('url')?>/cameraimg/<?=$card->image?>' style='float:left; padding: 0 10px 10px 0;width: 250px'/>
	<strong>About <?=strtoupper($cardtype)?></strong><br/><?=$card->description?><div style='clear:both'></div>
<br/>
To learn more about <?=$row->camera_name?>'s storage capacity , click <a href='' class='btn btn-warning'><?=$row->camera_name?> recommended cards</a>
</p>


<h3><?=strtoupper($cardtype)?> Memory Cards</h3>
<?php
echo "<table class='table table-striped table-condensed'>
	<tr><th>Card Name</th><th>Capacity</th><th>Manufacturer</th><th>Class Rating</th><th>Reviews</th></tr>";
foreach($res as $r)
{
	echo "<tr>
		<td><a href='".get_bloginfo('url')."/card/$r->slug/'>$r->name</a></td>
		<td>$r->cap_gb GB</td>
		<td>$r->brand</td>
		<td>$r->class_rating</td>
		<td><a href='".amazonize($r->amazon_url)."' target='_blank' rel='nofollow'>read reviews</a></td>
		</tr>";
}
echo "</table>";

$amazonsearchurl = "http://www.amazon.com/s/ref=nb_sb_noss?url=search-alias%3Daps&field-keywords=$cardtype+memory+card";
?>

<p>Can't find what you're looking for ? <a href='<?=amazonize($amazonsearchurl)?>'>search more <?=$cardtype?> memory cards</a></p>
	
<?php get_footer(); ?>
