<?php
include "wp-load.php";
include "php-ofc-library/open-flash-chart.php";
include "amazon.php";

function get_price($camera_id,$title)
{
	$cachefile = "cache/".$camera_id.".txt";	
	
	if (file_exists($cachefile))
	{
		$filetimestamp = filemtime($cachefile);
		$diff = time() - $filetimestamp;
		
		//echo "file:".$filetimestamp." - ".$diff;		
		if ($diff < 3600*12)	//12 jam cache deh
		{
			echo "<!-- Use price data cache -->";
			return ($cachefile);
		}
	}
	
	global $wpdb;

	$day = 90;
	$bg = "ffffff";
	$min = 0;
	$max = 0;
	$step = 2;

	$sql = "SELECT price,last_update FROM camerafollow.items_price 
		WHERE item_shortname='".$camera_id."' AND merchant='amazon_us' ORDER BY last_update DESC LIMIT ".$day;
	$res = $wpdb->get_results($sql);
	if (!$res)
	{
		return("");
	}
	else
	{
		$max = 0;
		$min = 0xffffff;
		
		$count=0;
		foreach ($res as $row)
		{
			$d = new dot(intval($row->price));
			$dt = date("d-M-Y",strtotime($row->last_update)); (++$count%7==1)?$date[]=$dt:$date[]='';
			$data[] = $d->size(3)->halo_size(5)->colour('#005fbf')->tooltip("US$#val#<br>$dt");
			
			if ($row->price > $max) $max=$row->price;
			if ($row->price < $min) $min=$row->price;
		}
		
		$max = $max + 5;
		$min = $min - 5;
		
		$diff = $max - $min;
		$step = round($diff/20);
		if ($step<=5) $step = 5;
	}

	$data = array_reverse($data);
	$date = array_reverse($date);

	$title = new title($title." - ".date("M Y") );

	$area = new area();
	// set the circle line width:
	$area->set_width( 1 );
	$area->set_default_dot_style($d);
	$area->set_colour( '#ccc' );
	$area->set_fill_colour( '#aad4ff' );
	$area->set_fill_alpha( 0.5 );
	$area->set_values( $data );

	$x_label = new x_axis_labels();
	$x_label->set_labels($date);
	$x_label->set_vertical();

	$x = new x_axis();
	$x->offset(true)->steps(7);
	$x->set_labels($x_label);

	$y = new y_axis();
	$y->set_range( $min, $max, $step );

	$chart = new open_flash_chart();
	$chart->set_title( $title );
	$chart->add_element( $area );
	$chart->set_y_axis( $y );
	$chart->set_x_axis( $x );
	$chart->set_bg_colour("#".$bg);

	//echo $chart->toPrettyString();
	file_put_contents($cachefile,$chart->toPrettyString());
	
	return($cachefile);
}

function get_asin($url)
{
	$path = parse_url($url, PHP_URL_PATH);
	$info = explode("/",$path);
	$ASIN = $info[3];
	return($ASIN);	
}

function get_amazon_image($cam1,& $desc)
{
	global $wpdb;

	$row1 = $wpdb->get_row("SELECT url FROM camerafollow.items_schedule WHERE item_shortname='".$cam1."' AND merchant='amazon_us'");
	if ($row1->url=="")
		return("");
	$asin = get_asin($row1->url);

	$cache1 = $wpdb->get_row("SELECT * FROM camerafollow.cacheamazon WHERE asin='".$asin."' AND updated > NOW()-INTERVAL 3 DAY");

	if (!$cache1)
	{
		$obj = new AmazonProductAPI();
		try
	    {
	         $amz1 = $obj->getReviewByAsin($asin);

	         $sql = "REPLACE INTO camerafollow.cacheamazon SET response='".mysql_real_escape_string(json_encode($amz1))."',updated=NOW(),asin='".$asin."'";
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
	$img =$amz1->Items->Item->LargeImage->URL;
	$desc = $amz1->Items->Item->ItemAttributes->Feature;

	return($img);
}

?>