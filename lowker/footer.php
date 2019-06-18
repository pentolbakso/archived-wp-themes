
<div id="footer">
	<ul><?php wp_list_pages('title_li=&depth=1'); ?>
		<li><a href='<?=get_bloginfo('url')?>/a/terbaru-tahun-<?=date("Y")?>/'>Lowongan Terbaru <?=date("Y")?></a></li>
		<li><a href='<?=get_bloginfo('url')?>/category/blog/'>Blog</a></li>
	</ul>

	<?=get_bloginfo("blogname")." - ".date("Y")?>
</div>

		</div> <!-- end span -->
	</div> <!-- end row -->
</div>	<!-- end container -->

</div>

<!-- Script -->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<!--<script src="<?=get_bloginfo('template_directory')?>/bootstrap/js/jquery.min.js"></script>-->
<script src="<?=get_bloginfo('template_directory')?>/bootstrap/js/bootstrap.min.js"></script>
<!--<script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.1.1/js/bootstrap.min.js"></script>-->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-519c47d44366b4f6"></script>

<script>
<?php
$id = get_query_var("jobid");
?>

$("#submitcarikerja").click(function() 
{   
    var nama = $('#cjnama').val();
    var umur = $('#cjumur').val();
    var pend = $('#cjpendidikan').val();
    var komen = $('#cjkomentar').val();
    var kontak = $('#cjkontak').val();
    var test = $('#cjtest').val();

    $('#cjprogress').show().html('Mengirim informasi, mohon tunggu ...');
 
    $.ajax({
        cache: false,
        type: "POST",
        url: "<?=get_bloginfo('template_directory')?>/sendinfo.php",
        data: "act=carikerja&id=<?=$id?>&nama="+nama+"&umur="+umur+"&pendidikan="+pend+"&komentar="+komen+"&kontak="+kontak+"&test="+test,
        success: function(data) 
        {
            $('#cjprogress').html(data);
            //$("#submitcarikerja").attr("disabled", true);
        }
    });
});

$("#submitpengalaman").click(function() 
{   
    var nama = $('#pgnama').val();
    var masakerja = $('#pgmasakerja').val();
    var komen = $('#pgkomentar').val();
    var test = $('#pgtest').val();

    $('#pgprogress').show().html('Mengirim informasi, mohon tunggu ...');
 
    $.ajax({
        cache: false,
        type: "POST",
        url: "<?=get_bloginfo('template_directory')?>/sendinfo.php",
        data: "act=pengalaman&id=<?=$id?>&nama="+nama+"&masakerja="+masakerja+"&komentar="+komen+"&test="+test,
        success: function(data) 
        {
            $('#pgprogress').html(data);
            //$("#submitcarikerja").attr("disabled", true);
        }
    });
});
</script> 


<script type="text/javascript">
  <!--  
  $(document).ready(function() {
   
   var docHeight = $(window).height();
   var footerHeight = $('#footer').height();
   var footerTop = $('#footer').position().top + footerHeight;
   
   if (footerTop < docHeight) {
    $('#footer').css('margin-top', 10 + (docHeight - footerTop) + 'px');
   }
  });  
  // -->
</script>

<?php if (!current_user_can('manage_options')) { ?>
	<!-- Histats.com  START (hidden counter)-->
	<script type="text/javascript">document.write(unescape("%3Cscript src=%27http://s10.histats.com/js15.js%27 type=%27text/javascript%27%3E%3C/script%3E"));</script>
	<a href="http://www.histats.com" target="_blank" title="invisible hit counter" ><script  type="text/javascript" >
	try {Histats.start(1,2107552,4,0,0,0,"");
	Histats.track_hits();} catch(err){};
	</script></a>
	<noscript><a href="http://www.histats.com" target="_blank"><img  src="http://sstatic1.histats.com/0.gif?2107552&101" alt="invisible hit counter" border="0"></a></noscript>
	<!-- Histats.com  END  -->
<?php } ?>

</body>
</html>