<?php
/**
 * @author Ardan [ardzz]
 * Dibuat dengan penuh cinta ❤ hehe
 * @package Spotify Converter CLI
 */
$green = "\e[1;92m";
$cyan = "\e[1;36m";
$normal = "\e[0m";
$blue = "\e[34m";
$green1 = "\e[0;92m";
$yellow = "\e[93m";
$red = "\e[91m";
$red1 = "\e[1;91m";
$error = " [ " . $red . "ERROR" . $normal . " ]";

function getstr($string, $start, $end)
{
	$str = explode($start, $string);
	$str = explode($end, $str[1]);
	return $str[0];
}

function download($file_source, $file_target)
{
	$rh = fopen(str_replace(" ","%20",$file_source), 'rb');
	$wh = fopen($file_target, 'w+b');
	if (!$rh || !$wh) {
		return false;
	}

	while (!feof($rh)) {
		if (fwrite($wh, fread($rh, 4096)) === FALSE) {
			return false;
		}

		flush();
	}

	fclose($rh);
	fclose($wh);
	return true;
}

function get_token()
{
	/*
	JANGAN DI USILIN AKUN SPOTIFYNYA! :'(
	*/
	$ch = curl_init();
	$client_id = "774b29d4f13844c495f206cafdad9c86";
	curl_setopt($ch, CURLOPT_URL, "https://accounts.spotify.com/authorize?response_type=token&redirect_uri=https%3A%2F%2Fdeveloper.spotify.com%2Fcallback&client_id=" . $client_id . "&scope=user-read-private+user-read-email+user-library-read+user-follow-read+user-top-read+playlist-modify-public+user-read-playback-state+user-modify-playback-state+user-read-recently-played+user-read-currently-playing+user-follow-modify+playlist-modify-private+playlist-read-collaborative+user-library-modify+playlist-read-private+user-read-birthdate&state=ctgy5i");
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.62 Safari/537.36',
		"Cookie: _ga=GA1.2.1400013853.1531556388; optimizelyEndUserId=oeu1531556778066r0.9727553258197312; optimizelySegments=%7B%226174980032%22%3A%22search%22%2C%226176630028%22%3A%22none%22%2C%226179250069%22%3A%22false%22%2C%226161020302%22%3A%22gc%22%7D; optimizelyBuckets=%7B%7D; __gads=ID=28bc86adbee50354:T=1531557120:S=ALNI_MaqUPrP86jkKgJ2S0a9Ib249d2hyg; spot=%7B%22t%22%3A1531557710%2C%22m%22%3A%22id%22%2C%22p%22%3A%22open%22%7D; sp_t=8edd33adaac1c268d7c9a97ea7ae7205; sp_last_utm=%7B%22utm_source%22%3A%22spotify_premiumupsell%22%2C%22utm_medium%22%3A%22houseads%22%2C%22utm_campaign%22%3A%222017q4_apac_apac_premiumbusiness_weekly_filler%20800x435%20desk%20desk%22%2C%22utm_content%22%3A%22id_id_desktopoverlay_weekly_3_desktop%22%7D; _gid=GA1.2.1275834701.1539641026; sp_ab=%7B%7D; remember=ranggamaulana529%40gmail.com; csrf_token=AQAzigqKqb8uxSfU2fqm0uQaMCyEygKRZRjaA3JIfT4iVhW_uo5hL8RX6d5CyozjVx4AkS0l64KtZg8PKiUnGLWisnLklG2Ilbl_7guHaZD-eDi8UYWaPwWaYfk1MRyObUzNhg; sp_dc=AQBjgBateCciYY1Rg75uYAtnESR1stiGjDOPqI_PiC8QepoA5kn6SV2RkClZ3MNAiZOY1gaKIVlRhADyUVL9GBRYTRY6hS_NQjwTFQJTqcY6WA; wp_sso_token=AQBjgBateCciYY1Rg75uYAtnESR1stiGjDOPqI_PiC8QepoA5kn6SV2RkClZ3MNAiZOY1gaKIVlRhADyUVL9GBRYTRY6hS_NQjwTFQJTqcY6WA; sp_ac=AQA6g7OXpPyujWguqv3vtwlOSyB5YcCxQN5HU4jU7CSa7OEjRYrQgix__GTWMMr3ZSQGsUut8CDMEkxRZLzt6NFcDM-T831lFrLN_PpyBCv_UESwk3kis6hmpLUmFheFFeG3iAiO2SxEYPTG0zbvdE8qLIDOVzO4xltq4B7O9Ir5_1AZtxqz0UUUV30gpzBMm32eXjLWGuiIXDYv9uYE4eCcFLKhQwpJkHLcTbIU; _gat=1",
	));
	$res = curl_exec($ch);
	$header = substr($res, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
	$body = substr($res, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
	curl_close($ch);

	// return ["header" => $header,"body" => $body];

	return getstr($header, "#access_token=", "&");
}

function curl($id, $tkn)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/playlists/" . $id);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.62 Safari/537.36',
		"authorization: Bearer " . $tkn,
	));
	$res = curl_exec($ch);
	$header = substr($res, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
	$body = substr($res, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
	curl_close($ch);
	return ["header" => $header, "body" => $body];
}

function yt_uwowo($jula)
{
	$js = json_decode(file_get_contents("https://www.googleapis.com/youtube/v3/search?part=id,snippet&q=" . urlencode($jula) . "&type=video&key=AIzaSyAi1Gv5cA4BitVVtW9l7Flc4Les7AhrDlE") , 1);
	$judul = $js["items"][0]["snippet"]["title"];
	$cenel = $js["items"][0]["snippet"]["channelTitle"];
	$id = $js["items"][0]["id"]["videoId"];
	$link = "https://www.youtube.com/watch?v=" . $id;
	return $id;
}

function post_data($url, $param)
{
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, $url);
	curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_HTTPHEADER, array(
		"User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.62 Safari/537.36"
	));
	curl_setopt($c, CURLOPT_POSTFIELDS, $param);
	curl_setopt($c, CURLOPT_POST, 1);
	$out = curl_exec($c);
	return $out;
}

