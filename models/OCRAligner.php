<?php
require_once("Token.php");
require_once("Alignment.php");
require_once("Aligner.php");

class OCRAligner extends Aligner{


	
	function OCRAligner(){
		$this->token=new Token();
	}
	
	
	function GetOptimalAlignment()
	{
		$m=count($this->sentence1->tokens); // Length of the first sentence
		$n=count($this->sentence2->tokens); // Length of the second sentence

		$this->optimal_alignment['sentence1'] = array();
		$this->optimal_alignment['sentence2'] = array();
		$this->optimal_alignment['relation'] = array();

	
		$i=$m-1;$j=$n-1;
		while($i >= 0 && $j >= 0) { // Start interation
		$base1 = $this->sentence1->tokens[$i];
		$base2 = $this->sentence2->tokens[$j];
		$pointer = $this->matrix[$i][$j]['pointer'];
		if($pointer == "NW") {
			$i--;
			$j--;
			if($base1==$base2){
			$this->optimal_alignment['sentence1'][]=$base1;
			$this->optimal_alignment['sentence2'][]=$base2;
			$this->optimal_alignment['relation'][]="Aligned";			
			}
			else{
			$this->optimal_alignment['sentence1'][]=$base1;
			$this->optimal_alignment['sentence2'][]=$base2;
			$this->optimal_alignment['relation'][]="Not Aligned";
			}
		} else if($pointer == "LE") {
			$j--;
			$this->optimal_alignment['sentence1'][]="";
			$this->optimal_alignment['sentence2'][]=$base2;
			$this->optimal_alignment['relation'][]="Not Aligned";    
		}else if($pointer == "UP") {
			$i--;
			$this->optimal_alignment['sentence1'][]=$base1;
			$this->optimal_alignment['sentence2'][]="";
			$this->optimal_alignment['relation'][]="Not Aligned";
		}
	 }// End interation
	    
	    if($i < 0) { 
	 // copy the rest of sentence2 to the optimal Alignment
	  while($j >= 0) {
		$base2 = $this->sentence2->tokens[$j];
		$j--;
		$this->optimal_alignment['sentence1'][]="";
		$this->optimal_alignment['sentence2'][]=$base2;  
		$this->optimal_alignment['relation'][]="Not Aligned";
	  } // End While
	 } // End if
	  
	    if($j < 0) { 
	 // copy the rest of sentence1 to the optimal Alignment
	  while($i >= 0) {
		$base1 = $this->sentence1->tokens[$i];
		$i--;
		$this->optimal_alignment['sentence1'][]=$base1;
		$this->optimal_alignment['sentence2'][]="";  
		$this->optimal_alignment['relation'][]="Not Aligned";
	  } // End While
	 } // End if
  
		$this->optimal_alignment['sentence1']= array_reverse($this->optimal_alignment['sentence1']);
		$this->optimal_alignment['sentence2']= array_reverse($this->optimal_alignment['sentence2']); 
		$this->optimal_alignment['relation']= array_reverse($this->optimal_alignment['relation']); 

	} // End of GetOptimalAlignment
	
	

	// check if the two words are aligned or not
	function isAligned($w1,$w2)
	{
		 $w1=trim($w1);
		 $w2=trim($w2);
		
		// ignore non alphanumeric characters
		  $w1=$this->token->removeNonAlphanumeric($w1);
		  $w2=$this->token->removeNonAlphanumeric($w2);

		  $w1=$this->token->removeDiacritics($w1);
		  $w2=$this->token->removeDiacritics($w2);

		// 	convert words to lower case		
		  $w1=$this->token->lowerCase($w1);
		  $w2=$this->token->lowerCase($w2);

		// Levenshtein
  	    $similar=$this->token->isSimilarTo($w1,$w2);
	
		if($w1==$w2 || $similar)
			return True;
		 else 
			return False;	
	}
	
}


?>