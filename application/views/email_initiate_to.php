<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Accept your cycret</title>
</head>
<body>
<h3>Hello, <?php echo $recipientName;?>!</h3>
<?php echo $senderName;?> would like to send you an encrypted message from Cycret.com. Do you accept?<br />


<a href="<?php echo base_url();?>/cycret/initiate/url/<?php echo $uniqueUrl;?>/accept/yes">Yes, I want to receive the message</a><br />
<a href="<?php echo base_url();?>/cycret/initiate/url/<?php echo $uniqueUrl;?>/accept/no">No, I don't want to receive the message</a>
<br />

<b>What happens when I accept the message?</b><br />
When you accept the message, you will be shown a <i>keypair</i>, or password number. This is the only time you will see the keypair, so remember it 
or write it down somewhere. You need this keypair to decrypt your message.<br />
A notification will be sent to the sender that he or she can encrypt the message. After encryption you will receive an email
that you can decrypt the secret message using the keypair. After decryption (or after a set period of time, depending on the sender's preference) the 
message and email addresses are deleted from the database, leaving no trace at all.

<b>What happens when I don't accept the message?</b><br />
When you choose to decline the message the sender will be notified, and all data will be erased from the database.

<b>What is Cycret?</b><br />
Cycret (<a href="http://www.cycret.com">www.cycret.com</a>) is way to safely send encrypted messages to someone you trust. Because of the unique
two-pass encryption process, Cycret is very secure and does not leave any traces of the message on your computer or the sender's computer.


<br /><br />
Thank you for your confidence in Cycret.<br />
Best regards,
<br /><br />
The Cycret Team<br />
<a href="<?php echo base_url();?>">www.cycret.com</a>
</body>
</html>