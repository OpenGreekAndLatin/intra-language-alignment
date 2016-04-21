<?
// Sentence CLASS
require_once("Token.php");

class Sentence{
	
	private $text="";
	public $tokens=array();
	
	public function Sentence($txt){
		$this->text=$txt;
		$this->tokenize();
	}
	
	function setText($txt){
		$this->text=$txt;
		$this->tokenize();
	}
	
	
	// Tokenize the sentence and save it as an array of tokens
	// The whitespace tokenizer simply breaks on whitespace
	function tokenize(){
		$tokens= explode(" ",$this->text);
		foreach($tokens as $k=>$tok)
			$this->tokens[]=$tok;				
	}

}


?>