<?php
/* 
Template Name: Price 
*/
global $wpdb;
?>

<?php get_header(); ?>

<div id="pricepage">

		<h2>Camera Prices</h2>
		<p>Click on camera name for more information eg: price history and trends.
		This is snapshot of current camera prices</p>
		<p>
			<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode("http://www.camerarumors.net/prices"); ?>&amp;layout=button_count&amp;show_faces=true&amp;width=90&amp;action=like&amp;font=segoe+ui&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true"></iframe>&nbsp;&nbsp;
			<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="camerarumors">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
			<g:plusone size="medium"></g:plusone>
		</p>		
		
<?php
$sql = "SELECT c.camera_id,c.camera_name,c.brand,p.price AS price,p.last_update FROM camerafollow.camera c
	LEFT JOIN (SELECT * FROM 
		(SELECT * FROM camerafollow.items_price WHERE merchant='amazon_us' ORDER BY last_update DESC) AS abc 
		GROUP BY item_shortname) AS p ON c.camera_id = p.item_shortname
	WHERE c.enabled=1
	ORDER BY c.brand ASC,c.release_date DESC";

$result = $wpdb->get_results($sql);
echo "<table id='pricetable' cellspacing='10'>";
$n=1;
$brand = "";

echo "<tr><td style='border:none'></td><td style='border:none'></td>";
foreach($result as $row)
{
	if ($brand != $row->brand)
	{
		echo "</td></tr>
			<tr><td><strong>".$row->brand."</strong></td><td>";
		$brand = $row->brand;
	}
		
	//echo "<li><a href='#'>".$row->camera_name."</a> (".$row->price.")</li>";
	
	$url = get_bloginfo("url")."/i/".$row->camera_id."-price-history/";
	echo "<div class='priceitem'><a href='".$url."'>".$row->camera_name."</a> <span class='theprice'>US$ ".$row->price."</span> </div> ";
	
	$n++;
	
}
echo "</td></tr></table>";
?>

	<p style='color:#626262;font-size:12px'>Note: All prices and availability are subject to change. 
	All prices are accurate at the time the information was acquired. We can not guarantee the price at the time of purchase.</p>		
	
</div>

<?php //get_sidebar(); ?> 
<?php get_footer(); ?>