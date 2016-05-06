
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Intra-Language Alignment </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style/style.css">        
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">    
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Tangerine">
    <!-- google fonts -->
	<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700,300italic' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>    

</head>
<body style="font-family: 'Roboto Condensed', sans-serif;background-color:#EEE">
<div class="warpper">
<div class="headDiv">
<h1> Aeschylus </h1>
<h4>First edition: <font color="#EE0">tlg0085.tlg001.perseus-grc2.xml</font><br>
	Second edition:<font color="#AAA"> tlg0085.tlg001.opp-grc3.xml</font></h4>
</div>
<div style="width:95%;margin: auto; padding-top:10px;">
<?php
$models_path="../models/";
	require_once($models_path."Sentence.php");
	require_once($models_path."Aligner.php");
	require_once($models_path."Alignment.php");
$lines=file("files/tlg0085.tlg001.perseus-grc2_3.txt");
$numOfLines=count($lines);
$n=$_REQUEST['n'];
$start=$_REQUEST['start'];
if(!is_numeric($n)) $n=100;//$numOfLines;
if(!is_numeric($start)) $start=0;
$pagination=get_pagination($numOfLines,$n,$start);
echo $pagination;
$currentView=array_slice($lines,$start*$n,$n);

 		$aligner=new Aligner();
		$alignment= new Alignment();

		$aligner->setOptions(1,1,1,0);
		
foreach($currentView as $k=>$v)
{
 	$sentece=explode("\t",$v);
 	echo "<div class='row divBlock'> <div class='col-md-3'>" ;	
	echo  "[".$sentece[0]."]<br>";
	echo "(I)  ".$sentece[1]."<br>(II) ".$sentece[2];
	echo "</div><div class='col-md-9'>";
	$alignedSentences=$aligner->PairwiseAlignment($sentece[1],$sentece[2]); 
	$alignment->setAlignment($alignedSentences);
	//echo $alignment->getResults();
	echo $alignment->niceVisualisation();
	echo "</div></div>";
  		
}

function get_pagination($numOfLines,$n,$start)
{
  $ret="<table class='margin:autuo'> <tr>";	
  $numOfPages=$numOfLines/$n;

  for($i=0;$i<$numOfPages;$i++)
   if($i==$start)
   	$ret.="<td ><a href='?start=".($i)."' class='paginate_button_selected'>".($i+1)."</a></td>";
   else
	$ret.="<td ><a href='?start=".($i)."' class='paginate_button'>".($i+1)."</a></td>";
  $ret.="</tr></table>";
  return $ret;
}
?>
</div></div>
</body>
</html>