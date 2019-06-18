<?php
$q= strtolower(get_query_var('q'));
$q = str_replace("-"," ",$q);
?>

<div class='well well-search'>

	<form method='get' class='form-search' action='<?=get_bloginfo('url')?>/'>
		<i class="icon-search icon-white"></i>
		<input name='q' class='input-xlarge' type='text' value='<?=$q?>' placeholder='Song name or artist..' style='margin-top:10px;padding:10px;font-size:18px'/>
		<input name='fromsearch' type='hidden' value='1'/>
		<input type='submit' class='btn btn-success' value='Search !'  style='background-color: #6B7459;padding:10px 30px;margin-top:10px;margin-left: 15px;font-weight:bold;font-style:italic;font-size: 18px'/>
	</form>

</div>

