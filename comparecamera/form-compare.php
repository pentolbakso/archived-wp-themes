<?php
include "../../../wp-load.php";

global $wpdb;
$res = $wpdb->get_results("SELECT DISTINCT name FROM compare.camera");
$data = "[";
foreach($res as $row)
	$data .= "\"".$row->name."\",";
$data = substr_replace($data,"",-1);
$data .= "]";
?>

<div class='comparebox-modal'>
	<form method='get' action='<?=get_bloginfo('url')?>/'>
		<input name='s1' type='text' value='<?=$s1?>' placeholder='Camera 1' class="typeahead" autocomplete="off"/>
		<span style='font-size: 22px; font-weight:bold;color:#666;font-style:italic;margin:0px 10px;'>Vs</span>
		<input name='s2' type='text' value='<?=$s2?>' placeholder='Camera 2' class="typeahead" autocomplete="off"/>
		<input type='hidden' name='act' value='search'/>
		<p><input type='submit' class='btn btn-warning btn-lg' value='Compare Camera !' style='padding:10px 30px'/></p>
	</form>
</div>	

<script>
$('.typeahead').typeahead({
	name: 'camera',
	local: <?=$data?>,
	limit: 8
});
</script>