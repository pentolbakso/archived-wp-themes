<?php 
include "myfunctions.php";
include "paginator.class.php";
$persh = get_query_var("persh");
$persh = urldecode($persh);

$filter = "company LIKE '".$persh."'";
$pages = new Paginator;
$count = jobcount($filter);
$pages->items_total = $count;
$pages->paginate();
$str = joblist($filter,$pages->limit,$snippet);

global $the_title,$the_canonical,$the_description;
$the_title = "Info ".$count." lowongan kerja di ".$persh;
$the_description = "<meta name='description' content='Lowongan di perusahaan ".$persh." : ".$snippet."'/>";

?>
<?php get_header(); ?>

<?php include "searchbox.php"; ?>


<p><center>
<script type="text/javascript"><!--//<![CDATA[
   var loc = escape(window.location).substring(0, 2000);
   var cb = Math.floor(Math.random()*99999999999);
   var scheme = (location.protocol=='https:')?'https://':'http://';
   document.write ("<scr"+"ipt type='text/javascript' src='"+scheme+"Lowongan.gsspc"+"ln.jp/yie/ld/gpass?zoneid=6408&type=js&cb="+cb);
   document.write ("&amp;loc=" + loc);     
   document.write ("'><\/scr"+"ipt>");
//]]>--></script>
<script type='text/javascript'><!--//<![CDATA[
if(typeof gssp_6408 == 'object' && gssp_6408 != '') {
   var m3_u = (location.protocol=='https:')?'https://'+ gssp_6408.t:'http://'+ gssp_6408.t;
   var m3_r = Math.floor(Math.random()*99999999999);
   if (!document.MAX_used) document.MAX_used = ',';
   document.write ("<scr"+"ipt type='text/javascript' src='"+m3_u);
   document.write ("?zoneid=6408");
   document.write ('&amp;cb=' + m3_r);
   if (document.MAX_used != ',') document.write ("&amp;exclude=" + document.MAX_used);
   document.write (document.charset ? '&amp;charset='+document.charset : (document.characterSet ? '&amp;charset='+document.characterSet : ''));
   document.write ("&amp;loc=" + escape(window.location).substring(0, 2000));
   if (document.referrer) document.write ("&amp;referer=" + escape(document.referrer));
   if (document.context) document.write ("&context=" + escape(document.context));
   if (document.mmm_fo) document.write ("&amp;mmm_fo=1");
   document.write("&key=" + gssp_6408.k);
   document.write ("'><\/scr"+"ipt>");
}
//]]>--></script>
</center></p>

<h2>Lowongan pekerjaan  <small><?=$persh?></small></h2>

<?php
echo "<p>Ditemukan sekitar ".$count." lowongan yang sedang/pernah dibuka oleh ".$persh."</p>";
echo $str;
echo "<div class='pagination'>".$pages->display_pages()."</div>";	
?>

<?php get_footer(); ?>
