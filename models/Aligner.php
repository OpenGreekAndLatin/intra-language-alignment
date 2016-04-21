<?php
require_once("Token.php");require_once("Alignment.php");
class Aligner{

	private $sentence1;
	private $sentence2;
	private $token;
	
	// Alignment Options
	private $punctuation=0;
	private $casesensitive=0;
	private $diacritics=0;
	private $levenshtein=0;

	// this variables will be ued in Needlman-Wunsch Algorithm
	// Changing these values will produce different result
	private $gap=-2;
	private $mismatch=-2;
	private $match=5;

	// $matrix is a 2 dimensional array to save the scores of Needlman-Wunsch Algorithm
	private $matrix=array();
	private $optimal_alignment=array();

	function Aligner(){
		$this->token=new Token();
	}
	
	function setSentences($s1,$s2)
	{
	 $this->sentence1=new Sentence(trim($s1));
	 $this->sentence2=new Sentence(trim($s2));
	}

	function setOptions($punc=1,$case=0,$diac=1,$lev=0)
	{
	 $this->punctuation=$punc;
	 $this->casesensitive=$case; // 1: case sensitive, 0: not case sensitive 
	 $this->diacritics=$diac;
	 $this->levenshtein=$lev;
	}
	
	
	function initialization()
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
	
