<?php
/*
Template Name: User
*/
global $wpdb;

$userid = get_query_var("userid");
global $the_title,$the_meta;

if ($userid>0) {

  $row = $wpdb->get_row("SELECT * FROM lontong.users WHERE id=$userid");
  if ($wpdb->num_rows==0) {
  	//redirect..
  	header("Location: ".get_bloginfo("url"));
  	return;
  }

  $the_title = "Profil ".$row->displayname;
  if (stripos($row->avatar, "http")!==FALSE)
    $the_photo = $row->avatar;
  else
    $the_photo = get_bloginfo('url')."/avatar/".$row->avatar;

} else {

  $the_title = "Profil Anonim ??";
  $the_photo = get_bloginfo('template_directory')."/anon.jpg";

}
?>

<?php get_header(); ?>

<div class='col-md-8'><div class='post well'>

<?php if ($userid > 0) { ?>
<h3><i class="fa fa-user"></i> <?=$row->displayname?></h3>

<div class="row">
  <div class="col-md-3"><img src="<?=$the_photo?>" class="img-responsive "/></div>
  <div class="col-md-9">
  	<dl>
  		<dt>Bergabung sejak</dt>
  		<dd><?=date("d M Y",strtotime($row->joined))?> (<em><?=elapsed(strtotime($row->joined))?></em>)</dd>

  		<dt>Tentang <?=$row->displayname?></dt>
  		<dd><?=($row->bio!="")?$row->bio:"<em>belum diisi</em>"?></dd>
      <!--
  		<dt>Total poin Karma</dt>
  		<dd><?=$row->karma?></dd>
      -->

  		<dt>Terakhir login</dt>
  		<dd><?=elapsed(strtotime($row->lastlogin))?></dd>

  	</dl>
  </div>
</div>
<?php } else { ?>

<h3><i class="fa fa-user"></i> Anonim</h3>

<div class="row">
  <div class="col-md-3"><img src="<?=get_bloginfo("template_directory")."/anon.jpg"?>" class="img-responsive "/></div>
  <div class="col-md-9">
    Anonim adalah member spesial LontongNET ! Bebas, tidak terdeteksi dan bergerak lincah seperti ninja.
    Tidak jelas identitasnya tapi mempunyai satu tujuan, HAVING FUN .. Yeah!
  </div>
</div>

<?php } ?>
 
<div class="row" id="userstuff">
  <div class="col-md-6">
  	<h4>Gambar/Video yg diupload</h4>
  	<ul class="list-unstyled">
<?php
$resUpload = $wpdb->get_results("SELECT post.*,c.num_of_comment,`like`-dislike as poin FROM lontong.post 
	LEFT JOIN (SELECT postid,count(*) as num_of_comment FROM lontong.comment GROUP BY postid) c ON c.postid=post.id
	WHERE userid=$userid ORDER BY poin DESC");
foreach($resUpload as $rowU)
	echo "<li><a href='".post_url($rowU->id,$rowU->title)."'>$rowU->title</a> <span>$rowU->poin poin</span> <span><i class='fa fa-comment'></i> $rowU->num_of_comment</span></li>";
?>  	
  	</ul>
  </div>
  <div class="col-md-6">
  	<h4>10 Komentar terakhir</h4>
  	<ul class="list-unstyled">
<?php
$resComment = $wpdb->get_results("SELECT comment.postid,comment,comment.added,post.title FROM lontong.comment 
	LEFT JOIN lontong.post ON post.id = comment.postid
	WHERE comment.userid=$userid ORDER BY comment.added DESC LIMIT 10");
foreach($resComment as $rowC)
	echo "<li>$rowC->comment <span><a href='".post_url($rowC->postid,$rowC->title)."'>link</a></span> <span>".date("d M",strtotime($rowC->added))."</span></li>";
?>  	
  	</ul>  	
  </div>
</div>

</div></div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>