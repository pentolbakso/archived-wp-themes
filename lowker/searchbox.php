<?php
$q= strtolower(get_query_var('cari'));
$di= strtolower(get_query_var('di'));
?>

<div class='hero-unit small-search' style='text-align:center'>

	<form method='get' class='form-search' action='<?=get_bloginfo('url')?>/'>
		<i class="icon-search icon-white"></i>
		<input name='cari' class='input-xlarge' type='text' value='<?=$q?>' placeholder='nama pekerjaan atau perusahaan' style='padding:10px;'/>
		<span style='font-size: 22px; font-weight:bold;color:#fff;font-style:italic;margin:0px 10px'>di</span>
		<input name='di' class='input-xlarge'  type='text' value='<?=$di?>' placeholder='kota atau propinsi'  style='padding:10px;'/>
		<input type='hidden' name='act' value='search'/>
		<input type='submit' class='btn btn-primary' value='Cari !'  style='padding:10px 30px;background-color:#DD6F00;color:#fff;margin-left: 15px;font-weight:bold;font-style:italic;font-size: 18px'/>
	</form>

</div>