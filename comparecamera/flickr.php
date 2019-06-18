<?php
$owner = $_POST['owner'];
$name = $_POST['ownername'];
$url = $_POST['url'];
?>

<p><img src='<?=$url?>' style='width:100%'/></p>

<div style='padding: 5px;text-align:right'>
	<a href='http://www.flickr.com/photos/<?=$owner?>/' target='_blank' class='btn'><span class='glyphicon glyphicon-share-alt'></span>&nbsp;<?=$name?>'s Flickr</a>
</div>
