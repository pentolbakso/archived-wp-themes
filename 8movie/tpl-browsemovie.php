<?php
/*
Template Name: Browse Movie
*/
include('moviefunction.php');

$year= get_query_var('year');
$genre= get_query_var('genre');
?>

<?php get_header(); ?>
<div id="page">

<?php
global $wpdb;

if ($year == "" && $genre == "")
{
	echo "<h3>Search by year: </h3>";
	echo "<p style='font-size: 14px;line-height:25px;'>";
	$year = 1950;
	while (1)
	{
		echo "<a href='".get_bloginfo('url')."/year/".$year."/'>".$year."</a> &nbsp;&nbsp; ";
	
		$year++;
		if ($year > 2014) break;
		
		if ($year%10==0) echo "<br/>";
	}
	echo "</p>";
	
	echo "<h3>Search by genre: </h3>";
	echo "<p style='font-size: 14px;line-height:25px;'>";

	$genres = array( 'Action','Comedy','Family','History','Mystery','Sci-Fi','War','Adventure',
		'Crime','Fantasy','Horror','Sport','Western','Animation','Documentary','Film-Noir',
		'Music','Talk-Show','Biography','Drama','Musical','Romance','Thriller' );
	
	foreach($genres as $genre)
		echo "<a href='".get_bloginfo('url')."/genre/".$genre."/'>".$genre."</a> &nbsp;&nbsp; ";
	
	echo "</p>";	
}
else if ($year != "")
{
	$results = $wpdb->get_results("SELECT substr(title,1,1) as f,id,title FROM 8movie.movie WHERE flag=1 AND year=".$year." ORDER BY f ASC");
	if ($results)
	{
		echo "<h3>".$year."'s Movies:</h3>";
		echo "<ul class='browsemovie'>";
		foreach($results as $row)
		{
			echo "<li><a href='".lp($row->id,$row->title)."'>".$row->title."</a></li>";
		}
		echo "</ul><div style='clear:both'></div>";
	}
}
else if ($genre != "")
{
	$results = $wpdb->get_results("SELECT substr(title,1,1) as f,id,title FROM 8movie.movie WHERE flag=1 AND imdb_genre LIKE '%".$genre."%' ORDER BY f ASC");
	if ($results)
	{
		echo "<h3>More ".ucfirst($genre)." Movies:</h3>";
		echo "<ul class='browsemovie'>";
		foreach($results as $row)
		{
			echo "<li><a href='".lp($row->id,$row->title)."'>".$row->title."</a></li>";
		}
		echo "</ul><div style='clear:both'></div>";
	}
}
?>

</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>