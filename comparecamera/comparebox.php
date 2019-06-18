<div class='comparebox'>

	<h2>Select two camera and Compare!</h2>
	<form method='get' action='<?=get_bloginfo('url')?>/'>
		<input name='s1' type='text' value='<?=$s1?>' placeholder='Camera 1' class="typeahead" autocomplete="off"/>
		<span style='font-size: 22px; font-weight:bold;color:#666;font-style:italic;margin:0px 20px;'>Vs</span>
		<input name='s2' type='text' value='<?=$s2?>' placeholder='Camera 2' class="typeahead" autocomplete="off"/>
		<input type='hidden' name='act' value='search'/>
		<p><input type='submit' class='btn btn-warning btn-lg' value='Compare !'  style='padding:10px 30px'/></p>
	</form>
	
</div>

<?php
global $wpdb;
$res = $wpdb->get_results("SELECT DISTINCT name FROM compare.camera");
$data = "[";
foreach($res as $row)
	$data .= "\"".$row->name."\",";
$data = substr_replace($data,"",-1);
$data .= "]";
?>

<script>
$('.typeahead').typeahead({
	name: 'camera',
	local: <?=$data?>,
	limit: 8
});
</script>