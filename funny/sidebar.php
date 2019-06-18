<?php
global $wpdb;
?>

<div class='col-md-4'><div id="sidebar">

	<div class='box'>
		<h4>Popular Post Bulan Ini</h4>
	  	<ul class="list-unstyled">
<?php
$resUpload = $wpdb->get_results("SELECT *,`like`-dislike as poin,c.num_of_comment FROM lontong.post
	LEFT JOIN (SELECT postid,count(*) as num_of_comment FROM lontong.comment GROUP BY postid) c ON c.postid=post.id
	WHERE MONTH(added) = MONTH(NOW())
	ORDER BY poin DESC LIMIT 10
	");
foreach($resUpload as $rowU)
	echo "<li><a href='".post_url($rowU->id,$rowU->title)."'>$rowU->title</a> <span>$rowU->poin</span> <span><i class='fa fa-comment'></i> $rowU->num_of_comment</span></li>";
?>  	
	  	</ul>
	</div>
	<div class='box'>
		<h4>Post Baru</h4>
	  	<ul class="list-unstyled">
<?php
$resUpload = $wpdb->get_results("SELECT * FROM lontong.post
	ORDER BY added DESC LIMIT 10
	");
foreach($resUpload as $rowU)
	echo "<li><a href='".post_url($rowU->id,$rowU->title)."'>$rowU->title</a> <span>".date("d M",strtotime($rowU->added))."</span></li>";
?>  	
	  	</ul>
	</div>

</div></div>