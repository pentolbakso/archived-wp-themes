<?php
global $the_title;
$the_title = "Halaman tidak ditemukan";
?>

<?php get_header(); ?>
<div id="page" style='text-align:center'>

<h2>Whooops, page yg anda cari tidak ada !!</h2>

<p>Hal ini bisa disebabkan karena: Kesalahan URL , halaman tsb sudah dihapus dari database, atau bisa juga karena ada masalah di server. 
Untuk melanjutkan pencarian, silahkan gunakan fasilitas form dibawah ini.</p>

<?php include('searchbox.php'); ?>

</div>

<?php get_footer(); ?>