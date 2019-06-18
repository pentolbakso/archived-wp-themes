<?php
if ($_POST["ajax"]==1)
	require_once("../../../wp-load.php");

if (session_id() == "") session_start();

//sleep(2);

global $wpdb;
$post_per_page = 5;

if (!isset($_SESSION["user_id"]))
	$leftjoin = "LEFT JOIN lontong.anon_vote thevote ON thevote.postid = post.id AND thevote.ip='".$_SERVER['REMOTE_ADDR']."'";
else
	$leftjoin = "LEFT JOIN lontong.vote thevote ON thevote.postid = post.id AND thevote.userid=".$_SESSION["user_id"];

if ($_SESSION["mode"]=="new")
	$order = "ORDER BY post.added DESC";
else if ($_SESSION["mode"]=="top")
	$order = "ORDER BY totalpoin DESC,c.num_of_comment DESC,post.added DESC";
else if ($_SESSION["mode"]=="random")
	$order = "ORDER BY rand()";
else //hot
	$order = "ORDER BY todaypoin DESC,stat.views DESC,post.added DESC";

if (isset($_POST["pg"]))
	$limit = "LIMIT ".($_POST["pg"]*$post_per_page).",$post_per_page";
else
	$limit = "LIMIT $post_per_page";

if (isset($_SESSION["channel"]) && $_SESSION["channel"]!="all")
	$where = "WHERE post.channel='".$_SESSION["channel"]."'";
else
	$where = "";

$sql = "SELECT post.*,(post.like - post.dislike) as totalpoin,users.displayname,c.num_of_comment,thevote.vote,stat.views,stat.likes-stat.dislikes as todaypoin 
	FROM lontong.post 
	LEFT JOIN lontong.users ON post.userid = users.id
	LEFT JOIN (SELECT postid,count(*) as num_of_comment FROM lontong.comment GROUP BY postid) c ON c.postid=post.id
	$leftjoin
	LEFT JOIN lontong.stat ON stat.postid = post.id AND thedate=DATE(NOW())
	$where
	$order 
	$limit";
//echo "<!-- $sql -->";
$res = $wpdb->get_results($sql);
foreach ($res as $row)
{
	$embed = "";
	if ($row->animated==0)
		$img = get_bloginfo("url")."/img/".$row->image;
	else
		$img = get_bloginfo("url")."/img/static-".$row->image;

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
			<?php if ($_SESSION['is_mobile']!=1) { ?>
			 <p class='meta'><?=date("d M Y",strtotime($row->added))?> oleh <a href="<?=get_bloginfo('url')."/u/$row->userid/"?>"><?=$user?></a> di <a href="<?=get_bloginfo('url')."/?channel=$row->channel"?>"><?=ucfirst($row->channel)?></a></p>
			<?php } else { echo "<p></p>"; } ?>
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
			<div class="imageWrapper">

			<?php if (isset($_SESSION["user_id"]) || $row->channel!="nsfw")	{ ?>	
				<a href='<?=post_url($row->id,$row->title)?>'><img src='<?=$img?>' class='img-responsive' style='width:100%'/></a>
				<?=(($row->animated==1)?'<h3>GIF : klik untuk play</h3>':'')?>
			<?php } else { ?>
				<a href='<?=get_bloginfo('url')?>/login/' target='_blank'><img src='<?=get_bloginfo('template_directory')?>/nsfw.png' class='img-responsive' style='width:100%'/></a>			
			<?php } ?>

			</div>
			<?php } else { ?>
			<div class="videoWrapper"><?=$embed?></div>
			<?php } ?>
		</div></div>

		<div class='col-md-5'><div class='right-content'>

			<?php if ($_SESSION['is_mobile']==1) { ?>
			<div class="row"><div class="col-md-6 col-xs-6">
				<div class="votebox" style="padding-left:10px">
					<?=$vote_button_up?>
					<?=$vote_button_down?>
					<button type="button" class="votecount"><?=$poin?></button>
				</div>
			</div>
			<div class="col-md-6 col-xs-6">
				<!-- AddThis Button BEGIN -->
				<div class="addthis_toolbox addthis_default_style addthis_32x32_style"
					addthis:url="<?=post_url($row->id,$row->title)?>"
			        addthis:title="<?=$row->title?>"
			        addthis:description="Klik untuk melihat gambar dan berkomentar. LontongNET tempatnya obat bosan">
				<a class="addthis_button_facebook"></a>
				<a class="addthis_button_twitter"></a>
				<a class="addthis_button_google_plusone_share"></a>
				</div>			
			</div></div>
			<?php } else { ?>

			<div class="row"><div class="col-md-12">
				<!-- AddThis Button BEGIN -->
				<div class="addthis_toolbox addthis_default_style addthis_32x32_style"
					addthis:url="<?=post_url($row->id,$row->title)?>"
			        addthis:title="<?=$row->title?>"
			        addthis:description="Klik untuk melihat gambar dan berkomentar. LontongNET tempatnya obat bosan">
				<a class="addthis_button_facebook"></a>
				<a class="addthis_button_twitter"></a>				
				<a class="addthis_button_google_plusone_share"></a>
				<a class="addthis_button_compact"></a>
				</div>
				<!-- AddThis Button END -->				
			</div></div>

			<?php } ?>


			<?php if ($_SESSION['is_mobile']!=1) { ?>
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

$sql = "SELECT comment.*,users.displayname,users.avatar,thelikes.id as liked FROM lontong.comment 
	LEFT JOIN lontong.users ON users.id = comment.userid
	$leftjoin
	WHERE postid=$row->id AND approved=1 AND comment.commentid=0 ORDER BY poin DESC LIMIT 3";

$resComment = $wpdb->get_results($sql);

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

}
?>
      </table>

      <div class="comment-count">
		<i class='fa fa-comment'></i> <a href='<?=post_url($row->id,$row->title)?>'><?=($row->num_of_comment==null)?0:$row->num_of_comment?> Komentar</a>
	  </div>

		<?php } //end if mobile ?>
		</div>
	</div> <!-- end col-md-5 -->
</div> <!-- end row -->
</div> <!-- end post -->
<?php
}	//end for loop post
?>

