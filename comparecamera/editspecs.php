<?php
include("../../../wp-load.php"); 

$camid = $_GET['id'];

global $wpdb;

if ($_POST['act'])
{
	$sql = "SELECT * FROM compare.specdetails";
	$res = $wpdb->get_results($sql,ARRAY_A);
	foreach($res as $row)
	{
		$set .= $row['field']."='".$wpdb->escape($_POST[$row['field']])."',";
	}

	$sql = "REPLACE INTO compare.specs SET ".$set."updated=NOW(),camera_id='".$camid."'";

	echo "<div style='padding:5px 10px;background-color:#d4ffaa'>";
	if ($wpdb->query($sql)===FALSE) echo "Update Failed<br/><textarea>$sql</textarea>";
	else echo "Update Success";
	echo "</div>";
}
?>

<body style='font-family:Cambria;'>
<style>
td { padding:0px;}
tr:hover { background-color: #ffc000;}
</style>
<h2>Specs <?=$camid?></h2>
<p>Format: String value=int/double value. eg: 25.4 megapixels=25.4
<br/>tambahkan int/double, jika ingin dicompare
<br/>JANGAN GUNAKAN NILAI "0" (NOL)
</p>

<?php
$sql = "SELECT * FROM compare.specs WHERE camera_id='".$camid."' LIMIT 1";
$val = $wpdb->get_row($sql,ARRAY_A);

$sql = "SELECT * FROM compare.specdetails";
$dtlres = $wpdb->get_results($sql,ARRAY_A);

echo "<form method='post'><table cellspacing=0 cellpadding=0>";
foreach ($dtlres as $dtl)
{
	$thefield = $dtl['field'];
	$theval = $val[$thefield];
	echo "<tr ><td>".$thefield." - <small>(".$dtl['caption'].")</small></td>
	<td><input type='text' name='".$thefield."' value='".$theval."' style='width:300px'/></td>
	<td><small style='color:#005fbf'>".$dtl['hint']."</small></td></tr>";
}

echo "<tr><td></td><td><input type='submit' value='Submit' name='act'/></td><td></td></tr>
	</table></form>";

$row = $wpdb->get_row($sql,ARRAY_A);
?>
</body>
