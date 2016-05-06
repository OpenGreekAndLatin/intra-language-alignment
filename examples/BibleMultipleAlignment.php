<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Intra-Language Alignment </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../style/style.css">        
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">    
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Tangerine">
    <!-- google fonts -->
	<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700,300italic' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>    
	<style>
	.form-control {
    width:auto;
    display:inline-block;
    border-color:#FF2222;
    }
	</style>
</head>
<body style="font-family: 'Roboto Condensed', sans-serif;background-color:#FFF"><?

$models_path="../models/";
require_once($models_path."Sentence.php");
require_once($models_path."MultipleAligner.php");
require_once($models_path."Alignment.php");
$verse[]="Revelation 1-4";

$translations=array("asv","ceb","rhe","leb","gw","nlt","rsv");
//$files=array("Matthew/asv/1.txt","Matthew/ceb/1.txt","Matthew/rhe/1.txt","Matthew/leb/1.txt","Matthew/gw/1.txt","Matthew/nlt/1.txt","Matthew/rsv/1.txt");
$texts=array();

$al=new MultipleAligner();
$al->setOptions(0,0,0,0);

foreach($translations as $k=>$tr)
{
	$texts[]=file("Matthew/".$tr."/1.txt");
}

for($i=0;$i <  sizeof($texts[0]);$i++){
	$s=array();
	foreach($texts as $k=>$t)
	{
 		$s[]=$t[$i];
	}
	$r=$al->align($s);
	echo implode("<br>",$s);
	echo multipleVisualsation($r[0],sizeof($s));
}



///////////////////////////////////////////////////////////////
//******************* Multiple Alignment Visualisation ********

function GetArray($txt,$n){
	$arr=explode(" ",$txt);
	$tds=array();
	for($k=0;$k < sizeof($arr); $k++)
	{
		$v=$arr[$k];
		$cells=array_reverse(explode("||",$v));
		for($i=0;$i<$n;$i++) 
			$tds[$k][$i]="";
		foreach($cells as $s=>$cell)
			$tds[$k][$n-$s-1]=$cell;
	}
	return $tds;
}

function multipleVisualsation($txt,$n)
{	
	global $translations;
	$arr=explode(" ",$txt);
	$tds=array();
	$html="<table class='table'>";

	for($k=0;$k < sizeof($arr); $k++)
	{
		$v=$arr[$k];
		$cells=array_reverse(explode("||",$v));
		for($i=0;$i<$n;$i++) 
			$tds[$k][$i]="<td></td>";
		foreach($cells as $s=>$cell)
			$tds[$k][$n-$s-1]="<td>".$cell."</td>";
	}
	//print_r($tds);
	$table=array();
	foreach($tds as $k=>$v)
	{
		for($i=0;$i<$n;$i++)
			$table[$i].=$v[$i];
	}
	foreach($table as $k=>$td)
		$html.="<tr><th class='info'>".strtoupper($translations[$k])."</th>".$td."</tr>";
	$html.="</table>";
	return $html;
}


?>
</body>
</html>