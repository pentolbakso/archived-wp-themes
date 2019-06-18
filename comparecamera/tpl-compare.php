<?php
$cam1 = get_query_var("cam1");
$cam2 = get_query_var("cam2");

include_once ("myfunctions.php");
global $wpdb;

$row1 = $wpdb->get_row("SELECT * FROM compare.camera WHERE camera_id='".$cam1."'");
if (!$row1)
	$errormsg[] = "CameraID ".$cam1." not found !";

$row2 = $wpdb->get_row("SELECT * FROM compare.camera WHERE camera_id='".$cam2."'");
if (!$row2)
	$errormsg[] = "CameraID ".$cam2." not found !";

if (count($errormsg)>0)
{
	include ("header.php");

	echo "<div class='alert alert-error'><h4>Houston, we have problem!</h4>";
	foreach ($errormsg as $e)
		echo "$e<br/>";
	echo "</div>";

	include ("searchbox.php");
	include ("footer.php");
	return;
}
else
{
	//---AMAZONG
	$amz1 = get_amazon($cam1);
	$amz2 = get_amazon($cam2);
	$review1 = $amz1->Items->Item->CustomerReviews->IFrameURL;
	$review2 = $amz2->Items->Item->CustomerReviews->IFrameURL;
	$img1 = $amz1->Items->Item->LargeImage->URL;
	if ($img1=="") $img1 = get_bloginfo('template_directory')."/no-photo.jpg";
	$img2 = $amz2->Items->Item->LargeImage->URL;
	if ($img2=="") $img2 = get_bloginfo('template_directory')."/no-photo.jpg";

	//---PRICE
	$price1 = get_price($row1->price_id,$lastprice1);
	$price2 = get_price($row2->price_id,$lastprice2);

	//---SLUG
	$VERSUS_SLUG = versus_slug($row1->camera_id,$row2->camera_id);
}
?>

<?php include("header.php"); ?>

