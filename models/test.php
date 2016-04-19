
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alignment</title>
	<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>    <!-- FA-Icons -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">    
    
</head>
<body style="font-family: 'Roboto Condensed', sans-serif;">
<?
// test file

require_once("Sentence.php");
require_once("Aligner.php");
require_once("Alignment.php");
$s1="How are you? every thing is ok ?!";
$s2="Hallo, are you doing? every thing is fine ?!";

$al=new Aligner();
$alignment= new Alignment();

$al->setOptions(1,0,0,0);
$alignedSentences=$al->align($s1,$s2); 

$alignment->setAlignment($alignedSentences);
echo $alignment->exportResults();
/*
$sen1=new Sentence($s);
print_r($sen1->tokens);
$token=new Token();
$sen1->tokens[2]=$token->removePunctuation($sen1->tokens[2]);
print_r($sen1->tokens); */
?>

</body>
</html>