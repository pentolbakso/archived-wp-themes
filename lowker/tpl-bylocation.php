<?php
/*
Template Name: By Location
*/
include "myfunctions.php";

$bln= strtolower(get_query_var('bulan'));
$thn= strtolower(get_query_var('tahun'));
$loc= strtolower(get_query_var('location'));
$loc = unslug(trim($loc));

global $wpdb;
$sql = "SELECT provinsi FROM job.locations WHERE city='".$loc."' OR provinsi='".$loc."' LIMIT 1";
$row = $wpdb->get_row($sql);
$prov = longprov($row->provinsi);

$searcloc = $loc;
//if (strlen($prov)>0) $searcloc = $searcloc." \"".$prov."\"";

if ($bln=='tahun')
{
	$next = $thn+1;
	//$filter = "year(post_date)=".$thn;
	$filter = "(post_date BETWEEN '".$thn."-01-01' AND '".$next."-01-01') AND MATCH(city,prov) AGAINST ('".$searcloc."')";
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

	$filter = "(post_date BETWEEN '".$thn."-".$m."-01' AND '".($nextyear)."-".$nextmonth."-01') AND MATCH(city,prov) AGAINST ('".$searcloc."')";
}


$count = jobcount($filter);
if ($count > 0)
{	
	$list = joblist($filter,20,$snippet);
}

//--------------- header ----------
global $the_title,$the_canonical,$the_description;
$the_title = "Lowongan Kerja Terbaru di ".ucwords($loc)." (".ucfirst($bln)." ".$thn.")";
$the_description = "<meta name='description' content='Terbaru di ".ucwords($loc)." (".ucfirst($bln)." ".$thn.") : ".$snippet."'/>";
?>

<?php get_header(); ?>

<?php include "searchbox.php"; ?>

<h2><?=$the_title?></h2>

<?php
echo "<ul class='breadcrumb'>
	<li><a href='".get_bloginfo("url")."'>Home</a></li>
	<li>&raquo; <a href='".get_bloginfo("url")."/terbaru/'>Terbaru</a></li>
	<li>&raquo; <a href='".get_bloginfo("url")."/a/terbaru-tahun-".$thn."/'>Tahun ".$thn."</a></li>";

if ($bln != "tahun")
	echo "<li>&raquo; <a href='".get_bloginfo("url")."/a/terbaru-".lcfirst($bln)."-".$thn."/'>Bulan ".ucfirst($bln)."</a></li>";
if ($prov != "")
	echo "<li>&raquo; <a href='".get_bloginfo("url")."/b/terbaru-".lcfirst($bln)."-".$thn."-di-".slug($prov)."/'>".ucwords($prov)."</a></li>";
else if ($loc != "")
	echo "<li>&raquo; ".ucwords($loc)."</li>";

echo "</ul>";
?>

<p>Berikut ini lowongan kerja terbaru di <?=ucwords($loc)?> untuk periode <?=$bln." ".$thn?>.
Klik link pada list dibawah untuk mengetahui info pekerjaan tersebut. 
Jika rekan-rekan tidak menemukan pekerjaan yang sesuai, silahkan gunakan box 'Pencarian' yang ada di bagian terbawah halaman ini</p><hr/>

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

<?php get_footer(); ?>