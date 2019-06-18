<?php
include "myfunctions.php";
global $wpdb;

$id = get_query_var("jobid");

$theurl = "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

$sql = "SELECT j.*,d.fulldescription AS fulldesc FROM job.jobs AS j LEFT JOIN job.descriptions d ON j.id=d.id WHERE j.id=".$id;
//echo $sql;
$row = $wpdb->get_row($sql);
if ($row==NULL)
{
	go_404();
	return;
}

$desc = $row->fulldesc;
if (strlen($desc)==0)
	$desc = get_jobdesc($row->id,$row->site,$row->url,$row->api);

$len = strlen($desc);
if ($len==0 || $len < 100) 
	$desc = strip_tags($row->description);

if (strlen($row->company)>0) $company = $row->company;
else $company = "Perusahaan";

if (strlen($row->city)>0) { $loc = $row->city; $company.= " di ".$row->city; }
else if (strlen($row->prov)>0) { $loc = longprov($row->prov); $company.= " di ".longprov($row->prov); }

//--------------- header ----------
global $the_title,$the_canonical,$the_description;
if (stripos($row->title, "lowongan")!==FALSE)
	$the_title = $company." membuka ".$row->title;
else if (stripos($row->title, "urgent")!==FALSE)
	$the_title = $company." membutuhkan ";
else
	$the_title = $company." membutuhkan ".$row->title;

$the_title .= " - ".indo_date(strtotime($row->post_date));
$permalink = get_bloginfo('url')."/id/".$row->id."/".jobslug($row->title,$row->company,$loc,strtotime($row->post_date));
$the_canonical = "<link rel='canonical' href='".$permalink."/' />";
$the_description = "<meta name='description' content='".indo_date(strtotime($row->post_date))." - ".$row->description."'/>";
?>

<?php get_header(); ?>

<?php include "searchbox.php"; ?>

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

<?php
$thn = date("Y",strtotime($row->post_date));
$bln = int2month(date("m",strtotime($row->post_date)));
echo "<ul class='breadcrumb'>
	<li><a href='".get_bloginfo("url")."'>Home</a></li>
	<li>&raquo; <a href='".get_bloginfo("url")."/terbaru/'>Terbaru</a></li>
	<li>&raquo; <a href='".get_bloginfo("url")."/a/terbaru-tahun-".$thn."/'>Tahun ".$thn."</a></li>";

if ($bln != "tahun")
	echo " <li>&raquo; <a href='".get_bloginfo("url")."/a/terbaru-".lcfirst($bln)."-".$thn."/'>Bulan ".ucfirst($bln)."</a></li>";

echo "</ul>";

$jobtitle = $row->title;
if (stripos($jobtitle, "lowongan")===FALSE && stripos($jobtitle, "urgent")===FALSE && stripos($jobtitle, "butuh")===FALSE)
	$jobtitle = "Lowongan ".$jobtitle;

?>

<h2><?=$jobtitle?></h2>

<p><?=$loc?> - <?=indo_date(strtotime($row->post_date))?>, <?=$company?> membutuhkan tenaga kerja untuk posisi <em><?=$row->title?></em>.
Untuk info lengkap silahkan baca requirement di bawah ini atau klik kirim lamaran.</p>


<div class='row'>
	<div class='span9'>

<div class='tabbable'>
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab1" data-toggle="tab">Lowongan</a></li>
		<li><a href="#tab2" data-toggle="tab">Berbagi Pengalaman</a></li>
		<li><a href="#tab3" data-toggle="tab">Mencari Kerja</a></li>
		<li><a href="#tab4" data-toggle="tab">Info Perusahaan</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab1">
			<div class='row'><div class='span5'>
			<p style='color:#444'>Dibawah ini adalah info lengkap mengenai lowongan pekerjaan <em><?=$row->title?></em>.
Sebelum melamar pekerjaan, baca baik-baik persyaratan, jadwal wawancara, lokasi kerja, dan cara menghubungi perusahaan (email / nomor telepon).
Untuk mengirimkan lamaran, klik tombol "Kirim Lamaran" yang ada di bawah.</p>
			</div>
			<div class='span4'>
				<?php if (strlen($desc)>255) { ?>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-1117201847231228";
/* lowker-300x250 */
google_ad_slot = "8297848395";
google_ad_width = 300;
google_ad_height = 250;
//-->
</script>
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
				<?php } ?>
			</div>
			</div>

			<hr/>

			<div class='jobrequirement'><?=($desc)?></div>
				
			<div class='row'><div class='span2'>
			<p><a class="btn btn-primary" href="#myModal" role="button" data-toggle="modal" style='margin-bottom:10px;font-style:italic;font-weight:bold;'><i class="icon-envelope icon-white"></i>  Kirim Lamaran</a></p>
			</div>
			<div class='span7'>
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style">
<span style='float:left'>Yuk Berbagi! -> </span>
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<!-- AddThis Button END -->
			</div>
			</div>

			<!--<div id="social">
					<div class='fb-like'><fb:like href="<?=$permalink?>" send="false" layout="button_count" width="450" show_faces="false"></fb:like></div>
					<div class='twitter'><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="low_indo_net">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
			</div>
			-->

			<?php if (strlen($desc)<=255) { ?>
			<p><center>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-1117201847231228";
/* lowker-468x60 */
google_ad_slot = "9774581590";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
			</center></p>

			<?php } ?>

			<!-- related job -->
			<h3>Lowongan yang berhubungan:</h3>
			<?php
			$loc = longprov($row->prov);
			if ($loc!="n/a")
			{
				$filter = " MATCH(city,prov) AGAINST ('".$loc."')";
			}
			else if (strlen($row->city)>0)
			{
				$filter = " MATCH(city,prov) AGAINST ('".$row->city."')";
			}

			$count = jobcount($filter);
			if ($count == 0) $filter = "1=1";

			$str = joblist($filter,5,$snippet);
			echo $str;
			?>

