<?php
include('moviefunction.php');

$q= get_query_var('q');
global $wpdb;

?>

<?php get_header(); ?>
<div id="page">
<h2>Results for "<em><?=str_replace("-"," ",$q)?></em>"</h2>

<?php
include_once("moviefunction.php");
global $wpdb;
$LIMIT = 12;
$sql = "SELECT id,title,imdb_outline,imdb_rating,poster_img,rt_runtime,rt_director,rt_cast,MATCH(title) AGAINST ('".$q."') as score
	FROM 8movie.movie WHERE flag=1 AND MATCH(title) AGAINST ('".$q."') ORDER BY score DESC LIMIT ".$LIMIT;
$results = $wpdb->get_results($sql);
if ($results)
{
	echo "<ul class='moviefrontpage'>";
	foreach($results as $row)
	{
		echo "<li>
			<div class='poster'><a href='".lp($row->id,$row->title)."'><img src='".poster($row->poster_img)."' title='Watch ".$row->title."'/></a></div>
			<div class='title'><a href='".lp($row->id,$row->title)."'>".$row->title."</a></div>
			<div class='desc'>".$row->imdb_outline."
			<br/><small>Director: ".$row->rt_director.". Cast: ".$row->rt_cast.". Duration: ".$row->rt_runtime."min</small></div>
			<div style='clear:both'></div>
			</li>";
		/*
		echo "<li>
			<div class='poster'><a href='".lp($row->id,$row->title)."'><img src='".poster($row->poster_img)."'/ style='height:120px;width:84px'></a></div>
			<div class='title'>".$row->title."</div>
			<div style='margin-left:90px'>".rating($row->imdb_rating,$row->rt_audience_score)."</div>
			<div class='desc'>".$row->imdb_outline."
			<br/>Director: ".$row->rt_director.". Cast: ".$row->rt_cast.". Duration: ".$row->rt_runtime."min.
			</div>
			<div style='clear:both'></div>
			</li>";
		*/
	}
	echo "</ul>";
}
?>
</div> <!-- end page -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>