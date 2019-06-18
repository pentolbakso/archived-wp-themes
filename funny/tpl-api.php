<?php
/*
Template Name: API endpoint
*/
if ($_GET["key"]!="simanisjembatanancol") 
{
	header("Location: ".get_bloginfo('url'));
	return;
}

$APIVERSION = "0.9.1";
global $wpdb;

if ($_GET["method"]=="get") {

	/*
	?what=new&page=1&count=10&channel=all
	*/

	$what = $_GET["what"];
	$count = $_GET["count"];

	if ($what=="new")
		$order = "ORDER BY post.added DESC";
	else if ($what=="top")
		$order = "ORDER BY totalpoin DESC,c.num_of_comment DESC,post.added DESC";
	else if ($what=="random")
		$order = "ORDER BY rand()";
	else //hot
		$order = "ORDER BY todaypoin DESC,stat.views DESC,post.added DESC";

	if (isset($_GET["pg"]))
		$limit = "LIMIT ".($_GET["pg"]*$count).",$count";
	else
		$limit = "LIMIT $count";

	if (isset($_GET["channel"]) && $_GET["channel"]!="all")
		$where = "WHERE post.channel='".$_GET["channel"]."'";
	else
		$where = "";

	$sql = "SELECT post.*,(post.like - post.dislike) as totalpoin,users.displayname,stat.views,stat.likes-stat.dislikes as todaypoin,c.num_of_comment 
		FROM lontong.post 
		LEFT JOIN lontong.users ON post.userid = users.id
		LEFT JOIN lontong.stat ON stat.postid = post.id AND thedate=DATE(NOW())
		LEFT JOIN (SELECT postid,count(*) as num_of_comment FROM lontong.comment GROUP BY postid) c ON c.postid=post.id
		$where
		$order 
		$limit";
	//echo "<!-- $sql -->";
	$hasil = array();

	$res = $wpdb->get_results($sql,ARRAY_A);
	foreach ($res as $row)
	{
		$embed = "";
		if ($row["animated"]==="0") {
			if ($row["youtube"]!="")
				$img = "http://i.ytimg.com/vi/".$row["youtube"]."/hqdefault.jpg";
			else
				$img = get_bloginfo("url")."/img/".$row["image"];
		}
		else
			$img = get_bloginfo("url")."/img/static-".$row["image"];

		if ($row["youtube"]!="")
			$embed = ytembed($row["youtube"]);

		if ($row["userid"]==0) $user = "Anonim";
		else $user = $row["displayname"];

		$poin = $row["like"] - $row["dislike"];

		$ret = array();
		$ret["id"] = $row["id"];
		$ret["title"] = $row["title"];
		$ret["channel"] = $row["channel"];
		$ret["gif"] = $row["animated"];
		$ret["image"] = $img;
		if ($row["animated"]!=="0")
			$ret["gif_image"] = get_bloginfo("url")."/img/".$row["image"];
		else
			$ret["gif_image"] = "";
		$ret["youtube_id"] = $row["youtube"];
		$ret["youtube_embed"] = $embed;
		$ret["user"] = $user;
		$ret["poin"] = $poin;
		$ret["tag"] = $row["tags"];
		$ret["credit"] = $row["credit"];
		$ret["spoiler"] = $row["spoiler"];
		$ret["date"] = $row["added"];
		$ret["comment_count"] = $row["num_of_comment"];
		$ret["weburl"] = post_url($row["id"],$row["title"]);

		$hasil[] = $ret;
	}

	$json = array();
	$json["Version"] = $APIVERSION;
	$json['Result'] = "OK";
	$json['TotalRecordCount'] = count($hasil);
	$json['Records'] = $hasil;
	print json_encode($json);
	//print_r($json);

}
else
{
	$json = array();
	$json["Version"] = $APIVERSION;
	$json['Result'] = "ERROR";
	$json['Message'] = "Unknown Command";
	print json_encode($json);

}

?>