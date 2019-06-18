<?php
/*
Template Name: WMSIWT
*/

include('moviefunction.php');

$theurl = "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$mood= get_query_var('mood');
$era= get_query_var('era');
global $wpdb;

?>

<?php get_header(); ?>
<div id="page" style='line-height: 18px'>
<h2>What Movie Should I Watch Tonight?</h2>

<p>8Movie.net will work hard to find a recommended movie for you ! First, tell us how you fell today ??</p>
<form method='get'>
	<input name='mood' type='radio' value='lonely' <?=($mood=='lonely')?'checked':''?>>I'm lonely and desperate.</input><br/>
	<input name='mood' type='radio' value='stress' <?=($mood=='stress')?'checked':''?>>I'm so stressed out at the office / school</input><br/>
	<input name='mood' type='radio' value='sing' <?=($mood=='sing')?'checked':''?>>I'm so happy i want to sing !!</input><br/>	
	<input name='mood' type='radio' value='mad' <?=($mood=='mad')?'checked':''?>>I'm mad !! I want to kill somebody</input><br/>	
	<input name='mood' type='radio' value='fml' <?=($mood=='fml')?'checked':''?>>FML !! I want to kill my self</input><br/>	
	<input name='mood' type='radio' value='bored' <?=($mood=='bored')?'checked':''?>>Stoned and bored :-|</input><br/>
	<input name='mood' type='radio' value='good' <?=($mood=='good')?'checked':''?>>I'm feeling good</input><br/>
	<input name='mood' type='radio' value='dontknow' <?=($mood=='dontknow')?'checked':''?>>I don't know</input><br/>
	<input name='mood' type='radio' value='none' <?=($mood=='none')?'checked':''?>>I don't have feeling. I'm a robot ..beep</input><br/>
	
<p>What kind of movies??</p>
	<input name='era' type='radio' value='classic' <?=($era=='classic')?'checked':''?>>I love classic movies</input><br/>
	<input name='era' type='radio' value='80s' <?=($era=='80s')?'checked':''?>>80's movies only</input><br/>
	<input name='era' type='radio' value='90s' <?=($era=='90s')?'checked':''?>>90's movies only</input><br/>	
	<input name='era' type='radio' value='20s' <?=($era=='20s')?'checked':''?>>New movies only please</input><br/>		

<p><input type='submit' value='Recommend me a movie !' style='padding: 3px 20px;font-size: 18px'></p>

</form>

<?php

if (strlen($mood)>0)
{
	if ($mood=='lonely') $filter = "imdb_genre LIKE '%romance%'";
	else if ($mood=='stress') $filter = "imdb_genre LIKE '%comedy%'";
	else if ($mood=='sing') $filter = "imdb_genre LIKE '%music%'";
	else if ($mood=='mad') $filter = "imdb_genre LIKE '%war%'";
	else if ($mood=='fml') $filter = "MATCH(title) AGAINST ('beautiful happy')";
	else if ($mood=='bored') $filter = "imdb_genre LIKE '%adventure%'";
	else if ($mood=='good') $filter = "imdb_genre LIKE '%action%'";
	else if ($mood=='dontknow') $filter = "1=1";
	else $filter = "1=1";

	if ($era=='classic') $filter .= " AND year < 1970";
	else if ($era=='80s') $filter .= " AND year >= 1980 AND year <= 1989";
	else if ($era=='90s') $filter .= " AND year >= 1990 AND year <= 1999";
	else if ($era=='20s') $filter .= " AND year >= 2000";

	$sql = "SELECT id,title,imdb_storyline,imdb_rating,poster_img,trailer_url
		FROM 8movie.movie WHERE flag=1 AND ".$filter." ORDER BY RAND() DESC LIMIT 1";
	$row = $wpdb->get_row($sql);
	if ($row)
	{

?>

<hr/>
<h2>Here's a movie for you..Like it?</h2>

<div class='social-button-container'>
	<iframe src="http://www.facebook.com/plugins/like.php?href=<?=urlencode($theurl)?>&amp;layout=button_count&amp;show_faces=true&amp;width=90&amp;action=like&amp;font=segoe+ui&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true"></iframe>&nbsp;&nbsp;
	<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
</div>	

<div class='movietrailer'>

<center>
	<h3><?=$row->title?></h3>
	<?=trailer($row->trailer_url)?>
	<br/><br/>Watch in: Standard Definition (SD) | <a href='<?=get_bloginfo('url')."/watch/".$row->id."/"?>' rel='noindex' target='_blank'>High Definition (HD)</a>
	<br/>
</center>
</div>

<div class='movieinfo'>
<img src='<?=poster($row->poster_img)?>'>
<p><?=parse(random_welcome($row->id,$row->title),$row)?></p>
<p>Storyline: <?=$row->imdb_storyline?></p>
<div style='clear:both'></div>
<a href='<?=get_bloginfo('url')."/watch/".$row->id."/"?>' rel='noindex' target='_blank'><img src='<?=get_bloginfo('template_directory')?>/img/watchnow.gif' style='width: 300px'></a>
</div>

<?php
	}
	else {
		echo "<p>We have problem..".$wpdb->last_error."</p>";
	}

}
?>
</div> <!-- end page -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>