<?php
function add_rewrite_rules($wp_rewrite ) 
{
	$new_rules = array( 
		'camera/(.+?)/?$' => 'index.php?id='.$wp_rewrite->preg_index(1),
		'card/(.+?)/?$' => 'index.php?cid='.$wp_rewrite->preg_index(1),
		'cards/(.+?)-for-(.+?)/?$' => 'index.php?ctype='.$wp_rewrite->preg_index(1)."&id=".$wp_rewrite->preg_index(2)
		);

	$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'add_rewrite_rules');

$wp->add_query_var('id');
$wp->add_query_var('cid');
$wp->add_query_var('q');
$wp->add_query_var('ctype');

function parseQuery()
{
	if (get_query_var('id') != '' || get_query_var('cid') != '' || get_query_var('q') != '')
	{
		add_action('template_redirect','my_template');
	}
}
add_filter('parse_query','parseQuery');

function my_template()
{
	global $template;
	if (get_query_var('ctype') != '')
	{
		$template = get_query_template('cardtypetemplate');
		include ($template);
		exit();
	}
	else if (get_query_var('id') != '')
	{
		$template = get_query_template('cameratemplate');
		include ($template);
		exit();
	}
	else if (get_query_var('cid') != '')
	{
		$template = get_query_template('cardtemplate');
		include ($template);
		exit();
	}
	else if (get_query_var('q') != '')
	{
		$template = get_query_template('searchtemplate');
		include ($template);
		exit();
	}
}

function show_posts($atts)
{
	extract( shortcode_atts( array(
		'cat' => 'articles',
	), $atts ) );
	
	$count = 100;
	$cat_id = get_category_by_slug($cat)->term_id;
	
	$str = "";
	query_posts(array('category__in' => array($cat_id),'posts_per_page' => $count));
	if (have_posts())
	{
		$str .= "<ul>";
		while (have_posts())
		{
			the_post();
			$str .= "<li>";
			$str .= "<a href='".get_permalink()."'>".get_the_title()."</a>";
			$str .= "<p>".get_the_excerpt()."</p>";
			$str .= "</li>";
		}
		$str .= "</ul>";
	}
	wp_reset_query();
	
	$str .= "<div style='clear:both'></div>";
	return($str);
	
}
add_shortcode( 'show_posts', 'show_posts' );

?>