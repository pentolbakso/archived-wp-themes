<?php
if ( function_exists('register_sidebar') )
register_sidebar(array('name'=>'sidebar',
        'before_widget' => '<li class=sidebar-box>',
        'after_widget' => '</li>',
        'before_title' => '<h4>',
        'after_title' => '</h4>',
    ));

function cam_add_rewrite_rules($wp_rewrite ) 
{
	$new_rules = array( 
		'i/(.+?)-price-history/?$' => 'index.php?cameraid='.$wp_rewrite->preg_index(1),
		'g/(.+?)-(.+?)/?$' => 'index.php?go='.$wp_rewrite->preg_index(1).'&cameraid='.$wp_rewrite->preg_index(2)
		);
	$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'cam_add_rewrite_rules');

$wp->add_query_var('cameraid');
$wp->add_query_var('go');

function cam_parseQuery()
{
	if (get_query_var('cameraid') != '' || get_query_var('go') != '')
	{
		add_action('template_redirect','cam_template');
	}
}
add_filter('parse_query','cam_parseQuery');

function cam_template()
{
	//hack to prevent 404 return on valid page
	global $wp_query;
	status_header( 200 );
	$wp_query->is_404 = false;

	global $template;
	if (get_query_var('go') != '')
	{
		$template = get_query_template('tpl-redirect');
		include ($template);
		exit();
	}
	else if (get_query_var('cameraid') != '')
	{
		$template = get_query_template('tpl-price-history');
		include ($template);
		exit();
	}	
}	
	
function ChangeSearchString( $search_rewrite ) 
{
	if( !is_array( $search_rewrite ) )
		return $search_rewrite;

	$new_array = array();

	foreach( $search_rewrite as $pattern => $_s_query_string )
		$new_array[ str_replace( 'search/', 'related/', $pattern ) ] = $_s_query_string;

	$search_rewrite = $new_array;

	unset( $new_array );

	return $search_rewrite;
}
add_filter('search_rewrite_rules', 'ChangeSearchString');
	
function add_rewrite_rules( $wp_rewrite )
{
    $new_rules = array('^search/(.+)\$' => 'index.php?s=' .$wp_rewrite->preg_index(1));
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'add_rewrite_rules');


function filter_search_query(){
	global $wp;
	if (!empty($wp->query_vars['s'])){
		$wp->set_query_var('s', str_replace('-',' ',$wp->query_vars['s']));
	}	
}
add_action('parse_request', 'filter_search_query');


function makeSearchURL($keyword)
{
	$keyword = str_ireplace(" ","-",$keyword);
	$url = get_bloginfo("url")."/related/".$keyword."/";
	return($url);
}

function filter_where( $where = '' ) {
	// posts in the last 30 days
	$where .= " AND post_date > '" . date('Y-m-d', strtotime('-12 months')) . "'";
	return $where;
}

function show_posts($atts)
{
	extract( shortcode_atts( array(
		'cat' => 'articles',
		'view' => 'simple',
		'time' => 'true',
	), $atts ) );
	
	$count = 100;
	$parent = get_category_by_slug($cat);
	$categories = get_categories('parent='.$parent->term_id);
	foreach ($categories as $child)
	{
		$catlist[] = $child->term_id;
	}
	
	$catlist[] = $parent->term_id;
	
	
	// Create a new filtering function that will add our where clause to the query

	$str = "";
	if ($time=='true') add_filter( 'posts_where', 'filter_where' );
	query_posts(array('category__in' => $catlist,'posts_per_page' => $count));
	if ($time=='true') remove_filter( 'posts_where', 'filter_where' );
	if (have_posts())
	{
		$str .= "<ul style='margin:0px;padding:0px;list-style-type:none'>";
		while (have_posts())
		{
			the_post();
			$str .= "<li>";
			$str .= "<span class='date-meta'>".get_the_time('j-M-y')."</span><a href='".get_permalink()."'>".get_the_title()."</a>";
			if ($view != 'simple')
				$str .= "<p>".get_the_excerpt()."</p>";
			$str .= "</li>";
		}
		$str .= "</ul>";
	}
	else
	{
		$str .= "<p> --- </p>";
	}
	wp_reset_query();
	
	$str .= "<div style='clear:both'></div>";
	return($str);
	

}
add_shortcode( 'show_posts', 'show_posts' );
	
?>