<?php
/*
Template Name: Signup
*/
if (session_id() == "") session_start();

if (isset($_SESSION["user_id"]))
{
	get_header();

	echo "<div class='col-md-8'><div class='post well'>
		<h3><i class='fa fa-pencil'></i> Signup</h3>
		<p>Sepertinya anda sudah terdaftar. Untuk daftar baru, harus logout dulu.</p>
		<p><a href='".get_bloginfo('url')."/logout/'>Logout</a></p>
	</div></div>";

	get_sidebar();
	get_footer();
	return;
}

global $wpdb;

if (isset($_REQUEST["via"]) && $_REQUEST["via"]=="facebook" && !isset($_POST["join"]))
{
	$config = dirname(__FILE__) . '/hybridauth/config.php';
	//$config = 'hybridauth/config.php';
	require_once("hybridauth/Hybrid/Auth.php" );
	$provider = $_REQUEST["via"];

	try {

		$hybridauth = new Hybrid_Auth($config);
		$adapter = $hybridauth->authenticate($provider);
		$user_profile = $adapter->getUserProfile();

	} catch (Exception $e) {

		echo "Error Connect to $provider. Code: ".get_error_provider($e->getCode());
		return;
	}

	//lanjut step 2
	$_POST["next"] = 1;
	$provider = "facebook";
}
?>

<?php get_header(); ?>

<div class='col-md-8'><div class='post well'>

<?php if (!isset($_POST["next"])) { ?>

	<h3><i class="fa fa-pencil"></i> Signup - step 1</h3>

	<p>Daftar via Facebook biar lebih praktis! Dan jangan khawatir tentang nama asli FB, karena di step berikutnya agan bisa pilih nama alias yg lebih pas.</p>

    <div class="row">
    	<div class="col-md-5"><a type="button" href="<?=get_bloginfo("url")?>/signup/?via=facebook" class="btn btn-social btn-block" style="background-color:#3b5998"><i class="fa fa-facebook-square" style="font-size:22px"></i> Facebook</a></div>
    </div>

    <p style="margin:20px 0">atau, gunakan form dibawah ini untuk mendaftar menggunakan email</p>

    <form role="form" method="post"><div class="row"><div class="col-md-8">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" name="email" placeholder="Email (harus diisi)">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" name="password" placeholder="Password (minimal 4 karakter)">
      </div>
      <button type="submit" class="btn btn-primary" name="next" value="1">Next</button>
    </div></div></form>

<?php } else { 

	//step 2
	if (isset($_POST["join"])) {

	$sql = "SELECT id FROM lontong.users WHERE displayname='".mysql_real_escape_string($_POST["displayname"])."'";
	$wpdb->get_results($sql);
	if ($wpdb->num_rows>0)
		$errormsg = "Oops, sudah ada yang pakai Display Name '".$_POST["displayname"]."' ! coba nama yg lain";
	
	//check upload file
	$avatar = "";
	if (!isset($errormsg) && strlen($_FILES["avatar"]["name"])>0) {

		//var_dump($_FILES["avatar"]);

		$allowedExts = array("gif", "jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["avatar"]["name"]);
		$extension = end($temp);
		if ((($_FILES["avatar"]["type"] == "image/gif")
		|| ($_FILES["avatar"]["type"] == "image/jpeg")
		|| ($_FILES["avatar"]["type"] == "image/jpg")
		|| ($_FILES["avatar"]["type"] == "image/pjpeg")
		|| ($_FILES["avatar"]["type"] == "image/x-png")
		|| ($_FILES["avatar"]["type"] == "image/png"))
		&& ($_FILES["avatar"]["size"] < 50000)
		&& in_array($extension, $allowedExts))
		{
		  	if ($_FILES["avatar"]["error"] > 0)
		    {
		    	$errormsg = "Error upload avatar. Code utk mimin: " . $_FILES["avatar"]["error"];
		    }
		  	else
		    {
			    $newname = sanitize_title($_POST["displayname"]).".".$extension; 
			    $target = "avatar/".$newname;

			    //overwrite
		    	move_uploaded_file($_FILES["avatar"]["tmp_name"],$target);
			    $avatar = $newname;
		    }
		}
		else
		{
			$errormsg = "Error upload avatar! Tolong cek filenya (harus image) dan ukurannya harus < 50KB";
		}		
	}

	if ($avatar=="")
		$avatar = $_POST["photo"];
	
	//create user
	if (!isset($errormsg)) {
		
		$sql = "INSERT INTO lontong.users SET email='".mysql_real_escape_string($_POST["email"])."',
			password=PASSWORD('".mysql_real_escape_string($_POST["password"])."'),
			displayname='".mysql_real_escape_string($_POST["displayname"])."',
			avatar='".mysql_real_escape_string($avatar)."',
			bio='".mysql_real_escape_string($_POST["bio"])."',
			provider='".mysql_real_escape_string($_POST["provider"])."',
			joined=NOW()";
		if ($wpdb->query($sql)===FALSE)
			$errormsg = "Ooops, ada masalah create user baru. Info buat mimin: ".$wpdb->last_error;
		else
			$successmsg = "Oksip, anda sudah resmi jadi seorang Lontonger ! Have fun and keep uploading !";
	}

	}//end join
?>

	<h3><i class="fa fa-pencil"></i> Signup - step 2</h3>

<?php 
if (isset($successmsg)) {
	echo "<p class='text-success'>$successmsg</p>
	<p>Silahkan <a href='javascript:void(0)' data-toggle='modal' data-target='#loginModal'>Login</a></p>";	

} else {

	if ($provider == "facebook") {
		$displayname = $user_profile->displayName;
		$email = $user_profile->email;
		$photo = $user_profile->photoURL;
		$password = "";
	} else {
		$displayname = "";
		$email = $_POST['email'];
		$photo = $_POST['photo'];
		$password = $_POST['password'];
		$provider = $_POST['provider'];
	}
	
	if (isset($errormsg)) echo "<p class='text-danger'>$errormsg</p>";
?>	

	<p>Satu langkah lagi untuk bergabung!</p>

    <form role="form" method="post" enctype="multipart/form-data"><div class="row"><div class="col-md-8">
      <div class="form-group">
        <label for="displayname">Display Name <small>yang dilihat oleh user lain</small></label>
        <input type="text" class="form-control" name="displayname" value="<?=$displayname?>" placeholder="Nama (bebas dan harus lucu)">
      </div>
	  <div class="form-group">
	    <label for="avatarFile">Avatar Photo <small>optional</small></label>
	    <p><img src="<?=$photo?>" style="width:50px; height:50px"/></p>
	    <input type="file" id="avatarFile" name="avatar">
	    <p class="help-block">file size maks: 50KB</p>
	  </div>
      <div class="form-group">
        <label for="password">Tentang Agan <small>optional</small></label>
        <textarea class="form-control" name="bio" rows="5" placeholder="Cerita sedikit dong tentang agan .."></textarea>
      </div>
      <input type="hidden" name="email" value="<?=$email?>"/>
      <input type="hidden" name="password" value="<?=$password?>"/>
      <input type="hidden" name="photo" value="<?=$photo?>"/>
      <input type="hidden" name="provider" value="<?=$provider?>"/>
      <input type="hidden" name="next" value="1"/>
      <button type="submit" class="btn btn-primary" name="join">Join</button>
    </div></div></form>

<?php }

} //end if next

?>

</div></div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>