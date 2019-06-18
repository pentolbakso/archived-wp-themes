<?php
error_reporting(E_ALL ^ E_NOTICE);

function theme_add_rewrite_rules($wp_rewrite ) 
{
	$new_rules = array( 
		'media/(.+?)-mp3/?$' => 'index.php?q='.$wp_rewrite->preg_index(1),
		'media-uni/(.+?)-mp3/?$' => 'index.php?qunicode='.$wp_rewrite->preg_index(1),
		'artist/(.+?)-mp3/?$' => 'index.php?q='.$wp_rewrite->preg_index(1),
		'track/(.+?)/(.+?)/?$' => 'index.php?yid='.$wp_rewrite->preg_index(1),
		'dl/(.+?)/(.+?)/?$' => 'index.php?type='.$wp_rewrite->preg_index(1).'&did='.$wp_rewrite->preg_index(2),
		'artists/(.+?)/page/(.+?)/?$' => 'index.php?letter1='.$wp_rewrite->preg_index(1).'&halaman='.$wp_rewrite->preg_index(2),
		'albums/(.+?)/page/(.+?)/?$' => 'index.php?letter2='.$wp_rewrite->preg_index(1).'&halaman='.$wp_rewrite->preg_index(2),
		);
	$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'theme_add_rewrite_rules');

$wp->add_query_var('q');
$wp->add_query_var('qunicode');
$wp->add_query_var('yid');
$wp->add_query_var('type');
$wp->add_query_var('did');
$wp->add_query_var('letter1');
$wp->add_query_var('letter2');
$wp->add_query_var('halaman');

function theme_parseQuery()
{
	if (get_query_var('q') != '' || get_query_var('yid') != '' 
		|| get_query_var('did') != '' || get_query_var('qunicode') != ''
		|| get_query_var('letter1') != '' || get_query_var('letter2') != '')
	{
		add_action('template_redirect','theme_template');
	}
}
add_filter('parse_query','theme_parseQuery');

function theme_template()
{
	//hack to prevent 404 return on valid page
	global $wp_query;
	status_header( 200 );
	$wp_query->is_404 = false;

	global $template;

	if (get_query_var('q') != '' ||  get_query_var('qunicode') != '')
	{
		$template = get_query_template('tpl-search');
		include ($template);
		exit();
	}
	else if (get_query_var('yid') != '')
	{
		$template = get_query_template('tpl-select');
		include ($template);
		exit();
	}
	else if (get_query_var('did') != '')
	{
		$template = get_query_template('tpl-download');
		include ($template);
		exit();
	}
	else if (get_query_var('letter1') != '')
	{
		$template = get_query_template('tpl-artist');
		include ($template);
		exit();
	}
	else if (get_query_var('letter2') != '')
	{
		$template = get_query_template('tpl-album');
		include ($template);
		exit();
	}

}
?>