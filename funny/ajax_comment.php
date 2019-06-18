<?php
require_once("../../../wp-load.php");

if (session_id() == "") session_start();

//sleep(1);

if (isset($_SESSION["user_id"])) {
  $fi = "userid";
  $va = $_SESSION["user_id"];

  $avatarimg = $_SESSION["user_photo"];
  $userid = $_SESSION["user_id"];
  $userdisplay = $_SESSION["user_display"];

} else {
  //anonim

  $fi = "ip";
  $va = $_SERVER['REMOTE_ADDR'];

  $avatarimg = get_bloginfo("template_directory")."/anon.jpg";
  $userid = 0;
  $userdisplay = "Anonim";
}


global $wpdb;
if (!empty($_POST["comment"]))
{
  //insert stat
  $sql = "INSERT INTO lontong.stat (postid,thedate,comments) VALUES(".$_POST["postid"].",NOW(),1) 
    ON DUPLICATE KEY UPDATE comments=comments+1";
  $wpdb->query($sql);  
} 

if (isset($_POST["commentid"]) && !empty($_POST["comment"]))
{
  //reply

  $sql = "INSERT INTO lontong.comment ($fi,postid,commentid,comment,added) VALUES (
    '$va',".$_POST["postid"].",".$_POST["commentid"].",'".mysql_real_escape_string($_POST["comment"])."',NOW())";

  if ($wpdb->query($sql)===FALSE)
    $errormsg = "Oops, submit comment error: ".$wpdb->last_error;
  else {

    $content = "<div class='comment'>
      <img src='$avatarimg' class='avatar' style='float:left;margin:3px 10px 10px 0'/>
      <div class='topinfo'><a href='".get_bloginfo('url')."/u/$userid/'>$userdisplay</a> - <span>baru</span></div>
      <div class='comment-content'>".$_POST['comment']."</div>
      <div class='clearfix'></div>
      </div>";
  }

  if (isset($errormsg))
    echo json_encode(array("success" => "false","message"=> "$errormsg"));
  else
    echo json_encode(array("success" => "true","message"=> "$content"));

}
else if (isset($_POST["postid"]) && !empty($_POST["comment"]))
{
  //new comment

  $sql = "INSERT INTO lontong.comment ($fi,postid,comment,added) VALUES (
    '$va',".$_POST["postid"].",'".mysql_real_escape_string($_POST["comment"])."',NOW())";
  if ($wpdb->query($sql)===FALSE)
    $errormsg = "Oops, submit comment error: ".$wpdb->last_error;
  else {

    $content = "<tr>
    <td style='width:1px'><img src='$avatarimg' class='avatar'/></td>
    <td><div class='comment'>
      <div class='topinfo'><a href='".get_bloginfo('url')."/u/$userid/'>$userdisplay</a> - <span>baru</span></div>
      <div class='comment-content'>".$_POST['comment']."</div>
      <div class='bottominfo'>
        <a href='javascript:void(0)'><i class='fa fa-thumbs-o-up'></i> like</a>
        <a href='javascript:void(0)'><i class='fa fa-reply'></i> balas</a>
        <a href='javascript:void(0)'><i class='fa fa-warning'></i> lapor!</a>
      </div>
      <div class='replybox'></div>
      </div>
    </td>   
    </tr>";
  }

  if (isset($errormsg))
    echo json_encode(array("success" => "false","message"=> "$errormsg"));
  else
    echo json_encode(array("success" => "true","message"=> $content));

}

?>