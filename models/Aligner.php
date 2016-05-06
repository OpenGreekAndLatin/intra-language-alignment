<?php
require_once("Token.php");
require_once("Alignment.php");
class Aligner{

	protected $sentence1;
	protected $sentence2;
	protected $token;
	
	// Alignment Options
	protected $NonAlphanumeric=0;
	protected $casesensitive=0;
	protected $diacritics=0;
	protected $levenshtein=0;

	// this variables will be ued in Needlman-Wunsch Algorithm
	// Changing these values will produce different result
	protected $gap=-2;
	protected $mismatch=-2;
	protected $match=5;

	// $matrix is a 2 dimensional array to save the scores of Needlman-Wunsch Algorithm
	protected $matrix=array();
	protected $optimal_alignment=array();

	public function Aligner(){
		$this->token=new Token();
	}
	
	protected function setSentences($s1,$s2)
	{
	 $this->sentence1=new Sentence(trim($s1));
	 $this->sentence2=new Sentence(trim($s2));
	}

	public function setOptions($punc=1,$case=0,$diac=1,$lev=0)
	{
	 $this->NonAlphanumeric=$punc;
	 $this->casesensitive=$case; // 1: case sensitive, 0: not case sensitive 
	 $this->diacritics=$diac;
	 $this->levenshtein=$lev;
	}
	
// first step: intialize the alignment matrix	
	protected function initialization()
	{
		$this->matrix=array(); // reset Matrix variable
		
		$m=count($this->sentence1->tokens); // Length of the first sentence
		$n=count($this->sentence2->tokens); // Length of the second sentence

		// initialize the matrix
		for($i=0;$i<= $m;$i++)
			for($j=0;$j<= $n;$j++)
				$this->matrix[$i][$j]['val']=0;
		for($i=0;$i<= $m;$i++)
			$this->matrix[$i+1][0]['val']=($i+1)*$this->gap;
		for($i=0;$i<= $n;$i++)
			$this->matrix[0][$i+1]['val']=($i+1)*$this->gap;		
		// End of initialization
	}

// second step: fill the matrix with values according to needlemann wunsch schema 
	protected function fillMarix()
	{
		$m=count($this->sentence1->tokens); // Length of the first sentence
		$n=count($this->sentence2->tokens); // Length of the second sentence
	
		for($i=0;$i<= $m;$i++){
			for($j=0;$j<= $n;$j++){
				$sc=($this->isAligned($this->sentence1->tokens[$i],$this->sentence2->tokens[$j]))? $this->match: $this->mismatch;			
				
				$ma=$this->matrix[$i-1][$j-1]['val'] + $sc; // Matching/Mismatching
				$hgap = $this->matrix[$i-1][$j]['val'] + $this->gap; // Horizental gap
				$vgap = $this->matrix[$i][$j-1]['val'] + $this->gap; // Vertical gap
				
				$MaxValue=max($ma,$hgap,$vgap);
				
				$pointer="NW";
				
				if($MaxValue==$hgap && $MaxValue > $ma) 
					$pointer="UP";
				else if($MaxValue==$vgap && $MaxValue > $ma)
					$pointer="LE"; 
				
				$this->matrix[$i][$j]['val']=$MaxValue;
				$this->matrix[$i][$j]['pointer']=$pointer;
			}	
	}
}

// third step: extract the optimal alignment from the matrix
	protected function GetOptimalAlignment()
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
			if($this->isAligned($base1,$base2)){
			$this->optimal_alignment['sentence1'][]=$base1;
			$this->optimal_alignment['sentence2'][]=$base2;
			$this->optimal_alignment['relation'][]="Aligned";			
			}
			else{
			$this->optimal_alignment['sentence1'][]=$base1;
			$this->optimal_alignment['sentence2'][]=$base2;
			$this->optimal_alignment['relation'][]="Not Aligned";
/*
			$this->optimal_alignment['sentence1'][]="";
			$this->optimal_alignment['sentence2'][]=$base2;
			$this->optimal_alignment['relation'][]="Not Aligned";
*/			}
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
	

	
// check if the two words are aligned or not taking in account alignment's options
	protected function isAligned($w1,$w2)
	{
		$w1=trim($w1);
		$w2=trim($w2);
		
		if($this->NonAlphanumeric==1){         // ignore NonAlphanumeric
			$w1=$this->token->removeNonAlphanumeric($w1);
			$w2=$this->token->removeNonAlphanumeric($w2);
		}

		if( $this->diacritics==1){		// ignore 	diacritics
		  $w1=$this->token->removeDiacritics($w1);
		  $w2=$this->token->removeDiacritics($w2);
		}

		if($this->casesensitive==0){		// 	convert words to lower case		
		  $w1=$this->token->lowerCase($w1);
		  $w2=$this->token->lowerCase($w2);
		}
	
		$similar=False;
		if($this->levenshtein==1)
		{
		  $similar=$this->token->isSimilarTo($w1,$w2);
		}
	
		if($w1==$w2 || $similar)
			return True;
		 else 
			return False;	
	}



// PairwaiseAlignment: align two sentences	
	public function PairwiseAlignment($sen1,$sen2)
	{
	 $this->setSentences($sen1,$sen2);
	 $this->initialization();
	 $this->fillMarix();
	 $this->GetOptimalAlignment();
	 
	 return $this->optimal_alignment;
	}



// this function is used for testing purposes to print the score matrix
	protected function printMatrix()
	{
		$m=count($this->sentence1->tokens); // Length of the first sentence
		$n=count($this->sentence2->tokens); // Length of the second sentence
		echo "<table class='table'><tr><td></td>";
		for($i=0;$i<$m;$i++)
			echo "<th>".$this->sentence1->tokens[$i]."</th>";
		echo "</tr>";
		for($j=0;$j<$n;$j++)
		{
			echo "<tr><th>".$this->sentence2->tokens[$j]."</th>";
			for($i=0;$i<$m;$i++)
				echo "<td>".$this->matrix[$i][$j][val]."(".$this->matrix[$i][$j]['pointer'].")</td>";
		}
	}

}


?>