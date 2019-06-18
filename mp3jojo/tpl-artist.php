<?php
$letter = get_query_var("letter1");
$page = get_query_var("halaman");

global $the_title,$the_description;
$the_title = "[MP3] Browse Artists - ".strtoupper($letter)." - Page $page";

$DATAPERPAGE = 1200;
global $wpdb;
$limit = "LIMIT ".($page*$DATAPERPAGE).",$DATAPERPAGE";

if ($letter!='other')
	$sql = "SELECT name FROM mp3.artists WHERE SUBSTR(name,1,1)='$letter' ORDER BY name ASC $limit";
else
{
	$tmp = array();
	foreach(range('A','Z') as $i) $tmp[] = "'".$i."'";
	foreach(range('0','9') as $i) $tmp[] = "'".$i."'";

	$filter = implode(",", $tmp);

	$sql = "SELECT name FROM mp3.artists WHERE SUBSTR(name,1,1) NOT IN ($filter) ORDER BY name ASC $limit";

}
$res = $wpdb->get_results($sql,ARRAY_A)
?>

<?php get_header(); ?>

<div class='span12'>
<?php include "searchbox.php"; ?>

<div id="social" style='max-width:300px;margin:0px auto'>
	<!-- AddThis Button BEGIN -->
	<div class="addthis_toolbox addthis_default_style">
	<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
	<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
	<a class="addthis_counter addthis_pill_style"></a>
	</div>
	<!-- AddThis Button END -->
</div>

<h2>Browse Artists '<?=strtoupper($letter)?>' - Page <?=$page?></h2>

</div>

<div class='span4'>
	<ul class='unstyled'>
	<?php
	for($i=0; $i<400; $i++)
	{	
		$kw = urldecode($res[$i]['name']);
		if ($kw!="") echo "<li><a href='".searchurl($kw)."'>".$kw."</a></li>";
	}
	?>	
	</ul>
</div>

<div class='span4'>
	<ul class='unstyled'>
	<?php
	for($i=400; $i<800; $i++)
	{	
		$kw = urldecode($res[$i]['name']);
		if ($kw!="") echo "<li><a href='".searchurl($kw)."'>".$kw."</a></li>";
	}
	?>	
	</ul>
</div>

<div class='span4'>
	<ul class='unstyled'>
	<?php
	for($i=800; $i<1200; $i++)
	{	
		$kw = urldecode($res[$i]['name']);
		if ($kw!="") echo "<li><a href='".searchurl($kw)."'>".$kw."</a></li>";
	}
	?>	
	</ul>
</div>

<div class='span12' style='margin-top: 30px; border-top: 1px solid #ddd;padding-top: 15px'>

	<strong>Jump to page: </strong>
<?php
$row = $wpdb->get_row("SELECT COUNT(*) AS c FROM mp3.artists WHERE SUBSTR(name,1,1)='$letter'");
$count = $row->c;

$totalpages = ($count / $DATAPERPAGE);

for ($i=1; $i<=$totalpages; $i++)
{
	echo "<a href='".get_bloginfo('url')."/artists/$letter/page/$i/' class='".(($i==$page)?'btn btn-primary':'')."'>$i</a> ,";
}
?>
	
</div>

<?php get_footer(); ?>