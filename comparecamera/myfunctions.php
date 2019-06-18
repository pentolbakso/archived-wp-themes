<?php
include "wp-load.php";
include "php-ofc-library/open-flash-chart.php";
include ("amazon.php");

function get_price($price_id,&$updated)
{
	if ($price_id=="") return("n/a");

	global $wpdb;
	$sql = "SELECT price,last_update FROM camerafollow.items_price 
		WHERE item_shortname='".$price_id."' AND merchant='amazon_us' ORDER BY last_update DESC LIMIT 1";
	$row = $wpdb->get_row($sql);
	if (!$row)
	{
		$updated = "";
		return("not available");
	}
	else
	{
		$updated = $row->last_update;
		return("$".$row->price);
	}
}

function get_price_history($price_id)
{
	if ($price_id=="") return("");

	global $wpdb;

	$sql = "SELECT item_shortname,DATE_FORMAT(last_update,'%Y%m') AS updated,UNIX_TIMESTAMP(last_update) AS ts,price  
		FROM camerafollow.items_price WHERE item_shortname='".$price_id."' AND merchant='amazon_us' 
		GROUP BY item_shortname,updated ORDER BY item_shortname,last_update ASC;";	
	//echo $sql;
	$res = $wpdb->get_results($sql);

	$json = array();
	$n = 0;
	$c = 0;
	foreach($res as $row)
	{
		$json[$n][0] = intval($row->ts)*1000;
		$json[$n][1] = intval($row->price);
		$n++;
	}

	//$json = json_decode($str);
	$json = json_encode($json);
	return($json);
}

function get_summary($camid1,$camid2)
{
	global $wpdb;

	$slug = versus_slug($camid1,$camid2);

	$sql = "SELECT * FROM compare.summary WHERE slug='".$slug."'";
	$row = $wpdb->get_row($sql,ARRAY_A);
	
	return($row['summary']);
}

function youtube_embed($code)
{
	$url = "http://www.youtube.com/v/".$code."&feature=youtube_gdata_player?fs=1&amp;hl=en_US&showinfo=1&modestbranding=0&controls=0&rel=0";
	$width = 640;
	$height = 360;
	
	return "<object width='$width' height='$height'><param name='movie' value='".$url."''></param><param name='allowFullScreen' value='true'></param><param name='allowscriptaccess' value='always'></param><embed src='".$url."' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='true' width='$width' height='$height'></embed></object>";	
}

function get_flickr($camid,$tags,$left=FALSE)
{
	if ($tags=="") return("<li class='na'>not available</li>");

	global $wpdb;

	$cache = $wpdb->get_row("SELECT * FROM compare.cacheflickr WHERE camera_id='".$camid."' AND updated > NOW()-INTERVAL 3 DAY");
	if (!$cache)
	{	
		$KEY = "02c631fdd8654c430628331e5fca2434";
		$url = "http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=".$KEY."&tags=".urlencode($tags)."&tag_mode=all&min_upload_date=".$date."&sort=interestingness-desc&extras=owner_name%2Cdate_upload%2Curl_q%2C+url_z&per_page=20&format=json&nojsoncallback=1";

		echo "<!--$url-->";

		$str = file_get_contents($url);
		$arr = json_decode($str);
		if ($arr->stat!="ok")
		{
			//frakkk!!!!
			echo "<li>Houston, we have problem !! : ".$arr->message."</li>";
			return($ret);
		}

        $sql = "REPLACE INTO compare.cacheflickr SET
         response='".$wpdb->escape($str)."',updated=NOW(),camera_id='".$camid."'";
        //echo $sql;
        if ($wpdb->query($sql)===FALSE)
        	echo "Error Inject";
	}
	else
	{
		$str = $cache->response;
		$arr = json_decode($str);
	}
	
	if (count($arr->photos->photo)>0)
	{
		$class = ($left)?'left':'';
		foreach($arr->photos->photo as $photo)
		{
			$ret .= "<li class='".$class."'>
				<a href='#flickr-modal' class='flickr' data-toggle='modal' fownername='".htmlentities($photo->ownername,ENT_QUOTES)."' fowner='".$photo->owner."' fz='".$photo->url_z."'><img src='".$photo->url_q."' title='".htmlentities($photo->title,ENT_QUOTES)." by ".htmlentities($photo->ownername,ENT_QUOTES)."'/></a>
				</li>";
		}
	}
	return($ret);
}

