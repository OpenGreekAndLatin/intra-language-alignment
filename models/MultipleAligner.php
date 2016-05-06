<?php
// multiple translations aligner
require_once("Aligner.php");
require_once("Token.php");

class MultipleAligner extends Aligner{


	function MultipleAligner(){
		$this->token=new Token();
	}
	
	

//////////////////////////////////////////////////////////////
// General Alignment
//////////////////////////////////////////////////////////////
	
	function align($sentences)
	{	
		 if(sizeof($sentences) > 1){
		  // run the alignment algorithm
		  $alignment12=$this->PairwiseAlignment($sentences[0],$sentences[1]);

		  $sen12=array();
	 	  for($i=0;$i< sizeof($alignment12['sentence1']);$i++)
	 	  	 $sen12[$i]=trim($alignment12['sentence1'][$i])."||".trim($alignment12['sentence2'][$i]);

	 	  $newSentences[0]=implode(" ",$sen12);
		  
		  for($i=2;$i < sizeof($sentences) ; $i++)
		 	  	$newSentences[$i-1]=$sentences[$i];

	 	  
	 	  return $this->align($newSentences);
		 }else{	  

			return $sentences;
		 }	 
	}


	function fillMarix()
	{
		$this->matrix=array();
		$m=count($this->sentence1->tokens); // Length of the first sentence
		$n=count($this->sentence2->tokens); // Length of the second sentence

		for($i=0;$i<= $m;$i++){
			$tokens12=explode("||",$this->sentence1->tokens[$i]);
			for($j=0;$j<= $n;$j++){
				$sc= $this->mismatch;
				if($this->isAligned_multi($tokens12,$this->sentence2->tokens[$j]))
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
	
	
	function isAligned_multi($tokensArr,$token)
	{
	  $aligned=false;
	  foreach($tokensArr as $key=>$tok)
	  	if($this->isAligned($token,$tok))
	  		$aligned=true;
	  return $aligned;
	}



}


?>

