<?php
/*
Template Name: Upload (No Menu)
*/
if (session_id() == "") session_start();

if (isset($_SESSION["user_id"])) $userid = $_SESSION["user_id"];
else $userid = 0;	//anonymous	

if (isset($_POST["upload"]) && strlen($_POST["title"])==0)
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
else if (isset($_POST["upload"]))
{
	//checkpoint
	echo json_encode(array("success" => "false","message"=> "Error: Tolong cek kembali inputnya, ada yg kurang?"));
	return;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title>Upload For Mobile</title>
<?php wp_head(); ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" type="image/x-icon" href="<?=get_bloginfo('template_directory')?>/lontongnet.ico">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootswatch/3.0.3/cosmo/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?=get_bloginfo('template_directory')?>/basic.css" type="text/css" media="screen" />
<link href="https://fonts.googleapis.com/css?family=Droid+Sans" rel="stylesheet" type="text/css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
</head>
<body>

<div class='container'>
<div class='row'><div id='main'>

<div class='col-md-12'><div class='post well'>

<h3><i class="fa fa-upload"></i> Upload</h3>

<?php
if (!isset($_SESSION["user_id"]) && !isset($_GET["anonymous"])) {
?>

<p>Idealnya sih untuk upload gambar atau video ke <?=get_bloginfo("name")?> harus login. 
Tapi disini bebas aja lah. Mau login sok, mau anonim kayak ninja juga monggo. Yg penting upload :)</p>

<div class="row"><div class="col-md-6">
	<a class="btn btn-default input-block" href="<?=get_bloginfo('url')?>/upload/?anonymous"><i class="fa fa-question-circle"></i> Upload Sebagai Anonim</a>
</div></div>	

<p style="margin:20px 0">atau, login untuk upload</p>

<div class="row"><div class="col-md-6">
	<a class="btn btn-primary input-block" href="<?=get_bloginfo('url')?>/login/?from=<?=$_SERVER["REQUEST_URI"]?>"><i class="fa fa-arrow-right"></i> Login</a>
</div></div>	

<?php } else { ?>

<div id="howToUpload">
	<p>Pilih cara upload: </p>
	<div class="row">
		<div class="col-md-6"><a href="javascript:void(0)" faction="url" class="btn btn-lg btn-primary showForm btn-block">Upload dari URL / Youtube</a>
		<p><em>kalau imagenya berada di server lain (berupa URL) atau kalau mau share video youtube</em></p></div>
		<div class="col-md-6"><a href="javascript:void(0)" faction="image" class="btn btn-lg btn-primary showForm btn-block">Upload File Image</a>
		<p><em>kalau file imagenya ada di handphone lo (koleksi bro? hehe)</em></p></div>
	</div>
</div>

<div id="uploadDiv" style="display:none;margin-top:10px">
    <form role="form" method="post" id="uploadForm" enctype="multipart/form-data"><div class="row"><div class="col-md-12"><div class="well">

      <div style="display:none" id="urlStuff">
      <div class="form-group">
        <label for="url">URL</label>
        <input type="text" class="form-control" id="url" name="url" placeholder="http://">
        <p class="help-block">Alamat URL dari image atau YouTube URL (jika mau submit video)</p>
      </div>
      </div>

      <div style="display:none" id="fileStuff">
	  <div class="form-group">
	    <label for="imageFile">File Image</label>
	    <input type="file" id="imageFile" name="imageFile">
	    <p class="help-block">ukuran maks: 5MB, format: png,jpg,gif</p>
	  </div>
	  </div>

      <div class="form-group">
        <label for="title">Judul</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="judul yg membuat orang penasaran untuk cek"/>
      </div>

<?php
$res = $wpdb->get_results("SELECT * FROM lontong.channel");
foreach($res as $row)
  $options .= "<option value='$row->slug'>$row->name</option>";
?>

      <div class="form-group">
        <label for="title">Pilih Channel</label>
        <select class="form-control" id="channel" name="channel"><?=$options?></select>
      </div>

      <div class="form-group">
        <label for="spoiler"><input type="checkbox" name="showSpoiler" id="showSpoiler" class="showStuff"/> Spoiler <small>(optional)</small></label>
        <div style="display:none" id="spoilerStuff">
        <textarea class="form-control" id="spoiler" name="spoiler" placeholder=""></textarea>
        <p class="help-block">Kalau sekiranya joke-nya bakal susah dipahami orang lain, kasih sedikit contekan dong :)</p>
        </div>
      </div>

      <div class="form-group">
        <label for="tag"><input type="checkbox" name="showTags" id="showTags" class="showStuff"/> Tags <small>(optional)</small></label>
		<div style="display:none" id="tagStuff">
        <input type="text" class="form-control" id="tag" name="tag" placeholder="">
        <p class="help-block">Kata kunci yg berhubungan dengan image/video. Pisahkan dengan koma, contoh: kucing, karaoke, lucu</p>
        </div>
      </div>

      <div class="form-group">
        <label for="credit"><input type="checkbox" name="showCredit" id="showCredit" class="showStuff"/> Credits <small>(optional)</small></label>
		<div style="display:none" id="creditStuff">
        <input type="text" class="form-control" id="credit" name="credit" placeholder="http://">
        <p class="help-block">Biasakan untuk memberi apresiasi buat pembuat asli joke/image/video ini</p>
        </div>
      </div>

      <div class="form-group">
        <label for="credit"><input type="checkbox" name="comply" checked/> Upload ini sudah sesuai dengan aturan LontongNET</label>
      </div>
      <input type="hidden" name="upload" value="1"/>
      <button type="button" class="btn btn-primary" id="uploadButton"><i class="fa fa-right-arrow"></i> Upload</button>
    </div></div></div></form>
</div>

<div id="uploadProgress"></div>

<?php } ?>

</div></div>

<script type = "text/javascript">

$(".showForm").click(function() 
{   
  var act = $(this).attr('faction');

  if (act == "url") {

  	$("#urlStuff").show();
  	$("#fileStuff").hide();

  } else {

  	$("#urlStuff").hide();
  	$("#fileStuff").show();
  }

  $("#howToUpload").hide();
  $("#uploadDiv").fadeIn("slow");

});

$(".showStuff").change(function() 
{   
  var act = $(this).attr('id');

  //alert(act);

  if (act == "showSpoiler") $("#spoilerStuff").toggle();
  else if (act == "showCredit") $("#creditStuff").toggle();
  else if (act == "showTags") $("#tagStuff").toggle();

});

$("#uploadButton").click(function() 
{   
  var formData = new FormData($('form')[0]);

  $("#uploadProgress").html("<i class='fa fa-cog fa-spin'></i> Memproses ... tunggu bentar ya");

  $.ajax({
      cache: false,
      type: 'POST',
      dataType: 'json',
      url: '<?=get_bloginfo('url')?>/upload/',
      data: formData,
      contentType: false,
      processData: false,
      success: function(data) 
      {
          if (data.success == 'true') {
          	//redirect
          	$("#uploadProgress").html("Upload Success Gan !!");
          	$("#uploadButton").addClass('disabled');
          	
          	//var url = data.message;
          	/*
          	setTimeout(function(){
          		window.location.href = url;
			}, 1000);
						*/


          } else {
          	//display error
          	$("#uploadProgress").html("<span class='text-danger'>"+data.message+"</span>");
          }
      }
  });
});

</script>

</div>
</div>

</div> <!-- end container -->

</html>