function get_amazon($cam1)
{
	global $wpdb;

	$row1 = $wpdb->get_row("SELECT asin FROM compare.camera WHERE camera_id='".$cam1."'");
	if ($row1->asin=="")
		return("");

	$cache1 = $wpdb->get_row("SELECT * FROM compare.cacheamazon WHERE asin='".$row1->asin."' AND updated > NOW()-INTERVAL 1 DAY");

	if (!$cache1)
	{
		$obj = new AmazonProductAPI();
		try
	    {
	         $amz1 = $obj->getReviewByAsin($row1->asin);

	         $sql = "REPLACE INTO compare.cacheamazon SET response='".mysql_real_escape_string(json_encode($amz1))."',updated=NOW(),asin='".$row1->asin."'";
	         //echo $sql;
	         if ($wpdb->query($sql)===FALSE)
	         	echo "Error Inject: ".$wpdb->last_error;
	    }
	    catch(Exception $e)
	    {
	        echo $e->getMessage();
	    }
	}
	else
		$amz1 = json_decode($cache1->response);

	//print_r($amz1);

	return($amz1);
}

function parse_keyvalue($str,&$arr)
{
	$tmp = explode("=", $str);
	$key = trim($tmp[0]);
	if (count($tmp)>1)
		$val = trim($tmp[1]);

	$arr[$key]=$val;
}

function admin($field,$camid,$specs=FALSE)
{
	if (current_user_can('manage_options'))
	{
		if ($specs==FALSE)
			$editurl = get_bloginfo('template_directory')."/edit.php?field=".$field."&id=".$camid;
		else
			$editurl = get_bloginfo('template_directory')."/editspecs.php?id=".$camid;

		if ($specs) $width=900;
		else $width=600;

		$href = '<a href="#" onclick="javascript:void window.open(\''.$editurl.'\',\'1351751142277\',\'width='.$width.',height=500,toolbar=0,menubar=1,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0\');return false;">Edit '.$field.'</a>';
		return "<p><small>[$href]</small></p>";
	}
}
function admin_summary($camid1,$camid2)
{
	if (current_user_can('manage_options'))
	{
		$editurl = get_bloginfo('template_directory')."/editsummary.php?cam1=".$camid1."&cam2=".$camid2;		
		$width=600;

		$href = '<a href="#" onclick="javascript:void window.open(\''.$editurl.'\',\'1351751142277\',\'width='.$width.',height=500,toolbar=0,menubar=1,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0\');return false;">Edit '.$field.'</a>';
		return "<p><small>[$href]</small></p>";
	}
}

function show_specs($camid1,$camid2)
{
	global $wpdb;
	$fi = $wpdb->get_results("SELECT * FROM compare.specdetails WHERE enabled=1");
	//print_r($fi);

	$res = 	$wpdb->get_results("SELECT * FROM compare.specs WHERE camera_id='".$camid1."' OR camera_id='".$camid2."'",ARRAY_A);
	foreach($res as $r)
		$cam[$r['camera_id']] = $r;

	$ret = "<table class='table-specs' style='margin:8px auto'>";

	foreach($fi as $f)
	{
		$tmp1 = $cam[$camid1][$f->field];
		$tmp2 = $cam[$camid2][$f->field];

		//format val : key=value
		$arr1 = "";
		parse_keyvalue($tmp1,$arr1);
		$val1 = key($arr1);
		if (trim($arr1[$val1])=="") $dval1= -1;
		else $dval1 = floatval($arr1[$val1]);

		$arr2 = "";
		parse_keyvalue($tmp2,$arr2);
		$val2 = key($arr2);
		if (trim($arr2[$val2])=="") $dval2= -1;
		else $dval2 = floatval($arr2[$val2]);

		$class1 = $class2 = "";
		if ($dval1>=0 && $dval2>=0) 
		{
			if ($dval1>$dval2) 
			{
				if($f->reversed==1) $class2="better"; else $class1="better";
			}
			else if ($dval1<$dval2) 
			{
				if($f->reversed==1) $class1="better"; else $class2="better";
			}
		}

		if ($val1=="") $val1="<span class='na'>no data</span>";
		if ($val2=="") $val2="<span class='na'>no data</span>";

		if ($f->group != $group)
		{
			$group = $f->group;
			$ret .= "<tr><td colspan='3' class='group'>".$group."</td></tr>";
		}

		if (stripos($val1,"http://")!==FALSE) $val1="<a href='$val1' target='_blank'>Link</a>";
		if (stripos($val2,"http://")!==FALSE) $val2="<a href='$val2' target='_blank'>Link</a>";

		$ret .= "<tr><td width='35%' class='left $class1 spec'><a href='#cmiiw' class='incorrect hide' data-toggle='modal' camid='$camid1' field='$f->field'>incorrect?</a>".$val1."</td>
			<td width='30%' class='mid'>".$f->caption."</td>
			<td width='35%' class='right $class2 spec'>".$val2."<a href='#cmiiw' class='incorrect hide' data-toggle='modal' camid='$camid2' field='$f->field'>incorrect?</a></td></tr>";
	}
	$ret .= "</table>";

	return($ret);
}

