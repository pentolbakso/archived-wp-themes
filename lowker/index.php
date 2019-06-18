<?php get_header(); ?>

<?php
include "searchbox.php";
include_once "myfunctions.php";

global $wpdb;
$result = $wpdb->get_results("SELECT * FROM job.rotatekw ORDER BY RAND() LIMIT 1");
$kw = $result[0]->keyword;
$headline = $result[0]->headline;

if (strlen($kw)>3)
{
	$arr = explode(" ", $kw);
	$boolean_search = "";
	foreach($arr as $r)
		$boolean_search .= "+".$r." ";
	$boolean_search = trim($boolean_search);
	$filter = "MATCH(title) AGAINST ('".$boolean_search."' IN BOOLEAN MODE)";

	$count = jobcount($filter);
	if ($count==0)
	{
		//retry pencarian yg santai
		$filter = "";
		if (strlen($q)>0)
			$filter = "MATCH(title) AGAINST ('".$kw."' IN BOOLEAN MODE)";
	}
}
else
{
	$filter = "title LIKE '%".$kw."%'";
}
$list = joblist($filter,20);

echo "<h3 style='margin-bottom:20px;color: #666;text-align:center'>".$headline." Berikut 20 lowongan kerja terbaru: </h3>";
echo $list;
?>
<p style='text-align:center'><a class="btn btn-primary" href='<?=get_bloginfo('url')?>/?cari=<?=urlencode($kw)?>&di='><i class="icon-envelope icon-tasks"></i>  Lihat Semua Lowongan <?=ucwords($kw)?></a></p>

<?php get_footer(); ?>