<p><center>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-1117201847231228";
/* lowker-468x15 */
google_ad_slot = "5204781191";
google_ad_width = 468;
google_ad_height = 15;
//-->
</script>
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</center></p>

		</div>
		<div class="tab-pane" id="tab2">
			<p>Pernah bekerja di posisi ini dan punya tips berguna untuk dibagi? Berbagilah dengan sahabat lain yang sedang membutuhkan info pekerjaan. 
			Gunakan form dibawah.</p>
			<hr/>
<?php
$komen = $wpdb->get_results("SELECT * FROM job.pengalaman WHERE jobid=".$id." AND approved=1 ORDER BY postdate DESC");
if ($wpdb->num_rows==0)
{
	echo "<div style='text-align:center'><em>Yaaah...belum ada yang berbagi pengalaman kerjanya</em></div>";
}
else
{
	echo "<h4>".$wpdb->num_rows." komentar</h4>";
	foreach ($komen as $kom)
	{
		$nama = (strlen($kom->nama)>0)?$kom->nama:"Anonim";
		if (strlen($kom->masakerja)>0) $masakerja = ", pengalaman kerja ".$kom->masakerja." th";

		echo "<blockquote>".($kom->komentar)."<small>".$nama.$masakerja."</small>
		<small>diposting pada: ".date("j M Y",strtotime($kom->postdate))."</small>
		</blockquote>";
	}
}
?>

			<hr/>
			<h3>Form</h3>
			<form class="form" method='post'>
			Nama saya <input type="text" name="nama" id="pgnama" placeholder="nama anda" class='input-small'>. 
			Saya pernah/masih bekerja di profesi/jabatan ini selama kurang lebih <input type="text" name="masakerja" id="pgmasakerja" placeholder="1" class='input-mini'> tahun.
			Komentar dan tips dari saya tentang pekerjaan ini adalah: 
			<br/><textarea name='komentar' id="pgkomentar" placeholder='isi pengalaman anda disini...' rows='5' style='width:95%'></textarea>
			<br/>Apa ibukota negara kita? <input type="text" name="test" id="pgtest" placeholder="" class='input-medium'> <small>*mohon diisi untuk mencegah spam</small>
			<br/><input type='hidden' name='act' value='pengalaman'>
			<input type='button' value='Kirim'  id='submitpengalaman' class='btn btn-primary'>			
			<div id='pgprogress'></div>
			</form>

		</div>	
		<div class="tab-pane" id="tab3">
			<p>Ingin mencari pekerjaan di profesi/jabatan ini tapi tidak ada lowongan di daerah anda? Silahkan gunakan form dibawah untuk promosi diri.</p>
			<hr/>
