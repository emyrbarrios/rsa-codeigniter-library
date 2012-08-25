<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Encrypted email</title>
</head>
<body>
<h3>Hello, <?php echo $recipientName;?>!</h3>
<?php echo $senderName; ?> encrypted a message for you at <a href="http://www.cycret.com">www.cycret.com</a>. 
<br />
You can decrypt it here: <br />
<a href="<?php echo base_url();?>/cycret/decrypt/url/<?php echo $uniqueUrl;?>">http://www.nopaintjustpixels.com/mvc/index.php/cycret/decrypt/url/<?php echo $uniqueUrl;?></a>
<br />
You can use the key that you've been given earlier.

<br /><br />
Thank you for your confidence in Cycret.<br />
Best regards,
<br /><br />
The Cycret Team<br />
<a href="<?php echo base_url();?>">www.cycret.com</a>
</body>
</html>