<?php
include "../../../wp-load.php";

$field = $_POST['field'];
$camid = $_POST['camid'];
$camera = ucwords(str_ireplace("-", " ", $camid));
?>

<?php 
if ($field=="expert_reviews")
{
	$title = "Suggest new review";
	$str_url = "Review URL";
}
else if ($field=="video_reviews")
{
	$title = "Suggest new video review";
	$str_url = "Video URL (Youtube, etc)";
}
else if ($field=="sample_videos")
{
	$title = "Suggest new sample video";
	$str_url = "Video URL (Youtube, etc)";
}
else if ($field=="learning_material")
{
	$title = "Suggest new learning material";
	$str_url = "Item URL (Amazon, etc)";
}
else if ($field=="accesories")
{
	$title = "Suggest new accessories";
	$str_url = "Item URL (Amazon, etc)";
}
?>

<h3><?=$title?></h3>

<form role="form" id="form-input">
  <div class="form-group">
    <label for="idCamera">For Camera</label>
    <input type="text" name='camera' id="idCamera" class="form-control" value='<?=$camera?>' disabled>
  </div>
  <div class="form-group">
    <label for="idURL"><?=$str_url?></label>
    <textarea name='url' class="form-control" rows='3' id="idURL" placeholder="input url, please enter one per line"></textarea>
  </div>
  <div class="form-group">
    <label for="idComment">Your comment (optional)</label>
    <textarea name='comment' class="form-control" rows='2' id="idComment" placeholder="your comment here.."></textarea>
  </div>
  <input type="hidden" id="idParam" value="<?=$field?>"/>
  <input type="hidden" id="idSuggestValue" value=""/>
  <div id='sendprogress'></div>
  <button type="button" id="sendtip" class="btn btn-warning">Submit</button>
</form>


<script>
$("#sendtip").click(function() 
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
            $("#sendtip").attr("disabled", true);
        }
    });
});
</script> 

