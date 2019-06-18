<?php

include("../../../wp-load.php"); 
$cam1 = $_GET['cam1'];
$cam2 = $_GET['cam2'];

function versus_slug($cam1,$cam2)
{
	$arr[]=$cam1;
	$arr[]=$cam2;
	sort($arr);
	$slug = $arr[0]."-vs-".$arr[1];

	return($slug);
}

global $wpdb;
if ($_POST['act'])
{
	$sql = "REPLACE INTO compare.summary SET pagetitle='".$_POST['pagetitle']."',
		pagedesc='".$_POST['pagedesc']."',
		pagekeyword='".$_POST['pagekeyword']."',
		summary='".$_POST['data']."',slug='".$_POST['slug']."',updated=NOW()";
	
	echo "<div style='padding:5px 10px;background-color:#d4ffaa'>";
	if ($wpdb->query($sql)===FALSE) echo "Update Failed : $wpdb->last_error";
	else echo "Update Success";
	echo "</div>";
}

$slug = versus_slug($cam1,$cam2);
$sql = "SELECT * FROM compare.summary WHERE slug='".$slug."'";
$row = $wpdb->get_row($sql,ARRAY_A);
?>

<html>

<head>

<!-- Skin CSS file -->
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/assets/skins/sam/skin.css">
<!-- Utility Dependencies -->
<script src="http://yui.yahooapis.com/2.9.0/build/yahoo-dom-event/yahoo-dom-event.js"></script> 
<script src="http://yui.yahooapis.com/2.9.0/build/element/element-min.js"></script> 
<!-- Needed for Menus, Buttons and Overlays used in the Toolbar -->
<script src="http://yui.yahooapis.com/2.9.0/build/container/container_core-min.js"></script>
<script src="http://yui.yahooapis.com/2.9.0/build/menu/menu-min.js"></script>
<script src="http://yui.yahooapis.com/2.9.0/build/button/button-min.js"></script>
<!-- Source file for Rich Text Editor-->
<script src="http://yui.yahooapis.com/2.9.0/build/editor/editor-min.js"></script>

</head>
<body style='font-family:Cambria'>
<h2>Summary of <small><?=$slug?></small></h2>
<form method='post' class="yui-skin-sam">
	Page Title: <input name='pagetitle' type='text' style='width:400px' value='<?=$row['pagetitle']?>'/>
	<br/>Desc: <input name='pagedesc' type='text' style='width:400px' value='<?=$row['pagedesc']?>'/>
	<br/>Keywords: <input name='pagekeyword' type='text' style='width:400px' value='<?=$row['pagekeyword']?>'/>
	<br/><textarea id='msgpost' name='data'><?=$row['summary']?></textarea>
	<input name='slug' type='hidden' value='<?=$slug?>'/>	<br/><input name='act' type='submit' value='submit' style='padding:5px 20px;'/>
</form>
</body>

<script>
var myEditor = new YAHOO.widget.Editor('msgpost', {
    height: '500px',
    width: '500px',
    handleSubmit: 'true',
    dompath: true //Turns on the bar at the bottom
});
myEditor.render();
</script>

</html>