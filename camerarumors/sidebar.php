<div id='sidebar'>
	<ul>
		<li class='sidebar-box'>
			<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
			<input type="submit" id="searchsubmit" value="Search &raquo;" />
			<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" size="26" />
		</li>
		<?php if ( !function_exists('dynamic_sidebar') 	|| !dynamic_sidebar('sidebar') ) : ?>
		<?php endif; ?> 
	</ul> 
</div>
