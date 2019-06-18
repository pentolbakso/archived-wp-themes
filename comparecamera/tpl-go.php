<?php
$asin = get_query_var('asin');
$title = get_query_var('title');

$AFF_ID = "compare-camera-20";

$url = "http://www.amazon.com/dp/$asin/?tag=".$AFF_ID;

header("Location: ".$url);
?>