	function fillMarix()
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
			if($this->isAligned($base1,$base2)){
			$this->optimal_alignment['sentence1'][]=$base1;
			$this->optimal_alignment['sentence2'][]=$base2;
			$this->optimal_alignment['relation'][]="Aligned";			
			}
			else{
			$this->optimal_alignment['sentence1'][]=$base1;
			$this->optimal_alignment['sentence2'][]="";
			$this->optimal_alignment['relation'][]="Not Aligned";

			$this->optimal_alignment['sentence1'][]="";
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
		
		if($this->punctuation==1){         // ignore punctuation
			$w1=$this->token->removePunctuation($w1);
			$w2=$this->token->removePunctuation($w2);
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
	
	function PairwiseAlignment($sen1,$sen2)
	{
	 $this->setSentences($sen1,$sen2);
	 
	 $this->initialization();
	 $this->fillMarix();
	 $this->GetOptimalAlignment();
	 
	 return $this->optimal_alignment;
	}

//******************* Mutliple Alignment ********************
// these functions need to be gerneralised to align any number
// of translations, not only 3 ()
//***********************************************************
	function MultipleAlignment($sen1,$sen2,$sen3)
	{		 
	 	 $alignment12=$this->PairwiseAlignment($sen1,$sen2);
	 	 $sen12=array();
	 	 for($i=0;$i< sizeof($alignment12['sentence1']);$i++)
	 	 	$sen12[]=$alignment12['sentence1'][$i]."||".$alignment12['sentence2'][$i];
	 	 $sen12=implode(" ",$sen12);	
	 	 
	 	 $this->setSentences($sen12,$sen3);
	 	 $this->initialization();
	 	 $this->fillMarix2();	 	 
	 	 $this->GetOptimalAlignment2();
	 	 
	 	 return $this->optimal_alignment;
	}
	
// this function is used by multiple Alignment
	function fillMarix2()
	{
				$this->matrix=array();
				$m=count($this->sentence1->tokens); // Length of the first sentence
				$n=count($this->sentence2->tokens); // Length of the second sentence
	
				for($i=0;$i<= $m;$i++){
					$tokens12=explode("||",$this->sentence1->tokens[$i]);
					for($j=0;$j<= $n;$j++){
						
						//$sc=($this->isAligned($tokens12[0],$this->sentence2->tokens[$j]) || $this->isAligned($tokens12[1],$this->sentence2->tokens[$j]) )? $this->match: $this->mismatch;			
						$sc= $this->mismatch;
						if($this->isAligned($tokens12[0],$this->sentence2->tokens[$j]) || $this->isAligned($tokens12[1],$this->sentence2->tokens[$j]))
							$sc=$this->match;
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

// this function is used by multiple Alignment
	function GetOptimalAlignment2()
	{
		$m=count($this->sentence1->tokens); // Length of the first sentence
		$n=count($this->sentence2->tokens); // Length of the second sentence

		$this->optimal_alignment['sentence1'] = array();
		$this->optimal_alignment['sentence2'] = array();
		$this->optimal_alignment['sentence3'] = array();
		$this->optimal_alignment['relation'] = array();
		
		$i=$m-1;$j=$n-1;
		while($i >= 0 && $j >= 0) { // Start interation
		$tokens=explode("||",$this->sentence1->tokens[$i]);
		$base1 = trim($tokens[0]);//$this->sentence1->tokens[$i];
		$base2 = trim($tokens[1]);
		$base3 = trim($this->sentence2->tokens[$j]);
		$pointer = $this->matrix[$i][$j]['pointer'];
		
		if($pointer == "NW") {
			$i--;
			$j--;
			if($this->isAligned($base1,$base3) || $this->isAligned($base2,$base3)){
			$relation=array("success","success","success");
			if($this->isAligned($base2,$base3) && ! $this->isAligned($base1,$base3) )
				$relation=array("danger","success","success");
			if(!$this->isAligned($base2,$base3) &&  $this->isAligned($base1,$base3) )
				$relation=array("success","danger","success");
			if(!$this->isAligned($base2,$base3) &&  $this->isAligned($base1,$base2) )
				$relation=array("success","success","danger");
			$this->optimal_alignment['sentence1'][]=$base1;
			$this->optimal_alignment['sentence2'][]=$base2;
			$this->optimal_alignment['sentence3'][]=$base3;
			$this->optimal_alignment['relation'][]=$relation;			
			}
			else{
			$this->optimal_alignment['sentence1'][]=$base1;
			$this->optimal_alignment['sentence2'][]=$base2;
			$this->optimal_alignment['sentence3'][]="";
			if($this->isAligned($base1,$base2))
				$this->optimal_alignment['relation'][]=array("success","success","danger");
			else
				$this->optimal_alignment['relation'][]=array("danger","danger","danger");

			$this->optimal_alignment['sentence1'][]="";
			$this->optimal_alignment['sentence2'][]="";
			$this->optimal_alignment['sentence3'][]=$base3;
			$this->optimal_alignment['relation'][]=array("danger","danger","danger");
			}
		} else if($pointer == "LE") {
			$j--;
			$this->optimal_alignment['sentence1'][]="";
			$this->optimal_alignment['sentence2'][]="";
			$this->optimal_alignment['sentence3'][]=$base3;
			$this->optimal_alignment['relation'][]=array("danger","danger","danger");    
		}else if($pointer == "UP") {
			$i--;
			$this->optimal_alignment['sentence1'][]=$base1;
			$this->optimal_alignment['sentence2'][]=$base2;
			$this->optimal_alignment['sentence3'][]="";
			if($this->isAligned($base1,$base2))
				$this->optimal_alignment['relation'][]=array("success","success","danger");
			else
				$this->optimal_alignment['relation'][]=array("danger","danger","danger");
		}
	 }// End interation
	    
	    if($i < 0) { 
	 // copy the rest of sentence2 to the optimal Alignment
	  while($j >= 0) {
		$base3 = $this->sentence2->tokens[$j];
		$j--;
		$this->optimal_alignment['sentence1'][]="";
		$this->optimal_alignment['sentence2'][]="";
		$this->optimal_alignment['sentence3'][]=$base3;  
		$this->optimal_alignment['relation'][]=array("danger","danger","danger");
	  } // End While
	 } // End if
	  
	    if($j < 0) { 
	 // copy the rest of sentence1 to the optimal Alignment
	  while($i >= 0) {
		$tokens=explode("||",$this->sentence1->tokens[$i]);
		$base1 = trim($tokens[0]);//$this->sentence1->tokens[$i];
		$base2 = trim($tokens[1]);
		$i--;
		$this->optimal_alignment['sentence1'][]=$base1;
		$this->optimal_alignment['sentence2'][]=$base2;
		$this->optimal_alignment['sentence3'][]="";
		if($this->isAligned($base1,$base2))
			$this->optimal_alignment['relation'][]=array("success","success","danger");
		else
			$this->optimal_alignment['relation'][]=array("danger","danger","danger");  
		
	  } // End While
	 } // End if
  
		$this->optimal_alignment['sentence1']= array_reverse($this->optimal_alignment['sentence1']);
		$this->optimal_alignment['sentence2']= array_reverse($this->optimal_alignment['sentence2']); 
		$this->optimal_alignment['sentence3']= array_reverse($this->optimal_alignment['sentence3']); 
		$this->optimal_alignment['relation']= array_reverse($this->optimal_alignment['relation']); 

	} // End of GetOptimalAlignment
//***********************************************************


// this function is used for testing purposes to print the score matrix
	function printMatrix()
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