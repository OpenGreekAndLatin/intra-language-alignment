<?

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