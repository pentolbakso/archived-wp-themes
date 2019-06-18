<?php
require_once("../../../wp-load.php");

if (session_id() == "") session_start();

global $wpdb;
if (isset($_POST["mode"]))
{

  if ($_POST["mode"]=="up") $vote = 1;
  else $vote = -1;

  if (isset($_SESSION["user_id"]))
  {
    $sql = "INSERT IGNORE INTO lontong.vote (userid,postid,vote,added) VALUES (
        ".$_SESSION["user_id"].",".$_POST["postid"].",$vote,NOW())";
  }
  else
  {
    //anon
    $sql = "INSERT IGNORE INTO lontong.anon_vote (ip,postid,vote,added) VALUES (
        '".$_SERVER['REMOTE_ADDR']."',".$_POST["postid"].",$vote,NOW())";
  }

  $ret = $wpdb->query($sql);
  if ($ret===FALSE)
    $errormsg = "Oops, submit comment error: ".$wpdb->last_error;
  else if ($ret==0)
    $errormsg = "Sudah pernah vote post ini?";

  if (isset($errormsg))
    echo json_encode(array("success" => "false","message"=> "$errormsg"));
  else {
    //update poin
    if ($_POST["mode"]=="up")
      $sql = "UPDATE lontong.post SET `like`=`like`+1 WHERE id=".$_POST["postid"];
    else
      $sql = "UPDATE lontong.post SET `dislike`=`dislike`+1 WHERE id=".$_POST["postid"];
    $wpdb->query($sql);

    //update stat
    $postid = $_POST["postid"];
    if ($_POST["mode"]=="up")
      $sql = "INSERT INTO lontong.stat (postid,thedate,likes) VALUES($postid,NOW(),1) 
        ON DUPLICATE KEY UPDATE likes=likes+1";
    else
      $sql = "INSERT INTO lontong.stat (postid,thedate,dislikes) VALUES($postid,NOW(),1) 
        ON DUPLICATE KEY UPDATE dislikes=dislikes+1";
    $wpdb->query($sql);

    echo json_encode(array("success" => "true","message"=> "OK"));
  }

}

?>