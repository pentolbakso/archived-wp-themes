<?php
/* 
Template Name: FinderTemplate 
*/
?>

<?php get_header(); ?>

<?php
$permalink = "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
?>

<div class="content">

<h2>Memory card finder</h2>

<div id="social">
	<div class='fb-like'><fb:like href="<?=$permalink?>" send="false" layout="box_count" width="450" show_faces="false"></fb:like></div>
	<div class='twitter'><a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical" data-via="">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
</div>

<p>Search memory cards based on its storage capacity, technology and class speed rating</p>

<div class="memoryform">
	<form>
	<table border='0' class='table'>
	<tr><td>Choose capacity:</td>
		<td><select name='cardgb' id='cardgb' class="input-xlarge" >
			<option value='2'>2 GB</option>
			<option value='4'>4 GB</option>
			<option value='8' selected>8 GB</option>
			<option value='16'>16 GB</option>
			<option value='32'>32 GB</option>
			<option value='64'>64 GB</option>
			<option value='128'>128 GB</option>
			</select>		
			<span class='help-block'>Choose the right memory card based on your capacity need.
			Recommended capacity is 8GB
			</span>
		</td>
	</tr>

	<tr><td>Card type :</td>
		<td><select name='memo' id='cardtype' class="input-xlarge" >
			<option value='sd'>SD</option>
			<option value='sdhc' selected>SDHC</option>
			<option value='sdxc'>SDXC</option>
			<option value='cf'>Compact Flash</option>
			<option value='msduo'>Memory Duo</option>
			</select>
			<span class='help-block'>SD card: up to 4GB
				<br/>SDHC card: 4GB up to 32GB (faster)
				<br/>SDXC card: 32GB and beyond (fastest)
				<br/>CF card: Compact Flash
			</span>
		</td>
	</tr>
	<tr><td>Class Rating :</td>
		<td><select name='cardclass' id='cardclass' class="input-xlarge" >
			<option value='0' selected>Show All</option>
			<option value='2'>Class 2</option>
			<option value='4'>Class 4</option>
			<option value='6'>Class 6 (recommended)</option>
			<option value='10'>Class 10</option>
			</select>
			<span class='help-block'>Each class designation directly reflects the minimum sustained Data Transfer Rate (DTR) of the card
				<br/>Class 2: minimum of 2MB/sec
				<br/>Class 4: minimum of 4MB/sec
				<br/>Class 6: minimum of 6MB/sec
				<br/>Class 10: minimum of 10MB/sec
			</span></a>
		</td>
	</tr>
	<tr><td>&nbsp;</td>
		<td colspan='2'><a id='findcards' href='#' class='btn btn-primary'><i class='icon icon-search icon-white'></i> Find It !</a></td>
	</tr>
	</table>
	</form>
</div>

<div id='theList'>
</div>

</div>	<!-- content-->
	
<div style="clear:both"></div>

<script>
$("a#findcards").click(function() 
{   
	var gb = $('#cardgb').attr('value');
	var type = $('#cardtype').attr('value');
	var cclass =$('#cardclass').attr('value');

    $('#theList').show().html('<div style="padding: 5px"><img src="<?=get_bloginfo('template_directory')?>/img/ajax-loader.gif" /> Consulting the Oracle..please wait</div>');
 
    $.ajax({
        cache: false,
        type: "POST",
        url: "<?=get_bloginfo('template_directory')?>/findcards.php",
        data: "cap="+gb+"&type="+type+"&class="+cclass+"",
        success: function(data) 
        {
            $('#theList').html(data);
        }
    });
});
</script> 

<?php get_footer(); ?>

