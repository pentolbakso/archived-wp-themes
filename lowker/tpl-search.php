<?php
/* 
Template Name: Search Result
*/
include_once "myfunctions.php";
include_once "paginator.class.php";

$q= strtolower(get_query_var('cari'));
$di= strtolower(get_query_var('di'));
$act = get_query_var('act');

$pg = $_GET['page'];
if ($pg=="") $pg="1";

//begin: blocking bad word in search --------------------------------
$stringToCheck= $q." ".$di;
if(!empty($stringToCheck)){
	$p1 = array("4r5e", "5h1t", "5hit", "a55", "anal", "anus", "ar5e", "arrse", "arse", "ass", "ass-fucker", "asses", "assfucker", "assfukka", "asshole", "assholes", "asswhole", "a_s_s", "bitch", "b00bs", "b17ch", "b1tch", "ballbag", "balls", "ballsack", "bastard", "beastial", "beastiality", "bellend", "bestial", "bestiality", "biatch", "bitch", "bitcher", "bitchers", "bitches", "bitchin", "bitching", "bloody", "blow job", "blowjob", "blowjobs", "boiolas", "bollock", "bollok", "boner", "boob", "boobs", "booobs", "boooobs", "booooobs", "booooooobs", "breasts", "buceta", "bugger", "bum", "bunny fucker", "butt", "butthole", "buttmuch", "buttplug", "c0ck", "c0cksucker", "carpet muncher", "cawk", "chink", "cipa", "cl1t", "clit", "clitoris", "clits", "cnut", "cock", "cock-sucker", "cockface", "cockhead", "cockmunch", "cockmuncher", "cocks", "cocksuck", "cocksucked", "cocksucker", "cocksucking", "cocksucks", "cocksuka", "cocksukka", "cok", "cokmuncher", "coksucka", "coon", "cox", "crap", "cum", "cummer", "cumming", "cums", "cumshot", "cunilingus", "cunillingus", "cunnilingus", "cunt", "cuntlick", "cuntlicker", "cuntlicking", "cunts", "cyalis", "cyberfuc", "cyberfuck", "cyberfucked", "cyberfucker", "cyberfuckers", "cyberfucking", "d1ck", "damn", "dick", "dickhead", "dildo", "dildos", "dink", "dinks", "dirsa", "dlck", "dog-fucker", "doggin", "dogging", "donkeyribber", "doosh", "duche", "dyke", "ejaculate", "ejaculated", "ejaculates", "ejaculating", "ejaculatings", "ejaculation", "ejakulate", "f u c k", "f u c k e r", "f4nny", "fag", "fagging", "faggitt", "faggot", "faggs", "fagot", "fagots", "fags", "fanny", "fannyflaps", "fannyfucker", "fanyy", "fatass", "fcuk", "fcuker", "fcuking", "feck", "fecker", "felching", "fellate", "fellatio", "fingerfuck", "fingerfucked", "fingerfucker", "fingerfuckers", "fingerfucking", "fingerfucks", "fistfuck", "fistfucked", "fistfucker", "fistfuckers", "fistfucking", "fistfuckings", "fistfucks", "flange", "fook", "fooker", "fuck", "fucka", "fucked", "fucker", "fuckers", "fuckhead", "fuckheads", "fuckin", "fucking", "fuckings", "fuckingshitmotherfucker", "fuckme", "fucks", "fuckwhit", "fuckwit", "fudge packer", "fudgepacker", "fuk", "fuker", "fukker", "fukkin", "fuks", "fukwhit", "fukwit", "fux", "fux0r", "f_u_c_k", "gangbang", "gangbanged", "gangbangs", "gaylord", "gaysex", "goatse", "God", "god-dam", "god-damned", "goddamn", "goddamned", "hardcoresex", "hell", "heshe", "hoar", "hoare", "hoer", "homo", "hore", "horniest", "horny", "hotsex", "jack-off", "jackoff", "jap", "jerk-off", "jism", "jiz", "jizm", "jizz", "kawk", "knob", "knobead", "knobed", "knobend", "knobhead", "knobjocky", "knobjokey", "kock", "kondum", "kondums", "kum", "kummer", "kumming", "kums", "kunilingus", "l3i+ch", "l3itch", "labia", "lmfao", "lust", "masterbate", "masterbation", "masterbations", "masturbate", "mo-fo", "mof0", "mofo", "mothafuck", "mothafucka", "mothafuckas", "mothafuckaz", "mothafucked", "mothafucker", "mothafuckers", "mothafuckin", "mothafucking", "mothafuckings", "mothafucks", "mother fucker", "motherfuck", "motherfucked", "motherfucker", "motherfuckers", "motherfuckin", "motherfucking", "motherfuckings", "motherfuckka", "motherfucks", "muff", "mutha", "muthafecker", "muthafuckker", "muther", "mutherfucker", "n1gga", "n1gger", "nazi", "nigg3r", "nigg4h", "nigga", "niggah", "niggas", "niggaz", "nigger", "niggers", "nob", "nob jokey", "nobhead", "nobjocky", "nobjokey", "numbnuts", "nutsack", "orgasim", "orgasims", "orgasm", "orgasms", "p0rn", "pawn", "pecker", "phonesex", "pigfucker", "pimpis", "piss", "pissed", "pisser", "pissers", "pisses", "pissflaps", "pissin", "pissing", "pissoff", "poop", "porn", "porno", "pornography", "pornos", "prick", "pricks", "pron", "pube", "pusse", "pussi", "pussies", "pussy", "pussys", "rectum", "retard", "rimjaw", "rimming", "s hit", "s.o.b.", "sadist", "schlong", "screwing", "scroat", "scrote", "scrotum", "semen", "sex", "sh!+", "sh!t", "sh1t", "shag", "shagger", "shaggin", "shagging", "shemale", "shi+", "shit", "shitdick", "shite", "shited", "shitey", "shitfuck", "shitfull", "shithead", "shiting", "shitings", "shits", "shitted", "shitter", "shitters", "shitting", "shittings", "shitty", "skank", "slut", "sluts", "smegma", "smut", "snatch", "son-of-a-bitch", "spac", "spunk", "s_h_i_t", "t1tt1e5", "t1tties", "teets", "teez", "testical", "testicle", "tit", "titfuck", "tits", "titt", "tittie5", "tittiefucker", "titties", "tittyfuck", "tittywank", "titwank", "tosser", "turd", "tw4t", "twat", "twathead", "twatty", "twunt", "twunter", "v14gra", "v1gra", "fuckes", "viagra", "vulva", "whoar", "whore", "willies", "willy", "xrated", "xxx","fucker","k0nt0l", "kont0l", "k0ntol", "fuckr", "pepek", "titit", "zakar", "toket");
	$p2 = array('kancut','musek','harem','gigolo','gay','homo','janda','cabe','lontong','kontol','penis','pijat','vagina','jilbab','video','adult','milf','xxx','xx','erotic','aurat','naked','choot','pics','babhi','photos','nu dity','bhabhi','sex','klitoris','porn','nude','masturbation','erection','chut','abortion','b okep','porno','hot','fuck','pussy','ngentot','tube ','bugil','telanjang','porno','seks','payudara','b oobs','tits','fuck*','meki','cerita panas','ranjang','ternoda','bokef','fuck','SEKS',' SEX','MILF','fucking','fucking','Sex','Video','Vid eos','fuck','hindi','Bokep','Mesum','mesum','BOKEP ','chudai','Chudai','hindi','film','stories','chod a','papa','girl','mujhe','women','Kwentong','kwent ong','kalibugan','boobs','porn','tits','lesbian',' Lesbian','Nude','Porn','Boobs','Tits','Xxx','XXX', 'PORN','LESBIAN','NUDE','hentai','Hentai','HENTAI' ,'BOOBS','ASS','ass','Ass','gangbang','Gangbang',' GANGBANG','Murder','murder','MURDER','kill','KILL' ,'Kill','EROTIC','Erotic','erotic','sensor','perawan','bispak','bisyar','pelacur','mucikari','cewek','3gp','bugil','jablay','jablai','mesum','abg','bayaran','menggelinjang','selingkuh','saritem');
	$badWords = array_merge($p1,$p2);
	$badWords = array_unique($badWords,SORT_STRING);
	foreach($badWords as $badWord) {
	    if (preg_match("/\b".$badWord."\b/", $stringToCheck)) {

			global $wpdb;
			$sql = "INSERT INTO job.badkw SET ip='".$_SERVER['REMOTE_ADDR']."',keyword='".$q."',location='".$di."',postdate=NOW()";
			@$wpdb->query($sql);

	        header("Location:".get_bloginfo('url'));
	        exit;
	    }
	}
}
//--------------------------------------------------------------------

