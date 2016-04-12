<?php

function compute($arr1,$arr2,$p,$c)
{

	$gap=-2;
	$mismatch=-2;
	$match=5;
	$m=count($arr1);
	$n=count($arr2);
	$matrix=array();
	
	// initialize the matrix
	for($i=0;$i<= $m;$i++)
		for($j=0;$j<= $n;$j++)
			$matrix[$i][$j]['val']=0;
	for($i=0;$i<= $m;$i++)
		$matrix[$i+1][0]['val']=($i+1)*$gap;
	for($i=0;$i<= $n;$i++)
		$matrix[0][$i+1]['val']=($i+1)*$gap;		
	// End of initialization
	


////////////////// Start Computing //////////////////////////////////////////
	for($i=0;$i<= $m;$i++)
		for($j=0;$j<= $n;$j++){
			$sc=(isAligned($arr1[$i],$arr2[$j],$p,$c,$p,$c))? $match: $mismatch;	
			$ma=$matrix[$i-1][$j-1]['val'] + $sc;
            $hgap = $matrix[$i-1][$j]['val'] + $gap;
            $vgap = $matrix[$i][$j-1]['val'] + $gap;
            $val=max($ma,$hgap,$vgap);
            $pointer="NW";
            if($val==$hgap) 
            	$pointer="UP";
            else if($val==$vgap)
            	$pointer="LEFT"; 
            $matrix[$i][$j]['val']=$val;
            $matrix[$i][$j]['pointer']=$pointer;
		}	

//////////////// Find the best alignment /////////////////////////////////////
$i=$m;
$j=$n;
$optimal_alignment['sentence1'] = array();
$optimal_alignment['sentence2'] = array();
$optimal_alignment['alignment'] = array();	
$optimal_alignment['score'] = $matrix[$i][$j]['val'];

 while($i !== 0 and $j !== 0) {
    $base1 = $arr1[$i-1];
    $base2 = $arr2[$j-1];
    $pointer = $matrix[$i][$j]['pointer'];
    if($pointer == "NW") {
    	$i--;
	    $j--;
   		$optimal_alignment['sentence1'][]=$base1;
	   	$optimal_alignment['sentence2'][]=$base2;
    } else if($pointer == "LEFT") {
    	$j--;
	    $optimal_alignment['sentence1'][]=" ";
    	$optimal_alignment['sentence2'][]=$base2;    
    }else if($pointer == "UP") {
    	$i--;
		$optimal_alignment['sentence1'][]=$base1;
	    $optimal_alignment['sentence2'][]=" ";
    }

 }
 if($i==0) { 
 // copy the rest of sentence2 to the optimal Alignment
  while($j !== 0) {
    $j--;
  	$base2 = $arr2[$j];
    $optimal_alignment['sentence1'][]="";
    $optimal_alignment['sentence2'][]=$base2;  
  }
 }
 
  if($j==0) { 
 // copy the rest of sentence1 to the optimal Alignment
  while($i !== 0) {
    $i--;
  	$base1 = $arr1[$i];
    $optimal_alignment['sentence1'][]=$base1;
    $optimal_alignment['sentence2'][]="";  
  }
 }
 
	$optimal_alignment['sentence1']= array_reverse($optimal_alignment['sentence1']);
	$optimal_alignment['sentence2']= array_reverse($optimal_alignment['sentence2']);


    echo "<table  class='table'><tr>";
    
    for($i=0;$i<count($optimal_alignment['sentence1']); $i++)
     	if(isAligned($optimal_alignment['sentence1'][$i],$optimal_alignment['sentence2'][$i],$p,$c))
    		echo "<td class='success'>".$optimal_alignment['sentence1'][$i]."</td>";
    	else 	
    		echo "<td class='danger'>".$optimal_alignment['sentence1'][$i]."</td>";
    echo "</tr><tr>";
    for($i=0;$i<count($optimal_alignment['sentence2']); $i++)
	    if(isAligned($optimal_alignment['sentence1'][$i],$optimal_alignment['sentence2'][$i],$p,$c))
    		echo "<td class='success'>".$optimal_alignment['sentence2'][$i]."</td>";
    	else 
    		echo "<td class='danger'>".$optimal_alignment['sentence2'][$i]."</td>";
    echo "</tr></table>";

}

// Using the simplest tokenizer: takes whitespace as 
function tokenize($sentence) {
	return explode(" ",$sentence);
}

function isAligned($w1,$w2,$p,$c)
{
$punc=array(",",";","'","(",")",'"',"?","]","[","!","." ,"“",":","”",",");
if($p==1)
{
	$w1=str_replace($punc,"",$w1);
	$w2=str_replace($punc,"",$w2);
}
if($c==0)
{
 $w1=strtolower($w1);
 $w2=strtolower($w2);
}

 if(trim(($w1))==trim($w2))
 	return True;
 else 
 	return False;

}
?>

