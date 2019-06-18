<?php

include_once "simple_html_dom.php";

$param = array();
$the_title="";
$the_canonical="";
$the_noindex="";
$the_description="";

function slug($terms)
{
	$terms = strtolower($terms);
	$terms = str_replace(" ","-",$terms);
	return($terms);
}
function unslug($terms)
{
	$terms = str_replace("-"," ",$terms);
	return($terms);
}
function jobslug($title,$company,$loc,$date)
{
	$terms = $title;	
	
	if (strlen($title)<70)
	{		
		$datestring = int2month(date("m",$date))."-".date("Y",$date);
		
		$locstr="";
		if (strlen($loc)>0)
		{
			if (stripos($title, $loc)===FALSE)
				$locstr = $loc;
		}
			
		$companystr="";
		if (stripos($title, $company)===FALSE)
			$companystr = $company;
		
		if (strlen($companystr)>0)
			$terms = $terms." di ".$companystr." ".$locstr." ".$datestring; 
		else
		{
			if (strlen($locstr)>0) $locstr= " di ".$locstr;
			
			$terms = $terms." ".$locstr." ".$datestring;
		}
	}

	$terms = strtolower($terms);
	$arr = array(",",".",":","/","(",")","&","!","?","\"","'","\/","#","@","*","[","]","=","+");
	
	$terms = str_replace($arr,"-",$terms);
	$terms = str_replace(" ","-",$terms);	
	$terms = preg_replace('/--+/', '-', $terms);

	$company = trim($company,"-");
	
	return($terms);
}

function companyslug($company)
{
	$company = urlencode($company);
	/*

	$arr = array(",",".",":","/","(",")","&","!","?","\"","'","\/","#","@","*","[","]","=","+");
	
	$company = strtolower($company);
	//$company = str_replace("pt","",$company);
	$company = str_replace($arr,"-",$company);
	$company = trim($company);
	$company = str_replace(" ","-",$company);	
	$company = preg_replace('/--+/', '-', $company);

	$company = trim($company,"-");
	*/
	return($company);

}

function int2month($m)
{
	if ($m==1) $blnstr = "Januari";
	else if ($m==2) $blnstr = "Februari";
	else if ($m==3) $blnstr = "Maret";
	else if ($m==4) $blnstr = "April";
	else if ($m==5) $blnstr = "Mei";
	else if ($m==6) $blnstr = "Juni";
	else if ($m==7) $blnstr = "Juli";
	else if ($m==8) $blnstr = "Agustus";
	else if ($m==9) $blnstr = "September";
	else if ($m==10) $blnstr = "Oktober";
	else if ($m==11) $blnstr = "November";
	else if ($m==12) $blnstr = "Desember";
	else $blnstr = $m;
	
	return($blnstr);
}

function month2int($m)
{
	$m = ucfirst($m);
	
	if ($m=="Januari") return(1);
	else if ($m=="Februari") return(2);
	else if ($m=="Maret") return(3);
	else if ($m=="April") return(4);
	else if ($m=="Mei") return(5);
	else if ($m=="Juni") return(6);
	else if ($m=="Juli") return(7);
	else if ($m=="Agustus") return(8);
	else if ($m=="September") return(9);
	else if ($m=="Oktober") return(10);
	else if ($m=="November") return(11);
	else if ($m=="Desember") return(12);
	else return(0);
}

function time_elapsed($ptime) 
{
	#$ptime = strtotime($ptime);
    $etime = time() - $ptime;
    
    if ($etime < 1) {
        return '0 seconds';
    }
    
    $a = array( 12 * 30 * 24 * 60 * 60  =>  'tahun',
                30 * 24 * 60 * 60       =>  'bulan',
                24 * 60 * 60            =>  'hari',
                60 * 60                 =>  'jam',
                60                      =>  'menit',
                1                       =>  'detik'
                );
    
    foreach ($a as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? ' ' : ' ');
        }
    }
}

function jobcount($filter)
{
	global $wpdb;
	$ret = "";
	
	$sql = "SELECT count(*) AS c FROM job.jobs WHERE ".$filter;
	//echo $sql;

	$count = $wpdb->get_row($sql)->c;
	return($count);
}

