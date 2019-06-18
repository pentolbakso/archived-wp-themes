<?php
if (session_id() == "") session_start();

global $wpdb;
$postid = get_query_var("postid");

//insert stat
$sql = "INSERT INTO lontong.stat (postid,thedate,views) VALUES($postid,NOW(),1) 
	ON DUPLICATE KEY UPDATE views=views+1";
$wpdb->query($sql);

if (isset($_SESSION["user_id"]))
	$leftjoin = "LEFT JOIN lontong.vote thevote ON thevote.postid = post.id AND thevote.userid=".$_SESSION["user_id"];
else
	$leftjoin = "LEFT JOIN lontong.anon_vote thevote ON thevote.postid = post.id AND thevote.ip='".$_SERVER['REMOTE_ADDR']."'";

$sql = "SELECT post.*,users.displayname,c.total,s.views,thevote.vote FROM lontong.post 
	LEFT JOIN lontong.users ON post.userid = users.id
	LEFT JOIN (SELECT postid,count(*) as total FROM lontong.comment GROUP BY postid) c ON c.postid=post.id
	LEFT JOIN (SELECT postid,sum(views) as views FROM lontong.stat GROUP BY postid) s ON s.postid=post.id	
	$leftjoin
	WHERE post.id = $postid";
$res = $wpdb->get_results($sql);
//echo "<!-- $sql -->";

$row = $res[0];

global $the_title,$the_meta;

$the_title = $row->title;

$the_meta = "
<link rel='canonical' href='".post_url($row->id,$row->title)."' />
<meta name='description' content='Klik untuk melihat gambar/video berjudul $row->title, berikan voting atau komentar ...' />
<meta name='twitter:card' content='photo' />
<meta name='twitter:site' content='@lontongNET' />
<meta name='twitter:image' content='".get_bloginfo("url")."/img/".$row->image."' />
<meta property='og:title' content='$row->title' />
<meta property='og:site_name' content='LontongNET' />
<meta property='og:url' content='".post_url($row->id,$row->title)."' />
<meta property='og:description' content='Klik untuk melihat gambar/video berjudul $row->title, berikan voting atau komentar ...' />
<meta property='og:type' content='article' />
<meta property='og:image' content='".get_bloginfo("url")."/img/".$row->image."' />
<meta property='fb:app_id' content='225944537596416' />";
?>


<?php get_header(); ?>

<div class='col-md-12'>

