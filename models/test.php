
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alignment</title>
	<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>    <!-- FA-Icons -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css"> 
    <link rel="stylesheet" href="../style/style.css">    
   
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">    
    
</head>
<body style="font-family: 'Roboto Condensed', sans-serif;">
<?php
// test file

require_once("Sentence.php");
require_once("Aligner.php");
require_once("Alignment.php");
// Revelation 1 BBE

$Ts1= "<i><u><h4>BBE (Bible in Basic English): </i></u>";
$Ts2= "<i><u>ASV (American standadrd Version):  </i></u>";
$Ts3= "<i><u>NLT (New Living Translation):  </i></u>";
/*
$verse[]="Revelation 1-1";
$s1[]="The Revelation of Jesus Christ which God gave him so that his servants might have knowledge of the things which will quickly take place: and he sent and made it clear by his angel to his servant John; ";
$s2[]="The Revelation of Jesus Christ, which God gave him to show unto his servants, [even] the things which must shortly come to pass: and he sent and signified [it] by his angel unto his servant John; ";
$s3[]="This is a revelation from Jesus Christ, which God gave him to show his servants the events that must soon take place. He sent an angel to present this revelation to his servant John,";

$verse[]="Revelation 1-2";
$s1[]="Who gave witness of the word of God, and of the witness of Jesus Christ, even of all the things which he saw.";
$s2[]="who faithfully reported everything he saw. This is his report of the word of God and the testimony of Jesus Christ.";
$s3[]="And the Spirit and the bride say, Come. And let him who gives ear, say, Come. And let him who is in need come; and let everyone desiring it take of the water of life freely.";

$verse[]="Revelation 1-3";
$s1[]="A blessing be on the reader, and on those who give ear to the prophet's words, and keep the things which he has put in the book: for the time is near.";
$s2[]="Blessed is he that readeth, and they that hear the words of the prophecy, and keep the things that are written therein: for the time is at hand.";
$s3[]="God blesses the one who reads the words of this prophecy to the church, and he blesses all who listen to its message and obey what it says, for the time is near.";
*/
$verse[]="Revelation 1-4";
$s1[]="John to the seven churches which are in Asia: Grace to you and peace, from him who is and was and is to come; and from the seven Spirits which are before his high seat;";
$s2[]="John to the seven churches that are in Asia: Grace to you and peace, from him who is and who was and who is to come; and from the seven Spirits that are before his throne;";
$s3[]="This letter is from John to the seven churches in the province of Asia. Grace and peace to you from the one who is, who always was, and who is still to come; from the sevenfold Spirit before his throne;";

$verse[]="Revelation 1-5";
$s1[]="And from Jesus Christ, the true witness, the first to come back from the dead, and the ruler of the kings of the earth. To him who had love for us and has made us clean from our sins by his blood;";
$s2[]="and from Jesus Christ, [who is] the faithful witness, the firstborn of the dead, and the ruler of the kings of the earth. Unto him that loveth us, and loosed us from our sins by his blood;";
$s3[]=" and from Jesus Christ. He is the faithful witness to these things, the first to rise from the dead, and the ruler of all the kings of the world. All glory to him who loves us and has freed us from our sins by shedding his blood for us.";

$verse[]="Revelation 1-6";
$s1[]="And has made us to be a kingdom and priests to his God and Father; to him let glory and power be given for ever and ever. So be it.";
$s2[]="and he made us [to be] a kingdom, [to be] priests unto his God and Father; to him [be] the glory and the dominion for ever and ever. Amen.";
$s3[]="He has made us a Kingdom of priests for God his Father. All glory and power to him forever and ever! Amen.";

$verse[]="Revelation 1-7";
$s1[]="See, he comes with the clouds, and every eye will see him, and those by whom he was wounded; and all the tribes of the earth will be sorrowing because of him. Yes, so be it. ";
$s2[]="Behold, he cometh with the clouds; and every eye shall see him, and they that pierced him; and all the tribes of the earth shall mourn over him. Even so, Amen.";
$s3[]="Look! He comes with the clouds of heaven. And every one will see him even those who pierced him. And all the nations of the world will mourn for him. Yes! Amen!";

$str="διαδέχεται τὸ αἰγαῖον ἑλλήσποντος, λῆγον! εἰς= ἀβυδον) καὶ σηστόν εἶτα ἡ προποντὶς λήγει εἰς χαλκηδόνα καὶ βυζάντιον";
//echo $str="and from Jesus Christ, [who is] the faithful witness, the firstborn of the dead,\n";
$temp=preg_replace("/\P{L}+/u", " ", $str);
echo preg_replace("/[ \t\n\r]+/si"," ",$temp);
/*
$al=new Aligner();
$alignment= new Alignment();

$al->setOptions(1,0,0,0);

for($i=0;$i < sizeof($s1);$i++)
{
	echo "<h2>".$verse[$i]."</h2>$Ts1<span class='s1'>".$s1[$i]."</span><br>$Ts2<span class='s2'>".$s2[$i]."</span><br>$Ts3<span class='s3'>".$s3[$i]."</span>";
	$alignedSentences=$al->MultipleAlignment($s1[$i],$s2[$i],$s3[$i]);
	$alignment->setAlignment($alignedSentences);
	echo $alignment->print_multiple_alignment();
}
*/
?>

</body>
</html>