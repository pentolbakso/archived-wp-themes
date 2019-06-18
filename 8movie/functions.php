<?php
if ( function_exists('register_sidebar') )
register_sidebar(array('name'=>'sidebar',
        'before_widget' => '<p>',
        'after_widget' => '</p>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));

function movie_add_rewrite_rules($wp_rewrite ) 
{
	$new_rules = array( 
		'v/(.+?)-(.+?)/?$' => 'index.php?movieid='.$wp_rewrite->preg_index(1).'&title='.$wp_rewrite->preg_index(2),
		'genre/(.+?)/?$' => 'index.php?genre='.$wp_rewrite->preg_index(1),
		'year/(.+?)/?$' => 'index.php?year='.$wp_rewrite->preg_index(1),
		's/(.+?)/?$' => 'index.php?q='.$wp_rewrite->preg_index(1),
		'watch/(.+?)/?$' => 'index.php?watchid='.$wp_rewrite->preg_index(1),
		'go-graboid/(.+?)/?$' => 'index.php?graboidid='.$wp_rewrite->preg_index(1)
		);
	$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'movie_add_rewrite_rules');
#add_action('admin_init', 'flush_rewrite_rules');graboidid

$wp->add_query_var('movieid');
$wp->add_query_var('title');
$wp->add_query_var('genre');
$wp->add_query_var('year');
$wp->add_query_var('q');
$wp->add_query_var('watchid');
$wp->add_query_var('graboidid');
$wp->add_query_var('mood');
$wp->add_query_var('era');

function movie_parseQuery()
{
	if (get_query_var('movieid') != '' || get_query_var('title') != '' 
	|| get_query_var('genre') != '' || get_query_var('year') != ''
	|| get_query_var('q') != '' || get_query_var('watchid') != '' || get_query_var('graboidid') != '')
	{
		add_action('template_redirect','movie_template');
	}
}
add_filter('parse_query','movie_parseQuery');

function movie_template()
{
	//hack to prevent 404 return on valid page
	global $wp_query;
	status_header( 200 );
	$wp_query->is_404 = false;

	global $template;
	if (get_query_var('movieid') != '' || get_query_var('title') != '')
	{
		$template = get_query_template('tpl-viewmovie');
		include ($template);
		exit();
	}
	else if (get_query_var('genre') != '' || get_query_var('year') != '')
	{
		$template = get_query_template('tpl-browsemovie');
		include ($template);
		exit();
	}
	else if (get_query_var('q') != '')
	{
		$template = get_query_template('tpl-searchmovie');
		include ($template);
		exit();
	}
	else if (get_query_var('watchid') != '')
	{
		$template = get_query_template('tpl-watchmovie');
		include ($template);
		exit();
	}
	else if (get_query_var('graboidid') != '')
	{
		$template = get_query_template('tpl-graboid');
		include ($template);
		exit();
	}

}

?>