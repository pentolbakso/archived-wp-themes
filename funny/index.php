<?php
if (session_id() == "") session_start();

if (isset($_GET["new"])) $_SESSION["mode"] = "new";
else if (isset($_GET["top"])) $_SESSION["mode"] = "top";
else if (isset($_GET["random"])) $_SESSION["mode"] = "random";
else $_SESSION["mode"] = "hot";

if (isset($_GET["channel"])) $_SESSION["channel"] = $_GET["channel"];
else if (!isset($_SESSION["channel"])) $_SESSION["channel"] = "all";

global $the_title,$the_meta;

if ($_SESSION["mode"]!="hot")
  $the_title = ucfirst($_SESSION["mode"]);
else if ($_SESSION["channel"]!="all")
  $the_title = ucfirst($_SESSION["channel"]);

$the_meta = "
<meta property='og:title' content='".get_bloginfo('name')." - ".get_bloginfo('description')."' />
<meta property='og:site_name' content='".get_bloginfo('name')."' />
<meta property='og:url' content='".get_bloginfo('url')."' />
<meta property='og:description' content='Klik untuk melihat gambar/video dan memberikan voting atau komentar ...' />
<meta property='og:type' content='article' />
<meta property='fb:app_id' content='225944537596416' />";
?>

<?php get_header(); ?>

<div class='col-md-12' id='maincontent'>
<?php include_once("load_content.php"); ?>
</div>

<script>

<?php
global $wpdb;
if ($_SESSION["channel"] == "all")
	$row = $wpdb->get_row("SELECT count(*) as tot FROM lontong.post");
else
	$row = $wpdb->get_row("SELECT count(*) as tot FROM lontong.post WHERE channel='".$_SESSION["channel"]."'");
$post_per_page = 5;
$totalpage = ceil($row->tot / $post_per_page);
?>
var processing = false;
var nextPage = 1;
var totalPage = <?=$totalpage?>;
$(window).scroll(function(e){

    if (processing == false) {

      if ($(window).scrollTop() < $(document).height() - $(window).height() - 150) { 
        return(false);
      } else {
        
        if (nextPage < totalPage) {
	        loadContent(nextPage);
	        nextPage++;
    	}
      }
    }

});

function loadContent(page) { 

  processing = true;
  $('#infiniteLoader').show('fast');
  
  $.ajax({
      cache: false,
      type: 'POST',
      url: '<?=get_bloginfo('template_directory')?>/load_content.php',
      data: 'ajax=1&pg='+page,
      success: function(html) 
      {
        $('#infiniteLoader').hide('1000');
        $("#maincontent").append(html);
        addthis.toolbox('.addthis_toolbox');
        processing = false;
      }
  });

  return(false);
}
</script>

<?php get_footer(); ?>