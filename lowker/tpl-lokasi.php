<?php
/*
Template Name: Page Lokasi
*/
include "header.php";
?>

<?php include "searchbox.php"; ?>

<h2>Lowongan Pekerjaan Berdasar Lokasi</h2>

<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<!-- AddThis Button END -->
<p>&nbsp;</p>

<?php
$cur = "";
$sql = "SELECT provinsi,city FROM job.locations ORDER BY provinsi ASC";
$results = $wpdb->get_results($sql);
if ($results)
{
	$prefixurl = get_bloginfo("url")."/?cari=&di=";

	echo "<table class='table table-bordered'>
		<tr><td width='50%' valign='top'>
			<dl>";
	//echo "<ul class='provinsi'>";
	$n = 0;
	foreach($results as $row)
	{			
		if ($row->provinsi != $cur)
		{
			if ($cur != "") echo "</dd>";
			
			if ($n++ == 13)
				echo "</dl></td><td width='50%' valign='top'><dl>";
			
			echo "<dt style='margin-top:10px'><a href='".$prefixurl.urlencode(strtolower($row->provinsi))."'><strong>".$row->provinsi."</strong></a></dt><dd>";
			echo "<a href='".$prefixurl.urlencode(strtolower($row->city))."' class='city'>".$row->city."</a> &nbsp;";
			
			$cur = $row->provinsi;
			
		}
		else
		{
			echo "<a href='".$prefixurl.urlencode(strtolower($row->city))."/' class='city'>".$row->city."</a> ";
		}
		
	}
	//echo "</p></li></ul><div style='clear:both'></div>";
	echo "</dl></td></tr></table>";
}

?>

<?php include "footer.php"; ?>