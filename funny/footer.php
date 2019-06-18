</div>
</div>

<div id="infiniteLoader"><h3><i class="fa fa-cog fa-spin"></i> Loading ...</h3></div>

<div id="footer">
<div class="row">
  <div class="col-md-6">
    <h4>Tentang LontongNET</h4>
    <p>LontongNET adalah situs untuk mengobati penyakit bosan :) Disini semua orang bisa memposting gambar atau video lucu, keren, atau
    menginspirasi. Ya pokoknya yang seru-seru dan tidak garing! Pendaftaran tidak diharuskan, namun member akan mendapat beberapa kelebihan. User bisa memvoting,
    komentar, dan sharing media. Dan LontongNET akan selalu gratis! App for Android dan iPhone akan segera menyusul.</p>
  </div>
  <div class="col-md-3">
    <h4>Komunitas</h4>
    <ul class="list-unstyled">
    <li><a href="https://www.facebook.com/lontongnetofficial"><i class="fa fa-facebook-square"></i> Facebook Fan</a></li>
    <li><a href="https://twitter.com/LontongNET"><i class="fa fa-twitter-square"></i> Follow @LontongNET</a></li>
    <li><a href="#"><i class="fa fa-google-plus-square"></i> Google+</a></li>     
    </ul>
  </div>
  <div class="col-md-3">
    <h4>More</h4>
    <ul class="list-unstyled">
    <?php wp_list_pages('title_li=&depth=1'); ?>
    </ul>
  </div>
</div>
</div>

</div> <!-- end container -->

<?php if (!isset($_SESSION["user_id"])) { ?>
<div id="login-modal" class="modal fade noborder"><div class="modal-dialog">
<div class="modal-content well-sm">
  <p style="font-size:20px"><i class="fa fa-beer"></i> <b>Terima Kasih</b> atas partisipasinya!</p>
  <p>LontongNET terbuka dan gratis untuk semuanya. Tidak perlu mendaftar, tapi member mendapat beberapa kelebihan.</p>
  <p style="font-size:16px;font-weight:bold">Daftar atau <a href="<?=get_bloginfo("url")?>/login/">Login</a></p>
  <p><a type="button" href="<?=get_bloginfo("url")?>/signup/?via=facebook" class="btn btn-social" style="background-color:#3b5998"><i class="fa fa-facebook-square" style="font-size:22px"></i> Daftar via Facebook</a></p>
  <p><a href="<?=get_bloginfo("url")?>/signup/">Daftar via Email</a></p>
</div>
<?php } ?>
</body>
<!-- Script -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>