if ($act=='search')
{
	global $wpdb;
	if (($q != "" || $di != "") && $pg=="1")
	{
		$sql = "INSERT INTO job.searchkw SET keyword='".$q."',location='".$di."',postdate=NOW()";
		@$wpdb->query($sql);
	}
}

$arr = explode(" ", $q);
$boolean_search = "";
foreach($arr as $r)
	$boolean_search .= "+".$r." ";
$boolean_search = trim($boolean_search);

if (strlen($q)==0)
{
	if (strlen($di)>0)
	{
		$filter = " MATCH(city,prov) AGAINST ('".$di."')";
	}
	else
	{
		$filter = "1=1";
	}

	$count = jobcount($filter);
}
else if (strlen($q)>3)
{
	$filter = "";
	if (strlen($q)>0)
		$filter = "MATCH(title) AGAINST ('".$boolean_search."' IN BOOLEAN MODE)";

	if (strlen($di)>0)
	{
		if (strlen($filter)>0) $filter.= " AND ";
		$filter.= " MATCH(city,prov) AGAINST ('".$di."')";
	}
	
	$count = jobcount($filter);
	if ($count==0)
	{
		//retry pencarian yg santai

		$filter = "";
		if (strlen($q)>0)
			$filter = "MATCH(title) AGAINST ('".$q."' IN BOOLEAN MODE)";

		if (strlen($di)>0)
		{
			if (strlen($filter)>0) $filter.= " AND ";
			$filter.= " MATCH(city,prov) AGAINST ('".$di."')";
		}

		$count = jobcount($filter);	

		//masih ga ketemu cuk?
		if ($count == 0)
		{
			$notif = "Kami tidak menemukan pekerjaan sesuai kriteria yang dicari.
				Berikut ini pekerjaan terbaru yang mirip atau berada di lokasi yang sama.";

			if (strlen($q)>0)
				$filter = "title LIKE '%".$q."%'";

			if (strlen($di)>0)
			{
				if (strlen($filter)>0) $filter.= " OR ";
				$filter .= "city LIKE '%".$di."%'";
			}

			$count = jobcount($filter);	
		}
	}
}
else
{
	$filter = "title LIKE '%".$q."%'";
	if (strlen($di)>0)
	{
		if (strlen($filter)>0) $filter.= " AND ";
		$filter.= " MATCH(city,prov) AGAINST ('".$di."')";
	}
	$count = jobcount($filter);	
}


