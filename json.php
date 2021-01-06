<?php




$championsfgc = file_get_contents($championsjsonurl);
$championsdecode = json_decode($championsfgc, true);

		  
	$dosya = fopen('champnames.php', 'w');
	$abcd = "";


		foreach($championsdecode['data'] as $lol=> $value){
	
			$champnames = $value["name"]; 
			$champids = $value["key"];
	
	
			 $abcd .= ' case '.$champids.': return "'.$champnames.'"; break; ';
	
			
		}


	fwrite($dosya,'<?php function champidtoname($id){ switch($id){'.$abcd.'}} ?>');
	fclose($dosya);



?>