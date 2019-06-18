<?php
/*
Template Name: Login
*/
if (session_id() == "") session_start();

if (isset($_SESSION["user_id"]))
{
	//$target = "img/test-wm.jpg"; 
	//watermarkImage("img/test.jpg",$target);

	get_header();

	echo "<div class='col-md-8'><div class='post well'>
		<h3><i class='fa fa-lock'></i> Login</h3>
		<p>Saat ini, anda sudah login sebagai ".$_SESSION["user_display"]."</p>
		<p>Bukan ".$_SESSION["user_display"]." ?? <a href='".get_bloginfo('url')."/logout/'>Logout</a></p>
	</div></div>";

	get_sidebar();
	get_footer();
	return;
}

global $wpdb;
if (isset($_REQUEST["via"]))
{
	if ($_REQUEST["via"]=="facebook")
	{
		//via social media
		$config = dirname(__FILE__) . '/hybridauth/config.php';
		//$config = 'hybridauth/config.php';
		require_once("hybridauth/Hybrid/Auth.php" );
		$provider = $_REQUEST["via"];

		try {

			$hybridauth = new Hybrid_Auth($config);
			$adapter = $hybridauth->authenticate($provider);
			$user_profile = $adapter->getUserProfile();

		} catch (Exception $e) {
			$errormsg = "Error Connect to $provider. Code: ".get_error_provider($e->getCode());
			echo $errormsg;
			//echo json_encode(array("success" => "false","message"=> "Error Connect to $provider. Code: ".get_error_provider($e->getCode())));
			return;
		}

		//cek db
		$sql = $wpdb->prepare("SELECT * FROM lontong.users WHERE email = '%s' AND provider='%s'",$user_profile->email,$provider);
		$row = $wpdb->get_row($sql);
		if ($row==null) {

			//user not found
			$errormsg = "Oops, email belum terdaftar. Silahkan daftar dulu";
			//echo json_encode(array("success" => "false","message"=> "$errormsg"));
		}
		else
		{
			if (strlen($row->avatar)==0)
				$avatarimg = get_bloginfo("template_directory")."/anon.jpg";
			else if (stripos($row->avatar, "facebook.com")!==FALSE)
				$avatarimg = $row->avatar;
			else
				$avatarimg = get_bloginfo("url")."/avatar/".$row->avatar;

			$_SESSION["user_id"] = $row->id;
			$_SESSION["user_photo"] = $avatarimg;
			$_SESSION["user_display"] = $row->displayname;

			//echo json_encode(array("success" => "true","message"=> "login success"));
			$session = md5("$row->id <> ".$_SERVER["REMOTE_ADDR"]);
			$domain = ($_SERVER['HTTP_HOST'] != 'localhost' && $_SERVER['HTTP_HOST'] != '127.0.0.1') ? $_SERVER['HTTP_HOST'] : '';
			setcookie("kuekering", $session, time()+60*60*24*30,'/',$domain,false);
			$wpdb->query("UPDATE lontong.users SET loginsession='$session',lastlogin=NOW() WHERE id=$row->id");

			if (empty($_REQUEST['from']))
				$redir = get_bloginfo("url");
			else
				$redir = $_REQUEST['from'];

			header("Location: $redir");
			return;
		}
	}
	else
	{
		//
		$sql = $wpdb->prepare("SELECT * FROM lontong.users WHERE email = '%s' AND password=PASSWORD('%s') AND LENGTH(provider)=0",$_POST["email"],$_POST["password"]);
		$row = $wpdb->get_row($sql);
		if ($row==null) {

			//user not found
			$errormsg = "Kombinasi email dan passwordnya belum pas. $sql";
			echo json_encode(array("success" => "false","message"=> "$errormsg"));
		}
		else
		{
			if (strlen($row->avatar)==0)
				$avatarimg = get_bloginfo("template_directory")."/anon.jpg";
			else if (stripos($row->avatar, "facebook.com")!==FALSE)
				$avatarimg = $row->avatar;
			else
				$avatarimg = get_bloginfo("url")."/avatar/".$row->avatar;

			$_SESSION["user_id"] = $row->id;
			$_SESSION["user_photo"] = $avatarimg;
			$_SESSION["user_display"] = $row->displayname;

			$session = md5("$row->id <> ".$_SERVER["REMOTE_ADDR"]);
			$domain = ($_SERVER['HTTP_HOST'] != 'localhost' && $_SERVER['HTTP_HOST'] != '127.0.0.1') ? $_SERVER['HTTP_HOST'] : '';
			setcookie("kuekering", $session, time()+60*60*24*30,'/',$domain,false);
			$wpdb->query("UPDATE lontong.users SET loginsession='$session',lastlogin=NOW() WHERE id=$row->id");

			echo json_encode(array("success" => "true","message"=> "login success"));
		}

		return;
	}

}
?>

<?php get_header(); ?>

<div class='col-md-8'><div class='post well'>

<h3><i class="fa fa-lock"></i> Login</h3>

<div class="row">
  <div class="col-md-6" style="border-right:1px solid #e5e5e5">
    <form role="form" method="post" action="<?=get_bloginfo("url")?>/login/">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" class="form-control input-sm" name="email" id="email" placeholder="Email">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control input-sm" name="password" id="password" placeholder="Password">
      </div>
      <a type="button" href="javascript:void(0)" class="btn btn-primary login" via="">Login</button>
      <a class="btn btn-link" href="<?=get_bloginfo('url')?>/signup/">Daftar</a>
      <input type="hidden" id="from" value="<?=$_REQUEST['from']?>"/>
    </form>
    <div id="progress" style="text-align:center"></div>
  </div>
  <div class="col-md-6"><!--<div class="panel panel-default"><div class="panel-heading">atau Login melalui</div><div class="panel-body">-->
    <p>atau login via social media:</p>
    <a type="button" href="<?=get_bloginfo("url")?>/login/?via=facebook" class="btn btn-block btn-social" style="background-color:#3b5998;color:#fff"><i class="fa fa-facebook-square" style="font-size:22px"></i> Facebook</a>
    <?php if (isset($errormsg)) echo "<div class='text-danger'>$errormsg</div>"; ?>
  </div><!--</div></div>-->
</div>  

<div class="row" style="margin-top:20px">
  <div class="col-md-12">
    <p>
    Belum join? Silahkan gabung gratis! 
    <a href="<?=get_bloginfo('url')?>/signup/" class="btn btn-danger btn-xs" style="color:#fff"><i class="fa fa-arrow-right" ></i> Daftar</a>
    </p>
  </div>
</div>

</div></div>

<script type = "text/javascript">
  $(".login").click(function() 
  {   
      var via = $(this).attr('via');
      var email = $("#email").val();
      var password = $("#password").val();

      $("#progress").html("memproses...tunggu bentar");

      $.ajax({
          cache: false,
          type: 'POST',
          dataType: 'json',
          url: '<?=get_bloginfo('url')?>/login/',
          data: 'via='+via+'&email='+email+'&password='+password,
          success: function(data) 
          {
              if (data.success == 'true') {
              	//redirect
              	$("#progress").html("success..redirecting");
              	var from = $("#from").val();
              	window.location.href = from;

              } else {
              	//display error
              	$("#progress").html("<span class='text-danger'>"+data.message+"</span>");
              }
          }
      });
  });
</script>

<?php get_sidebar(); ?>

<?php get_footer(); ?>