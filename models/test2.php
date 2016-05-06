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

require_once("Sentence.php");
require_once("MultipleAligner.php");
require_once("Alignment.php");

$verse[]="Revelation 1-4";
// NIV
/*
$s[0]="The LORD is with me; I will not be afraid. What can mere mortals do to me?";
$s[1]="Jehovah is on my side; I will not fear: What can man do unto me?";
$s[2]="The Lord is on my side; I will have no fear: what is man able to do to me?";
$s[3]="The Lord is with me, I will not be afraid; what can anyone do to me?";
*/
$s[0]= "And God said, “Let there be light,” and there was light."; //"NIV"
$s[1]= "And God said, Let there be light: and there was light."; //"ASV"
$s[2]=	"And God said, Let there be light: and there was light."; //"BBE"
$s[3]=	"God said, \"Let there be light.\" And so light appeared."; //"CEB"
$s[4]=	"Then God said, \"Let there be light\"; and there was light."; //"NKJV"
$s[5]="Then God said, “Let there be light,” and there was light."; //"NLT"
$al=new MultipleAligner();
$r=$al->align($s);
echo implode("<br>",$s);
echo multipleVisualsation($r[0],sizeof($s));
//print_r(GetArray($r[0],sizeof($s)));
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
		$html.="<tr>".$td."</tr>";
	$html.="</table>";
	return $html;
}


?>
</body>
</html>