<table class='table' style='margin-top:30px'>
	
	<tr><td class='left'>
			<img src='<?=$img1?>' style='width:250px;height:200px'/>
			<h2 style='padding:0 15px'><?=$row1->longname?></h2>
			<p><a href='<?=amazon_link($row1->asin,$row1->name)?>' target='_blank' class='btn btn-warning btn-lg' style='font-size:25px; padding: 5px 40px'><?=$price1?></a></p>
			<p><span class='label label-default'>Amazon Sales Rank #<?=$amz1->Items->Item->SalesRank?></span></p>
			<p><span class='label label-default'><?=date("d M Y",strtotime($row1->announced))?></span></p>
			<?=admin("intro",$row1->camera_id)?>
		</td>
		<td>
			<img src='<?=$img2?>' style='width:250px;height:200px'/>
			<h2 style='padding:0 15px'><?=$row2->longname?></h2>
			<p><a href='<?=amazon_link($row2->asin,$row2->name)?>' target='_blank' class='btn btn-warning btn-lg' style='font-size:25px; padding: 5px 40px'><?=$price2?></a></p>
			<p><span class='label label-default'>Amazon Sales Rank #<?=$amz2->Items->Item->SalesRank?></span></p>
			<p><span class='label label-default'><?=date("d M Y",strtotime($row2->announced))?></span></p>
			<?=admin("intro",$row2->camera_id)?>		
		</td>
	</tr>

	<tr><td colspan='2' style='text-align:center'>
		<h4 class='subheadline'><a data-toggle="collapse" href="#specifications">Specifications - Features</a></h4>
		<div id='specifications' class='collapse in'><?=show_specs($row1->camera_id,$row2->camera_id)?>
		<?=admin("specs1",$row1->camera_id,TRUE)?><?=admin("specs2",$row2->camera_id,TRUE)?></div>
	</td></tr>

	<?php
		$html1 = parse($row1->camera_id,"expert_reviews",$row1->expert_reviews,$count1);
		$html2 = parse($row2->camera_id,"expert_reviews",$row2->expert_reviews,$count2);
	?>
	<tr><td colspan='2' style='text-align:center'>
		<h4 class='subheadline'><span class="label label-count"><?=$count1?></span><a data-toggle="collapse" href="#expert-review">Expert Reviews</a><span class="label label-count"><?=$count2?></span></h4>
		<div id='expert-review' class='collapse out'>
			<table class='table'>
			<tr><td class='left'><?=$html1?><?=admin("expert_reviews",$row1->camera_id)?></td>
			<td class='right'><?=$html2?><?=admin("expert_reviews",$row2->camera_id)?></td></tr>
			</table>
		</div>
	</td></tr>

	<?php
		$html1 = parse($row1->camera_id,"customer_reviews",$review1,$count1);
		$html2 = parse($row2->camera_id,"customer_reviews",$review2,$count2);
	?>
	<tr><td colspan='2' style='text-align:center'>
		<h4 class='subheadline'><a data-toggle="collapse" href="#customer-review">Customer Reviews</a></h4>
		<div id='customer-review' class='collapse out'>
			<table class='table'>
			<tr><td class='left'><?=$html1?><?=admin("customer_reviews",$row1->camera_id)?></td>
			<td class='right'><?=$html2?><?=admin("customer_reviews",$row2->camera_id)?></td></tr>
			</table>
		</div>
	</td></tr>

	<?php
		$html1 = parse($row1->camera_id,"video_reviews",$row1->video_reviews,$count1);
		$html2 = parse($row2->camera_id,"video_reviews",$row2->video_reviews,$count2,FALSE);
	?>
	<tr><td colspan='2' style='text-align:center'>
		<h4 class='subheadline'><span class="label label-count"><?=$count1?></span><a data-toggle="collapse" href="#video-review">Video Reviews</a><span class="label label-count"><?=$count2?></span></h4>
		<div id='video-review' class='collapse out'>
			<table class='table'>
			<tr><td class='left'><?=$html1?><?=admin("video_reviews",$row1->camera_id)?></td>
			<td class='right'><?=$html2?><?=admin("video_reviews",$row2->camera_id)?></td></tr>
			</table>
		</div>
	</td></tr>

	<tr><td colspan='2' style='text-align:center'>
		<h4 class='subheadline'><a data-toggle="collapse" href="#sample-photo">Sample Photos</a></h4>
		<div id='sample-photo' class='collapse out'>
			<table class='table'>
			<tr><td class='left'><?=parse($row1->camera_id,"sample_images",$row1->sample_images,$count1)?><?=admin("sample_images",$row1->camera_id)?></td>
			<td class='right'><?=parse($row2->camera_id,"sample_images",$row2->sample_images,$count2,FALSE)?><?=admin("sample_images",$row2->camera_id)?></td></tr>
			</table>
		</div>
	</td></tr>

	<?php
		$html1 = parse($row1->camera_id,"sample_videos",$row1->sample_videos,$count1);
		$html2 = parse($row2->camera_id,"sample_videos",$row2->sample_videos,$count2,FALSE);
	?>
	<tr><td colspan='2' style='text-align:center'>
		<h4 class='subheadline'><span class="label label-count"><?=$count1?></span><a data-toggle="collapse" href="#sample-video">Sample Videos</a><span class="label label-count"><?=$count2?></span></h4>
		<div id='sample-video' class='collapse out'>
			<table class='table'>
			<tr><td class='left'><?=$html1?><?=admin("sample_videos",$row1->camera_id)?></td>
			<td class='right'><?=$html2?><?=admin("sample_videos",$row2->camera_id)?></td></tr>
			</table>
		</div>
	</td></tr>

	<?php
		$html1 = parse($row1->camera_id,"learning_material",$row1->learning_material,$count1);
		$html2 = parse($row2->camera_id,"learning_material",$row2->learning_material,$count2,FALSE);
	?>
	<tr><td colspan='2' style='text-align:center'>
		<h4 class='subheadline'><span class="label label-count"><?=$count1?></span><a data-toggle="collapse" href="#learning">Learning Materials</a><span class="label label-count"><?=$count2?></span></h4>
		<div id='learning' class='collapse out'>
			<table class='table'>
			<tr><td class='left'><?=$html1?><?=admin("learning_material",$row1->camera_id)?></td>
			<td class='right'><?=$html2?><?=admin("learning_material",$row2->camera_id)?></td></tr>
			</table>
		</div>
	</td></tr>	

	<?php
		$html1 = parse($row1->camera_id,"accessories",$row1->accessories,$count1);
		$html2 = parse($row2->camera_id,"accessories",$row2->accessories,$count2,FALSE);
	?>
	<tr><td colspan='2' style='text-align:center'>
		<h4 class='subheadline'><span class="label label-count"><?=$count1?></span><a data-toggle="collapse" href="#accesories">Accessories</a><span class="label label-count"><?=$count2?></span></h4>
		<div id='accesories' class='collapse out'>
			<table class='table'>
			<tr><td class='left'><?=$html1?><?=admin("accessories",$row1->camera_id)?></td>
			<td class='right'><?=$html2?><?=admin("accessories",$row2->camera_id)?></td></tr>
			</table>
		</div>
	</td></tr>	

	<tr><td colspan='2' style='text-align:center'>
		<h4 class='subheadline'><a data-toggle="collapse" href="#price">Price Comparison</a></h4>
		<div id='price' class='collapse in'>
			<div id="placeholder" style="width:600px;height:300px;margin:10px auto"></div>
			<p>
				<a href='<?=amazon_link($row1->asin,$row1->name)?>' target='_blank' class='btn btn-success btn-shopping'><i class='glyphicon glyphicon-shopping-cart'></i> <?=$price1?></a> 
				Vs
				<a href='<?=amazon_link($row2->asin,$row2->name)?>' target='_blank' class='btn btn-success btn-shopping'><i class='glyphicon glyphicon-shopping-cart'></i> <?=$price2?></a>
			</p>
		</div>
	</td></tr>	

	<tr><td colspan='2' style='text-align:center'>
		<h4 class='subheadline'><a data-toggle="collapse" href="#summary">Summary</a></h4>
		<div id='summary' class='collapse in'>
			<div id='summarycontent'>
			<?=get_summary($row1->camera_id,$row2->camera_id)?>
			<br/><?=admin_summary($row1->camera_id,$row2->camera_id)?>
			</div>
		</div>
	</td></tr>	

	<tr><td colspan='2' style='text-align:center'>
		<h4 class='subheadline'><a data-toggle="collapse" href="#disqus">Discussion</a></h4>
		<div id='disqus' class='collapse in'>
			<div id='summarycontent'>				
		        <div id="disqus_thread"></div>
		        
		        <script type="text/javascript">
		            /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
		            var disqus_shortname = 'comparecamera'; // required: replace example with your forum shortname   
		    		var disqus_identifier = '<?=$VERSUS_SLUG?>';
		    		//var disqus_url = 'http://tech-in.org/submitted_ideas/idea.php?id=<?php echo $idea_id; ?>';

		            /* * * DON'T EDIT BELOW THIS LINE * * */
		            (function() {
		                var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
		                dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
		                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
		            })();
		        </script>
		        <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
		        <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
       
			</div>
		</div>
	</td></tr>	