function parse($camid,$field,$str,&$count,$left=TRUE)
{
	$ret = "";
	$count = 0;

	if ($field=="intro")
	{
		return(nl2br($str));
	}
	else if ($field=="expert_reviews")
	{
		$reviews = explode(PHP_EOL, $str);
		if (count($reviews)>0 && strlen($reviews[0])>0 )
		{
			foreach($reviews as $rev)
			{
				if (trim($rev)=="") continue;
				$arr = explode(";", $rev);

				//$ret.="<blockquote>".$arr[3]."<small><a href='".$arr[1]."' target='_blank'>".$arr[0]."</a></small>";
				$ret.= "<div class='review'>$arr[3]
				<div class='reviewinfo'><a href='$arr[1]' target='_blank'>$arr[0]</a> <span class='label label-primary'>$arr[2]</span></div>
				</div>";

				$count++;
			}
		} else {
			$ret.= "<div class='review na'>no reviews yet</div>";
		}

		$ret.="<a class='btn btn-xs btn-default suggest' data-toggle='modal' camid='$camid' field='$field' href='#suggestion'><span class='glyphicon glyphicon-plus'></span> Suggest a Review</a>";
	}
	else if ($field=="customer_reviews")
	{
	    $ret .= "<iframe src='$str' width='100%' scrolling='auto' height='1800px' frameborder='0'></iframe>";
	}
	else if ($field=="video_reviews" || $field=="sample_videos")
	{
		$reviews = explode(PHP_EOL, $str);
		if (count($reviews)>0 && strlen($reviews[0])>0 )
		{
			foreach($reviews as $rev)
			{
				if (trim($rev)=="") continue;

				$a = explode(";", $rev);
				if (strtolower($a[0])=="youtube")
						$embed = youtube_embed($a[1]);

				$ret .= "<div id='".$a[1]."' class='modal fade' role='modal' tabindex='-1'>
					<div style='padding:10px;text-align:center'>".$embed."</div>
					</div>";
			}

			$ret .= "<div class='row'><ul class='videothumbnails'>";
			foreach($reviews as $rev)
			{
				if (trim($rev)=="") continue;

				$a = explode(";", $rev);
				$class = ($left)?'left':'';
				$url = "http://img.youtube.com/vi/".$a[1]."/mqdefault.jpg";	//hqdefault
				$ret .= "<li class='".$class."'><a class='thumbnail' href='#".$a[1]."' data-toggle='modal'><img src='".$url."'/>
				<div class='caption'>".$a[2]."</div></a>
					</li>";

				$count++;
			}
			$ret .= "</ul></div>";

		}
		else
		{
			$ret .= "<p class='na'>no video yet</p>";
		}

		$ret.="<a class='btn btn-xs btn-default suggest' data-toggle='modal' camid='$camid' field='$field' href='#suggestion'><span class='glyphicon glyphicon-plus'></span> Suggest a Video</a>";
	}
	else if ($field=="sample_images")
	{
		if ($str!="")
		{
			$ret .= "<div class='row'><ul class='photothumbnails'>";
			$ret .= get_flickr($camid,$str,$left);
			$ret .= "</ul></div>";
		}
		else
			$ret = "<p class='na'>not available</p>";

	}
	else if ($field=="learning_material" || $field == "accesories")
	{
		$links = explode(PHP_EOL, $str);
		if (count($links)>0 && strlen($links[0])>0 )
		{
			$ret .= "<div class='row'><ul class='otherthumbnails'>";
			foreach($links as $l)
			{
				if (trim($l)=="") continue;

				$arr = explode(";", $l);
				if (strlen($arr[2])<15)
					$url = amazon_link($arr[2],$arr[0]);
				else
					$url = $arr[2];

				$class = ($left)?'left':'';
				$ret.= "<li class='".$class."'><a href='".$url."' class='thumbnail' target='_blank'>
					<img src='".$arr[1]."' title='".$arr[0]."' style='width:100px'/></a>";

				$count++;
			}
			$ret .= "</ul></div>";

		} else {
			$ret.= "<p class='na'>no data available yet</p>";
		}
		
		$ret.="<a class='btn btn-xs btn-default suggest' data-toggle='modal' camid='$camid' field='$field' href='#suggestion'><span class='glyphicon glyphicon-plus'></span> Suggest an Item</a>";
	}


	return($ret);
}

function amazon_link($ASIN,$title)
{
	$AFF_TAG="";

	$url = get_bloginfo('url')."/get/".$ASIN."/".sanitize_title($title)."/";
	//$url = "http://www.amazon.com/dp/".$ASIN."/?tag=".$AFF_TAG;
	return($url);
}

function versus_slug($cam1,$cam2)
{
	$arr[]=$cam1;
	$arr[]=$cam2;
	sort($arr);
	$slug = $arr[0]."-vs-".$arr[1];

	return($slug);
}

?>