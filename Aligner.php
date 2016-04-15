<?php

class Aligner{

private $sentence1;
private $sentence2;
private $punctuation=1;
private $casesensitive=0;
private $diacritics=1;
private $levenshtein=0;

// this variables will be ued in Needlman-Wunsch Algorithm
// Changing these values will produce different result
private $gap=-2;
private $mismatch=-2;
private $match=5;

// $matrix is a 2 dimensional array to save the scores of Needlman-Wunsch Algorithm
private $matrix=array();
private $optimal_alignment=array();

// Constructor
public function Aligner($sen1="",$sen2="",$punc=1,$case=0,$diac=1,$lev=0)
{
 $this->sentence1=$this->tokenize(trim($sen1));
 $this->sentence2=$this->tokenize(trim($sen2));
 $this->punctuation=$punc;
 $this->casesensitive=$case; // 1: case sensitive, 0: not case sensitive 
 $this->diacritics=$diac;
 $this->levenshtein=$lev;
}

function setSentences($s1,$s2)
{

 $this->sentence1=$this->tokenize(trim($s1));
 $this->sentence2=$this->tokenize(trim($s2));

}



function initializeation()
{
	$m=count($this->sentence1); // Length of the first sentence
	$n=count($this->sentence2); // Length of the second sentence

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
	$m=count($this->sentence1); // Length of the first sentence
	$n=count($this->sentence2); // Length of the second sentence
	
	for($i=0;$i<= $m;$i++){
		for($j=0;$j<= $n;$j++){
			$sc=($this->isAligned($this->sentence1[$i],$this->sentence2[$j]))? $this->match: $this->mismatch;			
			$ma=$this->matrix[$i-1][$j-1]['val'] + $sc; // M
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

function printMatrix()
{
	$m=count($this->sentence1); // Length of the first sentence
	$n=count($this->sentence2); // Length of the second sentence
	echo "<table class='table'><tr><td></td>";
	for($i=0;$i<$m;$i++)
		echo "<th>".$this->sentence1[$i]."</th>";
	echo "</tr>";
	for($j=0;$j<$n;$j++)
	{
		echo "<tr><th>".$this->sentence2[$j]."</th>";
		for($i=0;$i<$m;$i++)
			echo "<td>".$this->matrix[$i+1][$j+1][val]."(".$this->matrix[$i+1][$j+1]['pointer'].")</td>";
	}
}


function GetOptimalAlignment()
{
	$m=count($this->sentence1); // Length of the first sentence
	$n=count($this->sentence2); // Length of the second sentence

	$this->optimal_alignment['sentence1'] = array();
	$this->optimal_alignment['sentence2'] = array();

	
	$i=$m-1;$j=$n-1;
	while($i >= 0 && $j >= 0) {
    $base1 = $this->sentence1[$i];
    $base2 = $this->sentence2[$j];
    $pointer = $this->matrix[$i][$j]['pointer'];
    if($pointer == "NW") {
    	$i--;
	    $j--;
	    if($this->isAligned($base1,$base2)){
   		$this->optimal_alignment['sentence1'][]=$base1;
	   	$this->optimal_alignment['sentence2'][]=$base2;}
	   	else{
	   	$this->optimal_alignment['sentence1'][]=$base1;
	   	$this->optimal_alignment['sentence2'][]="";
   		$this->optimal_alignment['sentence1'][]="";
	   	$this->optimal_alignment['sentence2'][]=$base2;
	   	}
    } else if($pointer == "LE") {
    	$j--;
	    $this->optimal_alignment['sentence1'][]=" ";
    	$this->optimal_alignment['sentence2'][]=$base2;    
    }else if($pointer == "UP") {
    	$i--;
		$this->optimal_alignment['sentence1'][]=$base1;
	    $this->optimal_alignment['sentence2'][]=" ";
    }

 }
  if($i < 0) { 
 // copy the rest of sentence2 to the optimal Alignment
  while($j > 0) {
    $j--;
  	$base2 = $this->sentence2[$j];
    $this->optimal_alignment['sentence1'][]="";
    $this->optimal_alignment['sentence2'][]=$base2;  
  }
 }
 
  if($j < 0) { 
 // copy the rest of sentence1 to the optimal Alignment
  while($i > 0) {
    $i--;
  	$base1 = $this->sentence1[$i];
    $this->optimal_alignment['sentence1'][]=$base1;
    $this->optimal_alignment['sentence2'][]="";  
  }
 }
  
	$this->optimal_alignment['sentence1']= array_reverse($this->optimal_alignment['sentence1']);
	$this->optimal_alignment['sentence2']= array_reverse($this->optimal_alignment['sentence2']);
 
}

public function printAlignedSentences()
{
    echo "<table  class='table'><tr>";
    
    for($i=0;$i<count($this->optimal_alignment['sentence1']); $i++)
    {
     
       if($this->isAligned($this->optimal_alignment['sentence1'][$i],$this->optimal_alignment['sentence2'][$i])){
     	    if($this->optimal_alignment['sentence1'][$i]==$this->optimal_alignment['sentence2'][$i])  
    			echo "<td class='success'>".$this->optimal_alignment['sentence1'][$i]."</td>";
    		else
    			echo "<td class='info'>".$this->optimal_alignment['sentence1'][$i]."</td>";
    		
    	}else 	
    		echo "<td class='danger'>".$this->optimal_alignment['sentence1'][$i]."</td>";
    }
    echo "</tr><tr>";
    
    
    for($i=0;$i<count($this->optimal_alignment['sentence2']); $i++)
	{
	 if($this->isAligned($this->optimal_alignment['sentence1'][$i],$this->optimal_alignment['sentence2'][$i]))
	    {
	    	if($this->optimal_alignment['sentence1'][$i]==$this->optimal_alignment['sentence2'][$i])  
    			echo "<td class='success'>".$this->optimal_alignment['sentence2'][$i]."</td>";
    		else
    			echo "<td class='info'>".$this->optimal_alignment['sentence2'][$i]."</td>";
    	}else 
    		echo "<td class='danger'>".$this->optimal_alignment['sentence2'][$i]."</td>";
    }
    echo "</tr></table>";
}

public function compute()
{

	$this->initializeation();
	$this->fillMarix();
	$this->GetOptimalAlignment();
	$this->printAlignedSentences();

}



// The simplest tokenizer, it takes whitespace as 
function tokenize($sentence) {
	return explode(" ",$sentence);
}


// check if the two words are aligned or not
function isAligned($w1,$w2)
{
	$punc=array("·",",",";","'","(",")",'"',"?","]","[","!","." ,"“",":","”",",");

	if($this->punctuation==1)          // ignore punctuation
	{
		$w1=str_replace($punc,"",$w1);
		$w2=str_replace($punc,"",$w2);
	}

	if( $this->diacritics==1)		// ignore 	diacritics
	{
	  $w1=$this->cleanDiacritics($w1);
	  $w2=$this->cleanDiacritics($w2);
	}

	if($this->casesensitive==0)		// 	convert words to lower case	
	{
		$w1=strtolower($w1);
		$w2=strtolower($w2);
	}
	
	$similar=False;
	if($this->levenshtein==1)
	{
	  $similar=$this->leven($w1,$w2);
	}
	
	if(trim(($w1))==trim(($w2)) || $similar)
		return True;
	 else 
		return False;	
}

//https://en.wikipedia.org/wiki/Greek_diacritics
function cleanDiacritics($word)
{
    $original=	 array("ά","ά","ὰ","έ","ὲ","ή","ὴ","ί","ὶ","ό","ό","ὸ","ύ","ὺ","ώ","ὼ","ᾴ","ᾲ","ῄ","ῂ","ῴ","ῲ","Ά","Ὰ","Έ","Ὲ","Ὴ","Ή","Ί","Ὶ","Ὸ","Ό","Ύ","Ὺ","Ώ","Ὼ");
    $replacement=array("α","α","α","ε","ε","η","η","ι","ι","ο","ο","ο","υ","υ","ω","ω","ᾳ","ᾳ","ῃ","ῃ","ῳ","ῳ","Α","Α","Ε","Ε","Η","Η","Ι","Ι","Ο","Ο","Υ","Υ","Ω","Ω");
    return str_replace($original,$replacement,$word);
}

 function leven ( $s1 , $s2 ) {
	 $l1 = strlen( $s1 ) ; // Length of  $s1 
	 $l2 = strlen( $s2 ) ; // Length of  $s2 
	 $dis = range( 0 , $l2 ) ; // Erste Zeile mit (0,1,2,...,n) erzeugen
                                               // $dis stellt die vorrangeganene Zeile da.
	 for ( $x = 1 ; $x <= $l1 ; $x ++ ) {        
		 $dis_new[ 0 ] = $x ; // Das erste element der darauffolgenden Zeile ist $x, $dis_new ist damit die aktuelle Zeile mit der gearbeitet wird
		 for ( $y = 1 ; $y <= $l2 ; $y ++ ) {
			 if( $s1[ $x - 1 ] == $s2[ $y - 1 ] ) 				   $c =  0 ;
			 //else if(sameFamily($s1[ $x - 1 ],$s2[ $y - 1 ]))      $c=0.15;
			 //else if(similarPhonetic($s1[ $x - 1 ],$s2[ $y - 1 ])) $c=0.35;
			 else   											   $c= 1 ;
			 $dis_new[ $y ] = min ( $dis[ $y ] + 1 , $dis_new[ $y - 1 ] + 1 , $dis[ $y - 1 ] + $c ) ;	 
		 }
		 $dis = $dis_new ;              
	 }	
	 $distance=$dis[ $l2 ] ; // return the distance: # differenct letters
	 $maxLength=max(strlen($s1),strlen($s2));
	 if($maxLength!=0)
	  	$NormalisedDistance=$distance/$maxLength;
	 else 
	  	$NormalisedDistance=1;
	 return ($NormalisedDistance < 0.4);
 }

}

?>