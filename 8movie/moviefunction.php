<?php
include_once('ip2locationlite.class.php');

function poster($posterurl)
{
	// ex: ../movieposter/xxx.jpg
	$posterurl = str_replace("../","",$posterurl);	
	if (strlen($posterurl)>0 && file_exists($posterurl))
	{		
		$filename = get_bloginfo("url")."/".$posterurl;
	}
	else
	{
		$filename = get_bloginfo("template_directory")."/img/poster-notfound.png";
	}
	return($filename);
}
function clip($clipurl)
{
	// ex: ../movieclip/xxx.jpg	
	$clipurl = str_replace("../","",$clipurl);
	if (strlen($clipurl)>0 && file_exists($clipurl))
	{	
		$filename = get_bloginfo("url")."/".$clipurl;
	}
	else
	{
		$filename = get_bloginfo("template_directory")."/img/clip-notfound.png";
	}
	return($filename);
}
function get_youtube_id($url)
{
	//parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
	//return $my_array_of_vars['v'];    
	if (preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches))
		return($matches[0]);
	
	return "";
}
function rating($imdb_rating,$rt_rating)
{
	if (strlen($imdb_rating)>0)
		$rating = (int) (100 * $imdb_rating);
	else if (strlen($rt_rating)>0)
		$rating = $rt_rating;
	else
		$rating = rand(10,20);

	return "<div class='rating_bar'>
	<div style='width:".$rating."%'></div>
	</div>";
}

function noyear($title)
{
	$title = substr($title,0,strlen($title)-6);	
	return(trim($title));
}

function lp($id,$title)
{
	$title = str_replace("(","",$title);
	$title = str_replace(")","",$title);
	$title = str_replace("'","",$title);	
	$title = str_replace("\"","",$title);
	$title = str_replace(".","",$title);
	$title = str_replace("?","",$title);
	$title = str_replace("¡","",$title);
	$title = str_replace("¿","",$title);
	$title = str_replace(",","-",$title);
	$title = str_replace(" ","-",$title);
	$title = strtolower(trim($title));
	
	$title = random_title_slug($id,$title);
	$url = get_bloginfo("url")."/v/".$id."-".$title."/";
	return($url);
}

function trailer($url)
{
	$url = $url.'?fs=1&amp;hl=en_US';
	$width = 550;
	$height = 350;
	
	return "<object width='".$width."' height='".$height."'>
	<param name='movie' value='".$url."'?fs=0&amp;hl=en_US'></param>
	<param name='allowFullScreen' value='true'></param>
	<param name='allowscriptaccess' value='always'></param>
	<embed src='".$url."s?fs=0&autohide=1&controls=0&modestbranding=1&rel=0&showinfo=0&amp;hl=en_US' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='false' width='".$width."' height='".$height."'></embed>
	</object>";	
}


function parse($msg,$arr_movie)
{
	$msg = str_ireplace('[TITLE]',"<em>".noyear($arr_movie->title)."</em>",$msg);
	
	$studio = (strlen($arr_movie->rt_studio)>0)?$arr_movie->rt_studio:"Unknown";	
	$msg = str_ireplace('[STUDIO]',$studio,$msg);
	
	$release = (strlen($arr_movie->rt_releasedate)>0)?$arr_movie->rt_releasedate:$arr_movie->year;	
	$msg = str_ireplace('[RELEASEDATE]',$release,$msg);
	
	$director = (strlen($arr_movie->rt_director)>0)?$arr_movie->rt_director:"Unknown";	
	$msg = str_ireplace('[DIRECTOR]',$director,$msg);
	
	$cast = (strlen($arr_movie->rt_cast)>0)?$arr_movie->rt_cast:"some actors/actresses";	
	$msg = str_ireplace('[CAST]',$cast,$msg);
	
	$imdbvoter = (strlen($arr_movie->imdb_voter)>0)?$arr_movie->imdb_voter:rand(1,100);
	$msg = str_ireplace('[IMDBVOTER]',$imdbvoter,$msg);
	
	$imdbrating = (strlen($arr_movie->imdb_rating)>0)?$arr_movie->imdb_rating:"0";	
	$imdbrating = (int) 100 * $imdbrating;	
	$msg = str_ireplace('[IMDBRATING]',$imdbrating,$msg);
	
	$rtcritics = (strlen($arr_movie->rt_critics_score)>0)?$arr_movie->rt_critics_score:"0";
	$msg = str_ireplace('[RTCRITICS]',$rtcritics,$msg);
	
	$rtaudience = (strlen($arr_movie->rt_audience_score)>0)?$arr_movie->rt_audience_score:"0";
	$msg = str_ireplace('[RTAUDIENCE]',$rtaudience,$msg);
	
	$rtruntime = (strlen($arr_movie->rt_runtime)>0)?$arr_movie->rt_runtime:rand(60,120);
	$msg = str_ireplace('[RTRUNTIME]',$rtruntime,$msg);

	$msg = str_ireplace('[IMDBOUTLINE]',$arr_movie->imdb_outline,$msg);
	
	return($msg);
}