<?php
$promo = $wpdb->get_results("SELECT * FROM job.carikerja WHERE jobid=".$id." AND approved=1 ORDER BY postdate DESC");
if ($wpdb->num_rows==0)
{
	echo "<div style='text-align:center'><em>Hmmm...masih kosong nih</em></div>";
}
else
{
	echo "<h4>".$wpdb->num_rows." komentar</h4>";
	foreach ($promo as $pro)
	{
		$nama = (strlen($pro->nama)>0)?$pro->nama:"Anonim";
		$pendidikan = (strlen($pro->pendidikan)>0)?$pro->pendidikan:"N/A";
		$kontak = (strlen($pro->kontak)>0)?$pro->kontak:"N/A";
		if (strlen($pro->umur)>0) $umur = " (".$pro->umur." th)";

		echo "<blockquote>".nl2br($pro->komentar)."<small>".$nama.$umur." - Pendidikan Terakhir: ".$pendidikan." , ".$kontak."</small>
		<small>diposting pada: ".date("j M Y",strtotime($pro->postdate))."</small>
		</blockquote>";
	}
}
?>

			<hr/>
			<h3>Form</h3>
			<form class="form" method='post'>
			Nama saya <input type="text" name="nama" id="cjnama" placeholder="nama anda" class='input-medium'>. 
			Umur saya sekarang adalah <input type="text" name="umur" id="cjumur" placeholder="umur" class='input-mini'> tahun 
			dan pendidikan terakhir saya adalah <input type="text" name="pendidikan" id="cjpendidikan" placeholder="smp / sma / kuliah" class='input-medium'>.
			Sekilas tentang pribadi dan pengalaman kerja saya: 
			<br/><textarea name='komentar' id="cjkomentar" placeholder='ceritakan tentang diri anda disini' rows='5' style='width:95%'></textarea>
			<br/>Lebih lanjut bisa hubungi saya di <input type="text" name="kontak" id="cjkontak" placeholder="email atau no.telp" class='input-medium'>
			<br/>Apa ibukota negara kita? <input type="text" name="test" id="cjtest" placeholder="" class='input-medium'> <small>*mohon diisi untuk mencegah spam</small>
			<br/><input type='hidden' name='act' value='carikerja'>
			<input type='button'  id='submitcarikerja' value='Kirim' class='btn btn-primary'>			
			<div id='cjprogress'></div>
			</form>

		</div>	
		<div class="tab-pane" id="tab4">
			<p>Informasi mengenai perusahaan yang membuka lowongan ini, diambil dari hasil pencarian Google. Jadi, harap dicatat
				ada kemungkinan hasilnya tidak relevan/sesuai. 
				Gunakan fasilitas ini utk mempelajari profile sebuah perusahaan sebelum melamar.
				Hati-hati dengan penipuan.
			</p>
			<?php
			if (strlen($row->company)>0)
			{
				echo "<ul>
					<li>Cari info mengenai perusahaan <em>$row->company</em> di <a href='https://www.google.com/search?q=".urlencode($row->company)."' target='_blank' rel='nofollow'>Google</a></li>
					<li>Cari info mengenai perusahaan <em>$row->company</em> di <a href='http://www.bing.com/search?q=".urlencode($row->company)."' target='_blank' rel='nofollow'>Bing</a></li>
					</ul>";
			}
			else
			{
				echo "<p>Tidak ada info nama perusahaan pada lowongan kerja ini.</p>";
			}
			?>
		</div>	
	</div>
</div>

	</div>	<!-- end div 9 -->
	<div class='span3'>
		<p style='margin-bottom:20px'>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-1117201847231228";
/* lowker-200x90 */
google_ad_slot = "3728047991";
google_ad_width = 200;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
		</p>

		<table class='table table-condensed table-bordered' style='background-color: #eee'><tbody>
			<tr><td><dl><dt>Posisi:</dt><dd><?=$row->title?></dd></dl></td></tr>
			<tr><td><dl><dt>Perusahaan:</dt><dd><a href='<?=get_bloginfo('url')?>/perusahaan/<?=companyslug($row->company)?>/'><?=$row->company?></a></dd></dl></td></tr>
			<tr><td><dl><dt>Tanggal Buka:</dt><dd><?=$row->post_date?></dd><dd><?=time_elapsed(strtotime($row->post_date))?> yg lalu</dd></dl></td></tr>
			<?php if (longprov($row->prov) != "n/a") { ?>
			<tr><td><dl><dt>Propinsi:</dt><dd><a href='<?=get_bloginfo('url')?>/?cari=&di=<?=urlencode(strtolower(longprov($row->prov)))?>'><?=longprov($row->prov)?></a></dd></dl></td></tr>
			<?php } ?>
			<tr><td><dl><dt>Kota:</dt><dd><a href='<?=get_bloginfo('url')?>/?cari=&di=<?=urlencode(strtolower($row->city))?>'><?=$row->city?></a></dd></dl></td></tr>
		</tbody></table>

		<table class='table table-condensed table-bordered'><tbody>
			<tr><td><dl><dt>Pencarian Terbaru:</dt>
				<dd><div id='latestsearch'><?=latest_search()?></div></dd></dl></td></tr>
		</tbody></table>
	</div>

</div>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h2 id="myModalLabel"><?=$jobtitle?></h2>
  </div>
  <div class="modal-body">
    <div class='well'>   
	    <p><strong>Halo Teman, Gabung yuk ke komunitas kami untuk lowongan terbaru dan tips pekerjaan. GRATIS</strong></p>
    	<p style='border-top:1px solid #ddd'><fb:like-box href="https://www.facebook.com/pages/Lowonganindonesianet/542193242476532" width="292" show_faces="false" stream="false" header="false"></fb:like-box></p>
    	<p style='border-top:1px solid #ddd;padding-top: 15px'><a href="https://twitter.com/low_indo_net" class="twitter-follow-button" data-show-count="true" data-size="large">Follow @low_indo_net di Twitter</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></p>
    </div>
    
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Batal</button>
    <a class="btn btn-primary" href='<?=get_bloginfo('url')."/kirim-lamaran/".$id."/"?>' target='_blank'>Kirim Lamaran</a>
  </div>
</div>


<?php get_footer(); ?>