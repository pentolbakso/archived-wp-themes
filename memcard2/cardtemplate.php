<?php
/* 
Template Name: CardTemplate 
*/
?>


<?php
include_once("myfunctions.php");
global $wpdb;

$id= get_query_var('cid');
$sql = "SELECT * FROM memorycard.mem_card WHERE slug='".$id."'";
$row = $wpdb->get_row($sql);

global $the_title,$the_noindex,$the_canonical,$the_description;
$the_title = $row->name. " Review";

$specs = str_replace("\'", "'", $row->specs);

$permalink = "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
?>

<?php get_header(); ?>

<h2><?=$row->name?> Review</h2>

<div id="social">
  <div class='fb-like'><fb:like href="<?=$permalink?>" send="false" layout="box_count" width="450" show_faces="false"></fb:like></div>
  <div class='twitter'><a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical" data-via="">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
</div>

<p>Read customer and video reviews on <?=$row->brand?>'s <?=$row->cap_gb?> GB <?=strtoupper($row->type)?> memory card.
You could also browse other cards that have similar specs or brand. But, if you're looking for memory card capacity, go to <a href='<?=get_bloginfo('url')?>/wpcamera/browse/'>Browse</a> camera page and click on the camera you wish to view.
</p>

<div>
		<img src='<?php echo get_bloginfo('url')."/".$row->img_url; ?>' style='float:left;margin: 0px 10px 10px; width: 200px'/>	
		<?php echo nl2br($specs); ?>
    <div style='clear:both'></div>
</div>

<div class="row" style='margin: 30px 0'>
  <div class="span4 offset4">
  <a href='<?=amazonize($row->amazon_url)?>' target='_blank' class='btn btn-warning btn-large btn-block'><i class='icon icon-hand-right'></i> Check Price Here..</a>
  </div>
</div>
	
<div class="tabbable" style='margin-top:40px'> <!-- Only required for left/right tabs -->
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Similar Cards</a></li>
    <li><a href="#tab2" data-toggle="tab">User Reviews</a></li>
    <li><a href="#tab3" data-toggle="tab">Video Reviews</a></li>
    <li><a href="#tab5" data-toggle="tab">Where to Buy</a></li>
 </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="tab1">

<?php
$sql = "SELECT * FROM memorycard.mem_card WHERE type='".$row->type."' AND cap_gb='".$row->cap_gb."' AND id != ".$row->id;
$res = $wpdb->get_results($sql);
if ($wpdb->num_rows > 0)
{
	echo "<ul class='thumbnails'>";
	foreach($res as $row2)
	{
		echo "<li class='span2'><a href='".get_bloginfo('url')."/card/".$row2->slug."/' class='cardthumbnail'>
      <center><img src='".get_bloginfo('url')."/".$row2->img_url."' title='$row2->name'/></center></a>
      <p style='font-size:smaller;text-align:center'>$row2->brand $row2->cap_gb GB ".strtoupper($row2->type)." with Class $row2->class_rating rating</p>
      </li>";
	}
	echo "</ul>";
}
?>

    </div>
    <div class="tab-pane" id="tab2">
      <div>
<?php
$asin = $row->asin;
if (strlen($asin)==0)
{
    //ambil dari URL aja dah
    $arr = explode("/", $row->amazon_url);
    $c = count($arr);
    //print_r($arr);

    $asin = trim($arr[$c-2]);
    //echo $asin;

    $amazondata = get_amazon($asin);
    if (count($amazondata)>0)
    {
        $review = $amazondata->Items->Item->CustomerReviews->IFrameURL;
        echo "<iframe src='$review' width='100%' scrolling='auto' height='800px' frameborder='0'></iframe>";
    }
    else
    {
        echo "Oops, we have problem when loading the reviews. Meanwhile, read the review <a href='".amazonize($row->amazon_url)."'>here</a>";
    }
}

?>
    </div>
  </div>
    <div class="tab-pane" id="tab3">
      <p>Sorry..no video reviews for this card yet. <a href='#suggestModal' data-toggle="modal">suggest one</a></p>
    </div>
    <div class="tab-pane" id="tab4">
      <p>Card Reader</p>
    </div>
    <div class="tab-pane" id="tab5">
      <p>Where to buy this card at cheap and competitive price. Look for coupon codes and promotion before buying. Here some reputable online stores that sell the product:</p>
      <table class='table'>
        <tr><td><strong>Amazon</strong></td>
          <td style='font-size:12px'>Amazon is one online store that's become synonymous with deep discounts. From apparel and accessories to cameras and computers, Amazon.com is a hands-down leader when it comes to giving customers more for their money.</td>
          <td><a href='<?=amazonize($row->amazon_url)?>'/>Buy</a></td></tr>
        <tr><td><strong>B&H Photo Video</strong></td>
          <td style='font-size:12px'>B&H Photo Video, founded in 1973 and located at 420 Ninth Avenue on the corner of West 34th Street in Manhattan, New York City, is the largest non-chain photo and video equipment store in the United States.</td>
          <td>Buy</td></tr>
        <tr><td><strong>Adorama</strong></td>
          <td style='font-size:12px'>Adorama Camera, Inc is a camera and film equipment store established in 1979 and located in New York City. Adorama also has a significant online retail operation and in 2003 formed a sales alliance with Amazon.com in a deal to broaden its selection of camera products and accessories</td>
          <td>Buy</td></tr>
      </table>
    </div>
  </div>
</div>

	
<?php get_footer(); ?>