function ytv($id){
  $post = "https://www2.onlinevideoconverter.com/webservice";
  $param = "function=validate&args[dummy]=1&args[urlEntryUser]=https://www.youtube.com/watch?v=".$id."&args[fromConvert]=urlconverter&args[requestExt]=mp3&args[nbRetry]=0&args[videoResolution]=-1&args[audioBitrate]=0&args[audioFrequency]=0&args[channel]=stereo&args[volume]=0&args[startFrom]=-1&args[endTo]=-1&args[custom_resx]=-1&args[custom_resy]=-1&args[advSettings]=false&args[aspectRatio]=-1";
  $data = json_decode(post_data($post,$param),1);
if($data["result"]["status"] == "failed"){
  return false;
}else{
  if($data["result"]["id_process"] == "-1"){
    $data1 = post_data("https://www.onlinevideoconverter.com/success","id=".$data["result"]["dPageId"]);
    $data2 = getstr($data1,"<div id='stepProcessEnd'>",'<div id="queue" style="display:none; font-weight: 500;"></div>');
    $size = getstr($data2,'<p>
                                                .mp3                                                &nbsp;
                                                ','MB                                            </p>
                                        </div>');
    $urld = getstr($data2,'<div id=\'endGet\' class="download-section-1-1-button"> 
                                            <a class="download-button" href="javascript:;" id="downloada">Download</a>
                                            <a style=\'display:none\' class="download-button" href="','"');
    return ["url_download" => $urld,"size" => $size."MB"];

  }else{
  $post = "https://www2.onlinevideoconverter.com/webservice";
  $param = "function=processVideo&args[dummy]=1&args[urlEntryUser]=https://www.youtube.com/watch?v=".$data["result"]["keyHash"]."&args[fromConvert]=urlconverter&args[requestExt]=mp3&args[serverId]=".$data["result"]["serverId"]."&args[nbRetry]=0&args[title]=".$data["result"]["title"]."&args[keyHash]=".$data["result"]["keyHash"]."&args[serverUrl]=".$data["result"]["serverUrl"]."&args[id_process]=".$data["result"]["id_process"]."&args[videoResolution]=-1&args[audioBitrate]=0&args[audioFrequency]=0&args[channel]=stereo&args[volume]=0&args[startFrom]=-1&args[endTo]=-1&args[custom_resx]=-1&args[custom_resy]=-1&args[advSettings]=false&args[aspectRatio]=-1";
  $dpig = json_decode(post_data($post,$param),1)["result"]["dPageId"];
  $data1 = post_data("https://www.onlinevideoconverter.com/success","id=".$dpig);
    $data21 = getstr($data1,"<div id='stepProcessEnd'>",'<div id="queue" style="display:none; font-weight: 500;"></div>');
    $size1 = getstr($data21,'<p>
                                                .mp3                                                &nbsp;
                                                ','MB                                            </p>
                                        </div>');
    $urld1 = getstr($data21,'<div id=\'endGet\' class="download-section-1-1-button"> 
                                            <a class="download-button" href="javascript:;" id="downloada">Download</a>
                                            <a style=\'display:none\' class="download-button" href="','"');
    return ["url_download" => $urld1,"size" => $size1."MB"];

}
}
}

echo $green . "
 ╔═╗╔═╗╔═╗╔╦╗╦╔═╗╦ ╦
 ╚═╗╠═╝║ ║ ║ ║╠╣ ╚╦╝
 ╚═╝╩  ╚═╝ ╩ ╩╚   ╩ " . $normal . $yellow . "  ┌─┐┌─┐┌┐┌┬  ┬┌─┐┬─┐┌┬┐┌─┐┬─┐
                      │  │ ││││└┐┌┘├┤ ├┬┘ │ ├┤ ├┬┘
  " . $normal . $cyan . "ARDZZ" . $red . " ❤" . $normal . "             └─┘└─┘┘└┘ └┘ └─┘┴└─ ┴ └─┘┴└─

";
echo " [*] Example full url                 : https://open.spotify.com/user/spotify/playlist/{$cyan}37i9dQZF1DX8TvdyVZSYFY$normal\n";
echo " [*] Example Spotify Playlist ID      : {$cyan}37i9dQZF1DX8TvdyVZSYFY{$normal}\n";
$id_playlists = readline(" [*] Input Spotify Playlist ID        : ");
$token = get_token();

// echo " [*] Akses Token                 : ".$token."\n";

$get_pl = curl($id_playlists, $token) ["body"];
$js = json_decode($get_pl, 1);
$j = "";
$x = 1;

if (preg_match('/Invalid playlist Id/i', $get_pl)) {
	echo $error . " Invalid playlist Id!\n";
	exit();
}

echo "\n [*] Playlists Name   : " . $js["name"] . "\n";
echo " [*] Description      : " . $js["description"] . "\n\n";

if (!is_dir($js["name"])) {
	mkdir($js["name"]);
}

foreach($js["tracks"]["items"] as $tsp) {
	sleep(3); // delay 3 detik
	$judul = $tsp["track"]["name"];
	$url = $tsp["track"]["external_urls"]["spotify"];
	$ar = "";
	foreach($tsp["track"]["artists"] as $y) {
		$ar.= $y["name"] . " ";
	}

	$j.= $judul . "\n";
	echo " [" . $x++ . "] $url => " . $cyan . $judul . $normal . $green . " [ Spotify ]\n" . $normal;
	echo " [?] Retrieving song information from Youtube ...\n";
	$id = yt_uwowo($judul . " " . $ar);
	$jjs = json_decode(file_get_contents("https://api.zonkploit.com/yt/video-info/$id") , 1);
	echo " [*] Song title   : " . $cyan . $jjs["video_title"] . $normal . $red1 . " [ YouTube ]\n" . $normal;
	echo " [*] Channel Name : " . $jjs["channel_title"] . " [ " . $jjs["channel_id"] . " ]\n";
	echo " [*] Published    : " . $jjs["published"] . "\n";
	echo " [*] Duration     : " . $jjs["duration"] . "\n";
	echo " [*] Views        : " . $jjs["views"] . "\n";
	echo " [*] Likes        : " . $jjs["like"] . "\n";
	echo " [*] Id Video     : $id\n";
	echo " [*] YT link      : https://www.youtube.com/watch?v={$id}\n";

	// $mp3_link = json_decode(file_get_contents("https://api.zonkploit.com/yt/cv-mp3/$id"),1)["url"];

	$mp3_link = ytv($id)["url_download"];
	echo " [*] MP3 Link     : " . $mp3_link . "\n";
	$path = getcwd() . "/" . $js["name"];
	$judul = str_replace("/"," - ", $jjs["video_title"]) . " - [ SPOTIFY CONVERTER - ZploIT ].mp3";
	echo " [*] Downloading the mp3 file...\n";
	if ($mp3_link == "https://") {
		echo "$error Oopss! url not found!\n";
	}
	elseif (file_exists($path . "/" . $judul)) {
		echo " [*] " . $path . "/" . $judul . " is already exists!\n";
	}
	else {
		download($mp3_link, $path . "/" . $judul);
		echo " [*] Saving to " . $path . "/" . $judul . "\n";
	}

	echo "\n\n";
}

$count = count(explode("\n", $j)) - 1;
echo " [*] Number of Songs : " . $count . "\n";
echo " [+] Finish!\n";
?>