function joblist($filter,$LIMIT,&$snippet="")
{
	if (stripos($LIMIT, "limit")===FALSE) $LIMIT = " LIMIT ".$LIMIT;

	global $wpdb;
	$ret = "";
	$sql = "SELECT * FROM job.jobs WHERE ".$filter." ORDER BY post_date DESC ".$LIMIT;
	
	//echo "<!-- $sql -->";
	
	$snippet = "";
	$results = $wpdb->get_results($sql);
	if ($results)
	{
		$ret .= "<div class='well'><table class='table table-condensed'><tbody>";
		$counter = 1;
		foreach($results as $row)
		{
			$info = "";
			$lok = (strlen($row->city)>0)?$row->city:longprov($row->prov);
			$lokasi = "<a href='".get_bloginfo('url')."/?cari=&di=".urlencode($lok)."'>".$lok."</a>";
			if (strlen($row->company)>0) 
			{
				$info = $lokasi." | <a href='".get_bloginfo('url')."/perusahaan/".companyslug($row->company)."/'>".$row->company."</a>";				
			}
			else if (strlen($row->city)>0) { $info = $lokasi; }
			else if (strlen($row->prov)>0) { $info = $lokasi; }
			
			$date = date("d M",strtotime($row->post_date));
			$days = (strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",strtotime($row->post_date)))) / (60*60*24);
			$css="";
			if ($days == 0)
			{
				$date = "Hari ini";
				$css = 'today';
			}
			else if ($days == 1)
			{
				$date = "Kemarin";
				$css = 'yesterday';
			}
			else if ($days == 2)
			{
				$date = "2 hari lalu";
				$css = 'd2hari';
			}
			else if ($days == 3)
			{
				$date = "3 hari lalu";
				$css = 'd3hari';
			}

			if (strlen($row->company)>0) $company = ucwords($row->company);
			else 
			{
				$company = "Perusahaan";
				if (strlen($row->city)>0) $company.= " di ".$row->city;
				else if (strlen($row->prov)>0) $company.= " di ".$row->prov;
			}

			if (stripos($row->title, "lowongan")!==FALSE)
				$headline = $company." membuka ".$row->title;
			else if (stripos($row->title, "urgent")!==FALSE)
				$headline = $company." membuka lowongan kerja";
			else
				$headline = $company." membuka lowongan kerja untuk ".$row->title;

			$ret .= "<tr>
					<td><span class='mylabel $css' style='margin-right:10px'>".$date."</span>
						<a href='".get_bloginfo('url')."/id/".$row->id."/".jobslug($row->title,$row->company,$loc,strtotime($row->post_date))."/'>
						<span class='jobtitle'>".strip_tags($row->title)."</span></a>
							<div id='desc$counter' class='jobdesc collapse in'>".$headline.". ".strip_tags($row->description)."
							<p>".time_elapsed(strtotime($row->post_date))." yg lalu | ".indo_date(strtotime($row->post_date))." | ".$info." 
						<i class='icon-hand-right'></i>&nbsp;<a href='".get_bloginfo('url')."/id/".$row->id."/".jobslug($row->title,$row->company,$loc,strtotime($row->post_date))."/'>Info Lengkap</a></div>
				</td>
				</tr>";

			if ($counter < 6)
				$snippet .= $row->title.", ";

			$counter++;

		}
		$ret .= "</tbody></table></div>";
	}
	
	return($ret);
}

function get_jobdesc($id,$site,$url,$api)
{
	echo "<!-- scrape -->";
	global $wpdb;

	$indeed_desc = "";
	if ($api=="indeed")
	{
		$html = @file_get_html($url);
		if (!$html) return("");

		$url = $html->find("div[id=bvjl] a",0)->href;
		if (strlen($url)>0) $url = "http://id.indeed.com".$url;

		$indeed_desc = $html->find("span[class=summary]",0)->innertext;

		echo "<!-- $url -->";		
	}

	//scrape'em bitches
	$row = $wpdb->get_row("SELECT * FROM job.sites WHERE sitename='".$site."'");
	if ($row==NULL) return($indeed_desc);

	$desc_element = $row->desc_element;
	if (strlen($row->desc_element)==0) return($indeed_desc);

	if ($desc_element=="ref")
	{
		//ternyata ref
		$ref_site = $row->ref;
		echo "<!-- ref: $ref_site -->";
		$row = $wpdb->get_row("SELECT * FROM job.sites WHERE sitename='".$ref_site."'");
		if ($row==NULL) return($indeed_desc);
		$desc_element = $row->desc_element;
	}

	$html = @file_get_html($url);
	if (!$html) return($indeed_desc);

	$unique = $row->find_string;
	if (strlen($unique)>0)
	{
		foreach($html->find($desc_element) as $item)
		{
			if (strpos($item->innertext,$unique)!==FALSE)
			{
				if ($row->next_sibling>0) $item = $item->next_sibling();

				$desc = $item->innertext;
				break;
			}
		}
	}
	else
	{
		$pos = $row->pos;
		if (strlen($pos)==0) $pos = 0;
		
		$element = $html->find($desc_element,$row->pos);

		if (strlen($row->loop_tag)>0)
		{
			foreach ($element->find($row->loop_tag) as $item)
				$desc = $desc."\n".$item->innertext;
		}
		else
			$desc = $element->innertext;
	}

	echo "<!-- desclen:".strlen($desc)."-->";	

	if (strlen($desc)==0) $desc = $indeed_desc;

	$desc = strip_tags($desc,"<p><li><ul><ol>".$row->except_tag);
	$desc = trim($desc);


	if (strlen($desc)>0)
	{
		if ($row->nl2br==1) $desc = nl2br($desc);

		//remove attributes
		$desc = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i",'<$1$2>', $desc);

		//remove javascript
		$desc = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $desc);

		//remove double lines
		$desc = preg_replace('/^\n+|^[\t\s]*\n+/m','',$desc);

		if (strlen($row->end_marker)>=0)
		{
			if (($marker = stripos($desc,$row->end_marker))!==FALSE)
				$desc = trim(substr($desc,0,$marker));
		}
	}

	if (($marker = stripos($desc,"document.write"))!==FALSE)
		$desc = trim(substr($desc,0,$marker));

	if (stripos($desc, "<p>")===FALSE && stripos($desc, "<li>")===FALSE && stripos($desc, "<br>")===FALSE)
		$desc = nl2br($desc);

	if (strlen($desc)==0) $desc = "-";

	$sql = "REPLACE INTO job.descriptions SET id=".$id.",fulldescription='".$desc."',added=NOW()";
	if ($wpdb->query($sql) === FALSE)
	{
		//echo "QUERY FAILED: ".$sql;
		#mylog($sqlupdate);
	}	

	return($desc);
}

