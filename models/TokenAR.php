<?php

// Greek token
class TokenAR extends Token{
		
	protected static $levensteinThreshold=0.3;
	
	//https://en.wikipedia.org/wiki/Greek_diacritics
	// remove Arabic diacritics
	static function removeDiacritics($token){
    	$original=	 array("ً","ُ","ٌ","ِ","ٍ","ّ");
    	$replacement="";
    	return str_replace($original,$replacement,$token);
	}
	
	// convert $token to lowercase
	// there is no lower and upper case in Arabic
	static function lowerCase($token){
		return $token;
	}
	
	// remove Hamza (multiple forms of letter [A=ا] in arabic)
	static function removeHamza($token){
	    $original=	 array("أ","إ","آ");
    	$replacement="ا";
    	return str_replace($original,$replacement,$token);	
	}	
}

?>