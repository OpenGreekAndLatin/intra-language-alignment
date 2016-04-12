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
    
<?php
include("func.php");
$sentence1[]="And the earth was waste and void; and darkness was upon the face of the deep: and the Spirit of God moved upon the face of the waters. And God said, Let there be light: and there was light.";
$sentence2[]="And the earth was waste and without form; and it was dark on the face of the deep: and the Spirit of God was moving on the face of the waters. And God said, Let there be light: and there was light.";

$sentence1[]="God called the light “day,” and the darkness he called “night.” And there was evening, and there was morning the first day.";
$sentence2[]="God called the light “day” and the darkness “night.” And evening passed and morning came, marking the first day.";

$sentence1[]="ἔθνη δὲ οἰκεῖ τὰ πέρατα κατ ἀπηλιώτην βακτριανοὶ κατ εὖρον ἰνδοὶ κατὰ φοίνικα ἐρυθρὰ θάλασσα καὶ αἰθιοπία κατὰ λευκόνοτον οἱ ὑπὲρ σύρτιν γαράμαντες κατὰ λίβα αἰθίοπες καὶ δυσμικοὶ ὑπέρμαυροι κατὰ ζέφυρον στῆλαι καὶ ἀρχαὶ λιβύης καὶ εὐρώπης κατὰ ἀργέστην ἰβηρία ἡ νῦν ἰσπανία κατὰ δὲ θρασκίαν κελτοὶ καὶ τὰ ὅμορα κατὰ ἀπαρκτίαν οἱ ὑπὲρ τὴν θρᾴκην σκύθαι κατὰ βορρᾶν πόντος μαιῶτις καὶ σαρμάται κατὰ καικίαν κασπία θάλασσα καὶ σάκες.";
$sentence2[]="ἔθνη δὲ οἰκεῖν τὰ πέρατα κατ ἀπηλιώτην βακτριανούς κατ εὖρον ἰνδούς κατὰ φοίνικα ἐρυθρὰν θάλασσαν καὶ αἰθιοπίαν κατὰ νότον τὴν ὑπὲρ αἴγυπτον αἰθιοπίαν κατατὰ λευκόνοτον τοὺς ὑπὲρ σύρτεις γαράμαντας κατὰ λίβα αἰθίοπας καὶ δυσμικοὺς ὑπερμαύρους κατὰ ζέφυρον στήλας καὶ ἀρχὰς λιβύης καὶ εὐρώπης κατὰ ἀργέστην ἐβηρίαν τὴν νῦν ἰσπανίαν κατὰ δὲ θρασκίαν τοὺς ὑπὲρ θρᾴκην σκύθας κατὰ δὲ βορρᾶν πόντον μαιῶτιν σαρμάτας κατὰ καικίαν κασπίαν θάλασσαν καὶ σάκας.";

$sentence1[]="διαδέχεται τὸ αἰγαῖον ἑλλήσποντος λῆγον εἰς ἀβυδον καὶ σηστόν εἶτα ἡ προποντὶς λήγει εἰς χαλκηδόνα καὶ βυζάντιον";
$sentence2[]="διαδέχεται τὸ αἰγαῖον πέλαγος ἑλλήσποντος λῆγον εἰς ἀβυδον καὶ σηστόν εἶτα ἡ προποντὶς λήγουσα εἰς χαλκηδόνα καὶ βυζάντιον ";

$sentence1[]="τῆς δὲ λιβύης ἀπὸ τίγας ἕως στόματος κανωβικοῦ στάδια β,θσνβ τῆς δὲ ἀσίας ἀπὸ κανώβου ἕως τανάιδος ποταμοῦ μετὰ τῶν κόλπων ὁ παράπλους στάδια δ,ρια ὁμοῦ παράλιος σὺν κόλποις τῆς καθ ἡμᾶς οἰκουμένης στάδια ιγ,θοβ";
$sentence2[]="τῆς δὲ λιβύης ἀπὸ τιγγὸς ἕως στόματος κανωβικοῦ σταδίων μυριάδων δύο καὶ ‚θσνβ Τῆς δ Ἀσίας ἀπὸ κανώβου ἕως τανάιδος ποταμοῦ μετὰ τῶν κόλπων ὁ παράπλους σταδίων μυριάδων δ καὶ ρια ὁμοῦ παράλιος σὺν κόλποις τῆς καθ ἡμᾶς οἰκουμένης σταδίων μυριάδων ιγ καὶ ‚θοβ συμμετρουμένης τῆς μαιώτιδος λίμνης ἧς περίμετρος σταδίων ‚θ.";

$sentence1[]="ὑπὲρ δὲ τὸ ἱκάριον ἑξῆς ἀνακεῖται τὸ αἰγαῖον";
$sentence2[]="ὑπὲρ δὲ τὸ ἰκάριον ἑξῆς ἀναχεῖται τὸ αἰγαῖον";

for($i=0;$i < sizeof($sentence1);$i++)
{
echo $sentence1[$i]."<br><font color='red'>".$sentence2[$i]." </font>";	
  compute(tokenize($sentence1[$i]),tokenize($sentence2[$i]));
}
?>
</body>
</html>