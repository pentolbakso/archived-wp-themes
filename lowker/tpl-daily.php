<?php
/*
Template Name: Daily Update
*/
include_once "myfunctions.php";

$permalink = "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
?>

<?php get_header(); ?>

<?php include "searchbox.php"; ?>

<div id="social">
		<div class='fb-like'><fb:like href="<?=$permalink?>" send="false" layout="button_count" width="450" show_faces="false"></fb:like></div>
		<div class='twitter'><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="low_indo_net">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
</div>

<h2>Lowongan Kerja Terbaru Hari Ini</h2>

<?php

global $wpdb;
$ret = "";
$sql = "SELECT city,prov,id,title,company,post_date FROM job.jobs WHERE DATE(insert_date) = DATE(NOW())";

$snippet = "";
$results = $wpdb->get_results($sql);
if ($results)
{
	$ret .= "<div class='well'><table class='table table-condensed'><tbody>";
	$i = 1;
	foreach($results as $row)
	{
		$info = "";
		$lok = (strlen($row->city)>0)?$row->city:longprov($row->prov);
		$lokasi = "<a href='".get_bloginfo('url')."/?cari=&di=".urlencode($lok)."'>".$lok."</a>";
		if (strlen($row->company)>0) 
		{
			$info = $lokasi." | <a href='".get_bloginfo('url')."/perusahaan/".companyslug($row->company)."/'>".$row->company."</a>";				
		}
		else if (strlen($row->city)>0) { $info = $lokasi; }
		else if (strlen($row->prov)>0) { $info = $lokasi; }

		$ret .= "<tr><td>".$i++."</td>
				<td><a href='".get_bloginfo('url')."/id/".$row->id."/".jobslug($row->title,$row->company,$loc,strtotime($row->post_date))."/'>".strip_tags($row->title)."</a></td>
				<td>".$info."</td> 
			</tr>";
	}
	$ret .= "</tbody></table></div>";
}

echo $ret;
?>

<h2>Lowongan Kerja Kemarin (Arsip)</h2>

<?php
$ret = "";
$sql = "SELECT city,prov,id,title,company,post_date FROM job.jobs WHERE DATE(insert_date) = DATE(NOW() - INTERVAL 1 DAY)";

$snippet = "";
$results = $wpdb->get_results($sql);
if ($results)
{
	$ret .= "<div class='well'><table class='table table-condensed'><tbody>";
	$i = 1;
	foreach($results as $row)
	{
		$info = "";
		$lok = (strlen($row->city)>0)?$row->city:longprov($row->prov);
		$lokasi = "<a href='".get_bloginfo('url')."/?cari=&di=".urlencode($lok)."'>".$lok."</a>";
		if (strlen($row->company)>0) 
		{
			$info = $lokasi." | <a href='".get_bloginfo('url')."/perusahaan/".companyslug($row->company)."/'>".$row->company."</a>";				
		}
		else if (strlen($row->city)>0) { $info = $lokasi; }
		else if (strlen($row->prov)>0) { $info = $lokasi; }

		$ret .= "<tr><td>".$i++."</td>
				<td><a href='".get_bloginfo('url')."/id/".$row->id."/".jobslug($row->title,$row->company,$loc,strtotime($row->post_date))."/'>".strip_tags($row->title)."</a></td>
				<td>".$info."</td> 
			</tr>";
	}
	$ret .= "</tbody></table></div>";
}

echo $ret;

?>


<?php get_footer(); ?>