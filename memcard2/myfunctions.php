<?php

include_once("amazon.php");

function calculate_raw($raw_mb,$width,$height)
{
	/*
	raw 12 bit : http://www.allmemorycards.com/glossary/raw.htm
	2 000 000 pixels x 1 colour channel x 12 bits per colour channel 
	=24 000 000 bits = 3 000 000 bytes = 2.86 MB 
	*/
	
	if ($raw_mb == "")
	{
	$mp = $width * $height;
	$size = $mp * 1 * 12 / 8;
	$size = $size / (1024*1024);	//mb
	}
	else
	{
	$size = $raw_mb;
	}
	
	$cap['filesize'] = $size;
	$cap['2gb'] = round(2000/$size);
	$cap['4gb'] = round(4000/$size);
	$cap['8gb'] = round(8000/$size);
	$cap['16gb'] = round(16000/$size);
	$cap['32gb'] = round(32000/$size);
	$cap['64gb'] = round(64000/$size);
	
	return($cap);
}

function calculate_jpeg($jpeg_mb,$width,$height)
{
	/*
	http://www.allmemorycards.com/glossary/jpeg.htm
	2 000 000 pixels x 3 colour channels x 8 bits per colour channel 
	=48 000 000 bits = 6 000 000 bytes = 5.72 MB/5 = 1.14 MB	
	*/
	if (!$jpeg_mb)
	{
	$mp = $width * $height;
	$size = ($mp * 3) / (1024*1024);	//mb;
	$size = $size/5;	//jpeg ratio
	}
	else
	{
	$size = $jpeg_mb;
	}
	
	$cap['filesize'] = $size;
	$cap['2gb'] = round(2000/$size);
	$cap['4gb'] = round(4000/$size);
	$cap['8gb'] = round(8000/$size);
	$cap['16gb'] = round(16000/$size);
	$cap['32gb'] = round(32000/$size);
	$cap['64gb'] = round(64000/$size);
	
	return($cap);
}

function get_duration_min($cap_gb,$bitrate_mbit)
{
	$br = $bitrate_mbit/8;	//convert to byte
	$duration = ($cap_gb * 1000) / ($br * 60);
	
	$h = floor($duration/60);
	$min = $duration%60;
	
	if($h>0)
		return $h."h ".$min."m";
	else
		return $min."m";
	
}

function calculate_video_duration($mode_array,&$len)
{
	/*ref:
	http://imaging.nikon.com/lineup/microsite/d-movie/en/faq/index.htm
	*/

	//format mode: 1920x1080,30p=20
	$count=0;
	$notfound = FALSE;
	foreach($mode_array as $line)
	{

		$v = explode("=",$line);
		$mode = trim($v[0]);
		$bitrate = trim($v[1]);

		if ($bitrate!="")
		{			
			$out[$count]['mode']= $mode;
			$out[$count]['2gb'] = get_duration_min(2,$bitrate); 
			$out[$count]['4gb'] = get_duration_min(4,$bitrate); 
			$out[$count]['8gb'] = get_duration_min(8,$bitrate); 
			$out[$count]['16gb'] = get_duration_min(16,$bitrate); 
			$out[$count]['32gb'] = get_duration_min(32,$bitrate); 
			$out[$count]['64gb'] = get_duration_min(64,$bitrate); 
		}
		else
		{
			$out[$count]['mode']= $mode;
			$out[$count]['2gb'] = "*"; 
			$out[$count]['4gb'] = "*"; 
			$out[$count]['8gb'] = "*"; 
			$out[$count]['16gb'] = "*"; 
			$out[$count]['32gb'] = "*"; 
			$out[$count]['64gb'] = "*"; 
			$notfound = TRUE;
		}
		$count++;
	
	}
	$len = $count;
	
	return($out);
}

function show_card_list($cap,$sd,$sdhc,$sdxc)
{
	$filter = '';
	if ($sd==1) 
		if ($filter=='') $filter .= "type='sd'";
		else $filter .= " OR type='sd'";
	if ($sdhc==1)
		if ($filter=='') $filter .= "type='sdhc'";
		else $filter .= " OR type='sdhc'";
	if ($sdxc==1)
		if ($filter=='') $filter .= "type='sdxc'";
		else $filter .= " OR type='sdxc'";

	global $wpdb;
	$sql = "SELECT * FROM memorycard.mem_card WHERE cap_gb=".$cap." AND (".$filter.")";

	$res = $wpdb->get_results($sql);
	echo "<ul>";
	foreach($res as $row)
	{
		echo "<li><img src='".get_bloginfo('url')."/".$row->img_url."'/>
			<a href='".get_bloginfo('url')."/card/".$row->id."/".$row->slug."/'>".$row->name."</a><br/>
			Type: ".strtoupper($row->type)."<br/>
			Class Rating: ".$row->class_rating."<br/>
			</li>";
	}
	echo "</ul>";
}

function beautify_slug($slug)
{
	$slug = str_replace("-"," ",$slug);
	$slug = ucwords($slug);
	return($slug);
}

function get_recommendation($csv,&$img_url)
{
    if ($csv=="") return("");
    
    $img_url = "";
    global $wpdb;
    $sql = "SELECT * FROM memorycard.mem_card WHERE id IN (".$csv.")";
    //echo $sql;
    $res = $wpdb->get_results($sql);
    if ($wpdb->num_rows==0) return("");
    
    $count=0;
    $str = "<ul>";
    foreach($res as $row)
    {
	if ($count++ == 0) $img_url = $row->img_url;
	$str .= "<li><i class='icon icon-tag'></i> <a href='".get_bloginfo('url')."/card/".$row->slug."/'>".$row->name."</a></li>";
    }
    $str .= "</ul>";
    //echo $str;
    return($str);
}

function amazonize($url)
{
    $aff_id= "bestmemcard-20";

    if (stripos($url,"?")!==false)
    {
    	$url = $url."&tag=".$aff_id;
    }
    else
    {
    
	    if (substr($url,-1)!="/")
		$url=$url."/";

	    $url = $url."?tag=".$aff_id;
	}

    return($url);
}

function get_amazon($asin)
{
	if (strlen($asin)=="") return("");

	echo "<!-- get asin $asin -->";

	global $wpdb;
	$cache1 = $wpdb->get_row("SELECT * FROM memorycard.mem_amazoncache WHERE asin='".$asin."' AND updated > NOW()-INTERVAL 1 DAY");

	if (!$cache1)
	{
		$obj = new AmazonProductAPI();
		try
	    {
	         $amz1 = $obj->getReviewByAsin($asin);

	         $sql = "REPLACE INTO memorycard.mem_amazoncache SET response='".mysql_real_escape_string(json_encode($amz1))."',updated=NOW(),asin='".$asin."'";
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


?>