function latest_search()
{
	global $wpdb;
	$limit = 15;

	$ret = "";

	$sql = "SELECT * FROM job.searchkw GROUP BY keyword ORDER BY postdate DESC LIMIT $limit";
	$results = $wpdb->get_results($sql);
	foreach($results as $res)
	{
		$kw = str_replace("/","",$res->keyword);
		$lo = str_replace("/","",$res->location);

		if ($kw=="" || $lo=="")
			$term = "lowongan ".$kw.$lo;
		else
			$term = $kw." di ".$lo;

		$ret .= "<a href='".get_bloginfo('url')."/?cari=".urlencode($kw)."&di=".urlencode($lo)."'>".$term."</a> &nbsp; ";
	}

	return($ret);

}

function go_404()
{
	global $wp_query;
	status_header(404);
	$wp_query->is_404 = true;
	$wp_query->is_single = false;
	$wp_query->is_page = false;

	include(get_query_template( '404' ));
	exit();
}

function longprov($short)
{
	if (strlen($short)>2) return($short);

	if ($short=="AC") return ("Aceh");
	else if ($short=="BA") return ("Bali");
	else if ($short=="BB") return ("Bangka-Belitung");
	else if ($short=="BT") return ("Banten");
	else if ($short=="JB") return ("Jawa Barat");
	else if ($short=="JI") return ("Jawa Timur");
	else if ($short=="JT") return ("Jawa Tengah");
	else if ($short=="KB") return ("Kalimantan Barat");
	else if ($short=="KI") return ("East Kalimantan");
	else if ($short=="KR") return ("Kepulauan Riau");
	else if ($short=="KS") return ("Kalimantan Selatan");
	else if ($short=="KT") return ("Kalimantan Tengah");
	else if ($short=="LA") return ("Lampung");
	else if ($short=="MA") return ("Maluku");
	else if ($short=="MU") return ("Maluku Utara");
	else if ($short=="NB") return ("Nusa Tenggara Barat");
	else if ($short=="NT") return ("Nusa Tenggara Timur");
	else if ($short=="PA") return ("Papua");
	else if ($short=="PB") return ("Papua Barat");
	else if ($short=="RI") return ("Riau");
	else if ($short=="SA") return ("Sulawesi Utara");
	else if ($short=="SB") return ("West Sumatra");
	else if ($short=="SG") return ("Sulawesi Tenggara");
	else if ($short=="SN") return ("Sulawesi Selatan");
	else if ($short=="SR") return ("Sulawesi Barat");
	else if ($short=="SS") return ("indonesia");
	else if ($short=="ST") return ("Sulawesi Tengah");
	else if ($short=="SU") return ("North Sumatra");
	else if ($short=="JK") return ("Jakarta");
	else if ($short=="YO") return ("Yogyakarta");
	else return("n/a");
}

function indo_date($date)
{
	$str = date("d",$date)." ".int2month(date("m",$date))." ".date("Y",$date);
	return($str);
}
?>