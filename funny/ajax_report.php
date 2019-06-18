<?php
require_once("../../../wp-load.php");

if (session_id() == "") session_start();

if (isset($_SESSION["user_display"]))
  $name = $_SESSION["user_display"];
else
  $name = "Anonim (".$_SERVER["REMOTE_ADDR"].")";

global $wpdb;

if (isset($_POST["commentid"]))
{
  $sql = $wpdb->prepare("INSERT INTO lontong.lapor (name,commentid,added) VALUES('%s',%d,NOW())",$name,$_POST['commentid']);
  if ($wpdb->query($sql)===FALSE)
    $errormsg = "Oops, error: ".$wpdb->last_error;

  if (isset($errormsg))
    echo json_encode(array("success" => "false","message"=> "$errormsg"));
  else
    echo json_encode(array("success" => "true","message"=> "OK"));

}

?>