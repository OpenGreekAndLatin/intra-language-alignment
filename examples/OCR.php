<?php
$models_path="../models/";
require_once($models_path."Sentence.php");
require_once($models_path."OCRAligner.php");
require_once($models_path."Alignment.php");
?>
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
	<style>
	.form-control {
    width:auto;
    display:inline-block;
    border-color:#FF2222;
    }
	</style>
</head>
<body style="font-family: 'Roboto Condensed', sans-serif;background-color:#EEE">
<div class="warpper">
<div class="headDiv">
<h1>Example of Aligning OCR-outputs:</h1> 
</div>
<table class='table' style='width:90%; margin:auto' >
<tr><th>coo.31924054869700_ocr/024.txt</th><th>hvd.32044019207893_ocr/024.txt</th><th>Alignment</th></tr>
<?php
// read files
$url[0]="files/coo.31924054869700_ocr/024.txt";
$url[1]="files/hvd.32044019207893_ocr/024.txt";

 $content[0]=file_get_contents($url[0]);
 $content[1]=file_get_contents($url[1]);
 
 $lines1=explode("\n",$content[0]);
 $lines2=explode("\n",$content[1]);

 $aligner=new OCRAligner();
 $alignment= new Alignment();

 for($i=0;$i < sizeof($lines1);$i++){
 	$alignedSentences=$aligner->PairwiseAlignment($lines1[$i],$lines2[$i]); 
	$alignment->setAlignment($alignedSentences);
   
 echo "<tr>
 		<td class='col-md-4' style='background-color:#FFF'>".($lines1[$i])."</td>
 		<td class='col-md-4' style='background-color:#FFF'>".($lines2[$i])."</td>
 		<td class='col-md-4' style='background-color:#FFF'>".$alignment->OCRVisualisation()."</td></tr>";
}


?>


</table>
</div>
</body>
</html>