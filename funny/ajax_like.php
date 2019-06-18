<?php
require_once("../../../wp-load.php");

if (session_id() == "") session_start();

global $wpdb;

if (isset($_POST["commentid"]))
{

  if (isset($_SESSION["user_id"]))
  {
    $sql = "INSERT IGNORE INTO lontong.likes (userid,commentid,added) VALUES (
        ".$_SESSION["user_id"].",".$_POST["commentid"].",NOW())";
  }
  else
  {
    //anon
    $sql = "INSERT IGNORE INTO lontong.anon_likes (ip,commentid,added) VALUES (
        '".$_SERVER['REMOTE_ADDR']."',".$_POST["commentid"].",NOW())";
  }

  $ret = $wpdb->query($sql);
  if ($ret===FALSE)
    $errormsg = "Oops, error: ".$wpdb->last_error;
  else if ($ret==0)
  	$errormsg = "Sudah pernah like comment ini?";
  else //update comment poin
  	$wpdb->query($wpdb->prepare("UPDATE lontong.comment SET poin=poin+1 WHERE id=%d",$_POST["commentid"]));

  if (isset($errormsg))
    echo json_encode(array("success" => "false","message"=> "$errormsg"));
  else
    echo json_encode(array("success" => "true","message"=> "OK"));

}

?>