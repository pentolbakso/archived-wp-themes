<?php
$s1 = get_query_var("s1");
$s2 = get_query_var("s2");

include_once ("myfunctions.php");

global $wpdb;
$cam1 = $cam2 = "";
//convert dulu ke slug
$row1 = $wpdb->get_row("SELECT * FROM compare.camera WHERE name='".$s1."'");
if (!$row1)
	$errormsg[] = "Camera <strong>".$s1."</strong> not found !";
else
	$cam1 = $row1->camera_id;

$row2 = $wpdb->get_row("SELECT * FROM compare.camera WHERE name='".$s2."'");
if (!$row2)
	$errormsg[] = "Camera <strong>".$s2."</strong> not found !";
else
	$cam2 = $row2->camera_id;

if ($s1 == $s2)
	$errormsg[] = "Both camera are identic. Nothing to compare !";

if (count($errormsg)==0)
{
	$slug = versus_slug($cam1,$cam2);
	$row = $wpdb->get_row("SELECT slug FROM compare.summary WHERE slug='".$slug."'");
	if ($wpdb->num_rows>0)
	{
		$url = get_bloginfo("url")."/c/".$slug."/";
	}
	else
	{
		//not found eh?
		$url = get_bloginfo("url")."/c/".$cam1."-vs-".$cam2."/";
	}
	header("Location: ".$url);
	return;
}
?>

<?php include("header.php"); ?>

<?php
if (count($errormsg)>0)
{
	echo "<div class='alert alert-error' style='margin-top:50px'><h4>Ooops, we have problem!</h4>";
	foreach ($errormsg as $e)
		echo "$e<br/>";
	echo "</div>";
}
?>

<?php include ("comparebox.php"); ?>

<?php include("footer.php"); ?>