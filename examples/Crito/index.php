<?php session_start();
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

$models_Folder="models/";
require_once($models_Folder."Sentence.php");
require_once($models_Folder."Aligner3.php");
require_once($models_Folder."Alignment.php");

$aligner=new Aligner();
$alignment= new Alignment();
$aligner->setOptions(1,0,0,1);


?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Perseus Digital Library</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">    
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">  
		<link href='https://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'> 
		<link href='https://fonts.googleapis.com/css?family=PT+Sans+Narrow' rel='stylesheet' type='text/css'> 
		<link href='https://fonts.googleapis.com/css?family=GFS+Didot&subset=greek' rel='stylesheet' type='text/css'>
		<link href="assets/css/style.css" rel="stylesheet">
</head>
	<body>
		 <div class="wrapper" style='margin:auto'>
		 <center>
		 <h1>Manuscript Alignment </h1>
		 <h3><b>Plato's Crito</b> </h3><br>	 
		 <b>Clark:</b> A digital encoding of Ms Clark 39, 20v-26r, Oxford, University Bodleian Library <br>
		 <b>Paris1808:</b> A digital encoding of Ms Grec 1808, 17r-21v, Paris, Bibliothèque Nationale <br>
		 <b>Tuebingen:</b> A digital encoding of Ms Gr Mb 14, 21-38, Tübingen, Universität, Bibliothek
	<br><br>
		 </center>
<?php

	//parse the xml file
$filename[]="files/Clark.xml";
$filename[]="files/Paris1808.xml";
$filename[]="files/Tuebingen.xml";

$editions[$filename[0]]="CLARK";
$editions[$filename[1]]="Paris1808";
$editions[$filename[2]]="Tuebingen";
$_SESSION['texts']="";
foreach($filename as $k=>$v)
	read_File($v);

// navigation bar ** sections list
foreach($_SESSION['texts'] as $k=>$v)
{	
  $b.='<div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$k.' <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
  		<li><a href="?sec='.$k.'">'.$k.'</a></li>
  		<li role="separator" class="divider"></li>';
  foreach($v as $kk=>$vv)
    $b.="<li><a href='?sec=$k$kk'>".$k.$kk."</a></li>";
  $b.="</ul></div>";
}
echo "<center>".$b."</center>";

if($_REQUEST['sec']!=""){
	if(array_key_exists($_REQUEST['sec'],$_SESSION['texts']) and $_REQUEST['sec']!="")
	{
	 	$te= $_SESSION['texts'][$_REQUEST['sec']];
	 	foreach($te as $m=>$text){
			echo "<center><span class='div_title' style='color:#E33;font-size:28px;text-transform: uppercase'>".$_REQUEST['sec'].$m."</span></center>";
			$output="<div class='row' style='margin: auto'>";
			foreach($text as $k=>$v){
				$output.="<div class='col-md-4 '><span class='div_title'><center>".$editions[$k]."</center></span><div class='div_section'>";
				$output.="<br>";
				foreach($v as $kk=>$txt){
				$output.="<span class='said'>$txt</span><br><br>";
			}
			$output.="</div></div>";	 		
		}
		$output.="</div>";	
		echo $output;
	 }
}else{
		$k=$_REQUEST['sec'];
		$n1=substr($k,-1);
		$n=substr($k,0,-1);
		$text=$_SESSION['texts'][$n][$n1];
		echo "<center><span class='div_title' style='color:#E33;font-size:28px;text-transform: uppercase'>".$n.$n1."</span></center>";
		$output="<div class='row' style='margin: auto'>";
		foreach($text as $k=>$v){
			$output.="<div class='col-md-4 '><span class='div_title'><center>".$editions[$k]."</center></span><div class='div_section'>";
			$output.="<br>";
			foreach($v as $kk=>$txt){
				$output.="<span class='said'>$txt</span><br><br>";
				$arr[$editions[$k]][]=$txt;
			}
			$output.="</div></div>";	 		
		}
		$output.="</div>";	
		echo $output;	
		echo "<div style='margin:20px;padding:10px'><h1 >ALIGNMENT</h1><br>";
		// loop throw the sentences in the selected secttion
		for($i=0;$i< sizeof($arr[$editions[$filename[0]]]);$i++){
			// print the parallel sentences before alignment
			echo $arr[$editions[$filename[0]]][$i]."<br>"; 
			echo $arr[$editions[$filename[1]]][$i]."<br>";	
			echo $arr[$editions[$filename[2]]][$i]."<br>";		
			// clean the senteces from html tags and 
			$sentence1=preg_replace("/<(.*?)>/si","",$arr[$editions[$filename[0]]][$i]);
			$sentence2=preg_replace("/<(.*?)>/si","",$arr[$editions[$filename[1]]][$i]);	
			$sentence3=preg_replace("/<(.*?)>/si","",$arr[$editions[$filename[2]]][$i]);					
			// run the alignment 
			$alignedSentences=$aligner->MultipleAlignment($sentence1,$sentence2,$sentence3);
			$alignment->setAlignment($alignedSentences);
			echo "<br>";
			// print the alignment's results
			echo $alignment->print_multiple_alignment();
			echo "<br>";
		}
		echo "</div>";
	}
	
	} // end of if

//refine the text 
// deal with some special cases
	
function refine($contents){
	// replace Gaps
	$pattern="/<gap (.*?)\/>/si";
	$contents=preg_replace($pattern,"",$contents);
	
	// deal with choice: take the expan and delete the rest
	$pattern="/<choice(.*?)>(.*?)<expan>(.*?)<\/expan><\/choice>/si";
	preg_match_all($pattern,$contents,$output);
	$contents=str_replace($output[0],$output[3],$contents);

	// deal with app: take the first option (rdg) and delete the rest
	$pattern="/<app (.*?)><rdg>(.*?)<\/rdg>(.*?)<\/app>/si";
	preg_match_all($pattern,$contents,$output);
	$contents=str_replace($output[0],$output[2],$contents);

	return $contents;
}

// parse the XML files and save the text sections as session variable in the form of array 
// [section]=>[order]=>[edition]  ="text"
// [47]		=>[a]	 =>[paris1808]="text text text "

function read_File($filename){
	$content=refine(file_get_contents($filename));
	
	$xml=new SimpleXMLElement($content);
	foreach($xml->text->body->div as $k=>$v){
		$att=$v->attributes();
		$k="".$att['n'];
		$n1=substr($k,-1);
		$n=substr($k,0,-1);
		if(trim($n)!=""){
			$html=array();
			foreach($v->said as $k=>$vv)
				$html[]="<span class='speaker'>".$vv->attributes()['who']."</span> ".$vv;
			$_SESSION['texts'][$n][$n1][$filename]=$html;
		}
	}
}

?>
<script src=https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</div>
	</body>
</html>