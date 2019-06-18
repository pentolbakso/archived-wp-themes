<?php
include "../../../wp-load.php";
include "myfunctions.php";

$cap = $_POST['cap'];
$type = $_POST['type'];
$class = $_POST['class'];

global $wpdb;
$filter = '';
if ($class != '0')
	$filter.= " AND class_rating='".$class."'";
	
$filter.= " AND cap_gb='".$cap."'";
$filter.= " AND type='".$type."'";

$sql = "SELECT * FROM memorycard.mem_card WHERE 1=1 ".$filter;
$res = $wpdb->get_results($sql);

echo "<h3>Search Results</h3>";

if ($wpdb->num_rows==0)
{
	echo "<p>Sorry, we have no recommendation for this category</p>";
}
else
{

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

}
?>
