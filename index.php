
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Intra-Language Alignment </title>
	<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>    <!-- FA-Icons -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">    
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Tangerine">
<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700,300italic' rel='stylesheet' type='text/css'>
    <style>
.headDiv {
		font-family: 'Open Sans Condensed', sans-serif;
        font-size: 48px;
        font-weight:700;
        background-color:#333;margin-top:0px; padding:5px;color:#FFF
      }
#footer {
    background: #EEE;
    width: 100%;
    padding-top: 4px;
    min-height: 4.5em;
    box-shadow: 0px -1px 1px rgba(0, 0, 0, 0.05);
    border-top: 1px solid #DDD;
    text-align: center;
}      
.push {
	height: 4.5em;
    clear: both;
}
.warpper {
    min-height: 100%;
    height: auto !important;
    height: 100%;
    margin: 0 auto -4.5em;
}
.content{
padding:25px; font-size:14pt;
}
    </style>
</head>
<body style="font-family: 'Roboto Condensed', sans-serif;">
<div class="warpper">
<div class="headDiv">
	<h1><img src="logo_dh_light.png" height="80"><b>Intra-Language Alignment </b>(Test version)</h1>
</div>
<div style="width:90%;margin: auto; padding-top:20px;">
<div class="content">
Intra-language alignment is the alignment of two texts in the same language, 
for example Alignment of the American Standard Version (ASV) and the English Standard Version (ESV) of the new testament.<br> <a href="examples.php" target="_blank">Example</a></div>

<form>
	<div class="row" >
		<div class="col-md-6">First text <textarea name="Text1" class="form-control" rows="4"><?php echo $_REQUEST['Text1'];?></textarea></div>
		<div class="col-md-6">Second text <textarea name="Text2" class="form-control" rows="4"><?php echo $_REQUEST['Text2'];?></textarea></div>
		
	</div>
	<br />
	<label class="checkbox-inline"><input type="checkbox" name="punctuation" value="1" <?php if($_REQUEST['punctuation']==1) echo "checked";?>>Ignore punctuation</label>
	<label class="checkbox-inline"><input type="checkbox" name="case" value="1" <?php if($_REQUEST['case']==1) echo "checked";?>>Case sensitive</label>	
	<br><button type="submit" class="btn btn-primary">Align</button>
</form>	
	<?php
	 require_once("func.php");
	 if($_REQUEST['Text1']!="" && $_REQUEST['Text2']!="")
	 {
	 	$sentece1=$_REQUEST['Text1'];
	 	$sentece2=$_REQUEST['Text2'];
	 	$punct=$_REQUEST['punctuation'];
	 	$case=$_REQUEST['case'];
	 	
		  compute(tokenize($sentece1),tokenize($sentece2),$punct,$case);
	 }
	 ?>
</div>


                <div class="push">
                </div>
 </div>
 <div id="footer">
	<font size="2" color="#888">Alexander von Humboldt-Lehrstuhl für Digital Humanities - Uni Leipzig <br>Implemented by Tariq Yousef</font>            
</div>
</body>
</html>