</table>

<div id='flickr-modal' class='modal fade noborder'><div class='modal-dialog'><div class="modal-content"></div></div></div>
<div id='suggestion' class='modal fade noborder'><div class='modal-dialog'><div class="modal-content" style='padding:10px'></div></div></div>
<div id='cmiiw' class='modal fade noborder'><div class='modal-dialog'><div class="modal-content" style='padding:10px'></div></div></div>

<script type = "text/javascript">

$("a.flickr").click(function() 
{   
    var owner = $(this).attr('fowner');
    var url = $(this).attr('fz');
    var name = $(this).attr('fownername');

    $.ajax({
        cache: false,
        type: 'POST',
        url: '<?=get_bloginfo("template_directory")?>/flickr.php',
        data: 'url='+url+'&owner='+owner+'&ownername='+name,
        success: function(data) 
        {
            $('#flickr-modal').show();
            $('#flickr-modal .modal-content').show().html(data);
        }
    });
});

$("a.suggest").click(function() 
{   
    var field = $(this).attr('field');
    var camid = $(this).attr('camid');

    $('#suggestion .modal-content').show().html('<center><img src="<?=get_bloginfo('template_directory')?>/loader.gif"/></center>');

    $.ajax({
        cache: false,
        type: 'POST',
        url: '<?=get_bloginfo("template_directory")?>/form-suggest.php',
        data: 'field='+field+'&camid='+camid,
        success: function(data) 
        {
            $('#suggestion').show();
            $('#suggestion .modal-content').show().html(data);
        }
    });
});

$("a.incorrect").click(function() 
{   
    var field = $(this).attr('field');
    var camid = $(this).attr('camid');

    $('#cmiiw .modal-content').show().html('<center><img src="<?=get_bloginfo('template_directory')?>/loader.gif"/></center>');

    $.ajax({
        cache: false,
        type: 'POST',
        url: '<?=get_bloginfo("template_directory")?>/form-cmiiw.php',
        data: 'field='+field+'&camid='+camid,
        success: function(data) 
        {
            $('#cmiiw').show();
            $('#cmiiw .modal-content').show().html(data);
        }
    });
});

$(function () {

   //succes - data loaded, now use plot:
    var plotarea = $("#placeholder");
    var data1 = <?=get_price_history($row1->price_id)?>;
    var data2 = <?=get_price_history($row2->price_id)?>;

    $.plot($("#placeholder"), [ { label:'<?=$row1->name?>',data:data1},{label:'<?=$row2->name?>',data:data2} ], {
        xaxis: {
            mode: "time",
            timeformat: "%b %y",
            TickSize: [1, "month"],
        },
        lines: { show: true, steps: false ,},
        points: {"show": "true"},
        grid: { hoverable: true, clickable: true },
    });       

    // add some hovering logic to each point...
    var previousPoint = null;
    $("#placeholder").bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));

            if (item) {
                if (previousPoint != item.datapoint) {
                    previousPoint = item.datapoint;
                    $("#tooltip").remove();
                    var x = item.datapoint[0].toFixed(2), y = item.datapoint[1].toFixed(2);
                    showTooltip(item.pageX, item.pageY, "$" + y);
                }
            }
            else {
                $("#tooltip").remove();
                previousPoint = null;            
            }

    });

    // show the tooltip
    function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y - 35,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(0);
    }

});

$(".table-specs td.spec").hover(function() {

	$(this).find('.incorrect').removeClass("hide")
	},function() {
	$(this).find('.incorrect').addClass("hide")
});
</script> 

<?php
//insert stat

$m = date("Ym");
$statunique = $m.".".$VERSUS_SLUG;
$sql = "INSERT INTO compare.stat (uniqueid,month,cam1,cam2,slug,count) VALUES ('$statunique','$m','$row1->name','$row2->name','$VERSUS_SLUG','1')
	ON DUPLICATE KEY UPDATE count=count+1";
$wpdb->query($sql);

$sql = "INSERT INTO compare.lastcompare (slug,cam1,cam2,added) VALUES('$VERSUS_SLUG','$row1->name','$row2->name',NOW())";
$wpdb->query($sql);

?>


<?php include("footer.php"); ?>