    <meta charset="utf-8"><?php

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
	
	function niceVisualisation()
	{
	 $temp1=$temp2=$temp3="";
	  $alignedTexts="";
	  $notAligned[0]="";
	  $notAligned[1]="";
	  for($i=0;$i<count($this->AalignedSentences['sentence1']); $i++){
	  $class="";

	  if($this->AalignedSentences['relation'][$i]=="Aligned"){
	  		if($notAligned[0]!="" || $notAligned[1]!=""){
	  			if($temp1!="") 
	  				$temp1.="<td rowspan=3><img src='images/arr.png' width=30></td>";
 		  		$temp1.="<td class='Notshared' align='center'> ".$notAligned[0]." </td>";
			  	$temp2.="<td></td>";
		  		$temp3.="<td class='Notshared' align='center'> ".$notAligned[1]." </td>";
 				$notAligned[0]="";
	  			$notAligned[1]="";
	  		}
	  		 $alignedTexts.=" ".$this->AalignedSentences['sentence2'][$i];

	  }else{
	  		if($alignedTexts!=""){
		  		if($temp1!="") 
		  			$temp1.="<td rowspan=3><img src='images/arrRe.png' width=30></td>";
	  			$temp1.="<td ></td>";
	  			$temp2.="<td class='shared'> ".$alignedTexts." </td>";
	  			$temp3.="<td ></td>";
	  			$alignedTexts="";
	  		}	  	
		  	$notAligned[0].=" ".$this->AalignedSentences['sentence1'][$i];
	  		$notAligned[1].=" ".$this->AalignedSentences['sentence2'][$i];
	  	}
	  }	 
	  
	  
	  		if($alignedTexts!=""){
	  			if($temp1!="") 
	  				$temp1.="<td rowspan=3><img src='images/arrRe.png' width=30></td>";
	  			$temp1.="</td><td ></td>";
	  			$temp2.="<td class='shared'> ".$alignedTexts." </td>";
	  			$temp3.="<td ></td>";
	  			$alignedTexts="";
	  		}		  
	  	  		if($notAligned[0]!="" || $notAligned[1]!=""){
	  			if($temp1!="") 
	  				$temp1.="<td rowspan=3><img src='images/arr.png' width=30></td>";
 		  		$temp1.="<td class='Notshared'> ".$notAligned[0]." </td>";
			  	$temp2.="<td ></td>";
		  		$temp3.="<td class='Notshared'> ".$notAligned[1]." </td>";
 				$notAligned[0]="";
	  			$notAligned[1]="";
	  		}
	  $html="<table    style='border-width:0'>";
	  $html.="<tr>".$temp1."</tr>";
	  $html.="<tr>".$temp2."</tr>";
	  $html.="<tr>".$temp3."</tr>";
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

//////////////////////////////////////	///////////////////////	
	
	
//////////// new stuff for OCR /////////////////////////// 
		
	function OCRVisualisation()
	{
	  $html="";

	  for($i=0;$i<count($this->AalignedSentences['sentence1']); $i++){
		  if($this->AalignedSentences['relation'][$i]=="Aligned")
				$html.="<span class='shared'>".$this->AalignedSentences['sentence1'][$i]."</span>";
		  else
				$html.=" ".$this->createList(array($this->AalignedSentences['sentence1'][$i],$this->AalignedSentences['sentence2'][$i]));			
	  }	 
	  
	  return $html;	
	
	}
	
	function createList($options)
	{
	 $ret='<span class="form-group">      
	 		<select class="form-control inline" id="w1">';
	 foreach($options as $k=>$option)
	 	$ret.='<option>'.$option.'</option>';
	 $ret.='     </select>';
	 $ret.='</span>';
	 return $ret;
	}

}

?>