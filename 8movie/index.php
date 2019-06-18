<?php get_header(); ?>
<div id="page">

<?php
include_once("moviefunction.php");
global $wpdb;
$LIMIT = 20;
//$sql = "SELECT id,title,imdb_outline,imdb_rating,rt_runtime,poster_img FROM 8movie.movie WHERE flag=1 AND id > FLOOR(RAND()*(SELECT MAX(id) FROM 8movie.movie)) LIMIT ".$LIMIT;
$sql = "SELECT id,title,imdb_outline,imdb_rating,rt_runtime,poster_img FROM 8movie.movie WHERE flag=1 ORDER BY last_update DESC LIMIT ".$LIMIT;
$results = $wpdb->get_results($sql);
if ($results)
{
	echo "<ul class='moviefrontpage'>";
	foreach($results as $row)
	{
		$rating = (int)(100*$row->imdb_rating);
		if ($rating==0) $rating = rand(10,50);
		$runtime = (strlen($row->run_time)>0?$row->run_time:rand(70,120))."min";
		$n = $row->id%5;
		if ($n==0)
			$desc = "Watch ".noyear($row->title)." movie online for free. Rating: ".$rating."%. Runtime: ".$runtime.". Plot:".$row->imdb_outline;
		else if ($n==1)
			$desc = "Watch ".noyear($row->title)." movie free without downloading. Score: ".$rating."%. Movie time: ".$runtime.". ".$row->imdb_outline;
		else if ($n==2)
			$desc = "Watch ".noyear($row->title)." movie online now. IMDB Rating: ".$rating."%. Runtime: ".$runtime.". Synopsis:".$row->imdb_outline;
		else if ($n==3)
			$desc = "Watch ".noyear($row->title)." free online. Users rate: ".$rating."%. Elapsed time: ".$runtime.". Summary:".$row->imdb_outline;
		else
			$desc = "Watch ".noyear($row->title)." online movie now. Score: ".$rating."%. Runtime: ".$runtime.". Outline:".$row->imdb_outline;
		
		echo "<li>
			<div class='poster'><a href='".lp($row->id,$row->title)."'><img src='".poster($row->poster_img)."' title='Watch ".$row->title."'/></a></div>
			<div class='title'><a href='".lp($row->id,$row->title)."'>".$row->title."</a></div>
			<div class='desc'>".$desc."</div>
			<div style='clear:both'></div>
			</li>";
	}
	echo "</ul>";
}
?>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>