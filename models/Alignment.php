<?php

class Alignment{

	private $AalignedSentences; // array( [sentence1] => array(), [sentence2] => array() , [relation] => array() )
			
	public function Alignment($alignedSen=""){
	$this->AalignedSentences=$alignedSen;
	}
	
	
	function setAlignment($alignedSen){
	$this->AalignedSentences=$alignedSen;
	}
	
	// Export the Alignment results
	function getResults($format="html"){
		 switch(strtolower($format)){
			 case "html": { return $this->getResultsAsHTML(); break;}
			 case "xml":  { return $this->getResultsAsXML(); break;}
			 case "json": { return $this->getResultsAsJSON(); break;}
			 default : {return ""; break ;}
		 }
	}
	


// this function will be integrated with getResults
// it is now separated just for testing purposes
	function print_multiple_alignment(){
	  $temp1=$temp2=$temp3="";

	  for($i=0;$i<count($this->AalignedSentences['sentence1']); $i++){
	  $class="";
	  	switch($this->AalignedSentences['relation'][$i]){
	  		case "Aligned": { $class="success"; break;}
	  		case "Not Aligned": { $class="danger"; break;}
	  		default: {$class=""; break;}
	  	}
	  	$temp1.="<td class='".$this->AalignedSentences['relation'][$i][0]."'>".$this->AalignedSentences['sentence1'][$i]."</td>";
	  	$temp2.="<td class='".$this->AalignedSentences['relation'][$i][1]."'>".$this->AalignedSentences['sentence2'][$i]."</td>";
	  	$temp3.="<td class='".$this->AalignedSentences['relation'][$i][2]."'>".$this->AalignedSentences['sentence3'][$i]."</td>";
	  }	 
	  
	  $html="<table  class='table'>";
	  $html.="<tr>".$temp1."</tr>";
	  $html.="<tr>".$temp2."</tr>";
	  $html.="<tr>".$temp3."</tr>";
	  $html.="</table>";
	  return $html;	
	
	}
	
	
	function getResultsAsHTML()
	{	  
	  $temp1=$temp2="";

	  for($i=0;$i<count($this->AalignedSentences['sentence1']); $i++){
	  $class="";
	  	switch($this->AalignedSentences['relation'][$i]){
	  		case "Aligned": { $class="success"; break;}
	  		case "Not Aligned": { $class="danger"; break;}
	  		default: {$class=""; break;}
	  	}
	  	$temp1.="<td class='".$class."'>".$this->AalignedSentences['sentence1'][$i]."</td>";
	  	$temp2.="<td class='".$class."'>".$this->AalignedSentences['sentence2'][$i]."</td>";
	  }	 
	  
	  $html="<table  class='table'>";
	  $html.="<tr>".$temp1."</tr>";
	  $html.="<tr>".$temp2."</tr>";
	  $html.="</table>";
	  return $html;	
	}
	
	function getResultsAsJSON()
	{
	
	 return "";
	}
	
	function getResultsAsXML()
	{
	
	 return "";	
	}
	
}

?>