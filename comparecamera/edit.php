<?php
include("../../../wp-load.php"); 

$field = $_GET['field'];
$camid = $_GET['id'];

global $wpdb;

if ($_POST['act'])
{
	$sql = "UPDATE compare.camera SET ".$field."='".$_POST['data']."' WHERE camera_id='".$camid."'";
	
	echo "<div style='padding:5px 10px;background-color:#d4ffaa'>";
	if ($wpdb->query($sql)===FALSE) echo "Update Failed<br/><textarea>$sql</textarea>";
	else echo "Update Success";
	echo "</div>";
}

$sql = "SELECT hint FROM compare.hint WHERE field='".$field."'";
$hint = $wpdb->get_row($sql)->hint;

$sql = "SELECT * FROM compare.camera WHERE camera_id='".$camid."'";
$row = $wpdb->get_row($sql,ARRAY_A);
?>
<body style='font-family:Cambria'>
<h2><?=strtoupper($field)?> &raquo; <?=$camid?></h2>
<form method='post'>
	<textarea name='data' style='width:500px;height:200px;padding:5px' wrap="off"><?=$row[$field]?></textarea>
	<p><small>Note: <?=nl2br($hint)?></small></p>
	<br/><input name='act' type='submit' value='submit' style='padding:5px 20px;'>
</form>
</body>