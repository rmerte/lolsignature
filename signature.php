	

<?php
error_reporting(0);
include 'apikey.php';
include 'vendor/autoload.php';

use GDText\Box;
use GDText\Color;


$verisummoner = rawurlencode($_POST['sihirdaradi']);
$region = $_POST['region'];



//version




$versionlink= 'https://ddragon.leagueoflegends.com/api/versions.json';
$dataversion = curl_init();
curl_setopt($dataversion, CURLOPT_URL, $versionlink);
curl_setopt($dataversion, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($dataversion, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($dataversion, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($dataversion, CURLOPT_HTTPHEADER, [
  'X-Riot-Token: '. $key
]); 


$responsesdata = curl_exec($dataversion);
$versionarray = json_decode($responsesdata, true);
$dragonversion = $versionarray[0];

//champsname 
$championsjsonurl = 'http://ddragon.leagueoflegends.com/cdn/'.$dragonversion.'/data/en_US/champion.json';
include 'json.php';
include 'champnames.php';






curl_close($dataversion);
//summonerv4
$sv4url = 'https://'.$region.'.api.riotgames.com/lol/summoner/v4/summoners/by-name/'.$verisummoner;


$sv4 = curl_init();
curl_setopt($sv4, CURLOPT_URL, $sv4url);
curl_setopt($sv4, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($sv4, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($sv4, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($sv4, CURLOPT_HTTPHEADER, [
  'X-Riot-Token: '. $key
]); 

$responsesv4 = curl_exec($sv4);
$sv4array = json_decode($responsesv4, true);
$errorCode = curl_getinfo($sv4, CURLINFO_HTTP_CODE);
$summonerid = $sv4array['id'];
$summonerlevel = $sv4array['summonerLevel'];
$profileiconid = $sv4array['profileIconId'];
$summonername = $sv4array['name'];
$summonericonlink= 'http://ddragon.leagueoflegends.com/cdn/'.$dragonversion.'/img/profileicon/'.$profileiconid.'.png'; 





curl_close($sv4);

if($errorCode != 200) {
  switch ($errorCode) {
  case 400:
  echo 'Error: 400 (Bad Request)';
  break;
  case 403:
  echo 'Error: 403 (Forbidden)';
  break;
  case 404:
  echo 'Error: 404 (Not Found)';
  break;
  }
} else {
	// LEAGUEV4
	
	$leaguev4url = 'https://'.$region.'.api.riotgames.com/lol/league/v4/entries/by-summoner/'.$summonerid;


$leaguev4 = curl_init();
curl_setopt($leaguev4, CURLOPT_URL, $leaguev4url);
curl_setopt($leaguev4, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($leaguev4, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($leaguev4, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($leaguev4, CURLOPT_HTTPHEADER, [
  'X-Riot-Token: '. $key
]); 


$responseleaguev4 = curl_exec($leaguev4);
$leaguev4array = json_decode($responseleaguev4, true);

//Solo Duo Lig Bilgileri
$league = $leaguev4array[0]['tier'];
$division = $leaguev4array[0]['rank'];
$leaguepoints = $leaguev4array[0]['leaguePoints'];


//Solo Maçlar
$solowin = @$leaguev4array[1]['wins'];
$sololose = @$leaguev4array[1]['losses'];

//Flex Maçlar
$flexwin = @$leaguev4array[0]['wins'];
$flexlose = @$leaguev4array[0]['losses'];

//toplam

$toplammac = $flexwin+$flexlose+$solowin+$sololose;
$toplamwin = $flexwin+$solowin;
$kazanimyuzdesicalc = ($toplamwin*100)/$toplammac;
$kazanimyuzdesi = number_format($kazanimyuzdesicalc, 2);


if(empty($leaguev4array)){
	
	$league = "UNRANKED";
	$division = "";
	
}
	
	
	

curl_close($leaguev4);
	
// CHAMPION MASTERY V4
	
$cmv4url = 'https://'.$region.'.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-summoner/'.$summonerid;


$cmv4 = curl_init();
curl_setopt($cmv4, CURLOPT_URL, $cmv4url);
curl_setopt($cmv4, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($cmv4, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($cmv4, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($cmv4, CURLOPT_HTTPHEADER, [
  'X-Riot-Token: '. $key
]); 

	
	
	
$responsecmv4 = curl_exec($cmv4);
$cmv4array = json_decode($responsecmv4, true);


//champid
$champid1 = champidtoname($cmv4array[0]['championId']);
$champid2 = champidtoname($cmv4array[1]['championId']);
$champid3 = champidtoname($cmv4array[2]['championId']);

//champion points
$cpoints1 = $cmv4array[0]['championPoints'];
$cpoints2 = $cmv4array[1]['championPoints'];
$cpoints3 = $cmv4array[2]['championPoints'];

//champion levels

$clevel1 = $cmv4array[0]['championLevel'];
$clevel2 = $cmv4array[1]['championLevel'];
$clevel3 = $cmv4array[2]['championLevel'];


$champimage1l = 'http://ddragon.leagueoflegends.com/cdn/'.$dragonversion.'/img/champion/'.$champid1.'.png';
$champimage2l = 'http://ddragon.leagueoflegends.com/cdn/'.$dragonversion.'/img/champion/'.$champid2.'.png';
$champimage3l = 'http://ddragon.leagueoflegends.com/cdn/'.$dragonversion.'/img/champion/'.$champid3.'.png';

	
curl_close($cmv4);

//aaaaaaaaaaaaaaaaa









// echo '<img src="'.$summonericonlink.'">';


		
	
$font = dirname(__FILE__).'/font.ttf';
$font2 = dirname(__FILE__).'/font2.ttf';

$im = imagecreatefrompng('bg.png');
$fligram = imagecreatefrompng($summonericonlink);
//icon boyutlandırma

$yukseklik = 74;
$genislik = 74;
 
 
$yukseklik2 = 40;
$genislik2 = 40; 
 

 
/* hedef ve kaynak resimlerini oluştur */
$hedef = imagecreatetruecolor($genislik, $yukseklik);	
$hedef2 = imagecreatetruecolor($genislik2, $yukseklik2);
$hedef3 = imagecreatetruecolor($genislik2, $yukseklik2);
$hedef4	 = imagecreatetruecolor($genislik2, $yukseklik2);	
$kaynak = imagecreatefrompng($summonericonlink);
$champ1k = imagecreatefrompng($champimage1l);
$champ2k = imagecreatefrompng($champimage2l);
$champ3k = imagecreatefrompng($champimage3l);

$imagesize = getimagesize($summonericonlink);

$imagesizewidth= $imagesize[0];
$imagesizeheight = $imagesize[1];

// Resim boyutu
imagecopyresampled($hedef, $kaynak, 0, 0, 0, 0, $genislik, $yukseklik, $imagesizewidth, $imagesizeheight);
imagecopyresampled($hedef2, $champ1k, 0, 0, 0, 0, $genislik2, $yukseklik2, 120, 120); //malzahar
imagecopyresampled($hedef3, $champ2k, 0, 0, 0, 0, $genislik2, $yukseklik2, 120, 120);
imagecopyresampled($hedef4, $champ3k, 0, 0, 0, 0, $genislik2, $yukseklik2, 120, 120);

// Resim çıktı
imagepng($hedef, "icons/$profileiconid.png");
imagepng($hedef2, "champsquare/$champid1.png");
imagepng($hedef3, "champsquare/$champid2.png");
imagepng($hedef4, "champsquare/$champid3.png");


$champicon1 = imagecreatefrompng("champsquare/$champid1.png");
$champicon2 = imagecreatefrompng("champsquare/$champid2.png");
$champicon3 = imagecreatefrompng("champsquare/$champid3.png");


$cstars1 = imagecreatefrompng("stars/star$clevel1.png");
$cstars2 = imagecreatefrompng("stars/star$clevel2.png");
$cstars3 = imagecreatefrompng("stars/star$clevel3.png");

$yeniboyuticon = imagecreatefrompng("icons/$profileiconid.png");

//lig
$leagueicon = imagecreatefrompng("ranks/$league$division.png");



$textbox = new Box($im);
$textbox->setFontSize(14);
$textbox->setFontFace($font);
$textbox->setFontColor(new Color(255, 255, 255));
$textbox->setBox(
    57,  // distance from left edge
    250,  // distance from top edge
    111, // textbox width
    111  // textbox height
);


// now we have to align the text horizontally and vertically inside the textbox
$textbox->setTextAlign('center', 'top');
// it accepts multiline text
$textbox->draw($league.' '.$division);


// Profile Icon


 
imagecopy($im, $yeniboyuticon, 38, 38, 0, 0, imagesx($yeniboyuticon), imagesy($yeniboyuticon));
imagecopy($im, $leagueicon, 62, 130, 0, 0, imagesx($leagueicon), imagesy($leagueicon));


imagecopy($im, $cstars1, 290, 148, 0, 0, imagesx($cstars1), imagesy($cstars1));
imagecopy($im, $cstars2, 290, 199, 0, 0, imagesx($cstars2), imagesy($cstars2));
imagecopy($im, $cstars3, 290, 249, 0, 0, imagesx($cstars3), imagesy($cstars3));

imagecopy($im, $champicon1, 220, 127, 0, 0, imagesx($champicon1), imagesy($champicon1));
imagecopy($im, $champicon2, 220, 177, 0, 0, imagesx($champicon2), imagesy($champicon2));
imagecopy($im, $champicon3, 220, 227, 0, 0, imagesx($champicon3), imagesy($champicon3));





// Sihirdar adı
imagettftext($im, 25, 0, 145, 87, 0xFFFFFF, $font, $summonername);
imagettftext($im, 10, 0, 320, 144, 0xFFFFFF, $font, $cpoints1);
imagettftext($im, 10, 0, 320, 194, 0xFFFFFF, $font, $cpoints2);
imagettftext($im, 10, 0, 320, 244, 0xFFFFFF, $font, $cpoints3);


// imagettftext($im, 13, 0, 70, 270, 0xFFFFFF, $font, $league.' '.$division);
// imagettftext($im, 13, 0, 70, 270, 0xFFFFFF, $font, '.');

imagepng($im, "images/$summonerid.png");
imagedestroy($im);
imagedestroy($champicon1);
imagedestroy($champicon2);
imagedestroy($champicon3);
imagedestroy($cstars1);
imagedestroy($cstars2);
imagedestroy($cstars3);
imagedestroy($leagueicon);
imagedestroy($yeniboyuticon);
imagedestroy(imagecreatefrompng($summonericonlink));
imagedestroy(imagecreatefrompng($champimage1l));
imagedestroy(imagecreatefrompng($champimage2l));
imagedestroy(imagecreatefrompng($champimage3l));

// echo '<img src="images/'.$summonerid.'.png">';
	
$imza = 'images/'.$summonerid.'.png';

	
  
}
  



?>

<!DOCTYPE HTML>

<html lang="tr-TR">

<head>

	  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<title>LoL Signature</title>





<link rel="stylesheet" type="text/css" href="css/anasayfa.css"/>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css?family=Permanent+Marker&display=swap" rel="stylesheet">


</head>

<body>

	


<div class="container-fluid aramawrapper">


<h1>League Of Legends İmza</h1>
<img class="imza" src="<?php echo $imza; ?>" alt="imza">

<div><a  href="<?php echo $imza; ?>" download>
 <button type="button" class="btn btn-dark buton col-lg-3">İmzanı İndir!</button>
</a>
</div>




</div>

<div class="footer">© 2021 Copyright: <a class="linkfooter" href="https://tr.op.gg/summoner/userName=Alpha%20Draconis"<strong>Alpha Draconis</strong></a></div>




<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>









