<div class="span4" id='sidebar'>

	<div style='margin-bottom:20px'>
<!--Copy and paste the code below into the location on your website where the ad will appear.-->
<script type='text/javascript'>
var adParams = {a: '11920181', size: '300x250',serverdomain: 'ads.adk2.com'   };
</script>
<script type='text/javascript' src='http://cdn.adk2.com/adstract/scripts/smart/smart.js'></script>
	</div>

	<div style='max-width:240px;margin:0px auto 20px;border:none'>
		<?php
		global $wpdb;
		$row = $wpdb->get_row("SELECT * FROM mp3.apicache WHERE apiname='lastfm.topartist'");
		$json = $row->rawdata;

		$arr = json_decode($json,TRUE);
		$arr2 = $arr['artists']['artist'];
		shuffle($arr2);

		echo "<div id='artists'><ul class='unstyled'>";
		for ($i=0; $i<9; $i++)
		{
			$artist = $arr2[$i]['name'];
			$thumb = $arr2[$i]['image'][1]['#text'];	//medium

			echo "<li><a href='".searchurl($artist)."' title='Songs by $artist' alt='$artist'><img src='$thumb'/></a></li>";

		}
		echo "<ul></div><div style='clear:both'></div>";
		?>
	</div>

	<div class='well'>
		<h3>Recent Search</h3>
		<ul class='inline'>
			<?php
			$res = $wpdb->get_results("SELECT keyword FROM mp3.searchkw WHERE formsearch=1 ORDER BY added DESC LIMIT 15");
			foreach($res as $r)
			{
				echo "<li><a href='".searchurl($r->keyword)."'>".$r->keyword."</a></li>";
			}
			?>
		</ul>
	</div>

	<div class='well'>
		<h3>Recent Downloads</h3>
		<ul class='inline'>
			<?php
			
			$res = $wpdb->get_results("SELECT keyword FROM mp3.searchenginekw ORDER BY added DESC LIMIT 15");
			foreach($res as $r)
			{
				//echo "<li><a href='".searchurl($r->keyword)."'>".$r->keyword."</a></li>";
				echo "<li>".$r->keyword."</li>";
			}
			
			?>
		</ul>
	</div>

</div><!-- end span4-->