function random_title_slug($id,$title_slug)
{
	$i = $id%5;

	if ($i==0) $title_slug = "watch-".$title_slug."-free-online-megavideo";
	else if ($i==1) $title_slug = "watch-".$title_slug."-online-for-free-megavideo";	
	else if ($i==2) $title_slug = "watch-".$title_slug."-movie-online-megavideo-free";	
	else if ($i==3) $title_slug = "watch-".$title_slug."-online-megavideo";	
	else $title_slug = "watch-".$title_slug."-online-now-at-megavideo-free";	
	
	return($title_slug);
}

function random_welcome($id,$title)
{
	$i = $id%10;
	
	if ($i==0)
		$msg = "Watch [TITLE] online for free at megavideo, putLocker, videobb and novamov. [IMDBOUTLINE].
			Movie [TITLE] was directed by [DIRECTOR] and released on [RELEASEDATE].
			Right now, [IMDBVOTER] IMDB users rate the movie [IMDBRATING]% and RottenTomatoes people rate it [RTAUDIENCE]%.
			Cast of characters: [CAST]. Runtime: [RTRUNTIME] min.";
	else if ($i==1)
		$msg = "Watch or download [TITLE] at megavideo, putLocker,videobb and novamov for free. [IMDBOUTLINE].
			Movie [TITLE] released on [RELEASEDATE] and directed by [DIRECTOR].
			About [IMDBVOTER] IMDB users rate the movie as [IMDBRATING]% and also received [RTAUDIENCE]% score from RottenTomatoes users.
			Runtime: [RTRUNTIME] minutes.";
	else if ($i==2)
		$msg = "Watch for free, movie [TITLE] at putLocker, megavideo, videobb.
			The [RTRUNTIME] min duration movie was released on [RELEASEDATE] and the director is [DIRECTOR]. [IMDBOUTLINE].
			Currently, [IMDBVOTER] IMDB members rate the movie [IMDBRATING]% while RT users rate it [RTAUDIENCE]%. Movie duration: [RTRUNTIME] minutes.
			Actors/actresses: [CAST].";
	else if ($i==3)
		$msg = "Watch [TITLE] online for free wihout downloading at megavideo, putlocker.
			The movie [TITLE] was directed by [DIRECTOR] and released on [RELEASEDATE].
			While [IMDBVOTER] IMDB users rate the movie [IMDBRATING]% , the RottenTomatoes members rate it [RTAUDIENCE]%.
			Cast of characters: [CAST]. Duration: [RTRUNTIME] min. [IMDBOUTLINE].";
	else if ($i==4)
		$msg = "Watch the movie [TITLE] online for free at megavideo, putLocker, videobb and novamov.
			The movie's director is [DIRECTOR] and played by following actor/actresses: [CAST]. It was released on [RELEASEDATE].
			IMDB rating: [IMDBRATING]%. RottenTomatoes audience rating: [RTAUDIENCE]%. [IMDBOUTLINE]. Duration: [RTRUNTIME] min.";
	else if ($i==5)
		$msg = "Watch [TITLE] online for free without downloading at megavideo, putlocker, videobb and novamov. Plot: [IMDBOUTLINE]. 
			Rating: [IMDBRATING]% (IMDB) and [RTAUDIENCE]% (RottenTomatoes). Movie total duration: [RTRUNTIME] minutes.
			Director: [DIRECTOR]. Movie casts: [CAST].";
	else if ($i==6)
		$msg = "Watch [TITLE] free online at megavideo, putlocker, videobb and novamov. [IMDBOUTLINE].
			Audience rate the movies as [IMDBRATING]% (IMDB) and [RTAUDIENCE]% (RottenTomatoes). Movie's duration: [RTRUNTIME] min.
			Directed by [DIRECTOR] and the actors/actresses are [CAST].";
	else if ($i==7)
		$msg = "Watch [TITLE] online free at megavideo, putlocker, videobb and novamov.
			[TITLE] was directed by [DIRECTOR] and the actors/actresses are [CAST]. With duration of [RTRUNTIME] minutes, the movie
			rated [IMDBRATING]% by IMDB members and [RTAUDIENCE]% by RT members.[IMDBOUTLINE].";
	else if ($i==8)
		$msg = "Watch [TITLE] online now at megavideo, putlocker, videobb and novamov.[IMDBOUTLINE].
			The movie [TITLE] was directed by [DIRECTOR] and released on [RELEASEDATE]. Actors/actresses: [CAST]. 
			The movie rated [IMDBRATING]% by IMDB members and [RTAUDIENCE]% by RT members. Movie runtime: [RTRUNTIME] min.";
	else
		$msg = "Watch now online: [TITLE] at megavideo, putlocker, videobb and novamov.[IMDBOUTLINE].
			[TITLE] has duration over [RTRUNTIME] minutes and rated [IMDBRATING]% by IMDB members. This great movie is directed by [DIRECTOR].
			Movie cast are following: [CAST].";
	
	return($msg);			
}

function get_location(&$country,&$city)
{
	$ipLite = new ip2location_lite;
	$ipLite->setKey('843e6584e74a92b969d627b19aa27ef35ab6ce7602491744681681c5436f7273');

	$visitorGeolocation = $ipLite->getCity($_SERVER['REMOTE_ADDR']);
	if ($visitorGeolocation['statusCode'] == 'OK') 
	{
		$country = strtolower($visitorGeolocation['countryCode']);
		$city = $visitorGeolocation['cityName'];
	}
}
?>