<?php
foreach ($res as $row)
{
	$embed = "";
	$img = get_bloginfo("url")."/img/".$row->image;

	if ($row->youtube!="")
		$embed = ytembed($row->youtube);

	if ($row->userid==0) $user = "Anonim";
	else $user = $row->displayname;

	$poin = $row->like - $row->dislike;

	if (strlen($row->vote)==0) {
		$vote_button_up = "<button type='button' class='voteup voting' onclick=\"sendVote(this,$poin,$row->id,'up')\"><i class='fa fa-thumbs-o-up'></i></button>";
		$vote_button_down = "<button type='button' class='votedown voting' onclick=\"sendVote(this,$poin,$row->id,'down')\"><i class='fa fa-thumbs-o-down'></i></button>";
	} else {
		$upstyle = $downstyle = "";
		if ($row->vote>0)
			$upstyle = "style='background-color:#74DF00'";
		else
			$downstyle = "style='background-color:#DF0101'";

		$vote_button_up = "<button type='button' class='voting' disabled='disabled' $upstyle><i class='fa fa-thumbs-o-up'></i></button>";
		$vote_button_down = "<button type='button' class='voting' disabled='disabled' $downstyle><i class='fa fa-thumbs-o-down'></i></button>";
	}

?>
<div class='post'>
	<div class='row'>	
		<div class='col-md-7'><div class="left-content">
			<div class='row'>
      <div class='<?=(($_SESSION['is_mobile']==1)?'col-md-12':'col-md-9')?>'><h2><a href='<?=post_url($row->id,$row->title)?>'><?=$row->title?></a></h2>
			 <p class='meta'><?=date("d M Y",strtotime($row->added))?> oleh <a href="<?=get_bloginfo('url')."/u/$row->userid/"?>"><?=$user?></a> di <a href="<?=get_bloginfo('url')."/?channel=$row->channel"?>"><?=ucfirst($row->channel)?></a></p>
			</div>
			<?php if ($_SESSION['is_mobile']!=1) { ?>
			<div class='col-md-3'><div class="votebox" stye="padding-right:10px; text-align:right">
				<?=$vote_button_up?>
				<?=$vote_button_down?>
				<button type="button" class="votecount"><?=$poin?></button>
			</div></div>
			<?php } ?>
			</div>

			<?php if ($embed=="") { ?>

			<?php if (isset($_SESSION["user_id"]) || $row->channel!="nsfw")	{ ?>	
				<div><a href='<?=$img?>' title='Klik untuk melihat full image'><img src='<?=$img?>' class='img-responsive' style='width:100%'/></a></div>
			<?php } else { ?>
				<a href='<?=get_bloginfo('url')?>/login/' target='_blank'><img src='<?=get_bloginfo('template_directory')?>/nsfw.png' class='img-responsive' style='width:100%'/></a>			
			<?php } ?>

			<?php } else { ?>
			<div class="videoWrapper"><?=$embed?></div>
			<?php } ?>

<?php
if ($_SESSION["is_mobile"]!=1) {
	if (strlen($row->spoiler)>0) $spoiler = "<a href='javascript:showSpoiler()'>Klik untuk spoiler</a>";
	else $spoiler = "<span class='text-muted'>Tidak ada spoiler</span>";

	if (strlen($row->tags)>0) $tags = $row->tags;
	else $tags = "<span class='text-muted'>n/a</span>";

	if (strlen($row->credit)>0) $credit = "via ".$row->credit;
	else $credit = "<span class='text-muted'>n/a</span>";
?>

			 <div class='meta-bottom'>
			 	<ul class="list-unstyled">
			 	<li><i class="fa fa-bar-chart-o"></i> <?=$row->views?> view</li>
			 	<li style="padding-right:10px"><i class="fa fa-eye"></i> <?=$spoiler?>
<?php
if (strlen($row->spoiler)>0)
	echo "<p class='well' id='thespoiler' style='display:none'>$row->spoiler</p>";
?>
			 	</li>
			 	<li><i class="fa fa-tags"></i> <?=$tags?></li>
			 	<li><i class="fa fa-info-circle"></i> <?=$credit?></li>
			 	</ul>
			 </div>
<?php } ?>
		</div></div>

		<div class='col-md-5'><div class='right-content'>

<?php
/* NEXT FEATURE */

if ($_SESSION["mode"]=="new")
	$order = "ORDER BY p.added DESC";
else if ($_SESSION["mode"]=="top")
	$order = "ORDER BY totalpoin DESC,c.num_of_comment DESC,p.added DESC";
else if ($_SESSION["mode"]=="random")
	$order = "ORDER BY rand()";
else //hot
	$order = "ORDER BY todaypoin DESC,stat.views DESC,p.added DESC";

if (isset($_SESSION["channel"]) && $_SESSION["channel"]!="all")
	$where = "WHERE p.channel='".$_SESSION["channel"]."'";
else
	$where = "";

$sql = "SELECT * FROM (	
SELECT @rownum:=@rownum+1 AS posisi,s1.id
FROM (SELECT p.* ,(p.like - p.dislike) AS totalpoin,c.num_of_comment,stat.views,stat.likes-stat.dislikes AS todaypoin FROM lontong.post p
	LEFT JOIN (SELECT postid,COUNT(*) AS num_of_comment FROM lontong.comment GROUP BY postid) c ON c.postid=p.id
	LEFT JOIN lontong.stat ON stat.postid = p.id AND thedate=DATE(NOW())
	$where	
	$order) s1, (SELECT @rownum := 0) init
) s2
WHERE id=$postid";

//echo "<!-- $sql -->";
$r = $wpdb->get_row($sql);

