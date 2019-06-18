<?php
/*
Template Name: Terbaru ByDate
*/
include_once "myfunctions.php";
global $wpdb;

$bln= strtolower(get_query_var('bulan'));
$thn= strtolower(get_query_var('tahun'));

if ($bln=='tahun')
{
	$next = $thn+1;
	//$filter = "year(post_date)=".$thn;
	$filter = "post_date BETWEEN '".$thn."-01-01' AND '".$next."-01-01'";
}
else
{
	//$filter = "year(post_date)=".$thn." AND month(post_date)=".month2int($bln);
	$m = month2int($bln);
	if ($m<10) $m = "0".$m;

	$nextyear = $thn;
	$nextmonth = $m+1;
	if ($m==12) $nextyear = $thn+1;
	if ($nextmonth<10) $nextmonth = "0".$nextmonth;

	$filter = "post_date BETWEEN '".$thn."-".$m."-01' AND '".($nextyear)."-".$nextmonth."-01'";
}

$count = jobcount($filter);
if ($count > 0)
{	
	$list = joblist($filter,20,$snippet);
}

//--------------- header ----------
global $the_title,$the_canonical,$the_description;
if ($bln=='tahun')
	$the_title = "Lowongan Kerja Terbaru ".$thn;
else
	$the_title = "Lowongan Kerja Terbaru ".ucfirst($bln)." ".$thn;

$the_description = "<meta name='description' content='Terbaru di ".ucfirst($bln)." ".$thn." : ".$snippet."'/>";


?>

<?php get_header(); ?>

<?php include "searchbox.php" ?>

<h2><?=$the_title?></h2>

<?php
echo "<ul class='breadcrumb'>
	<li><a href='".get_bloginfo("url")."'>Home</a></li>
	<li>&raquo; <a href='".get_bloginfo("url")."/terbaru/'>Terbaru</a></li>
	<li>&raquo; <a href='".get_bloginfo("url")."/a/terbaru-tahun-".$thn."/'>Tahun ".$thn."</a></li>";

if ($bln != "tahun")
	echo " <li>&raquo; Bulan ".ucfirst($bln)."</li>";

echo "</ul>";
?>

<p>Berikut ini lowongan kerja terbaru di <?=ucwords($loc)?> untuk periode <?=$thn?>.
Klik link pada list dibawah untuk mengetahui info pekerjaan tersebut. 
Jika rekan-rekan tidak menemukan pekerjaan yang sesuai, silahkan gunakan box 'Pencarian' yang ada di bagian terbawah halaman ini</p>

<?php
if ($count > 0)
{	
	echo "<p>Kami menemukan sekitar <strong>".$count."</strong> lowongan kerja. Berikut ini 20 lowongan yang terakhir:</p>";
	echo $list;
}
else
{
	echo "<p>Ooops, kami tidak menemukan lowongan untuk daerah ini</p>";
}
?>

<?php

if ($bln=='tahun')
{
	$sql = "SELECT month(post_date) AS m,count(*) AS c FROM job.jobs WHERE year(post_date)=".$thn." 
		GROUP BY m ORDER BY m ASC";

	$results = $wpdb->get_results($sql);
	if ($results)
	{
		foreach($results as $row)
			$tot = $tot + $row->c;

		echo "<h4>Berdasar Bulan:</h4>";
		echo "<ul class='unstyled'>";

		foreach($results as $row)
		{		
			$blnstr = int2month($row->m);
			
			//$persen = (int) (($row->c / $tot) * 100);
			
			echo "<li><a href='".get_bloginfo("url")."/a/terbaru-".lcfirst($blnstr)."-".$thn."/'>".$blnstr."</a> (".$row->c.")</li>";

		}
		echo "</ul><div style='clear:both'></div>";
	}
}

$cur = "";
$sql = "SELECT provinsi,city FROM job.locations ORDER BY provinsi ASC";
$results = $wpdb->get_results($sql);
if ($results)
{
	$prefixurl = get_bloginfo("url")."/b/terbaru-".$bln."-".$thn."-di-";

	echo "<h4>Berdasar Provinsi:</h4>";
	echo "<table class='table table-bordered'>
		<tr><td width='50%' valign='top'>
			<dl>";
	//echo "<ul class='provinsi'>";
	$n = 0;
	foreach($results as $row)
	{			
		if ($row->provinsi != $cur)
		{
			if ($cur != "") echo "</dd>";
			
			if ($n++ == 13)
				echo "</dl></td><td width='50%' valign='top'><dl>";
			
			echo "<dt style='margin-top:10px'><a href='".$prefixurl.slug($row->provinsi)."/'><strong>".$row->provinsi."</strong></a></dt><dd>";
			echo "<a href='".$prefixurl.slug($row->city)."/' class='city'>".$row->city."</a> &nbsp;";
			
			$cur = $row->provinsi;
			
		}
		else
		{
			echo "<a href='".$prefixurl.slug($row->city)."/' class='city'>".$row->city."</a> ";
		}
		
	}
	//echo "</p></li></ul><div style='clear:both'></div>";
	echo "</dl></td></tr></table>";

}

?>

<?php get_footer(); ?>