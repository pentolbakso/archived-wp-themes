<div id='newcompare' class='modal hide noborder' role='modal' tabindex='-1'>
	<?php include ("searchbox.php"); ?>
</div>

<div id="footer">
	<div class='row'>	
		<div class="col-md-3 col-md-offset-2" style='text-align:left'>
			<h4>Popular This Month</h4>
			<ul class='comparisons'>
			<?php
			$m = date("Ym");
			$sql = "SELECT * FROM compare.stat WHERE month='$m' ORDER BY count DESC LIMIT 10";
			$res = $wpdb->get_results($sql);
			foreach($res AS $r)
			{
				echo "<li><span class='glyphicon glyphicon-camera'></span>&nbsp;&nbsp;<a href='".get_bloginfo('url')."/c/".$r->slug."/'>$r->cam1 vs $r->cam2</a></li>";
			}
			?>
			</ul>
		</div>
		<div class="col-md-3" style='text-align:left'>
			<h4>Last Comparisons</h4>
			<ul class='comparisons'>
			<?php
			$m = date("Ym");
			$sql = "SELECT * FROM compare.lastcompare GROUP BY slug ORDER BY added DESC LIMIT 10";
			$res = $wpdb->get_results($sql);
			foreach($res AS $r)
			{
				echo "<li><span class='glyphicon glyphicon-camera'></span>&nbsp;&nbsp;<a href='".get_bloginfo('url')."/c/".$r->slug."/'>$r->cam1 vs $r->cam2</a></li>";
			}
			?>
			</ul>
		</div>
		<div class="col-md-3" style='text-align:left'>			
			<h4>Find Us</h4>
			<div style='margin-bottom: 10px'>
				<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2FCompareCamera&amp;width=292&amp;height=62&amp;colorscheme=dark&amp;show_faces=false&amp;border_color&amp;stream=false&amp;header=false&amp;appId=103597163066273" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:62px;" allowTransparency="true"></iframe>
			</div>
		</div>
	</div>

	<div style='background-color:#000;padding: 5px 0'>
	<ul id='footer-links'>
		<li><a href='<?=get_bloginfo('url')?>'>Home</a></li>
		<?php wp_list_pages('title_li=&depth=1'); ?>
		<li><a href='<?=get_bloginfo('url')?>/blog/'>Blog</a></li>
		<li> | Copyright © 2012 - <?=date("Y")?> CompareCamera.org</li></ul>
	</div>

</div>

<div id='gocompare' class='modal fade noborder'><div class='modal-dialog'><div class="modal-content" style='padding:10px'></div></div></div>
<a href="#gocompare" data-toggle='modal' title="Start new compare!" id="feedback-badge"><span>New Compare</span></a>

</body>

<!-- script delay -->
<script type="text/javascript">
	$(document).ready(function () {
		$('#feedback-badge').feedbackBadge({
			//css3Safe: $.browser.safari ? true : false,
			onClick: function () { 

			    $('#gocompare .modal-content').show().html('<center><img src="<?=get_bloginfo('template_directory')?>/loader.gif"/></center>');

			    $.ajax({
			        cache: false,
			        type: 'GET',
			        url: '<?=get_bloginfo("template_directory")?>/form-compare.php',
			        success: function(data) 
			        {
			            $('#gocompare').show();
			            $('#gocompare .modal-content').show().html(data);
			        }
			    });
				//$('#gocompare').modal('show');
				//return false;
			},
			float: 'right'
		});
	});
</script>

<script type="text/javascript">
  $(document).ready(function() {
   
   var docHeight = $(window).height();
   var footerHeight = $('#footer').height();
   var footerTop = $('#footer').position().top + footerHeight;
   
   if (footerTop < docHeight) {
    $('#footer').css('margin-top', 10 + (docHeight - footerTop) + 'px');
   }
  });
</script>
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
<script type="text/javascript">
  (function() {
    var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
    li.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + '//platform.stumbleupon.com/1/widgets.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
  })();
</script>

<?php if (!current_user_can('manage_options')) { ?>
<!-- Start of StatCounter Code for Default Guide -->
<script type="text/javascript">
var sc_project=8603523; 
var sc_invisible=1; 
var sc_security="59c2868c"; 
var scJsHost = (("https:" == document.location.protocol) ?
"https://secure." : "http://www.");
document.write("<sc"+"ript type='text/javascript' src='" +
scJsHost+
"statcounter.com/counter/counter.js'></"+"script>");
</script>
<noscript><div class="statcounter"><a title="web analytics"
href="http://statcounter.com/" target="_blank"><img
class="statcounter"
src="http://c.statcounter.com/8603523/0/59c2868c/1/"
alt="web analytics"></a></div></noscript>
<!-- End of StatCounter Code for Default Guide -->
<?php } ?>


</html>