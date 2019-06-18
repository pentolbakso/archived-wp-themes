<?php
/*
Template Name: Terbaru FrontPage
*/
include_once "myfunctions.php";

$filter = "1=1";
$list = joblist($filter,20,$snippet);

//--------------- header ----------
global $the_title,$the_canonical,$the_description;
$the_description = "<meta name='description' content='Lowongan terbaru hari ini : ".$snippet."'/>";

?>

<?php get_header(); ?>

<?php include "searchbox.php"; ?>

<h2>Lowongan Kerja Terbaru ( selalu fresh!! )</h2>

<?php
echo "<ul class='breadcrumb'>
	<li><a href='".get_bloginfo("url")."'>Home</a></li>
	<li>&raquo; Terbaru</li>";
echo "</ul>";
?>

<p>Lowongan ter-update yg diambil dari beberapa situs lowongan kerja terbesar, disusun dan dikategorikan agar mudah untuk dinavigasi. 
Jika tidak menemukan apa yg rekan cari, silahkan gunakan fasilitas pencarian di bagian terbawah halaman ini</p>

<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<!-- AddThis Button END -->

<h3>20 lowongan terbaru</h3>
<?=$list?>

<p><center>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-1117201847231228";
/* lowker-728x90 */
google_ad_slot = "5344381992";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</center></p>

<div class='well'>

<h4>Berdasar Propinsi</h4>
<p>
<?php

$thn = date("Y");
$sql = "SELECT DISTINCT provinsi FROM job.locations ORDER BY provinsi ASC";
$results = $wpdb->get_results($sql);
foreach($results as $row)
{
	$prefixurl = get_bloginfo("url")."/b/terbaru-tahun-".$thn."-di-";
	echo "<a href='".$prefixurl.slug($row->provinsi)."/' class='lokasi'>".$row->provinsi."</a> &nbsp;";
}
?>
</p>

<h4>Arsip Lowongan</h4>
<ul class='unstyled'>
	<li><a href='<?=get_bloginfo('url')?>/a/terbaru-tahun-2013/'>Tahun 2013</a></li>
	<li><a href='<?=get_bloginfo('url')?>/a/terbaru-tahun-2012/'>Tahun 2012</a></li>
	<li><a href='<?=get_bloginfo('url')?>/a/terbaru-tahun-2011/'>Tahun 2011</a></li>
	<li><a href='<?=get_bloginfo('url')?>/a/terbaru-tahun-2010/'>Tahun 2010</a></li>
</ul>

</div>

<?php get_footer(); ?>