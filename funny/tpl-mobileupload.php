<?php
/*
Template Name: Mobile Upload
*/
if (session_id() == "") session_start();

if (isset($_SESSION["user_id"])) $userid = $_SESSION["user_id"];
else $userid = 0;	//anonymous	

if (strlen($_POST["title"])==0)
{
	echo json_encode(array("success" => "false","message"=> "Error: Kasih judul buat postnya, boleh ?"));
	return;
}
else if (strlen($_POST["url"])>0)
{
	//mode url
	$url = $_POST["url"];
	if (stripos($url, "youtube.com")!==FALSE)
	{
		//get youtube code

		preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);
		//print_r($matches);
		$youtubeid = $matches[0];
		if (strlen($youtubeid)>0)
		{
			global $wpdb;
			$sql = $wpdb->prepare("INSERT INTO lontong.post (title,channel,youtube,spoiler,tags,credit,userid,`like`,dislike,added) 
				VALUES ('%s','%s','%s','%s','%s','%s','%s',%d,%d,NOW())",
				$_POST['title'],
				$_POST['channel'],
				$youtubeid,
				$_POST['spoiler'],
				$_POST['tag'],
				$_POST['credit'],
				$userid,
				1,0);
			
			if ($wpdb->query($sql)===FALSE)
				$errormsg = "Ooops, ada masalah upload file. Info buat mimin: ".$wpdb->last_error;
		}
		else
		{
			$errormsg = "Error. Cek kembali apakah URL Youtube-nya sudah OK?";
		}
	}
	else
	{
		//download image dan convert

		$extension = pathinfo($url, PATHINFO_EXTENSION);
		$allowedExts = array("gif", "jpeg", "jpg", "png");

		if (in_array($extension, $allowedExts))
		{
			$hash = substr(md5($_POST["title"]."-".rand()),0,8);
			$image_file = sanitize_title($_POST["title"])."-$hash.$extension";
			$target = "img/".$image_file; 

			if (file_put_contents($target, file_get_contents($url))!==FALSE)
			{
				global $wpdb;

				if (is_animated($target)) {
					$ani = 1;

					//simpan first image
					$img = ImageCreateFromGif($target);
					if($img) {
						$staticfile = "img/static-".$image_file; 
						ImageGif($img,$staticfile);
					}
				}
				else {
					$ani = 0;
					if (watermarkImage($target,$target)==FALSE)
						$errormsg = "Error: ada masalah dengan image processing";
				}

				if (!$errormsg) {

					$sql = $wpdb->prepare("INSERT INTO lontong.post (title,channel,image,animated,spoiler,tags,credit,userid,`like`,dislike,added) 
						VALUES ('%s','%s','%s',%d,'%s','%s','%s','%s',%d,%d,NOW())",
						$_POST['title'],
						$_POST['channel'],
						$image_file,
						$ani,
						$_POST['spoiler'],
						$_POST['tag'],
						$_POST['credit'],
						$userid,
						1,0);
					
					if ($wpdb->query($sql)===FALSE)
						$errormsg = "Ooops, ada masalah upload file. Info buat mimin: ".$wpdb->last_error;
				}

			}	
			else
			{
				$errormsg = "Error Upload: Ooops, ada error ketika download URL";
			}		
		}
		else
		{
			$errormsg = "Error Upload: File tidak disupport";
		}

	}

	$url = post_url($wpdb->insert_id,$_POST['title']);

	if (isset($errormsg))
		echo json_encode(array("success" => "false","message"=> "$errormsg"));
	else
		echo json_encode(array("success" => "true","message"=> $url));

	return;

}
else if (strlen($_FILES["imageFile"]["name"])>0)
{
	//upload
	$allowedExts = array("gif", "jpeg", "jpg", "png");
	$temp = explode(".", $_FILES["imageFile"]["name"]);
	$extension = end($temp);
	if ((($_FILES["imageFile"]["type"] == "image/gif")
	|| ($_FILES["imageFile"]["type"] == "image/jpeg")
	|| ($_FILES["imageFile"]["type"] == "image/jpg")
	|| ($_FILES["imageFile"]["type"] == "image/pjpeg")
	|| ($_FILES["imageFile"]["type"] == "image/x-png")
	|| ($_FILES["imageFile"]["type"] == "image/png"))
	&& ($_FILES["imageFile"]["size"] < 5000000)
	&& in_array($extension, $allowedExts))
	{
	  	if ($_FILES["imageFile"]["error"] > 0)
	    {
	    	$errormsg = "Error upload. Code utk mimin: " . $_FILES["imageFile"]["error"];
	    }
	  	else
	    {
	    	$hash = substr(md5($_POST["title"]."-".rand()),0,8);

		    $image_file = sanitize_title($_POST["title"])."-$hash.$extension"; 
		    $target = "img/".$image_file;

		    //overwrite
	    	move_uploaded_file($_FILES["imageFile"]["tmp_name"],$target);

			if (is_animated($target)) {
				$ani = 1;

				//simpan first image
				$img = ImageCreateFromGif($target);
				if($img) {
					$staticfile = "img/static-".$image_file; 
					ImageGif($img,$staticfile);
				}
			}
			else {
				$ani = 0;
				if (watermarkImage($target,$target)==FALSE)
					$errormsg = "Error: ada masalah dengan image processing";
			}
	    }
	}
	else
	{
		$errormsg = "Error upload! Tolong cek filenya (harus image) dan ukurannya harus < 5MB";
	}

	if (!isset($errormsg))
	{
		global $wpdb;

		$sql = $wpdb->prepare("INSERT INTO lontong.post (title,channel,image,spoiler,tags,credit,userid,`like`,dislike,added) 
			VALUES ('%s','%s','%s','%s','%s','%s','%s',%d,%d,NOW())",
			$_POST['title'],
			$_POST['channel'],
			$image_file,
			$_POST['spoiler'],
			$_POST['tag'],
			$_POST['credit'],
			$userid,
			1,0);
		
		if ($wpdb->query($sql)===FALSE)
			$errormsg = "Ooops, ada masalah upload file. Info buat mimin: ".$wpdb->last_error;
	}	

	$url = post_url($wpdb->insert_id,$_POST['title']);

	if (isset($errormsg))
		echo json_encode(array("success" => "false","message"=> "$errormsg"));
	else
		echo json_encode(array("success" => "true","message"=> $url));

	return;

}
else
{
	//checkpoint
	echo json_encode(array("success" => "false","message"=> "Error: Tolong cek kembali inputnya, ada yg kurang? post: ".print_r($_POST,true)." files: ".print_r($_FILES,true)));
	return;
}

?>