<?php 
$arr = parse_url($_SERVER['REQUEST_URI']);
//echo "<!-- path: ".$arr['path']." -->";
if (get_query_var("postid") || ($arr['path'] == '/')) { ?>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-519c47d44366b4f6"></script>

<script>
function sendComment(t)
{   
  var datastring = $(t).serialize();
  console.log(datastring);

  $(t).find(".btn").html(".....");

  $.ajax({
      cache: false,
      type: 'POST',
      dataType: 'json',
      url: '<?=get_bloginfo('template_directory')?>/ajax_comment.php',
      data: datastring,
      success: function(data) 
      {
          if (data.success == 'true') {

            $(t).parent().find(".tableComment").append(data.message);
            <?php if (!isset($_SESSION["user_id"])) echo "$('#login-modal').modal('show');"; ?>

          } else {

          }

      }
  });

  $(t).find(".btn").html("Kirim");
  return(false);

};

function likeComment(t,commentID,poin)
{
  $(t).html("<i class='fa fa-thumbs-o-up'></i> ...");

  $.ajax({
      cache: false,
      type: 'POST',
      dataType: 'json',
      url: '<?=get_bloginfo('template_directory')?>/ajax_like.php',
      data: 'commentid='+commentID,
      success: function(data) 
      {
          if (data.success == 'true') {    
            poin++;          
            $(t).html("<i class='fa fa-thumbs-o-up'></i> liked ("+poin+")");
            <?php if (!isset($_SESSION["user_id"])) echo "$('#login-modal').modal('show');"; ?>
          } else {
            if (poin>0)
              $(t).html("<i class='fa fa-thumbs-o-up'></i> like ("+poin+")");
            else
              $(t).html("<i class='fa fa-thumbs-o-up'></i> like");
          }
      }
  });
};

function createReply(t,postID,commentID)
{
  var comment_html = "<form action='#' class='replyForm' onsubmit='return submitReply(this)'><div class='input-group'>"
            +"<input type='text' class='form-control input-sm' name='comment' placeholder='Tulis balasan..'>"
            +"<input type='hidden' name='postid' value='"+postID+"'/>"
            +"<input type='hidden' name='commentid' value='"+commentID+"'/>"
            +"<span class='input-group-btn'><button class='btn btn-default btn-sm' type='submit' >Balas</button></span>"
            +"</div></form>";

  $(t).closest("div").next().html(comment_html);
};

function submitReply(t)
{   

  var datastring = $(t).serialize();
  console.log(datastring);

  $(t).find(".btn").html("...");

  $.ajax({
      cache: false,
      type: 'POST',
      dataType: 'json',
      url: '<?=get_bloginfo('template_directory')?>/ajax_comment.php',
      data: datastring,
      success: function(data) 
      {
          if (data.success == 'true') {
            $(t).html(data.message);
            <?php if (!isset($_SESSION["user_id"])) echo "$('#login-modal').modal('show');"; ?>
          } else {
            $(t).html("<p class='text-danger' style='margin-top:10px;font-size:12px'>"+data.message+"</p>");
          }
      }
  });

  $(t).find(".btn").html("Balas ");

  return(false);
};

function sendReport(commentID)
{
  if (!confirm("Laporkan komentar ini ke Admin ?"))
    return (false);
  else {

    $.ajax({
        cache: false,
        type: 'POST',
        dataType: 'json',
        url: '<?=get_bloginfo('template_directory')?>/ajax_report.php',
        data: 'commentid='+commentID,
        success: function(data) 
        {
            if (data.success == 'true') {              
              alert("Thanks");
            } else {
              alert("Gagal!"+data.message);
            }
        }
    });

  }

};

function sendVote(t,poin,postid,mode)
{   
  console.log("poin: "+poin);

  $.ajax({
      cache: false,
      type: 'POST',
      dataType: 'json',
      url: '<?=get_bloginfo('template_directory')?>/ajax_vote.php',
      data: "postid="+postid+"&mode="+mode,
      success: function(data) 
      {
          if (data.success == 'true') {

            console.log("success vote");
            if (mode=="up")
              poin++;
            else 
              poin--;

            $(t).parent().children(".votecount").html(poin);

            $(t).parent().children(".voteup").attr('disabled', 'disabled');
            $(t).parent().children(".votedown").attr('disabled', 'disabled');

            if (mode=="up") {
              $(t).parent().children(".voteup").css('background-color', '#74DF00');
            } else { 
              $(t).parent().children(".votedown").css('background-color', '#DF0101');
            }

            <?php if (!isset($_SESSION["user_id"])) echo "$('#login-modal').modal('show');"; ?>

          } else {
            
            console.log("failed vote");

            //$(t).html();
            <?php if (isset($_SESSION['user_id'])) { ?>
            alert("Vote gagal, sudah pernah voting sebelumya?")
            <?php } else { ?>
            alert("Vote gagal, silahkan daftar/login terlebih dulu.")
            <?php } ?>
          }
      }      
  });

};
</script>

<?php } //end if home ?>


<!-- Start of StatCounter Code for Default Guide -->
<script type="text/javascript">
var sc_project=9726982; 
var sc_invisible=1; 
var sc_security="c8eedd22"; 
var scJsHost = (("https:" == document.location.protocol) ?
"https://secure." : "http://www.");
document.write("<sc"+"ript type='text/javascript' src='" +
scJsHost+
"statcounter.com/counter/counter.js'></"+"script>");
</script>
<noscript><div class="statcounter"><a title="web counter"
href="http://statcounter.com/free-hit-counter/"
target="_blank"><img class="statcounter"
src="http://c.statcounter.com/9726982/0/c8eedd22/1/"
alt="web counter"></a></div></noscript>
<!-- End of StatCounter Code for Default Guide -->

</html>