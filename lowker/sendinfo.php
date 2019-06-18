<?php

include "../../../wp-load.php";

$id = $_POST["id"];
$act = $_POST["act"];
$answer = $_POST["test"];

if (strtolower($answer)!="jakarta")
{
	echo "<p style='margin:10px 0'><span class='text-error'>Jawaban Salah. Mohon dicek lagi jawaban pertanyaan diatas (Apa ibukota negara?)</span></p>";
	return;
}

global $wpdb;

if ($act=='pengalaman')
{
	$nama = trim(strip_tags($_POST["nama"]));
	$masakerja = trim(strip_tags($_POST["masakerja"]));
	$komentar = trim(strip_tags($_POST["komentar"]));

	if (strlen($nama)==0)
	{
		echo "<p style='margin:10px 0'><span class='text-error'>Mohon masukkan nama anda</span></p>";
		return;
	}	

	$sql = "INSERT INTO job.pengalaman SET jobid=".$id.",
		nama='".$nama."',
		masakerja='".$masakerja."',
		komentar='".$komentar."',
		approved=1,
		ip='".$_SERVER['REMOTE_ADDR']."',
		postdate=NOW()";

	if ($wpdb->query($sql)===FALSE)
		$notif = "<span class='text-error'>Oops, ada sedikit masalah. Harap tenang, admin segera ke TKP :)</span>";
	else
		$notif = "<span class='text-success'>Pengiriman Berhasil! Admin akan me-review dahulu sebelum ditampilkan.</span>";
}
else if ($act == 'carikerja')
{
	$nama = trim(strip_tags($_POST["nama"]));
	$umur = trim(strip_tags($_POST["umur"]));
	$pendidikan = trim(strip_tags($_POST["pendidikan"]));
	$kontak = trim(strip_tags($_POST["kontak"]));
	$komentar = trim(strip_tags($_POST["komentar"]));

	if (strlen($nama)==0)
	{
		echo "<p style='margin:10px 0'><span class='text-error'>Mohon masukkan nama anda</span></p>";
		return;
	}	

	$sql = "INSERT INTO job.carikerja SET jobid=".$id.",
		nama='".$nama."',
		umur='".$umur."',
		pendidikan='".$pendidikan."',
		komentar='".$komentar."',
		kontak='".$kontak."',
		approved=1,
		ip='".$_SERVER['REMOTE_ADDR']."',
		postdate=NOW()";

	if ($wpdb->query($sql)===FALSE)
		$notif = "<span class='text-error'>Oops, ada sedikit masalah. Harap tenang, admin segera ke TKP :)</span>";
	else
		$notif = "<span class='text-success'>Pengiriman Berhasil! Admin akan me-review dahulu sebelum ditampilkan.</span>";

}

echo "<p style='margin:10px 0'>$notif</p>";

?>