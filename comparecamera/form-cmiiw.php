<?php
include "../../../wp-load.php";

$field = $_POST['field'];
$camid = $_POST['camid'];
$camera = ucwords(str_ireplace("-", " ", $camid));
?>

<?php 
$sql = "SELECT caption FROM compare.specdetails WHERE field='".$field."'";
$res = $wpdb->get_results($sql);
$caption = $res[0]->caption;
?>

<h3>Suggest correction for '<?=$caption?>'</h3>

<form role="form" id="form-input">
  <div class="form-group">
    <label for="idCamera">For Camera</label>
    <input type="text" name='camera' id="idCamera" class="form-control" value='<?=$camera?>' disabled>
  </div>
  <div class="form-group">
    <label for="idSuggestValue">Suggested Value</label>
    <textarea name='suggestvalue' class="form-control" rows='1' id="idSuggestValue"></textarea>
  </div>
  <div class="form-group">
    <label for="idURL">Reference URL (optional, recommended)</label>
    <textarea name='url' class="form-control" rows='1' id="idURL" placeholder="input url..."></textarea>
  </div>
  <div class="form-group">
    <label for="idComment">Your comment (optional)</label>
    <textarea name='comment' class="form-control" rows='2' id="idComment" placeholder="your comment here.."></textarea>
  </div>
  <input type="hidden" id="idParam" value="<?=$caption?>"/>
  <div id='sendprogress'></div>
  <button type="button" id="sendcorrect" class="btn btn-warning">Submit</button>
</form>


<script>
$("#sendcorrect").click(function() 
{   
   var camera = $('#idCamera').val();
   var url = $('#idURL').val();
   var comment = $('#idComment').val();
   var param = $('#idParam').val();
   var value = $('#idSuggestValue').val();

    $('#sendprogress').show().html('<img src="<?=get_bloginfo('template_directory')?>/loader.gif" style="margin:5px 0"/>');
 
    $.ajax({
        cache: false,
        type: "POST",
        url: "<?=get_bloginfo('template_directory')?>/sendtip.php",
        data: "camera="+camera+"&url="+url+"&comment="+comment+"&value="+value+"&param="+param,
        success: function(data) 
        {
            $('#sendprogress').html(data);
            $("#sendcorrect").attr("disabled", true);
        }
    });
});
</script> 

