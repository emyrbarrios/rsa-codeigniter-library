<?php foreach ($cycret as $cycret_item): ?>
Hello <?php echo $cycret_item->senderName ?>, <br />
<?php echo $cycret_item->recipientName ?> wants to send you a secure message. Do you accept?<br />
<a href="cycret/initialize/url/<?php echo $cycret_item->uniqueUrl ?>/status/initialize">Yes</a><br />
<a href="cycret/initialize/url/<?php echo $cycret_item->uniqueUrl ?>//status/decline">No</a><br />
<?php endforeach ?>

<?php 
//Pass single message stuff like this (reference to controller):
//echo $msgx; 
?>

<a href="../">Back</a>