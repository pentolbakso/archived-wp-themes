<?php
/* 
Template Name: Price History
*/
include "myfunctions.php";
?>

<?php
$camera_id= get_query_var('cameraid');
global $wpdb;
$sql = "SELECT camera_id,price,camera_name,brand,overview,DATE_FORMAT(release_date,'%M %D, %Y') AS released,DATEDIFF(NOW(),release_date) AS age,DATEDIFF(NOW(),last_update) AS last_price FROM camerafollow.items_price p 
	LEFT JOIN camerafollow.camera c ON p.item_shortname=c.camera_id
	WHERE p.item_shortname='".$camera_id."' ORDER BY last_update DESC LIMIT 1;";
$cam = $wpdb->get_row($sql);
if ($wpdb->num_rows==0)
{
	echo "<h1>Oooops..camera ID not found</h1>";
	return;
}

$file = get_price($camera_id,$cam->camera_name." Price");
?>

<?php get_header(); ?>

<div id="pricepage">

		<h1><?=$cam->camera_name?> Price</h1>
		<p>Compare <?=$cam->camera_name?> prices. Find the best deals available in US. Why pay more if you don't have to.</p>
<?php
$sql = "SELECT DATE_FORMAT(last_update,'%M %Y') AS updated,price  
	FROM camerafollow.items_price WHERE item_shortname='".$camera_id."' AND merchant='amazon_us' 
	GROUP BY updated ORDER BY last_update DESC;";
$result = $wpdb->get_results($sql);

$img = get_amazon_image($camera_id,$cam_desc);
if ($img=="")
	$img = get_bloginfo("url")."/cameraimg/no-image.jpg";
if ($cam_desc=="")
	$desc = "description not available yet";
else
{
	$desc = "";
	foreach($cam_desc as $d)
		$desc .= $d."\n";
}

/*
$img = "cameraimg/".$camera_id.".jpg";
if (file_exists($img)==false)
	$img = "cameraimg/no-image.jpg";
*/
$lastprice = "Today";
if ($cam->last_price>0) $lastprice = $cam->last_price." days ago";	
?>

<div style="float:left;width:450px;">
	<div style='background-color:#fff;text-align:center'><img style="height:350px" src="<?=$img?>"/></div>

	<p>Brand: <?=$cam->brand?>
	<br/>Release Date: <?=$cam->released?> (<?=$cam->age?> days ago)
	<br/>Price Last Checked: <span style='color:#5FBF00;font-weight:bold;'>US$ <?=$cam->price?></span> (<?=$lastprice?>)
	<br/><a href='<?=get_bloginfo("url")?>/g/review-<?=$camera_id?>/' rel='nofollow' target='_blank'>Read Users Reviews</a>
	</p>
	
	<div class='calltoaction'>
		<a href='<?=get_bloginfo("url")?>/g/compare-<?=$camera_id?>/' rel='nofollow' target='_blank'>Compare Prices</a>
	</div>
	
	<h3>Overview</h3>
	<p style='font-size:14px;line-height:22px;'><?=nl2br($desc)?></p>
</div>

<div style="float:left;width:350px;margin-left:50px">
	
	<div>
	<object type="application/x-shockwave-flash" data="<?=get_bloginfo("url")?>/open-flash-chart.swf" width="400" height="350">
    <param name="movie" value="<?=get_bloginfo("url")?>/open-flash-chart.swf" />
    <param name="salign" value="lt" />
    <param name="flashvars" value="data-file=<?=get_bloginfo("url")?>/cache/<?=$camera_id?>.txt" />
	</object>
	</div>
	
		<p>
			<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode("http://www.camerarumors.net/i/".$camera_id."-price-history/"); ?>&amp;layout=button_count&amp;show_faces=true&amp;width=90&amp;action=like&amp;font=segoe+ui&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true"></iframe>&nbsp;&nbsp;
			<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="camerarumors">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
			<g:plusone size="medium"></g:plusone>
		</p>		
	
	<h3>Price History</h3>
	<table id='pricehistory'>
<?php	
	$n=0;
	foreach($result as $row)
	{	
		//echo "<li><a href='#'>".$row->camera_name."</a> (".$row->price.")</li>";
		if ($n++==0)
			echo "<tr><td>".$row->updated."</td><td>US$ ".$cam->price."</td></tr>";
		else
			echo "<tr><td>".$row->updated."</td><td>US$ ".$row->price."</td></tr>";
	}
?>
	</table>
	
	<div class='calltoaction' style='padding: 1px 2px'>
		<a href='<?=get_bloginfo("url")?>/g/compare-<?=$camera_id?>/' rel='nofollow' target='_blank'>Check Price Now</a>
	</div>

	<p style='color:#626262;font-size:12px'>Note: All prices and availability are subject to change. 
	All prices are accurate at the time the information was acquired. We can not guarantee the price at the time of purchase.</p>
</div>

</div>

<?php //get_sidebar(); ?> 
<?php get_footer(); ?>