$pages = new Paginator;
$pages->items_total = $count;
$pages->paginate();
$str = joblist($filter,$pages->limit,$snippet);


//--------------- header ----------
global $the_title,$the_noindex,$the_canonical,$the_description;
$the_title = "Lowongan kerja".((strlen($q)>0)?" ".ucwords($q):"").((strlen($di)>0)?" di ".ucwords($di):"")." - Page #".$pg;
//$the_noindex = "<meta name='robots' content='noindex'>";
//$the_canonical = "<link rel='canonical' href=''>";
$the_description = "<meta name='description' content='Info Lowongan Kerja ".((strlen($q)>0)?" ".ucwords($q):"").((strlen($di)>0)?" di ".ucwords($di):"").": ".$snippet."'/>";

?>

<?php get_header(); ?>

<?php include "searchbox.php"; ?>

<p><center>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-1117201847231228";
/* lowker-728x90 */
google_ad_slot = "5344381992";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</center></p>

<h2>Hasil pencarian lowongan <em><u><?=$q?></u></em> <?=(strlen($di)>0)?" di <em>$di</em>":""?> </h2>

<?php

if ($notif!="")
	echo "<div class='alert alert-info'>$notif</div>";

echo "<p>Ditemukan sekitar <strong>".$count."</strong> pekerjaan yang sesuai :</p>";

echo '<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style">
<p>Jika teman menyukai hasil ini, mohon untuk di-Like / Share. Terima Kasih :)</p>
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<!-- AddThis Button END -->
';

echo $str;


if ($count > 5)
{
	echo '
<p><center>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-1117201847231228";
/* lowker-468x15 */
google_ad_slot = "5204781191";
google_ad_width = 468;
google_ad_height = 15;
//-->
</script>
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</center></p>';

}
echo "<div class='pagination'>".$pages->display_pages()."</div>";	
?>

<?php get_footer(); ?>