<?php
global $wpdb;
$id= get_query_var('movieid');
if (strlen($id)>0)
{
	$row = $wpdb->get_row("SELECT year,imdb_genre FROM 8movie.movie WHERE id=".$id." AND flag=1");
	$genres = explode("|",$row->imdb_genre);
	$genre = (strlen($genres[0])>0)?$genres[0]:'';
	$genre = trim($genre);
	$year = $row->year;

}
else
{
	//$genre = "Last Viewed";	//display doang
}

$q= get_query_var('q');

?>

<div id='sidebar'>

<div class='sidebar-box'>
	<p><form method='get' action='<?=get_bloginfo('url')?>'>
	<input type='text' name='q' style='padding: 3px' value='<?=str_replace("-"," ",$q)?>'><input type='submit' value='search' style='padding: 3px'>
	<br/><small style='color:#7f7f7f;margin-left: 5px'>search movie title (eg: zombie)</small>
	</form></p>
</div>

<div class='sidebar-box'>
	<h2>Similar <?=$genre?> Movies</h2>
	<p>
<?php
	if (strlen($id)>0)
	{
		$LIMIT = 5;
		$filter = "imdb_genre LIKE '%".$genres[0]."%'";
	}
	else
	{
		$LIMIT = 10;
		$filter = "1=1";
	}
	$results = $wpdb->get_results("SELECT id,title,imdb_outline,imdb_rating,poster_img FROM 8movie.movie WHERE flag=1 AND ".$filter." AND id > FLOOR(RAND()*(SELECT MAX(id) FROM 8movie.movie)) LIMIT ".$LIMIT); 
	if ($results)
	{
		echo "<p><ul class='moviesmall'>";
		foreach($results as $row)
		{
			$content = "Watch <b>".$row->title."</b> online.".$row->imdb_outline;
			echo "<li>
				<div class='poster'><a href='".lp($row->id,$row->title)."'><img src='".poster($row->poster_img)."' title='".$row->title."'/></a></div>
				<p>".substr($content,0,140)."...</p>
				<div style='clear:both'></div>
				</li>";
		}
		if ($genre != "Last Viewed")
			echo "<li><a href='".get_bloginfo('url')."/genre/".strtolower($genre)."/'>more ".strtolower($genre)." movies...</a></li>";
		echo "</ul></p>";
	}
?>
	</p>
</div>

<?php if (strlen($id)>0) { ?>

<!--
<div class='sidebar-box'>
	<h2><?=$year?>'s Movies</h2>
	<p>
<?php
/*
	$results = $wpdb->get_results("SELECT id,title,imdb_outline,imdb_rating,poster_img FROM 8movie.movie WHERE flag=1 AND year=".$year." AND id > FLOOR(RAND()*(SELECT MAX(id) FROM 8movie.movie WHERE year=".$year.")) LIMIT 5"); 
	if ($results)
	{
		echo "<p><ul class='moviesmall'>";
		foreach($results as $row)
		{
			$content = "Watch <b>".$row->title."</b> online. ".$row->imdb_outline;
			echo "<li>
				<div class='poster'><a href='".lp($row->id,$row->title)."'><img src='".poster($row->poster_img)."' title='".$row->title."'/></a></div>
				<p>".substr($content,0,140)."...</p>
				<div style='clear:both'></div>
				</li>";
		}
		echo "<li><a href='".get_bloginfo('url')."/year/".$year."/'>more ".$year." movies...</a></li>";
		echo "</ul></p>";
	}
*/
?>
	</p>
</div>
-->

<?php } ?>

<div class='sidebar-box'>
	<h2>Movie Genres</h2>
	<ul class='genre'>
<?php
	$genres = array( 'Action','Comedy','Family','History','Mystery','Sci-Fi','War','Adventure',
		'Crime','Fantasy','Horror','Sport','Western','Animation','Documentary','Film-Noir',
		'Music','Talk-Show','Biography','Drama','Musical','Romance','Thriller' );
	
	foreach($genres as $genre)
		echo "<li><a href='".get_bloginfo('url')."/genre/".$genre."/'>".$genre."</a></li>";
?>
	</ul>
</div>


</div>

<div style='clear:both'></div>