<?php

function compare_add_rewrite_rules($wp_rewrite ) 
{
	$new_rules = array( 
		'c/(.+?)-vs-(.+?)/?$' => 'index.php?cam1='.$wp_rewrite->preg_index(1).'&cam2='.$wp_rewrite->preg_index(2),
		'search/(.+?)-vs-(.+?)/?$' => 'index.php?s1='.$wp_rewrite->preg_index(1).'&s2='.$wp_rewrite->preg_index(2),
		'get/(.+?)/(.+?)/?$' => 'index.php?asin='.$wp_rewrite->preg_index(1).'&title='.$wp_rewrite->preg_index(2),
		);
	$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'compare_add_rewrite_rules');

$wp->add_query_var('s1');
$wp->add_query_var('s2');
$wp->add_query_var('cam1');
$wp->add_query_var('cam2');
$wp->add_query_var('asin');
$wp->add_query_var('title');

function compare_parseQuery()
{
	if (get_query_var('cam1') != '' || get_query_var('cam2') != '' || get_query_var('s1') != '' || get_query_var('s2') != ''|| get_query_var('asin') != '')
	{ 
		add_action('template_redirect','compare_template');
	}
}
add_filter('parse_query','compare_parseQuery');

function compare_template()
{
	//hack to prevent 404 return on valid page
	global $wp_query;
	status_header(200);
	$wp_query->is_404 = false;

	global $template;
	if (get_query_var('cam1') != '' && get_query_var('cam2') != '')
	{
		$template = get_query_template('tpl-compare');
		include ($template);
		exit();
	}	
	else if (get_query_var('s1') != '' && get_query_var('s2') != '')
	{
		$template = get_query_template('tpl-search');
		include ($template);
		exit();
	}	
	else if (get_query_var('asin') != '')
	{
		$template = get_query_template('tpl-go');
		include ($template);
		exit();
	}		
}

?>