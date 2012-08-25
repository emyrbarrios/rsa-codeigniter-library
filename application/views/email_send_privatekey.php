<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Accept your cycret</title>
</head>
<body>
<h3>Hello, <?php echo $recipientName;?>!</h3>

You can decrypt your future here:<br />
<a href="http://www.nopaintjustpixels.com/mvc/index.php/cycret/decrypt/<?php echo $uniqueUrl;?>">Clickka!</a>
<br />
Using the following private key: <br />
<?php echo privateKey;?>
<br />


Thank you!
</body>
</html>