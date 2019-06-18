<?php
global $wpdb;
$res = $wpdb->get_results("SELECT DISTINCT name FROM compare.camera");
$data = "[";
foreach($res as $row)
	$data .= "&quot;".$row->name."&quot;,";
$data = substr_replace($data,"",-1);
$data .= "]";
?>

<div class='searchbox'>

	<h2>Select two camera and Compare!</h2>
	<form method='get' class='form-search' action='<?=get_bloginfo('url')?>/'>
		<input name='s1' type='text' value='<?=$s1?>' placeholder='Camera 1' style='padding:10px;width:30%' 
			data-provide="typeahead" data-items="8" data-source="<?=$data?>" autocomplete="off"/>
		<span style='font-size: 22px; font-weight:bold;color:#666;font-style:italic;margin:0px 10px'>Vs</span>
		<input name='s2' type='text' value='<?=$s2?>' placeholder='Camera 2'  style='padding:10px;width:30%'
			data-provide="typeahead" data-items="8" data-source="<?=$data?>" autocomplete="off"/>
		<input type='hidden' name='act' value='search'/>
		<p><input type='submit' class='btn btn-warning' value='Compare !'  style='padding:10px 30px'/></p>
	</form>
	
</div>