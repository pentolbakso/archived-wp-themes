
<div id="footer">
	<ul><?php wp_list_pages('title_li=&depth=1'); ?>
		<li><a href='#suggestModal' data-toggle="modal">Tips/Suggestion</a></li>
		<li><a href='<?=get_bloginfo('url')?>/category/blog/'>Blog</a></li>
	</ul>

	<?=get_bloginfo("blogname")." - ".date("Y")?>
</div>

		</div> <!-- end span -->
	</div> <!-- end row -->
</div>	<!-- end container -->

</div>

<div id="suggestModal" class="modal hide fade" tabindex="-1" role="dialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Send Your Tips</h3>
  </div>
  <div class="modal-body">
    <p>Found something inaccurate or want to send me a tip ?</p>
    <form>
      <textarea id='commentbox' name='comment' class='input-block-level' rows='5' placeholder='Your comment here...'></textarea>
      <input type='checkbox' id='iamhuman' name='iamhuman' value='yes'/> check this box to prove you're not a bot
      <br/><br/><input type='button' class='btn btn-primary' id='sendtip' value='Send'/>
      <span id='sendresult'></span>
    </form>
  </div>
  
</div>

<script>
$("#sendtip").click(function() 
{   
   var comment = $('#commentbox').attr('value');
   var human = "no";
   if($("#iamhuman").is(':checked')==true)
       human = "yes";

    $('#sendresult').show().html('<img src="<?=get_bloginfo('template_directory')?>/img/ajax-loader.gif" />');
 
    $.ajax({
        cache: false,
        type: "POST",
        url: "<?=get_bloginfo('template_directory')?>/sendtip.php",
        data: "comment="+comment+"&iamhuman="+human,
        success: function(data) 
        {
            $('#sendresult').html(data);
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
<!-- Start of StatCounter Code for Default Guide -->
<script type="text/javascript">
var sc_project=6939755; 
var sc_invisible=1; 
var sc_security="bfba03fd"; 
</script>
<script type="text/javascript"
src="http://www.statcounter.com/counter/counter.js"></script>
<noscript><div class="statcounter"><a title="free hit
counter" href="http://statcounter.com/free-hit-counter/"
target="_blank"><img class="statcounter"
src="http://c.statcounter.com/6939755/0/bfba03fd/1/"
alt="free hit counter"></a></div></noscript>
<!-- End of StatCounter Code for Default Guide -->
<?php } ?>

</body>
</html>