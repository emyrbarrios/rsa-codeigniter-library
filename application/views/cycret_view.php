<h2>Cycret view all</h2>
<table>
<tr>
<td>Sender name</td>
<td>Sender email</td>
<td>Recipient name</td>
<td>Recipient email</td>
<td>Unique URL</td>
<td>Message</td>
<td>Create date</td>
<td>Burn type</td>
<td>Burn date</td>
<td>Status</td>
<td>&nbsp;</td>
</tr>

<?php foreach ($cycret as $cycret_item): ?>
<tr>
<td><?php echo $cycret_item->senderName ?></td>
<td><?php echo $cycret_item->senderEmail ?></td>
<td><?php echo $cycret_item->recipientName ?></td>
<td><?php echo $cycret_item->recipientEmail ?></td>
<td><?php echo $cycret_item->uniqueUrl ?></td>
<td><?php echo $cycret_item->message ?></td>
<td><?php echo $cycret_item->createDate ?></td>
<td><?php echo $cycret_item->burnType ?></td>
<td><?php echo $cycret_item->burnDate ?></td>
<td><?php echo $cycret_item->status ?></td>
<td><a href="cycret/message/<?php echo $cycret_item->uniqueUrl ?>">View</a></td>
</tr>
<?php endforeach ?>
</table>

<a href="cycret/create">Create new cycret</a>
