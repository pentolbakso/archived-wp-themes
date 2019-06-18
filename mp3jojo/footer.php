
</div> <!-- end row -->

<div id="footer"><div class='row'><div class='span12'>
  <p style='font-size:12px;line-height:14px'>
  <strong>Disclaimer:</strong> 
  <?=get_bloginfo('title')?> is just a free media file search engine. We never host music files.No files are hosted / cached / stored on our server. 
  The files are located on third party sites that are not obligated in anyway with our site. All media contents are copyrighted and owned by their respected owners.
  .<?=get_bloginfo('title')?> is not responsible for third party website content. 
  It is illegal for you to distribute or download copyrighted materials files without permission. 
  The files you download with <?=get_bloginfo('title')?> must be for time shifting, personal, private, non commercial use only and must remove the files after listening.
  </p>
  <ul>
  <li><a href='<?=get_bloginfo('url')?>/browse-music/'>Browse Music</a></li>
  <li><a href='<?=get_bloginfo('url')?>/new-releases/'>New Songs</a></li>
  <li><a href='<?=get_bloginfo('url')?>/privacy-policy/'>Privacy Policy</a></li>
  <li><a href='<?=get_bloginfo('url')?>/disclaimer/'>Disclaimer(DMCA)</a></li>
  </ul>

  <div style='clear:both'></div>
  <p>
  Browse Artists: 
  <?php
  foreach(range('A','Z') as $i)
  {
    echo "<a href='".get_bloginfo('url')."/artists/".strtolower($i)."/page/1/'>".$i."</a>&nbsp;";
  }
  
  foreach(range('0','9') as $i)
  {
    echo "<a href='".get_bloginfo('url')."/artists/".strtolower($i)."/page/1/'>".$i."</a>&nbsp;";
  }
  echo "<a href='".get_bloginfo('url')."/artists/other/page/1/'>#</a>";
  ?>
  </p>

<p><?=get_bloginfo("blogname")." - ".date("Y")?></p>
</div></div></div>


</div> <!-- end container main -->

<!-- Script -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<!--<script src="<?=get_bloginfo('template_directory')?>/bootstrap/js/jquery.min.js"></script>-->
<script src="<?=get_bloginfo('template_directory')?>/bootstrap/js/bootstrap.min.js"></script>
<!--<script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.1.1/js/bootstrap.min.js"></script>-->
<!-- Place this tag after the last +1 button tag. -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-519c47d44366b4f6"></script>

<script type = "text/javascript">
$("a.btn-play").click(function() 
{   
    var id = $(this).attr('yid');

    $.ajax({
        cache: false,
        type: 'POST',
        url: '<?=get_bloginfo("template_directory")?>/youtube-player.php',
        data: 'id='+id,
        success: function(data) 
        {
            $('#playthemusiccontent').show().html(data);
        }
    });
});
</script>   


<script>
$("#sendcomment").click(function() 
{   
   var comment = $('#comment').attr('value');
   var name = $('#name').attr('value');
   var human = "no";
   var yid = $('#yid').attr('value');

   if($("#iamhuman").is(':checked')==true)
       human = "yes";

    $('#commentprogress').show().html('<img src="<?=get_bloginfo('template_directory')?>/loader.gif" />');
 
    $.ajax({
        cache: false,
        type: "POST",
        url: "<?=get_bloginfo('template_directory')?>/sendcomment.php",
        data: "comment="+comment+"&iamhuman="+human+"&name="+name+"&id="+yid,
        success: function(data) 
        {
            $('#commentprogress').html(data);
            //$("#sendtip").attr("disabled", true);
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
    $('#footer').css('margin-top', 30 + (docHeight - footerTop) + 'px');
   }
  });  
  // -->
</script>

<?php if (!current_user_can('manage_options')) { ?>
<!-- Histats.com  START (hidden counter)-->
<script type="text/javascript">document.write(unescape("%3Cscript src=%27http://s10.histats.com/js15.js%27 type=%27text/javascript%27%3E%3C/script%3E"));</script>
<a href="http://www.histats.com" target="_blank" title="web stats analysis" ><script  type="text/javascript" >
try {Histats.start(1,2328592,4,0,0,0,"");
Histats.track_hits();} catch(err){};
</script></a>
<noscript><a href="http://www.histats.com" target="_blank"><img  src="http://sstatic1.histats.com/0.gif?2328592&101" alt="web stats analysis" border="0"></a></noscript>
<!-- Histats.com  END  -->
<?php } ?>

<?php
//capture google search
$referringPage = parse_url( $_SERVER['HTTP_REFERER'] );
if (stristr( $referringPage['host'], 'google.' ) || stristr( $referringPage['host'], 'bing.' ))
{
  parse_str( $referringPage['query'], $queryVars );
  $kw = $queryVars['q']; // This is the search term used
  if ($kw!="")
  {
    if (stripos($kw,"mp3jojo.com")!==false || stripos($kw,"http")!==false)
    {
    }
    else if (strlen($kw) == strlen(utf8_decode($kw))) {
    
    $kw = str_ireplace(array('+','mp3jojo','mp3','mp4','3gp','wmv','free','download','song','zippyshare','zippy','gratuit','kbps','128','320','256','4shared'), ' ', $kw);
    $kw = preg_replace('/\s{2,}/',' ', $kw);

    global $wpdb;
    $sql = "INSERT INTO mp3.searchenginekw (keyword,added) VALUES ('".mysql_real_escape_string(trim($kw))."',NOW())
      ON DUPLICATE KEY UPDATE added=NOW()";
    $wpdb->query($sql);
    }
  }
}
?>

<!-- Place this code above the closing body tag of the page -->
<script type="text/javascript">
var adParams = {
  a: '11920181', numOfTimes: '1',duration: '1' ,serverdomain: 'ads.adk2.com' ,period: 'Hour' 
};
</script>
<script type="text/javascript" src="http://cdn.adk2.com/adstract/scripts/popup/popup.js"></script>


</body>
</html>