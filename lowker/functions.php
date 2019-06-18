<?php
if ( function_exists('register_sidebar') )
register_sidebar(array('name'=>'sidebar',
        'before_widget' => '<p>',
        'after_widget' => '</p>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));

function lowker_add_rewrite_rules($wp_rewrite ) 
{
	$new_rules = array( 
		'id/(.+?)/(.+?)/?$' => 'index.php?jobid='.$wp_rewrite->preg_index(1),
		'a/terbaru-(.+?)-(.+?)/?$' => 'index.php?bulan='.$wp_rewrite->preg_index(1).'&tahun='.$wp_rewrite->preg_index(2),
		'b/terbaru-(.+?)-(.+?)-di-(.+?)/?$' => 'index.php?bulan='.$wp_rewrite->preg_index(1).'&tahun='.$wp_rewrite->preg_index(2).'&location='.$wp_rewrite->preg_index(3),
		'perusahaan/(.+?)/?$' => 'index.php?persh='.$wp_rewrite->preg_index(1),
		'kirim-lamaran/(.+?)/?$' => 'index.php?goid='.$wp_rewrite->preg_index(1),
		'location/(.+?)/?$' => 'index.php?goid='.$wp_rewrite->preg_index(1),
		);
	$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'lowker_add_rewrite_rules');
#add_action('admin_init', 'flush_rewrite_rules');

$wp->add_query_var('jobid');
$wp->add_query_var('goid');
$wp->add_query_var('keyword');
$wp->add_query_var('location');
$wp->add_query_var('bulan');
$wp->add_query_var('tahun');
$wp->add_query_var('cari');
$wp->add_query_var('di');
$wp->add_query_var('persh');

$wp->add_query_var('nama');
$wp->add_query_var('masakerja');
$wp->add_query_var('komentar');
$wp->add_query_var('umur');
$wp->add_query_var('pendidikan');
$wp->add_query_var('kontak');
$wp->add_query_var('act');

function lowker_parseQuery()
{
	if (get_query_var('jobid') != '' || get_query_var('keyword') != '' 
	|| get_query_var('location') != '' || get_query_var('bulan') != ''
	|| get_query_var('tahun') != '' || get_query_var('cari') != ''
	|| get_query_var('di') != '' || get_query_var('persh') != ''
	|| get_query_var('goid') != '')	
	{
		add_action('template_redirect','lowker_template');
	}
}
add_filter('parse_query','lowker_parseQuery');

function lowker_template()
{
	//hack to prevent 404 return on valid page
	global $wp_query;
	status_header( 200 );
	$wp_query->is_404 = false;

	global $template;
	if (get_query_var('cari') != '' || get_query_var('di') != '')
	{
		$template = get_query_template('tpl-search');
		include ($template);
		exit();
	}	
	else if (get_query_var('jobid') != '')
	{
		$template = get_query_template('tpl-jobdetail');
		include ($template);
		exit();
	}	
	else if (get_query_var('location') != '')
	{
		$template = get_query_template('tpl-bylocation');
		include ($template);
		exit();
	}	
	else if (get_query_var('bulan') != '' || get_query_var('tahun') != '')
	{
		$template = get_query_template('tpl-bydate');
		include ($template);
		exit();
	}
	else if (get_query_var('persh') != '')
	{
		$template = get_query_template('tpl-persh');
		include ($template);
		exit();		
	}
	else if (get_query_var('goid') != '')
	{
		$template = get_query_template('tpl-go');
		include ($template);
		exit();		
	}
}

?>