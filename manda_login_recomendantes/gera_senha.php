<?php

function gera_senha() 
{
	$length=rand(8, 10);
	$strength=4;
	$vowels = 'aeiouy';
	$consonants = 'bcdfghjklmnpqrstvwxz';
	if ($strength >= 1) {
		$consonants .= 'BCDFGHJKLMNPQRSTVWXZ';
	}
	if ($strength >= 2) {
		$vowels .= "AEIOUY";
	}
	if ($strength >= 3) {
		$consonants .= '0123456789';
	}
	if ($strength >= 4) {
		$consonants .= '*?!+';
	}
 
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}


 
?>

