<?php
/*
Template Name: Browse Artist & Album
*/
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

<p>
This page allows you to browse music by artists and albums. To view list of artists or albums, click on the letters below.
Discover new songs, albums, videos, and artists. Browse the artists and albums on this page to find the perfect songs for your preferences. 
When using our music search form, get started by choosing a filter then click the "Search" button.
</p>

</div>

<div class='span12'>
	<h3>Browse Artists</h3>
	<ul class='inline'>
	<?php
	foreach(range('A','Z') as $i)
	{
		echo "<li><a href='".get_bloginfo('url')."/artists/".strtolower($i)."/page/1/'>".$i."</a></li>";
	}
	{
	foreach(range('0','9') as $i)
		echo "<li><a href='".get_bloginfo('url')."/artists/".strtolower($i)."/page/1/'>".$i."</a></li>";
	}
	echo "<li><a href='".get_bloginfo('url')."/artists/other/page/1/'>#</a></li>"

	?>
	</ul>

</div>

<div class='span12'>
	<h3>Browse Albums</h3>
	<ul class='inline'>
	<?php
	foreach(range('A','Z') as $i)
	{
		echo "<li><a href='".get_bloginfo('url')."/albums/".strtolower($i)."/page/1/'>".$i."</a></li>";
	}
	foreach(range('0','9') as $i)
	{
		echo "<li><a href='".get_bloginfo('url')."/albums/".strtolower($i)."/page/1/'>".$i."</a></li>";
	}
	echo "<li><a href='".get_bloginfo('url')."/albums/other/page/1/'>#</a></li>"

	?>
	</ul>

</div>


<?php get_footer(); ?>
