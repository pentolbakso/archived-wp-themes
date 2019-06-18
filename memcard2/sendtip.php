<?php
include "../../../wp-load.php";
include "myfunctions.php";

$com = trim($_POST['comment']);
if (strlen($com)==0)
{
	echo "<span class='text-error'>Comment should not be empty!</span>";
	return;
}
if ($_POST['iamhuman']!='yes')
{
	echo "<span class='text-error'>Comment failed. Forgot something?</span>";
	return;
}

global $wpdb;
$sql = "INSERT INTO memorycard.mem_tips (comment,added) VALUES ('".mysql_real_escape_string($com)."',NOW())";
$res = $wpdb->query($sql);

echo "<span class='text-success'>Comment has been sent. Thanks for your input</span>";

?>
