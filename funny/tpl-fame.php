<?php
/*
Template Name: Hall Of Fame
*/
global $wpdb;
?>

<?php get_header(); ?>

<div class='col-md-8'><div class='post well'>

<h3><i class="fa fa-trophy"></i> Hall Of Fame</h3>

<div class="row" id="userstuff">
  <div class="col-md-6">
  	<h4>Top Post</h4>
  	<ul class="list-unstyled">
<?php
$resUpload = $wpdb->get_results("SELECT post.*,c.num_of_comment,`like`-dislike as poin FROM lontong.post 
	LEFT JOIN (SELECT postid,count(*) as num_of_comment FROM lontong.comment GROUP BY postid) c ON c.postid=post.id
	ORDER BY poin DESC LIMIT 10");
foreach($resUpload as $rowU)
	echo "<li><a href='".post_url($rowU->id,$rowU->title)."'>$rowU->title</a> <span>$rowU->poin poin</span> <span><i class='fa fa-comment'></i> $rowU->num_of_comment</span></li>";
?>  	
  	</ul>
  </div>
  <div class="col-md-6">
  	<h4>Most Commented Post</h4>
  	<ul class="list-unstyled">
<?php
$resUpload = $wpdb->get_results("SELECT post.*,c.num_of_comment,`like`-dislike as poin FROM lontong.post 
  LEFT JOIN (SELECT postid,count(*) as num_of_comment FROM lontong.comment GROUP BY postid) c ON c.postid=post.id
  ORDER BY num_of_comment DESC LIMIT 10");
foreach($resUpload as $rowU)
  echo "<li><a href='".post_url($rowU->id,$rowU->title)."'>$rowU->title</a> <span><i class='fa fa-comment'></i> $rowU->num_of_comment</span></li>";
?>  	
  	</ul>  	
  </div>
  <div class="col-md-6">
    <h4>Top User</h4>
    <ul class="list-unstyled">
<?php
$resUpload = $wpdb->get_results("SELECT * FROM lontong.users ORDER BY karma DESC LIMIT 10");
foreach($resUpload as $rowU)
  echo "<li><a href='".get_bloginfo('url')."/u/$rowU->id/'>$rowU->displayname</a> <span>$rowU->karma poin</span></li>";
?>    
    </ul>   
  </div>

  <div class="col-md-6">
    <h4>Top Uploader</h4>
    <ul class="list-unstyled">
<?php
$resUpload = $wpdb->get_results("SELECT users.*,c.num_of_post FROM lontong.users 
  LEFT JOIN (SELECT userid,count(*) as num_of_post FROM lontong.post GROUP BY userid) c ON c.userid=users.id
  ORDER BY num_of_post DESC LIMIT 10");
foreach($resUpload as $rowU)
  echo "<li><a href='".get_bloginfo('url')."/u/$rowU->id/'>$rowU->displayname</a> <span>$rowU->num_of_post post</span></li>";
?>    
    </ul>   
  </div>

  <div class="col-md-6">
    <h4>BONUS: Most Disliked Post</h4>
    <ul class="list-unstyled">
<?php
$resUpload = $wpdb->get_results("SELECT post.*,c.num_of_comment,dislike FROM lontong.post 
  LEFT JOIN (SELECT postid,count(*) as num_of_comment FROM lontong.comment GROUP BY postid) c ON c.postid=post.id
  ORDER BY dislike DESC LIMIT 10");
foreach($resUpload as $rowU)
  echo "<li><a href='".post_url($rowU->id,$rowU->title)."'>$rowU->title</a> <span><i class='fa fa-thumbs-o-down'></i> $rowU->dislike</span></li>";
?>    
    </ul>   
  </div>


</div>

</div></div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>