$pos = $r->posisi;
//echo "<!-- posisi post:$row->id -> $pos -->";
$sql = "SELECT p.id,p.title,(p.like - p.dislike) as totalpoin,c.num_of_comment,stat.views,stat.likes-stat.dislikes AS todaypoin
	FROM lontong.post p
	LEFT JOIN (SELECT postid,count(*) as num_of_comment FROM lontong.comment GROUP BY postid) c ON c.postid=p.id
	LEFT JOIN lontong.stat ON stat.postid = p.id AND thedate=DATE(NOW()) 
	$where
	$order
	LIMIT $pos, 1";
//echo "<!-- $sql -->";
$r = $wpdb->get_row($sql);
if ($r)
	$next = post_url($r->id,$r->title);
else
	$next = "javascript:void(0)";

?>			


			<?php if ($_SESSION['is_mobile']==1) { ?>
			<div class="row"><div class="col-md-6 col-xs-6">
				<div class="votebox" style="padding-left:10px">
					<?=$vote_button_up?>
					<?=$vote_button_down?>
					<button type="button" class="votecount"><?=$poin?></button>
				</div>
			</div>
			<div class="col-md-6 col-xs-6">
				<div class="pull-right">
				<a href="<?=$next?>" class="btn btn-sm btn-next">Next <i class="fa fa-caret-right"></i></a>
				</div>
			</div></div>
			<div class="row"><div class="col-md-12">
				<!-- AddThis Button BEGIN -->
				<div class="addthis_toolbox addthis_default_style addthis_32x32_style" style="padding-left:10px; margin-top:5px">
				<a class="addthis_button_facebook"></a>
				<a class="addthis_button_twitter"></a>
				<a class="addthis_button_google_plusone_share"></a>
				</div>			
			</div></div>

			<?php } else { ?>

			<div class="row"><div class="col-md-9">
				<!-- AddThis Button BEGIN -->
				<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
				<a class="addthis_button_facebook"></a>
				<a class="addthis_button_twitter"></a>
				<a class="addthis_button_google_plusone_share"></a>
				<a class="addthis_counter addthis_bubble_style"></a>
				</div>
				<!-- AddThis Button END -->				
			</div><div class="col-md-3">
				<div class="pull-right"><a href="<?=$next?>" class="btn btn-sm btn-next">Next <i class="fa fa-caret-right"></i></a></div>
			</div></div>

			<?php } ?>

			<form action="#" class="commentForm" onsubmit="return sendComment(this)" style="margin-top:10px"><div class="input-group">
				<input type="text" class="form-control input-sm" name="comment" placeholder="Tulis komentar..">
				<input type="hidden" name="postid" value="<?=$row->id?>"/>
				<span class="input-group-btn"><button class="btn btn-default btn-sm" type="submit">Kirim</button></span>
			</div></form>

			<table class="table table-condensed tableComment">
<?php
if (!isset($_SESSION["user_id"]))
	$leftjoin = "LEFT JOIN lontong.anon_likes thelikes ON thelikes.commentid = comment.id AND thelikes.ip='".$_SERVER['REMOTE_ADDR']."'";
else
	$leftjoin = "LEFT JOIN lontong.likes thelikes ON thelikes.commentid = comment.id AND thelikes.userid=".$_SESSION["user_id"];

