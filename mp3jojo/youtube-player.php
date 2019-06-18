<div id='player'>

<?php
$ID = $_POST['id'];

//http://www.youtube.com/v/rNDfBcIdf4k&feature=youtube_gdata_player?fs=1&amp;hl=en_US
$url = "http://www.youtube.com/v/$ID&feature=youtube_gdata_player";
$width = 540;
$height = 340;

echo
"<object width='".$width."' height='".$height."'>
<param name='movie' value='".$url."'?fs=0&amp;hl=en_US'></param>
<param name='allowFullScreen' value='false'></param>
<param name='allowscriptaccess' value='always'></param>
<embed src='".$url."?autoplay=1&fs=0&autohide=1&controls=1&modestbranding=1&rel=0&showinfo=1&amp;hl=en_US' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='false' width='".$width."' height='".$height."'></embed>
</object>";	

?>

</div>
<div id='playerinfo'>
	<a class='btn btn-download' href='#'><i class='icon icon-white icon-download'></i> Download</a>
</div>