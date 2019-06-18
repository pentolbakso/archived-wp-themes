<?php
require_once("../../../wp-load.php");

if (session_id() == "") session_start();

global $wpdb;
if (isset($_POST["via"]))
{
	if ($_POST["via"]=="facebook")
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
			echo json_encode(array("success" => "false","message"=> "Error Connect to $provider. Code: ".get_error_provider($e->getCode())));
			return;
		}

		//cek db
		$sql = "SELECT * FROM users WHERE email = '".mysql_real_escape_string($user_profile->email)."' AND provider='".mysql_real_escape_string($provider)."'";
		$row = $wpdb->get_row($sql);
		if ($row==null) {

			//user not found
			$errormsg = "Oops, email belum terdaftar. Silahkan daftar dulu";
			echo json_encode(array("success" => "false","message"=> "$errormsg"));
		}
		else
		{
			$_SESSION["user_id"] = $row->id;
			$_SESSION["user_photo"] = $row->avatar;
			$_SESSION["user_display"] = $row->displayname;

			echo json_encode(array("success" => "true","message"=> "login success"));
		}
	}
	else
	{
		//
		$sql = "SELECT * FROM users WHERE email = '".mysql_real_escape_string($_POST["email"])."' AND password=PASSWORD('".mysql_real_escape_string($_POST["password"])."') AND LENGTH(provider)=0";
		$row = $wpdb->get_row($sql);
		if ($row==null) {

			//user not found
			$errormsg = "Kombinasi email dan passwordnya belum pas. $sql";
			echo json_encode(array("success" => "false","message"=> "$errormsg"));
		}
		else
		{
			$_SESSION["user_id"] = $row->id;
			$_SESSION["user_photo"] = $row->avatar;
			$_SESSION["user_display"] = $row->displayname;

			echo json_encode(array("success" => "true","message"=> "login success"));
		}
	}

	return;
}

?>

<div class="modal-header">
<h3 class="modal-title"><i class='fa fa-edit'></i> Login LontongNET</h3>
</div>
<div class="modal-body">
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
      <input type="hidden" id="from" value="<?=$_POST['from']?>"/>
    </form>
  </div>
  <div class="col-md-6"><!--<div class="panel panel-default"><div class="panel-heading">atau Login melalui</div><div class="panel-body">-->
    <p>atau login via social media:</p>
    <a type="button" href="javascript:void(0)" class="btn btn-block btn-social login" style="background-color:#3b5998" via="facebook"><i class="fa fa-facebook-square" style="font-size:22px"></i> Facebook</a>
  </div><!--</div></div>-->
</div>  
<div id="progress" style="text-align:center"></div>

<div class="row" style="margin-top:20px">
  <div class="col-md-12">
    <div class="well well-sm">
    Belum join? Silahkan gabung gratis! 
    <a href="<?=get_bloginfo('url')?>/signup/" class="btn btn-danger btn-xs"><i class="fa fa-arrow-right" style="font-size:22px"></i> Daftar</a>
    </div>
  </div>
</div>
</div>

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
          url: '<?=get_bloginfo('template_directory')?>/ajax_login.php',
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