$resComment = $wpdb->get_results("SELECT comment.*,users.displayname,users.avatar,thelikes.id as liked FROM lontong.comment 
	LEFT JOIN lontong.users ON users.id = comment.userid
	$leftjoin
	WHERE postid=$row->id AND approved=1 ORDER BY commentid ASC,added DESC");

$threads = array();
foreach($resComment as $rowC) {
  if($rowC->commentid === '0') {
    $threads[$rowC->id] = array(
      'comment' => $rowC,
      'replies' => array()
    );
  } else {
    $threads[$rowC->commentid]['replies'][] = $rowC;
  }
}

//print_r($threads);

foreach($threads as $t) {

	if ($t['comment']->userid==0 || strlen($t['comment']->avatar)==0)
		$avatarimg = get_bloginfo("template_directory")."/anon.jpg";
	else if (stripos($t['comment']->avatar, "facebook.com")!==FALSE)
		$avatarimg = $t['comment']->avatar;
	else
		$avatarimg = get_bloginfo("url")."/avatar/".$t['comment']->avatar;

	if ($t['comment']->userid!=0) $user = $t['comment']->displayname;
	else $user = "Anonim";

	$c = count($t['replies']);

	if ($t['comment']->poin>0) $poin = "(".$t['comment']->poin.")";
	else $poin = "";

	if ($t['comment']->liked) $likestr = "<a href='javascript:void(0)' style='color:#666'><i class='fa fa-thumbs-o-up'></i> liked $poin</a>";
	else $likestr = "<a href='javascript:void(0)' onclick='likeComment(this,".$t['comment']->id.",".$t['comment']->poin.")'><i class='fa fa-thumbs-o-up'></i> like $poin</a>";

	echo "<tr>
		<td style='width:1px'><img src='$avatarimg' class='avatar'/></td>
		<td><div class='comment'>
			<div class='topinfo'><a href='".get_bloginfo('url')."/u/".$t['comment']->userid."'>$user</a> - <span>".elapsed(strtotime($t['comment']->added))."</span></div>
			<div class='comment-content'>".$t['comment']->comment."</div>
			<div class='bottominfo'>$likestr
				<a href='javascript:void(0)' onclick='createReply(this,$row->id,".$t['comment']->id.")' title='Balas komentar ini'><i class='fa fa-reply'></i></a>
				<a href='javascript:void(0)' onclick='sendReport(".$t['comment']->id.")' title='Laporkan komentar ini!'><i class='fa fa-flag'></i></a>
			</div>
			<div class='replybox'></div>
			</div>
		</td>		
		</tr>";

	foreach($t['replies'] as $r)
	{
		if ($r->userid==0 || strlen($r->avatar)==0)
			$avatarimg = get_bloginfo("template_directory")."/anon.jpg";
		else if (stripos($r->avatar, "facebook.com")!==FALSE)
			$avatarimg = $r->avatar;
		else
			$avatarimg = get_bloginfo("url")."/avatar/".$r->avatar;

		if ($r->displayname=="") $r->displayname = "Anonim";

		if ($r->poin>0) $poin = "(".$r->poin.")";
		else $poin = "";

		if ($r->liked) $likestr = "<a href='javascript:void(0)' style='color:#666'><i class='fa fa-thumbs-o-up'></i> liked $poin</a>";
		else $likestr = "<a href='javascript:void(0)' onclick='likeComment(this,".$r->id.",".$r->poin.")'><i class='fa fa-thumbs-o-up'></i> like $poin</a>";

	    $content = "<tr><td></td>
	      <td><div class='comment'>
	      <img src='$avatarimg' class='avatar' style='float:left;margin:3px 10px 10px 0'/>
	      <div class='topinfo'><a href='".get_bloginfo('url')."/u/$r->userid/'>$r->displayname</a> - <span>".elapsed(strtotime($r->added))."</span></div>
	      <div class='comment-content'>".$r->comment."</div>
		  <div class='bottominfo'>$likestr
			<a href='javascript:void(0)' onclick='sendReport(".$r->id.")'><i class='fa fa-flag' title='Laporkan komentar ini!'></i></a>
		  </div>
	      <div class='clearfix'></div>
	      </div>
	      </td></tr>";

	     echo $content;
	}
}
?>
      </table>

		</div>
	</div> <!-- end col-md-5 -->
</div> <!-- end row -->
</div> <!-- end post -->
<?php
}	//end for loop post
?>

</div>

<script>
function showSpoiler() {
	$("#thespoiler").toggle();
}
</script>

<?php if ($_SESSION["is_mobile"]==0) { ?>
<script type="text/javascript">
  $(document).ready(function() {
   
   var leftHeight = $(".left-content").height();
   var rightHeight = $(".right-content").height();

   if (leftHeight < rightHeight)
   	$(".left-content").css('height',rightHeight+50);

  });  
</script>
<?php } ?>

<?php get_footer(); ?>